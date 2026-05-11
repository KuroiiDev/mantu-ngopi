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