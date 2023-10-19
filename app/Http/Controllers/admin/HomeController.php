<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index() {
        return view('admin.dashboard');
        //$admin = Auth::guard('admin')->user();
        //echo 'bem-vindo '.$admin->name.' <a href="'.route('admin.logout').'">Desconectar</a>';
    }

    public function logout() {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
