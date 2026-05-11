@extends('layouts.app')

@section('title', 'Pemesanan - Mantu-Ngopi')

@section('content')
<div class="pb-24">

</div>
@endsection

{{-- Pills Kategori --}}
<div class="flex items-center gap-2 overflow-x-auto pb-3 mb-6 no-scrollbar">
    @foreach($categories as $category)
        @if($category->products->count() > 0)
            <a href="#category-{{ $category->id }}"
                class="shrink-0 px-4 py-1.5 bg-gray-800 hover:bg-purple-500/20 border border-gray-700 hover:border-purple-500/50 text-gray-400 hover:text-purple-400 text-sm rounded-full transition-colors">
                {{ $category->name }}
            </a>
        @endif
    @endforeach
</div>

<div x-data="{
    cart: [],

    get totalItems() {
        return this.cart.reduce((sum, i) => sum + i.qty, 0)
    },

    get totalPrice() {
        return this.cart.reduce((sum, i) => sum + (i.price * i.qty), 0)
    },

    formatRp(val) {
        return new Intl.NumberFormat('id-ID').format(Math.round(val))
    }
}" class="pb-24"></div>

{{-- Products by Category --}}
<div class="space-y-8">
    @foreach($categories as $category)
        @if($category->products->count() > 0)
            <div id="category-{{ $category->id }}">
                <h2 class="text-base font-semibold text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-purple-400 text-sm"></i>
                    {{ $category->name }}
                </h2>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

                </div>
            </div>
        @endif
    @endforeach
</div>

@php
    $productData = [
        'id' => $product->id,
        'name' => $product->name,
        'price' => $product->price,
        'image' => $product->image ? Storage::url($product->image) : '',
        'supplies' => $product->supplies->map(fn($s) => [
            'supply_id' => $s->id,
            'pivot_qty' => $s->pivot->qty,
        ]),
    ];
@endphp

supplies: {{ Js::from(
    \App\Models\Supply::all(['id', 'qty', 'reserved'])
        ->keyBy('id')
        ->map(fn($s) => ['qty' => $s->qty, 'reserved' => $s->reserved])
) }},

getEffectiveQty(supplyId) {
    const s = this.supplies[supplyId]
    if (!s) return 0
    return s.qty - s.reserved
},

isProductAvailableForQty(product, requestedQty) {
    for (const supply of product.supplies) {
        const effective = this.getEffectiveQty(supply.supply_id)
        if (effective < supply.pivot_qty * requestedQty) return false
    }
    return true
},
{{-- Badge Habis --}}
<div x-show="!isProductAvailable({{ Js::from($productData) }})"
    class="absolute top-2 left-2 z-10 px-2 py-0.5 bg-red-500/90 text-white text-xs rounded-full font-medium">
    <i class="fa-solid fa-circle-xmark mr-1"></i>Habis
</div>

{{-- Badge Tipis --}}
<div x-show="isProductAvailable({{ Js::from($productData) }}) && isProductLow({{ Js::from($productData) }})"
    class="absolute top-2 left-2 z-10 px-2 py-0.5 bg-orange-500/90 text-white text-xs rounded-full font-medium">
    <i class="fa-solid fa-triangle-exclamation mr-1"></i>Tipis
</div>
addToCart(product) {
    if (!this.isProductAvailable(product)) return

    const existing = this.cart.find(i => i.id === product.id)
    const newQty = existing ? existing.qty + 1 : 1

    if (!this.isProductAvailableForQty(product, newQty)) {
        alert('Stok tidak mencukupi!')
        return
    }

    if (existing) {
        existing.qty++
    } else {
        this.cart.push({ ...product, qty: 1 })
    }
},
incrementQty(productId) {
    const item = this.cart.find(i => i.id === productId)

    if (!this.isProductAvailableForQty(item, item.qty + 1)) {
        alert('Stok tidak mencukupi!')
        return
    }

    item.qty++
},

decrementQty(productId) {
    const item = this.cart.find(i => i.id === productId)

    if (item.qty <= 1) {
        this.removeFromCart(productId)
    } else {
        item.qty--
    }
},
{{-- Floating Cart Bar --}}
<div x-show="cart.length > 0"
    class="fixed bottom-6 left-1/2 -translate-x-1/2 z-40 w-full max-w-lg px-4">

    <button @click="modalOpen = true"
        class="w-full flex items-center justify-between bg-purple-600">

        <span x-text="totalItems"></span>
        <span x-text="'Rp ' + formatRp(totalPrice)"></span>

    </button>
</div>