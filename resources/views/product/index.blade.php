<?php
/** @var \Illuminate\Database\Eloquent\Collection $products */
?>
<x-app-layout>
    @if ($products->count() === 0)
        <div class="text-center text-gray-600 py-16 text-xl">
            There are no products published
        </div>
    @else
        <div class="grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl-grid-cols-4 p-5 ">
            @foreach ($products as $product)
                <div x-data="productItem({{ json_encode([
                    'id' => $product->id,
                    'image' => $product->image,
                    'title' => $product->title,
                    'price' => $product->price,
                    'addToCartUrl' => route('cart.add', $product),
                ]) }})"
                    class="border border-1 border-gray-200 rounded-md hover:border-slate-600 transition-colors bg-white">
                    <a href="{{ route('product.view', $product->slug) }}"
                        class="aspect-w-3 aspect-h-3 block overflow-hidden">
                        <img src="{{ $product->image }}" alt=""
                            class="object-cover rounded-lg hover:scale-105 hover:rotate-1 transition-transform">
                    </a>
                    <div class="p-4">
                        <h3 class="text-lg">
                            <a href="{{ route('product.view', $product->slug) }}">
                                {{ $product->title }}
                            </a>
                        </h3>
                        <h5 class="font-bold">${{ $product->price }}</h5>
                    </div>
                    <div class="flex justify-between py-3 px-4">
                        <button class="btn-primary bg-slate-900 text-white px-5 py-3" @click="addToCart()">
                            Add to Cart
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $products->links() }}
    @endif
</x-app-layout>
