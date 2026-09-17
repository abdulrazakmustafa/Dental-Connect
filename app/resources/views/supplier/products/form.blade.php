@php
    $nav = [
        ['route' => 'supplier.dashboard', 'label' => 'Dashboard', 'icon' => '🏠'],
        ['route' => 'supplier.products.index', 'label' => 'Products', 'icon' => '📦'],
        ['route' => 'supplier.rfqs.index', 'label' => 'RFQs', 'icon' => '✉️'],
        ['route' => 'marketplace.home', 'label' => 'Marketplace', 'icon' => '🛒'],
    ];
    $editing = $product->exists;
@endphp
<x-layouts.dashboard :title="$editing ? 'Edit Product' : 'Add Product'" :nav="$nav">
    <div class="mx-auto max-w-2xl">
        <div class="dc-card p-6">
            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-dc-danger/30 bg-red-50 p-4 text-sm text-dc-danger">
                    <ul class="list-disc space-y-1 pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ $editing ? route('supplier.products.update', $product) : route('supplier.products.store') }}" class="space-y-4">
                @csrf
                @if ($editing) @method('PUT') @endif

                <div>
                    <label class="mb-1 block text-sm font-medium">Product name</label>
                    <input class="dc-input" type="text" name="name" value="{{ old('name', $product->name) }}" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium">Category</label>
                        <select class="dc-input" name="category_id">
                            <option value="">None</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">Brand</label>
                        <input class="dc-input" type="text" name="brand" value="{{ old('brand', $product->brand) }}">
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Description</label>
                    <textarea class="dc-input" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium">Price (TZS)</label>
                        <input class="dc-input" type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">Unit</label>
                        <input class="dc-input" type="text" name="price_unit" placeholder="e.g. per box" value="{{ old('price_unit', $product->price_unit) }}">
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm">
                    <input type="hidden" name="price_visible" value="0">
                    <input type="checkbox" name="price_visible" value="1" @checked(old('price_visible', $product->price_visible ?? true)) class="rounded border-dc-border text-dc-teal">
                    Show price to clinics
                </label>

                <button type="submit" class="dc-btn-primary w-full">{{ $editing ? 'Save changes' : 'Add product' }}</button>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
