@extends('layouts.app')

@section('title', 'Pemesanan - Mantu-Ngopi')

@section('content')
    <div x-data="{
                cart: [],
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

                isProductAvailable(product) {
                    return this.isProductAvailableForQty(product, 1)
                },

                isProductLow(product) {
                    for (const supply of product.supplies) {
                        const effective = this.getEffectiveQty(supply.supply_id)
                        if (effective < 10 && effective >= supply.pivot_qty) return true
                    }
                    return false
                },

                addToCart(product) {
                    if (!this.isProductAvailable(product)) return
                    const existing = this.cart.find(i => i.id === product.id)
                    const newQty = existing ? existing.qty + 1 : 1

                    if (!this.isProductAvailableForQty(product, newQty)) {
                        alert('Stok tidak mencukupi untuk ' + newQty + 'x ' + product.name + '!')
                        return
                    }

                    if (existing) {
                        existing.qty++
                    } else {
                        this.cart.push({ ...product, qty: 1 })
                    }
                },

                removeFromCart(productId) {
                    this.cart = this.cart.filter(i => i.id !== productId)
                },

                incrementQty(productId) {
                    const item = this.cart.find(i => i.id === productId)
                    if (!item) return
                    if (!this.isProductAvailableForQty(item, item.qty + 1)) {
                        alert('Stok tidak mencukupi untuk ' + (item.qty + 1) + 'x ' + item.name + '!')
                        return
                    }
                    item.qty++
                },

                decrementQty(productId) {
                    const item = this.cart.find(i => i.id === productId)
                    if (item) {
                        if (item.qty <= 1) {
                            this.removeFromCart(productId)
                        } else {
                            item.qty--
                        }
                    }
                },

                get totalItems() {
                    return this.cart.reduce((sum, i) => sum + i.qty, 0)
                },

                get totalPrice() {
                    return this.cart.reduce((sum, i) => sum + (i.price * i.qty), 0)
                },

                formatRp(val) {
                    return new Intl.NumberFormat('id-ID').format(Math.round(val))
                },

                modalOpen: false,
                customer: '',

                isInCart(productId) {
                    return this.cart.find(i => i.id === productId)
                },

                getQty(productId) {
                    const item = this.cart.find(i => i.id === productId)
                    return item ? item.qty : 0
                }
            }" class="pb-24">

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

        {{-- Products by Category --}}
        <div class="space-y-8">
            @foreach($categories as $category)
                @if($category->products->count() > 0)
                    <div id="category-{{ $category->id }}">
                        <h2 class="text-base font-semibold text-white mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-layer-group text-purple-400 text-sm"></i>
                            {{ $category->name }}
                            <span class="text-xs text-gray-500 font-normal">{{ $category->products->count() }} menu</span>
                        </h2>

                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($category->products as $product)
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

                                <div class="relative bg-gray-800 rounded-xl border border-gray-700 overflow-hidden transition-all"
                                    :class="{
                                                        'opacity-60 cursor-not-allowed': !isProductAvailable({{ Js::from($productData) }}),
                                                        'cursor-pointer hover:border-purple-500/50': isProductAvailable({{ Js::from($productData) }}),
                                                        'border-purple-500/50 ring-1 ring-purple-500/30': isInCart({{ $product->id }}) && isProductAvailable({{ Js::from($productData) }}),
                                                    }" @click="addToCart({{ Js::from($productData) }})">

                                    {{-- Tombol detail --}}
                                    <a href="{{ route('cashier.products.show', $product) }}" @click.stop
                                        class="absolute top-2 right-2 z-10 w-10 h-10 rounded-full bg-gray-900/80 hover:bg-purple-600 text-gray-400 hover:text-white flex items-center justify-center text-lg transition-colors">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </a>

                                    {{-- Badge qty di cart --}}
                                    <div x-show="isInCart({{ $product->id }}) && isProductAvailable({{ Js::from($productData) }})"
                                        class="absolute top-2 left-2 z-10 w-6 h-6 rounded-full bg-purple-600 text-white flex items-center justify-center text-xs font-bold">
                                        <span x-text="getQty({{ $product->id }})"></span>
                                    </div>

                                    {{-- Badge Habis --}}
                                    <div x-show="!isProductAvailable({{ Js::from($productData) }})"
                                        class="absolute top-2 left-2 z-10 px-2 py-0.5 bg-red-500/90 text-white text-xs rounded-full font-medium">
                                        <i class="fa-solid fa-circle-xmark mr-1"></i>Habis
                                    </div>

                                    {{-- Badge Tipis --}}
                                    <div x-show="isProductAvailable({{ Js::from($productData) }}) && isProductLow({{ Js::from($productData) }}) && !isInCart({{ $product->id }})"
                                        class="absolute top-2 left-2 z-10 px-2 py-0.5 bg-orange-500/90 text-white text-xs rounded-full font-medium">
                                        <i class="fa-solid fa-triangle-exclamation mr-1"></i>Tipis
                                    </div>

                                    {{-- Gambar --}}
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                            class="w-full aspect-square object-cover">
                                    @else
                                        <div class="w-full aspect-square bg-gray-700 flex items-center justify-center">
                                            <i class="fa-solid fa-utensils text-gray-500 text-2xl"></i>
                                        </div>
                                    @endif

                                    {{-- Overlay habis --}}
                                    <div x-show="!isProductAvailable({{ Js::from($productData) }})"
                                        class="absolute inset-0 bg-gray-900/40">
                                    </div>

                                    {{-- Info --}}
                                    <div class="p-3">
                                        <p class="text-sm font-medium text-white truncate">{{ $product->name }}</p>
                                        <p class="text-xs text-purple-400 mt-0.5 font-medium">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Floating Cart Bar --}}
        <div x-show="cart.length > 0" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed bottom-6 left-1/2 -translate-x-1/2 z-40 w-full max-w-lg px-4">
            <button @click="modalOpen = true"
                class="w-full flex items-center justify-between bg-purple-600 hover:bg-purple-700 text-white px-5 py-3.5 rounded-xl shadow-lg transition-colors">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-full bg-purple-500 flex items-center justify-center text-sm font-bold"
                        x-text="totalItems"></span>
                    <span class="text-sm font-medium">Lihat Keranjang</span>
                </div>
                <span class="text-sm font-bold" x-text="'Rp ' + formatRp(totalPrice)"></span>
            </button>
        </div>

        {{-- Modal Checkout --}}
        <div x-show="modalOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4 bg-black/60"
            @click.self="modalOpen = false">
            <div x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                class="w-full max-w-md bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden" @click.stop>

                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-700">
                    <h2 class="text-base font-semibold text-white">Konfirmasi Pesanan</h2>
                    <button @click="modalOpen = false" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="p-5 space-y-4 max-h-[70vh] overflow-y-auto">

                    {{-- List Cart --}}
                    <div class="space-y-2">
                        <h3 class="text-xs font-medium text-gray-400 uppercase">Item Pesanan</h3>
                        <template x-for="item in cart" :key="item.id">
                            <div class="flex items-center justify-between py-2 border-b border-gray-700 last:border-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-white truncate" x-text="item.name"></p>
                                    <p class="text-xs text-purple-400" x-text="'Rp ' + formatRp(item.price)"></p>
                                </div>
                                <div class="flex items-center gap-2 ml-3">
                                    <button type="button" @click="decrementQty(item.id)"
                                        class="w-6 h-6 rounded-full bg-gray-700 hover:bg-gray-600 text-white flex items-center justify-center text-xs transition-colors">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                    <span class="text-sm text-white font-medium w-4 text-center" x-text="item.qty"></span>
                                    <button type="button" @click="incrementQty(item.id)"
                                        class="w-6 h-6 rounded-full bg-gray-700 hover:bg-gray-600 text-white flex items-center justify-center text-xs transition-colors">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                                <div class="ml-3 text-right min-w-fit">
                                    <p class="text-sm text-white font-medium"
                                        x-text="'Rp ' + formatRp(item.price * item.qty)"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Form --}}
                    <form :action="'{{ route('cashier.transactions.store') }}'" method="POST">
                        @csrf

                        <template x-for="(item, index) in cart" :key="item.id">
                            <div>
                                <input type="hidden" :name="'products[' + index + '][product_id]'" :value="item.id">
                                <input type="hidden" :name="'products[' + index + '][qty]'" :value="item.qty">
                            </div>
                        </template>

                        <div class="space-y-3 mt-4">
                            {{-- Customer --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5">
                                    Nama Pelanggan <span class="text-gray-600">(opsional)</span>
                                </label>
                                <input type="text" name="customer" x-model="customer"
                                    class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors"
                                    placeholder="Nama pelanggan">
                            </div>

                            {{-- Total --}}
                            <div class="bg-gray-900 rounded-lg p-3">
                                <div class="flex justify-between text-sm font-semibold text-white">
                                    <span>Total</span>
                                    <span x-text="'Rp ' + formatRp(totalPrice)"></span>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <button type="submit"
                                class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fa-solid fa-check mr-2"></i>
                                Buat Pesanan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
@endsection