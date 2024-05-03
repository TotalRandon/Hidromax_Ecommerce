<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Cart;

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
}
