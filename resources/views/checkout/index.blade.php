<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout - UNIQLO Store</title>
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
                    <div class="text-gray-600">Secure Checkout</div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl font-bold mb-8">Checkout</h1>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Checkout Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-6">Shipping Information</h2>
                        
                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="+62 812 3456 7890" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Shipping Address</label>
                                <textarea name="address" id="address" rows="4" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Street address, City, Province, Postal Code" required>{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                
                                @if($user->address)
                                    <p class="text-sm text-gray-600 mt-2">
                                        <input type="checkbox" id="save_address" checked class="rounded border-gray-300">
                                        <label for="save_address" class="ml-1">Use saved address</label>
                                    </p>
                                @endif
                            </div>
                            <button type="submit" class="w-full bg-black text-white py-3 rounded-lgfont-semibold hover:bg-gray-800">
                            Place Order
                            </button>
                        </form>
                    </div>
                </div>        

                <!-- Order Summary -->
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                                <h2 class="text-xl font-bold mb-4">Order Summary</h2>                <!-- Products -->
                                <div class="space-y-4 mb-4 max-h-64 overflow-y-auto">
                                    @foreach($cart as $id => $item)
                                        <div class="flex items-center space-x-3">
                                            @if($item['image'])
                                                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="h-16 w-16 object-cover rounded">
                                            @else
                                                <div class="h-16 w-16 bg-gray-200 rounded"></div>
                                            @endif
                                            <div class="flex-1">
                                                <p class="font-semibold text-sm">{{ $item['name'] }}</p>
                                                <p class="text-sm text-gray-600">Qty: {{ $item['quantity'] }}</p>
                                            </div>
                                            <p class="font-semibold">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                        </div>
                                    @endforeach
                                </div>                <div class="border-t pt-4 space-y-2">
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
                            </div>
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