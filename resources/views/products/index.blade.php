<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - UNIQLO Store</title>
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
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-gray-900 font-semibold">Products</a>
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

    <!-- Hero Section -->
    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-center mb-4">Discover Our Collection</h1>
            <p class="text-center text-gray-600 mb-8">Simple, high-quality clothing for everyday life</p>
            
            <!-- Search and Filter Bar -->
            <form action="{{ route('products.index') }}" method="GET" class="max-w-4xl mx-auto">
                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <!-- Search -->
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-black focus:ring focus:ring-black focus:ring-opacity-20">
                    
                    <!-- Sort -->
                    <select name="sort" class="border-gray-300 rounded-md shadow-sm focus:border-black focus:ring focus:ring-black focus:ring-opacity-20">
                        <option value="">Sort By</option>
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                    </select>
                    
                    <!-- Submit -->
                    <button type="submit" class="bg-black text-white px-6 py-2 rounded hover:bg-gray-800 whitespace-nowrap">Apply Filters</button>
                </div>
                
                <!-- Active Filters Display -->
                @if(request('search') || request('category') || request('sort'))
                    <div class="flex items-center gap-2 text-sm">
                        <span class="text-gray-600">Active filters:</span>
                        @if(request('search'))
                            <span class="bg-gray-200 px-3 py-1 rounded-full">Search: {{ request('search') }}</span>
                        @endif
                        @if(request('category'))
                            <span class="bg-gray-200 px-3 py-1 rounded-full">Category: {{ ucfirst(str_replace('-', ' ', request('category'))) }}</span>
                        @endif
                        @if(request('sort'))
                            <span class="bg-gray-200 px-3 py-1 rounded-full">Sort: {{ ucfirst(str_replace('_', ' ', request('sort'))) }}</span>
                        @endif
                        <a href="{{ route('products.index') }}" class="text-red-600 hover:text-red-800 ml-2">Clear all</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar -->
            <div class="w-full md:w-64 flex-shrink-0">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-lg mb-4">Categories</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('products.index') }}" class="block py-2 px-3 rounded {{ !request('category') ? 'bg-black text-white' : 'hover:bg-gray-100' }}">
                                All Products
                            </a>
                        </li>
                        @foreach ($categories as $category)
    <li>
        <a href="{{ route('products.index', ['category' => $category->slug]) }}"
           class="block py-2 px-3 rounded {{ request('category') == $category->slug ? 'bg-black text-white' : 'hover:bg-gray-100' }}">
            {{ $category->name }}
            <span class="text-sm text-gray-500">({{ $category->products_count }})</span>
        </a>
    </li>
@endforeach

                    </ul>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="flex-1">
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover rounded-t-lg">
                                    @else
                                        <div class="w-full h-64 bg-gray-200 rounded-t-lg flex items-center justify-center">
                                            <span class="text-gray-400">No image</span>
                                        </div>
                                    @endif
                                </a>
                                <div class="p-4">
                                    <p class="text-sm text-gray-500 mb-1">{{ $product->category->name }}</p>
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <h3 class="font-semibold text-lg mb-2 hover:text-gray-600">{{ $product->name }}</h3>
                                    </a>
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-xl font-bold">${{ number_format($product->price, 2) }}</span>
                                        @if($product->stock > 0)
                                            <span class="text-sm text-green-600">In Stock</span>
                                        @else
                                            <span class="text-sm text-red-600">Out of Stock</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Add to Cart Button -->
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST" onsubmit="console.log('Form submitted for product: {{ $product->id }}');">
                                        @csrf
                                        <button type="submit" class="w-full bg-black text-white py-2 rounded hover:bg-gray-800 transition {{ $product->stock == 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $product->stock == 0 ? 'disabled' : '' }}>
                                            Add to Cart 
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow p-12 text-center">
                        <p class="text-gray-500 text-lg">No products found.</p>
                    </div>
                @endif
            </div>
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