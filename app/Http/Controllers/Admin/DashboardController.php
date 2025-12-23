<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        
        $latestProducts = Product::with('category')
            ->latest()
            ->take(5)
            ->get();
        
        $latestOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();
        
        // Tambahkan variabel yang kurang ini
        $lowStockProducts = Product::where('stock', '<', 10)
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();
        
        $outOfStockProducts = Product::where('stock', 0)->count();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalOrders',
            'totalCustomers',
            'latestProducts',
            'latestOrders',
            'lowStockProducts',
            'outOfStockProducts'
        ));
    }
}