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