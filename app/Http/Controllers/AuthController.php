<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\States;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login() {
        return view('front.account.login');
    }

    public function register() {
        return view('front.account.register');
    }

    public function processRegister(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        if($validator->passes()) {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash('success', 'Usuario registrado com sucesso');

            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->passes()) {
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
                if(session()->has('url.intended')) {
                    return redirect(session()->get('url.intended'));
                }

                return redirect()->route('account.profile');
            } else {
                return redirect()->route('account.login')
                    ->with($request->only('email'))
                    ->with('error', 'Email/senha estão incorretos');
            }
        } else {
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }

    public function profile() {
        $userId = Auth::user()->id;

        $state = States::orderBy('name', 'ASC')->get();

        $user = User::where('id', $userId)->first();

        $address = CustomerAddress::where('user_id', $userId)->first();
        return view('front.account.profile',[
            'user' => $user,
            'states' => $state,
            'address' => $address
        ]);
    }

    public function updateProfile(Request $request){
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email,'.$userId.',id'        
        ]);

        if ($validator->passes()) {
            $user = User::find($userId);
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->save();

            session()->flash('success', 'Dados básicos atualizados com sucesso!');

            return response()->json([
                'status' => true,
                'message' => 'Dados básicos atualizados com sucesso!'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function updateAddress(Request $request){
        $userId = Auth::user()->id;

        $validator = Validator::make($request->all(),[
            'first_name' => 'required|string|min:2|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'state_id' => 'required|exists:states,id',
            'address' => 'required|string|max:1000',
            'apartment' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'zip' => 'required|string|regex:/^[0-9.\- ]{8,10}$/',
            'mobile' => 'required|string|min:10|max:20',
        ]);

        if ($validator->passes()) {
            CustomerAddress::updateOrCreate(
                ['user_id' => $userId],
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'address' => $request->address,
                    'apartment' => $request->apartment,
                    'city' => trim($request->city),
                    'state_id' => $request->state_id,
                    'zip' => preg_replace('/\D/', '', $request->zip),
                ]
            );

            session()->flash('success', 'Dados básicos atualizados com sucesso!');

            return response()->json([
                'status' => true,
                'message' => 'Dados básicos atualizados com sucesso!'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function logout() {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('account.login')
            ->with('success', 'Você deslogou da sua conta');
    }

    public function orders() {
        $data = [];
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();

        $data['orders'] = $orders;

        return view('front.account.order', $data);
    }

    public function orderDetail($id) {
        $user = Auth::user();

        $order = Order::with('items')
            ->where('user_id', $user->id)
            ->whereKey($id)
            ->firstOrFail();

        return view('front.account.order-detail', [
            'order' => $order,
            'orderItems' => $order->items,
            'orderItemsCount' => $order->items->count(),
        ]);
    }
}
