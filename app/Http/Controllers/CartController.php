<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
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

        if($product == null){
            return response()->json([
                'status' => false,
                'message' => 'Produto não encontrado'
            ]);
        }

        if($this->cart->count() > 0){
            // verifica se o produto foi adicionado ao carrinho
            // echo "Produto ja adicionado ao carrinho";

            $cartContent = $this->cart->content();
            $productAlreadyExist = false;

            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;
                }
            }

            if ($productAlreadyExist == false) {
                $this->cart->add($product->id, $product->title, 1, $product->price, [
                    'productImage' => (!empty( $product->product_images)) ? $product->product_images->first() : ''
                ]);

                $status = true;
                $message = $product->title.' adicionado ao carrinho com sucesso';
                session()->flash('success', $message);
            } else {
                $status = false;
                $message = $product->title.' ja foi adicionado ao carrinho';
            }

        } else {
            // caso o carrinho estar vazio

            $this->cart->add($product->id, $product->title, 1, $product->price, [
                'productImage' => (!empty( $product->product_images)) ? $product->product_images->first() : ''
            ]);
            $status = true;
            $message = $product->title.' adicionado ao carrinho com sucesso';
            session()->flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function cart()
    {
        $cartContent = $this->cart->content();
        $data['cartContent'] = $cartContent;
        return view('front.cart', $data);
    }

    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = $this->cart->get($rowId);

        $product = Product::find($itemInfo->id);
        // checar a quantidade em estoque

        if ($product->track_qty == 'yes') {

            if($qty <= $product->qty) {
                $this->cart->update($rowId, $qty);
                $message = 'Carrinho atualizado com sucesso';
                $status = true;
                session()->flash('success', $message);
            } else {
                $message = 'Não há no estoque ('.$qty.') '.$product->title.'';
                $status = false;
                session()->flash('error', $message);
            }
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

        if($itemInfo == null) {
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

    public function checkout(){

        // se o carrinho estiver vazio não é possivel acessar o tela de checkout
        if ($this->cart->count() == 0) {
            return redirect()->route('front.cart');
        }

        // se o usuario não estiver logado, redirecionar o visitante para tela de login
        if (Auth::check() == false) {

            if(!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]);
            }

            return redirect()->route('account.login');
        }

        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();

        session()->forget('url.intended');

        $states = States::orderBy('name', 'ASC')->get();

        return view('front.checkout', [
            'states' => $states,
            'customerAddress' => $customerAddress    
        ]);
    }

    public function processCheckout(Request $request){
        
        // passo 1 - aplicar validacao de dados 

        $validator = Validator::make($request->all(),[
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
                'message' => 'Algo de errado não esta certo',
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // passo 2 - savar dados de endereço do usuario na tabela ( Customers_addresses )

        $user = Auth::user();

        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
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

        //passo 3 - guardar os dados de pedidos na tabela ( Orders )

        if ($request->payment_method == 'stripe') {

            $shipping = 0;
            $discount = 0;
            $subTotal = $this->cart->subtotal(2, '.', '');
            $grandTotal = $subTotal+$shipping;

            $order = new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->user_id = $user->id;

            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->apartment = $request->apartment;
            $order->address = $request->address;
            $order->state_id = $request->state_id;
            $order->city = $request->city;
            $order->zip = $request->zip;
            $order->notes = $request->order_notes;
            $order->save();

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

            session()->flash('success', 'Seu pedido foi realiado com sucesso.');

            $this->cart->destroy();

            return response()->json([
                'message' => 'Pedido salvo com sucesso',
                'orderid' => $order->id,
                'status' => true,
            ]);

        } else {

        }
    }

    public function thankyou($id) {
        return view('front.thanks', [
            'id' => $id
        ]);
    }
}
