<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingCharge;
use App\Models\States;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Cart;
use Illuminate\Support\Facades\Auth;
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
        $product = Product::with('product_images')->find($request->id);

        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Produto não encontrado'
            ]);
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
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = $this->cart->get($rowId);
        $product = Product::find($itemInfo->id);

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

    if ($customerAddress) {
        // Calculo de frete
        $userState = $customerAddress->state_id;
        $shippingInfo = ShippingCharge::where('states_id', $userState)->first();

        $totalQty = $this->cart->count();
        $totalShippingCharge = $shippingInfo ? $shippingInfo->amount : 0;
        $grandTotal = $this->cart->subtotal(2, '.', '') + $totalShippingCharge;
    } else {
        $userState = null;
        $totalShippingCharge = 0;
        $grandTotal = $this->cart->subtotal(2, '.', '');
    }

    return view('front.checkout', [
        'states' => $states,
        'customerAddress' => $customerAddress,
        'totalShippingCharge' => $totalShippingCharge,
        'grandTotal' => $grandTotal
    ]);
}


    public function processCheckout(Request $request)
    {
        // passo 1 - aplicar validacao de dados 
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'city' => 'required',
            'state_id' => 'required',
            'zip' => 'required|min:8',
            'mobile' => 'required|min:11'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Algo de errado não está certo',
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // passo 2 - savar dados de endereço do usuario na tabela ( Customers_addresses )
        $user = Auth::user();

        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'apartment' => $request->apartment,
                'city' => $request->city,
                'state_id' => $request->state_id,
                'zip' => $request->zip,
            ]
        );

        // passo 3 - guardar os dados de pedidos na tabela ( Orders )

        // if ($request->payment_mathod == 'stripe') {

            // // Calulando frete + total dos itens

            // $shippingInfo = ShippingCharge::where('states_id', $request->state_id)->first();

            // if ($shippingInfo != null) {

            //     $shippingCharge = $shippingInfo->amount;
            //     $grandTotal = $subTotal + $shippingCharge;

            // } else {
            //     $shippingInfo = ShippingCharge::where('states_id')->first();

            //     $shippingCharge = $shippingInfo->amount;
            //     $grandTotal = $subTotal + $shippingCharge;

            // }

            $shipping = ShippingCharge::where('states_id', $request->state_id)->first();
            $shippingCharge = $shipping ? $shipping->amount : 0;
            $subTotal = $this->cart->subtotal(2, '.', '');
            $grandTotal = $subTotal + $shippingCharge;

            $order = new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shippingCharge;
            $order->grand_total = $grandTotal;
            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            
            $order->payment_status = 'not paid';
            $order->status = 'pending';

            $order->mobile = $request->mobile;
            $order->apartment = $request->apartment;
            $order->address = $request->address;
            $order->state_id = $request->state_id;
            $order->city = $request->city;
            $order->zip = $request->zip;
            $order->notes = $request->order_notes;
            $order->save();

        // } else {

        // }

        // passo 4 - armazenar os itens comprados pelo usuario na tabela ( Order_itens )
        foreach ($this->cart->content() as $item) {
            $orderItem = new OrderItem();
            $orderItem->product_id = $item->id;
            $orderItem->order_id = $order->id;
            $orderItem->name = $item->name;
            $orderItem->qty = $item->qty;
            $orderItem->price = $item->price;
            $orderItem->total = $item->price * $item->qty;
            $orderItem->save();
        }

        // Enviando email de compra realizada
        orderEmail($order->id, 'customer');

        session()->flash('success', 'Seu pedido foi realizado com sucesso.');

        $this->cart->destroy();

        return response()->json([
            'message' => 'Pedido salvo com sucesso',
            'order_id' => $order->id,
            'status' => true,
            'redirect_url' => route('front.thankyou', [ 'id' => $order->id ])
        ]);
    }

    public function thankyou($id)
    {
        return view('front.thanks', [
            'id' => $id
        ]);
    }

    public function getOrderSummery(Request $request)
    {
        $subTotal = $this->cart->subtotal(2, '.', '');

        if ($request->state_id > 0) {
            $shippingInfo = ShippingCharge::where('states_id', $request->state_id)->first();

            if ($shippingInfo != null) {
                $shippingCharge = $shippingInfo->amount;
            } else {
                $shippingInfo = ShippingCharge::where('states_id')->first();
                $shippingCharge = $shippingInfo ? $shippingInfo->amount : 0;
            }

            $grandTotal = $subTotal + $shippingCharge;

            return response()->json([
                'status' => true,
                'grandTotal' => number_format($grandTotal, 2, ',', '.'),
                'shippingCharge' => number_format($shippingCharge, 2, ',', '.')
            ]);
        } else {
            $grandTotal = $subTotal;

            return response()->json([
                'status' => true,
                'grandTotal' => number_format($grandTotal, 2, ',', '.'),
                'shippingCharge' => number_format(0, 2, ',', '.')
            ]);
        }
    }
}
