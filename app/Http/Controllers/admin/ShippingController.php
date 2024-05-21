<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingCharge;
use App\Models\States;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function create() {
        $states = States::get();

        $shippingCharges = ShippingCharge::select('shipping_charges.*', 'states.name')->leftJoin('states', 'states.id', 'shipping_charges.states_id')->get();

        $data['states'] = $states;
        $data['shippingCharges'] = $shippingCharges;

        return view('admin.shipping.create', $data);
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'states' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($validator->passes()) {

            $count = ShippingCharge::where('states_id', $request->states)->count();
            if($count > 0) {
                session()->flash('error', 'Meio de entrega já cadastrado');

                return response()->json([
                    'status' => true,
                ]);
            }

            $shipping = new ShippingCharge();
            $shipping->states_id = $request->states;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success', 'Meio de entrega adicionado com sucesso');

            return response()->json([
                'status' => true
            ]);
            
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id) {
        $shippingCarge = ShippingCharge::find($id);

        $states = States::get();
        $data['states'] = $states;
        $data['shippingCharge'] = $shippingCarge;

        return view('admin.shipping.edit', $data);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'states' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($validator->passes()) {

            $shipping = ShippingCharge::find($id);

            if($shipping == null) {
                session()->flash('success', 'Meio de entrega não encontrado');
    
                return response()->json([
                    'status' => true
                ]);
            }

            $shipping->states_id = $request->states;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success', 'Meio de entrega atualizado com sucesso');

            return response()->json([
                'status' => true
            ]);
            
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id) {
        $shippingCharge = ShippingCharge::find($id);

        if($shippingCharge == null) {
            session()->flash('success', 'Meio de entrega não encontrado');

            return response()->json([
                'status' => true
            ]);
        }

        $shippingCharge->delete();

        session()->flash('success', 'Meio de entrega excluido com sucesso');

        return response()->json([
            'status' => true
        ]);
    }

}
