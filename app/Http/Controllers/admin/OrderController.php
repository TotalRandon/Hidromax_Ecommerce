<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request) {

        $orders = Order::latest('orders.created_at')->select('orders.*', 'users.name', 'users.email');
        $orders = $orders->leftjoin('users', 'users.id', 'orders.user_id');

        if($request->get('keyword') != '') {
            $orders = $orders->where('users.name', 'like', '%'.$request->keyword.'%');
            $orders = $orders->orWhere('users.email', 'like', '%'.$request->keyword.'%');
            $orders = $orders->orWhere('orders.id', 'like', '%'.$request->keyword.'%');
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
            ->first();
    
        $orderItems = OrderItem::where('order_id', $id)->get();
    
        return view('admin.orders.detail', [
            'order' => $order,
            'orderItems' => $orderItems
        ]);
    }    

    public function changeOrderStatus(Request $request, $orderId) {
        $order = Order::find($orderId);
        $order->status = $request->status;
        $order->shipped_date = $request->shipped_date;
        $order->save();

        $message = 'Estatus do pedido atualizado com sucesso';

        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function sendInvoiceEmail(Request $request, $orderId) {
        orderEmail($orderId, $request->userType);

        $message = 'Email enviado com sucesso';

        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
