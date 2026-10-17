<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(Request $request) {

        $orders = Order::latest('orders.created_at')->select('orders.*', 'users.name', 'users.email');
        $orders = $orders->leftjoin('users', 'users.id', 'orders.user_id');

        if($request->get('keyword') != '') {
            $keyword = $request->keyword;
            $orders = $orders->where(function ($query) use ($keyword) {
                $query->where('users.name', 'like', '%'.$keyword.'%')
                    ->orWhere('users.email', 'like', '%'.$keyword.'%')
                    ->orWhere('orders.id', 'like', '%'.$keyword.'%');
            });
        }

        $orders = $orders->paginate(10);

        //$data['orders'] = $orders;

        return view('admin.orders.list', [
            'orders' => $orders
        ]);
    }

    public function detail($id) {
        $order = Order::select('orders.*', 'states.name as stateName')
            ->leftJoin('states', 'orders.state_id', '=', 'states.id')
            ->where('orders.id', $id)
            ->firstOrFail();
    
        $orderItems = OrderItem::where('order_id', $id)->get();
    
        return view('admin.orders.detail', [
            'order' => $order,
            'orderItems' => $orderItems
        ]);
    }    

    public function changeOrderStatus(Request $request, $orderId) {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,shipped,delivered,cancelled',
            'payment_status' => 'sometimes|required|in:paid,not paid',
            'shipped_date' => 'nullable|date',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $result = DB::transaction(function () use ($request, $orderId) {
            $order = Order::whereKey($orderId)->lockForUpdate()->firstOrFail();
            $newStatus = $request->status;

            if (in_array($newStatus, ['shipped', 'delivered'], true) && $order->approval_status !== 'approved') {
                return 'O pedido precisa ser aprovado por um superior antes de avançar para a entrega.';
            }
            if (in_array($newStatus, ['shipped', 'delivered'], true) && $order->approval_status === 'rejected') {
                return 'Um pedido rejeitado não pode avançar para a entrega.';
            }
            $effectivePaymentStatus = $request->input('payment_status', $order->payment_status);
            if ($effectivePaymentStatus === 'paid' && ($order->approval_status !== 'approved' || $order->status === 'cancelled' || $newStatus === 'cancelled')) {
                return 'O pagamento só pode ser registrado para um pedido aprovado e ativo.';
            }
            if ($order->status === 'delivered' && $newStatus !== 'delivered') {
                return 'Um pedido entregue não pode voltar para uma etapa anterior.';
            }
            if ($order->status === 'cancelled' && $newStatus !== 'cancelled') {
                return 'Um pedido cancelado não pode ser reaberto por este fluxo.';
            }
            if ($order->status === 'shipped' && $newStatus === 'pending') {
                return 'Um pedido enviado não pode voltar para pendente.';
            }
            if ($newStatus === 'delivered' && $order->status !== 'shipped' && $order->status !== 'delivered') {
                return 'O pedido precisa estar marcado como enviado antes de ser entregue.';
            }
            if ($newStatus === 'cancelled' && in_array($order->status, ['shipped', 'delivered'], true)) {
                return 'Pedidos já enviados ou entregues não podem ser cancelados por este fluxo.';
            }
            if ($newStatus === 'cancelled' && $order->payment_status === 'paid') {
                return 'Antes de cancelar, conclua o estorno fora do sistema e registre o pedido como não pago.';
            }

            if ($newStatus === 'cancelled' && $order->status !== 'cancelled' && $order->approval_status === 'approved') {
                foreach ($order->items()->orderBy('product_id')->get() as $item) {
                    $product = Product::whereKey($item->product_id)->lockForUpdate()->first();
                    if ($product && $product->track_qty === 'yes') {
                        $product->qty += $item->qty;
                        $product->save();
                    }
                }
            }

            $order->status = $newStatus;
            $order->shipped_date = $newStatus === 'shipped'
                ? ($request->shipped_date ?: now())
                : $request->shipped_date;
            if ($request->has('payment_status')) {
                $order->payment_status = $request->payment_status;
            }
            $order->save();

            return null;
        });

        if ($result) {
            return response()->json(['status' => false, 'message' => $result], 422);
        }

        $message = 'Estatus do pedido atualizado com sucesso';

        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function decideApproval(Request $request, $orderId)
    {
        $validator = Validator::make($request->all(), ['decision' => 'required|in:approved,rejected']);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $admin = Auth::guard('admin')->user();
        if (!$admin || (int) $admin->role !== 2) {
            abort(403, 'Somente um superior autorizado pode decidir sobre o pedido.');
        }

        $result = DB::transaction(function () use ($request, $orderId, $admin) {
            $order = Order::whereKey($orderId)->lockForUpdate()->firstOrFail();
            if ($order->approval_status !== 'awaiting') {
                return 'Este pedido já recebeu uma decisão de aprovação.';
            }
            if ($order->status === 'cancelled') {
                return 'Um pedido cancelado não pode ser aprovado.';
            }

            if ($request->decision === 'approved') {
                $items = $order->items()->orderBy('product_id')->get();
                $products = [];
                foreach ($items as $item) {
                    $product = Product::whereKey($item->product_id)->lockForUpdate()->first();
                    if (!$product) {
                        return 'Um produto deste pedido não está mais disponível no cadastro.';
                    }
                    if ($product->track_qty === 'yes' && (int) $product->qty < (int) $item->qty) {
                        return 'Estoque insuficiente para aprovar: '.$product->title.'.';
                    }
                    $products[] = [$product, $item->qty];
                }

                foreach ($products as [$product, $quantity]) {
                    if ($product->track_qty === 'yes') {
                        $product->qty -= $quantity;
                        $product->save();
                    }
                }
            }

            $order->approval_status = $request->decision;
            $order->approval_decided_by = $admin->id;
            $order->approval_decided_at = now();
            $order->save();

            return null;
        });

        if ($result) {
            return response()->json(['status' => false, 'message' => $result], 422);
        }

        $message = $request->decision === 'approved'
            ? 'Pedido aprovado. Ele agora pode avançar para a separação e entrega.'
            : 'Pedido rejeitado; ele não pode avançar para a entrega.';

        return response()->json(['status' => true, 'message' => $message]);
    }

    public function sendInvoiceEmail(Request $request, $orderId) {
        $request->validate(['userType' => 'required|in:customer,admin']);
        Order::findOrFail($orderId);
        orderEmail($orderId, $request->userType);

        $message = 'Email enviado com sucesso';

        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
