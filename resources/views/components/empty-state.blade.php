@props(['icon' => 'box', 'title', 'description', 'actionUrl' => null, 'actionText' => null])

<div class="bg-white rounded-lg shadow p-12 text-center">
    <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        @if($icon === 'cart')
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
        @elseif($icon === 'document')
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        @elseif($icon === 'search')
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        @else
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
        @endif
    </svg>
    <h2 class="text-2xl font-semibold mb-2">{{ $title }}</h2>
    <p class="text-gray-600 mb-6">{{ $description }}</p>
    @if($actionUrl && $actionText)
        <a href="{{ $actionUrl }}" class="inline-block bg-black text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800">
            {{ $actionText }}
        </a>
    @endif
</div>
<x-empty-state 
    icon="cart"
    title="Your cart is empty"
    description="Add some products to get started!"
    actionUrl="{{ route('products.index') }}"
    actionText="Browse Products"
/>