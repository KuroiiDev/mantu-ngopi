@extends('layouts.app')

@section('title', 'Edit Menu - Mantu-Ngopi')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Edit Menu</h1>
        <a href="{{ route('manager.products.index') }}"
            class="flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-500/10 border border-red-500/20 text-red-400 text-sm rounded-lg max-w-2xl mx-auto">
            <i class="fa-solid fa-circle-exclamation mr-2"></i>
            <ul class="list-disc list-inside mt-1 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('manager.products.update', $product) }}" method="POST" enctype="multipart/form-data" x-data="{
            supplies: {{ Js::from($supplies) }},
            rows: {{ Js::from($product->supplies->map(function ($s) {
        return [
            'supply_id' => $s->id,
            'qty' => $s->pivot->qty,
            'unit' => $s->pivot->unit,
        ];
    })) }},
            profitType: 'Rp',
            savedPrice: {{ $product->price }},

            init() {
                this.$nextTick(() => {
                    const diff = this.savedPrice - this.totalCost
                    this.profitValue = diff > 0 ? diff : 0
                })
            },

            profitValue: 0,

            addRow() {
                this.rows.push({ supply_id: '', qty: 0, unit: 'Kg' })
            },
            removeRow(index) {
                this.rows.splice(index, 1)
            },
            getSupplyPrice(supply_id) {
                const s = this.supplies.find(s => s.id == supply_id)
                return s ? s.price : 0
            },
            get totalCost() {
                return this.rows.reduce((sum, row) => {
                    return sum + (this.getSupplyPrice(row.supply_id) * (parseFloat(row.qty) || 0))
                }, 0)
            },
            get profitAmount() {
                if (this.profitType === '%') {
                    return this.totalCost * ((parseFloat(this.profitValue) || 0) / 100)
                }
                return parseFloat(this.profitValue) || 0
            },
            get finalPrice() {
                return this.totalCost + this.profitAmount
            },
            formatRp(val) {
                return new Intl.NumberFormat('id-ID').format(Math.round(val))
            }
        }">

        @csrf
        @method('PUT')

        {{-- Hidden final price --}}
        <input type="hidden" name="price" :value="finalPrice">

        <div class="max-w-2xl mx-auto space-y-4">

            {{-- Gambar --}}
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                <h2 class="text-sm font-semibold text-gray-300 mb-4">Gambar Menu</h2>
                <div x-data="{ preview: '{{ $product->image ? Storage::url($product->image) : '' }}' }">
                    <div class="mb-3">
                        <div x-show="!preview"
                            class="w-full h-44 rounded-lg bg-gray-900 border border-dashed border-gray-700 flex flex-col items-center justify-center text-gray-500">
                            <i class="fa-solid fa-image text-3xl mb-2"></i>
                            <span class="text-xs">Preview gambar</span>
                        </div>
                        <img x-show="preview" :src="preview" class="w-full h-44 rounded-lg object-cover">
                    </div>
                    <input type="file" name="image" accept="image/*"
                        @change="preview = URL.createObjectURL($event.target.files[0])"
                        class="w-full text-sm text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-purple-600 file:text-white file:text-xs file:cursor-pointer hover:file:bg-purple-700">
                    <p class="text-xs text-gray-500 mt-2">Kosongkan jika tidak ingin mengubah gambar</p>
                </div>
            </div>

            {{-- Informasi --}}
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 space-y-4">
                <h2 class="text-sm font-semibold text-gray-300">Informasi Menu</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Nama Menu</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}"
                        class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors"
                        placeholder="Contoh: Kopi Susu">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Kategori</label>
                    <select name="category_id"
                        class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm focus:outline-none focus:border-purple-500 transition-colors">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Resep --}}
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-gray-300">Resep Bahan Baku</h2>
                    <button type="button" @click="addRow()"
                        class="flex items-center gap-1.5 px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium rounded-lg transition-colors">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Bahan
                    </button>
                </div>

                {{-- Header --}}
                <div class="grid grid-cols-12 gap-2 mb-2 px-1" x-show="rows.length > 0">
                    <div class="col-span-5 text-xs text-gray-500">Bahan Baku</div>
                    <div class="col-span-2 text-xs text-gray-500">Qty</div>
                    <div class="col-span-2 text-xs text-gray-500">Satuan</div>
                    <div class="col-span-2 text-xs text-gray-500">Subtotal</div>
                    <div class="col-span-1"></div>
                </div>

                {{-- Rows --}}
                <div class="space-y-2">
                    <template x-for="(row, index) in rows" :key="index">
                        <div class="grid grid-cols-12 gap-2 items-center">
                            <div class="col-span-5">
                                <select :name="'supplies[' + index + '][supply_id]'" x-model="row.supply_id"
                                    class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm focus:outline-none focus:border-purple-500 transition-colors">
                                    <option value="" disabled>Pilih bahan</option>
                                    @foreach($supplies as $supply)
                                        <option value="{{ $supply->id }}">{{ $supply->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-2">
                                <input type="number" :name="'supplies[' + index + '][qty]'" x-model="row.qty" step="0.01"
                                    min="0"
                                    class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors"
                                    placeholder="0">
                            </div>
                            <div class="col-span-2">
                                <select :name="'supplies[' + index + '][unit]'" x-model="row.unit"
                                    class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm focus:outline-none focus:border-purple-500 transition-colors">
                                    <option value="Kg">Kg</option>
                                    <option value="L">L</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <span class="text-xs text-gray-400"
                                    x-text="'Rp ' + formatRp(getSupplyPrice(row.supply_id) * (parseFloat(row.qty) || 0))">
                                </span>
                            </div>
                            <div class="col-span-1 flex justify-center">
                                <button type="button" @click="removeRow(index)"
                                    class="w-8 h-8 flex items-center justify-center bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 rounded-lg transition-colors">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Empty state --}}
                <div x-show="rows.length === 0"
                    class="py-6 text-center text-gray-500 text-sm border border-dashed border-gray-700 rounded-lg">
                    <i class="fa-solid fa-box mb-2 text-xl block"></i>
                    Belum ada bahan baku
                </div>

                {{-- Total biaya bahan --}}
                <div class="mt-4 pt-4 border-t border-gray-700 flex justify-between items-center" x-show="rows.length > 0">
                    <span class="text-xs text-gray-500">Total Biaya Bahan</span>
                    <span class="text-sm font-medium text-white" x-text="'Rp ' + formatRp(totalCost)"></span>
                </div>
            </div>

            {{-- Harga --}}
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 space-y-4">
                <h2 class="text-sm font-semibold text-gray-300">Harga</h2>

                {{-- Keuntungan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Keuntungan</label>
                    <div class="flex gap-2">
                        <div class="flex rounded-lg border border-gray-700 overflow-hidden text-xs font-medium shrink-0">
                            <button type="button" @click="profitType = '%'"
                                :class="profitType === '%' ? 'bg-purple-600 text-white' : 'bg-gray-900 text-gray-400 hover:text-white'"
                                class="px-3 py-2 transition-colors">
                                %
                            </button>
                            <button type="button" @click="profitType = 'Rp'"
                                :class="profitType === 'Rp' ? 'bg-purple-600 text-white' : 'bg-gray-900 text-gray-400 hover:text-white'"
                                class="px-3 py-2 transition-colors">
                                Rp
                            </button>
                        </div>
                        <div class="relative flex-1">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"
                                x-text="profitType === 'Rp' ? 'Rp' : ''"></span>
                            <input type="number" x-model="profitValue" min="0"
                                :class="profitType === 'Rp' ? 'pl-9' : 'pl-3'"
                                class="w-full pr-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors"
                                placeholder="0">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"
                                x-show="profitType === '%'">%</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1.5" x-show="profitType === '%' && profitValue > 0"
                        x-text="'= Rp ' + formatRp(profitAmount)">
                    </p>
                </div>

                {{-- Breakdown --}}
                <div class="bg-gray-900 rounded-lg p-4 space-y-2 text-sm">
                    <div class="flex justify-between text-gray-400">
                        <span>Biaya Bahan</span>
                        <span x-text="'Rp ' + formatRp(totalCost)"></span>
                    </div>
                    <div class="flex justify-between text-gray-400">
                        <span>Keuntungan</span>
                        <span x-text="'Rp ' + formatRp(profitAmount)"></span>
                    </div>
                    <div class="flex justify-between text-white font-semibold border-t border-gray-700 pt-2">
                        <span>Harga Jual</span>
                        <span x-text="'Rp ' + formatRp(finalPrice)"></span>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fa-solid fa-floppy-disk mr-2"></i>
                Simpan Perubahan
            </button>

        </div>
    </form>
@endsection