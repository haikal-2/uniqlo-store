<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - UNIQLO Store</title>
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
                    <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-gray-900">
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

    <!-- Breadcrumbs -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex text-sm text-gray-500">
                <a href="{{ route('products.index') }}" class="hover:text-gray-700">Products</a>
                <span class="mx-2">/</span>
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-gray-700">{{ $product->category->name }}</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900">{{ $product->name }}</span>
            </nav>
        </div>
    </div>

    <!-- Product Detail -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Product Image -->
            <div>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full rounded-lg shadow-lg">
                @else
                    <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                        <span class="text-gray-400 text-xl">No image available</span>
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div>
                <p class="text-sm text-gray-500 mb-2">{{ $product->category->name }}</p>
                <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>
                <p class="text-3xl font-bold mb-6">${{ number_format($product->price, 2) }}</p>

                <div class="mb-6">
                    @if($product->stock > 0)
                        <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                            In Stock ({{ $product->stock }} available)
                        </span>
                    @else
                        <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">
                            Out of Stock
                        </span>
                    @endif
                </div>

                <div class="prose max-w-none mb-8">
                    <h3 class="text-lg font-semibold mb-2">Description</h3>
                    <p class="text-gray-700">{{ $product->description }}</p>
                </div>

                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition duration-300 {{ $product->stock == 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $product->stock == 0 ? 'disabled' : '' }}>
                        {{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                    </button>
                </form>

                <div class="mt-6 p-4 bg-gray-100 rounded-lg">
                    <h3 class="font-semibold mb-2">Product Details</h3>
                    <ul class="space-y-1 text-sm text-gray-700">
                        <li>• Free shipping on orders over $50</li>
                        <li>• 30-day return policy</li>
                        <li>• Secure checkout</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-bold mb-8">You May Also Like</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                        <div class="bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                            <a href="{{ route('products.show', $related->slug) }}">
                                @if($related->image)
                                    <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}" class="w-full h-48 object-cover rounded-t-lg">
                                @else
                                    <div class="w-full h-48 bg-gray-200 rounded-t-lg flex items-center justify-center">
                                        <span class="text-gray-400">No image</span>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <h3 class="font-semibold mb-2">{{ $related->name }}</h3>
                                    <p class="text-xl font-bold">${{ number_format($related->price, 2) }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
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