<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - UNIQLO Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-gray-900">UNIQLO STORE</a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-gray-900">Products</a>
                    <a href="{{ route('cart.index') }}" class="text-gray-900 font-semibold">
                        Cart ({{ count(session()->get('cart', [])) }})
                    </a>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-gray-900">Admin</a>
                        @else
                            <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-gray-900">My Orders</a>
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

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if(count($cart) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow">
                        @foreach($cart as $id => $item)
                            <div class="p-6 border-b last:border-b-0">
                                <div class="flex items-center space-x-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        @if($item['image'])
                                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="h-24 w-24 object-cover rounded">
                                        @else
                                            <div class="h-24 w-24 bg-gray-200 rounded flex items-center justify-center">
                                                <span class="text-gray-400 text-xs">No image</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold">{{ $item['name'] }}</h3>
                                        <p class="text-gray-600">${{ number_format($item['price'], 2) }}</p>
                                        
                                        <!-- Quantity Controls -->
                                        <div class="flex items-center space-x-2 mt-2">
                                            <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center space-x-2">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}" class="bg-gray-200 px-3 py-1 rounded hover:bg-gray-300" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>-</button>
                                                <span class="px-4 py-1 border rounded">{{ $item['quantity'] }}</span>
                                                <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="bg-gray-200 px-3 py-1 rounded hover:bg-gray-300">+</button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Subtotal & Remove -->
                                    <div class="text-right">
                                        <p class="text-lg font-bold mb-2">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                        <form action="{{ route('cart.remove', $id) }}" method="POST" onsubmit="return confirm('Remove this item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Clear Cart Button -->
                    <div class="mt-4">
                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Clear entire cart?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Clear Cart</button>
                        </form>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                        <h2 class="text-xl font-bold mb-4">Order Summary</h2>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Shipping</span>
                                <span>Free</span>
                            </div>
                            <div class="border-t pt-2 flex justify-between font-bold text-lg">
                                <span>Total</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        @auth
                            <a href="{{ route('checkout.index') }}" class="block w-full bg-black text-white text-center py-3 rounded-lg font-semibold hover:bg-gray-800">
                                Proceed to Checkout
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="block w-full bg-black text-white text-center py-3 rounded-lg font-semibold hover:bg-gray-800">
                                Login to Checkout
                            </a>
                        @endauth

                        <a href="{{ route('products.index') }}" class="block w-full text-center mt-3 text-gray-600 hover:text-gray-900">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart State -->
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <h2 class="text-2xl font-semibold mb-2">Your cart is empty</h2>
                <p class="text-gray-600 mb-6">Add some products to get started!</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-black text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800">
                    Browse Products
                </a>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="text-gray mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <p class="text-center">&copy; made by;  Kelompok 12.</p>
        </div>
    </footer>
</body>
</html>