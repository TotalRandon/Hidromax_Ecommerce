<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\States;
use App\Support\DeliveryPolicy;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    protected $cart;

    // Injetar Cart via construtor
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function addToCart(Request $request)
    {
        $product = Product::with('product_images')->where('status', 1)->find($request->id);

        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Produto não encontrado'
            ]);
        }

        if ($product->track_qty === 'yes' && $product->qty < 1) {
            return response()->json(['status' => false, 'message' => 'Produto sem estoque disponível']);
        }

        $cartContent = $this->cart->content();
        $productAlreadyExist = false;

        foreach ($cartContent as $item) {
            if ($item->id == $product->id) {
                $productAlreadyExist = true;
                break;
            }
        }

        if (!$productAlreadyExist) {
            $this->cart->add($product->id, $product->title, 1, $product->price, [
                'productImage' => (!empty($product->product_images)) ? $product->product_images->first() : ''
            ]);

            $status = true;
            $message = $product->title . ' adicionado ao carrinho com sucesso';
            session()->flash('success', $message);
        } else {
            $status = false;
            $message = $product->title . ' já foi adicionado ao carrinho';
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function cart()
    {
        $data['cartContent'] = $this->cart->content();
        return view('front.cart', $data);
    }

    public function updateCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rowId' => 'required|string',
            'qty' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $rowId = $request->rowId;
        $qty = (int) $request->qty;

        try {
            $itemInfo = $this->cart->get($rowId);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Item não encontrado no carrinho'], 404);
        }
        if (!$itemInfo) {
            return response()->json(['status' => false, 'message' => 'Item não encontrado no carrinho'], 404);
        }
        $product = Product::find($itemInfo->id);

        if (!$product || !$product->status) {
            return response()->json(['status' => false, 'message' => 'Produto indisponível'], 422);
        }

        // checar a quantidade em estoque
        if ($product->track_qty == 'yes' && $qty > $product->qty) {
            $message = 'Não há no estoque (' . $qty . ') ' . $product->title;
            $status = false;
            session()->flash('error', $message);
        } else {
            $this->cart->update($rowId, $qty);
            $message = 'Carrinho atualizado com sucesso';
            $status = true;
            session()->flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function deleteItem(Request $request)
    {
        $itemInfo = $this->cart->get($request->rowId);

        if ($itemInfo == null) {
            $errorMessage = 'Produto não encontrado no carrinho';
            session()->flash('error', $errorMessage);

            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        }

        $this->cart->remove($request->rowId);

        $message = 'Produto removido com sucesso';
        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function checkout()
{
    // se o carrinho estiver vazio não é possivel acessar o tela de checkout
    if ($this->cart->count() == 0) {
        return redirect()->route('front.cart');
    }

    // se o usuario não estiver logado, redirecionar o visitante para tela de login
    if (!Auth::check()) {
        if (!session()->has('url.intended')) {
            session(['url.intended' => url()->current()]);
        }
        return redirect()->route('account.login');
    }

    $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();
    $states = States::orderBy('name', 'ASC')->get();

    $checkoutItems = [];
    $subtotalCents = 0;
    foreach ($this->cart->content() as $item) {
        $product = Product::find($item->id);
        if (!$product || !$product->status) {
            return redirect()->route('front.cart')->with('error', 'Um produto do carrinho não está mais disponível.');
        }
        if ($product->track_qty === 'yes' && (int) $item->qty > (int) $product->qty) {
            return redirect()->route('front.cart')->with('error', 'O estoque de '.$product->title.' foi atualizado. Ajuste a quantidade antes de continuar.');
        }
        $priceCents = (int) round(((float) $product->price) * 100);
        $subtotalCents += $priceCents * (int) $item->qty;
        $checkoutItems[] = [
            'name' => $product->title,
            'qty' => (int) $item->qty,
            'total' => number_format(($priceCents * (int) $item->qty) / 100, 2, ',', '.'),
        ];
    }

    $totalShippingCharge = 0;
    $grandTotal = number_format($subtotalCents / 100, 2, '.', '');

    return view('front.checkout', [
        'states' => $states,
        'customerAddress' => $customerAddress,
        'totalShippingCharge' => $totalShippingCharge,
        'grandTotal' => $grandTotal,
        'checkoutItems' => $checkoutItems,
    ]);
}


    public function processCheckout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:1000',
            'apartment' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state_id' => 'required|exists:states,id',
            'zip' => 'required|string|regex:/^[0-9.\- ]{8,10}$/',
            'mobile' => 'required|string|min:10|max:20',
            'order_notes' => 'nullable|string|max:2000',
        ]);

        $validator->after(function ($validator) use ($request) {
            $state = States::find($request->state_id);
            if ($state && $state->code !== config('delivery.state_code')) {
                $validator->errors()->add('state_id', 'As entregas estão disponíveis somente no estado de São Paulo.');
            }

            if (!DeliveryPolicy::supportsCity($request->city)) {
                $validator->errors()->add('city', 'Ainda não atendemos essa cidade. Entregamos gratuitamente em: '.implode(', ', DeliveryPolicy::cities()).'.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Confira os dados informados.',
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if ($this->cart->count() === 0) {
            return response()->json(['status' => false, 'message' => 'O carrinho está vazio.'], 422);
        }

        $user = Auth::user();
        $order = DB::transaction(function () use ($request, $user) {
            $cartItems = $this->cart->content()->sortBy('id');
            $products = [];
            $subtotalCents = 0;

            foreach ($cartItems as $item) {
                $product = Product::whereKey($item->id)->lockForUpdate()->first();
                if (!$product || !$product->status) {
                    return ['error' => 'Um produto do carrinho não está mais disponível.'];
                }
                if (!is_numeric($item->qty) || (int) $item->qty < 1) {
                    return ['error' => 'A quantidade de um produto no carrinho é inválida.'];
                }
                if ($product->track_qty === 'yes' && (int) $item->qty > (int) $product->qty) {
                    return ['error' => 'Estoque insuficiente para '.$product->title.'.'];
                }

                $priceCents = (int) round(((float) $product->price) * 100);
                $subtotalCents += $priceCents * (int) $item->qty;
                $products[] = ['product' => $product, 'qty' => (int) $item->qty, 'price_cents' => $priceCents];
            }

            $addressData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'apartment' => $request->apartment,
                'city' => Str::title(trim($request->city)),
                'state_id' => $request->state_id,
                'zip' => preg_replace('/\D/', '', $request->zip),
            ];
            CustomerAddress::updateOrCreate(['user_id' => $user->id], $addressData);

            $subtotal = number_format($subtotalCents / 100, 2, '.', '');
            $order = new Order();
            $order->subtotal = $subtotal;
            $order->shipping = 0;
            $order->grand_total = $subtotal;
            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->payment_status = 'not paid';
            $order->status = 'pending';
            $order->approval_status = 'awaiting';
            $order->mobile = $request->mobile;
            $order->apartment = $request->apartment;
            $order->address = $request->address;
            $order->state_id = $request->state_id;
            $order->city = Str::title(trim($request->city));
            $order->zip = preg_replace('/\D/', '', $request->zip);
            $order->notes = $request->order_notes;
            $order->save();

            foreach ($products as $item) {
                $orderItem = new OrderItem();
                $orderItem->product_id = $item['product']->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item['product']->title;
                $orderItem->qty = $item['qty'];
                $orderItem->price = number_format($item['price_cents'] / 100, 2, '.', '');
                $orderItem->total = number_format(($item['price_cents'] * $item['qty']) / 100, 2, '.', '');
                $orderItem->save();
            }

            return $order;
        });

        if (is_array($order) && isset($order['error'])) {
            return response()->json(['status' => false, 'message' => $order['error']], 422);
        }

        try {
            orderEmail($order->id, 'customer');
        } catch (\Throwable $exception) {
            report($exception);
        }

        session()->flash('success', 'Recebemos seu pedido. Nossa equipe vai analisá-lo e entrará em contato para combinar o pagamento e os próximos passos.');

        $this->cart->destroy();

        return response()->json([
            'message' => 'Pedido recebido com sucesso.',
            'order_id' => $order->id,
            'status' => true,
            'redirect_url' => route('front.thankyou', [ 'id' => $order->id ])
        ]);
    }

    public function thankyou($id)
    {
        $order = Order::where('user_id', Auth::id())->whereKey($id)->firstOrFail();

        return view('front.thanks', [
            'order' => $order
        ]);
    }

    public function getOrderSummery(Request $request)
    {
        $subtotalCents = 0;
        foreach ($this->cart->content() as $item) {
            $product = Product::find($item->id);
            if ($product) {
                $subtotalCents += (int) round(((float) $product->price) * 100) * (int) $item->qty;
            }
        }
        $subTotal = $subtotalCents / 100;
        $supportedState = States::whereKey($request->state_id)
            ->where('code', config('delivery.state_code'))
            ->exists();
        $supportedCity = DeliveryPolicy::supportsCity($request->city);

        return response()->json([
            'status' => true,
            'deliveryAvailable' => $supportedState && $supportedCity,
            'deliveryMessage' => $supportedState && $supportedCity
                ? 'Entrega gratuita disponível para esta cidade.'
                : 'Informe uma cidade atendida em São Paulo para consultar a entrega gratuita.',
            'grandTotal' => number_format($subTotal, 2, ',', '.'),
            'shippingCharge' => number_format(0, 2, ',', '.'),
        ]);
    }
}
