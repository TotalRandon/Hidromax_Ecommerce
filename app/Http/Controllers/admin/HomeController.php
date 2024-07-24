<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index() {

        $totalOrders = Order::where('status', '!=', 'cancelled')->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 1)->count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('grand_total');

        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $currentDate = Carbon::now()->format('Y-m-d');

        // this month
        $revenueThisMonth = Order::where('status', '!=', 'cancelled')
                        ->whereDate('created_at', '>=', $startOfMonth)
                        ->whereDate('created_at', '<=', $currentDate)
                        ->sum('grand_total');
                    
        $lastMonthStartDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        $lastMonthEndDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');

        // last month
        $revenueLastMonth = Order::where('status', '!=', 'cancelled')
                        ->whereDate('created_at', '>=', $lastMonthStartDate)
                        ->whereDate('created_at', '<=', $lastMonthEndDate)
                        ->sum('grand_total');

        $lastThirtyDayStartDate = Carbon::now()->subDays(30)->format('Y-m-d');

        // 30 days
        $revenueLastThirtyDays = Order::where('status', '!=', 'cancelled')
                        ->whereDate('created_at', '>=', $lastThirtyDayStartDate)
                        ->whereDate('created_at', '<=', $currentDate)
                        ->sum('grand_total');

        return view('admin.dashboard', [
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalCustomer' => $totalCustomers,
            'totalRevenue' => $totalRevenue,
            'revenueThisMonth' => $revenueThisMonth,
            'revenueLastMonth' => $revenueLastMonth,
            'revenueLastThirtyDays' => $revenueLastThirtyDays
        ]);
        //$admin = Auth::guard('admin')->user();
        //echo 'bem-vindo '.$admin->name.' <a href="'.route('admin.logout').'">Desconectar</a>';
    }

    public function logout() {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
