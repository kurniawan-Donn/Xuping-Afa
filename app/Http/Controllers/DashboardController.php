<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUser = User::count();
        $totalProduct = Product::count();
        $totalActiveProduct = Product::active()->count();
        $totalInactiveProduct = Product::inactive()->count();
        return view('dashboard.index', compact(
            'totalUser',
            'totalProduct',
            'totalActiveProduct',
            'totalInactiveProduct'
        ));
    }
}
