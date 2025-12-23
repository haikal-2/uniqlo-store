<nav class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center space-x-8">
                <a href="/" class="text-2xl font-bold text-gray-900">UNIQLO STORE</a>
                <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-gray-900 {{ request()->routeIs('products.*') ? 'font-semibold' : '' }}">Products</a>
            </div>
            
            <div class="flex items-center space-x-4">
                <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-gray-900 flex items-center {{ request()->routeIs('cart.*') ? 'font-semibold' : '' }}">
                    <svg class="h-6 w-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Cart
                    @php
                        $cartCount = \App\Http\Controllers\CartController::getCartCount();
                    @endphp
                    @if($cartCount > 0)
                        <span class="ml-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $cartCount }}</span>
                    @endif
                </a>
                
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-gray-900">Admin</a>
                    @else
                        <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-gray-900 {{ request()->routeIs('orders.*') ? 'font-semibold' : '' }}">My Orders</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-gray-900">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Login</a>
                    <a href="{{ route('register') }}" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>