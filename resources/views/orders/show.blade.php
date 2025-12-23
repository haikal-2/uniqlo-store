<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - UNIQLO Store</title>
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
                    <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-gray-900">Cart</a>
                    <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-gray-900">My Orders</a>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-gray-900">Admin</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-gray-900">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Order Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-2xl font-bold">Order Details</h1>
                    <p class="text-gray-600">Order #{{ $order->order_number }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $order->getStatusBadgeClass() }}">
                        {{ ucfirst($order->status) }}
                    </span>
                    <a href="{{ route('orders.invoice', $order) }}" target="_blank" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">
                        Print Invoice
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold mb-2">Order Information</h3>
                    <p class="text-sm text-gray-600">Date: {{ $order->created_at->format('M d, Y H:i') }}</p>
                    <p class="text-sm text-gray-600">Total: ${{ number_format($order->total_amount, 2) }}</p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">Shipping Information</h3>
                    <p class="text-sm text-gray-600">{{ $order->customer_name }}</p>
                    <p class="text-sm text-gray-600">{{ $order->customer_phone }}</p>
                    <p class="text-sm text-gray-600">{{ $order->shipping_address }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold">Order Items</h2>
            </div>
            
            <div class="divide-y">
                @foreach($order->orderItems as $item)
                    <div class="p-6 flex items-center space-x-4">
                        @if($item->product && $item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name ?? 'Product' }}" class="h-20 w-20 object-cover rounded">
                        @else
                            <div class="h-20 w-20 bg-gray-200 rounded flex items-center justify-center">
                                <span class="text-gray-400 text-xs">No image</span>
                            </div>
                        @endif

                        <div class="flex-1">
                            <h3 class="font-semibold">{{ $item->product->name ?? 'Product no longer available' }}</h3>
                            <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                            <p class="text-sm text-gray-600">Price: ${{ number_format($item->price, 2) }}</p>
                        </div>

                        <div class="text-right">
                            <p class="font-bold">${{ number_format($item->price * $item->quantity, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-6 bg-gray-50 border-t">
                <div class="flex justify-between items-center">
                    <span class="font-semibold">Order Total:</span>
                    <span class="text-2xl font-bold">${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-800">← Back to Orders</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-gray mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <p class="text-center">&copy; made by;  Kelompok 12.</p>
        </div>
    </footer>
</body>
</html>