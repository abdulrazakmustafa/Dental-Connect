@php
    $isClinic = auth()->user()->hasAnyRole(['clinic_owner', 'clinic_admin', 'clinic_staff']);
    $isSupplier = auth()->user()->hasAnyRole(['supplier_owner', 'supplier_admin', 'supplier_staff']);
    $dashboardRoute = $isClinic ? 'clinic.dashboard' : ($isSupplier ? 'supplier.dashboard' : 'admin.dashboard');
    $nav = [
        ['route' => $dashboardRoute, 'label' => 'Dashboard', 'icon' => '🏠'],
        ['route' => 'marketplace.home', 'label' => 'Marketplace', 'icon' => '🛒'],
        $isClinic ? ['route' => 'clinic.rfqs.index', 'label' => 'My RFQs', 'icon' => '✉️'] : null,
        $isSupplier ? ['route' => 'supplier.products.index', 'label' => 'My Products', 'icon' => '📦'] : null,
        $isSupplier ? ['route' => 'supplier.rfqs.index', 'label' => 'RFQ Inbox', 'icon' => '✉️'] : null,
    ];
    $nav = array_values(array_filter($nav));
@endphp
<x-layouts.dashboard title="Marketplace" :nav="$nav">
    <form method="GET" class="mb-6 flex flex-wrap items-center gap-3">
        <input class="dc-input max-w-xs" type="text" name="q" value="{{ request('q') }}" placeholder="Search products">
        <select name="category" class="dc-input max-w-xs" onchange="this.form.submit()">
            <option value="">All categories</option>
            @foreach ($categories as $parent)
                <optgroup label="{{ $parent->name }}">
                    @foreach ($parent->children as $child)
                        <option value="{{ $child->id }}" @selected(request('category') == $child->id)>{{ $child->name }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
        <button type="submit" class="dc-btn-secondary">Filter</button>
    </form>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($products as $product)
            <a href="{{ route('marketplace.products.show', $product) }}" class="dc-card p-5">
                <p class="text-xs font-semibold uppercase text-dc-text-secondary">{{ $product->category?->name ?? 'Dental supplies' }}</p>
                <p class="mt-1 font-semibold">{{ $product->name }}</p>
                <p class="mt-1 text-xs text-dc-text-secondary">{{ $product->supplier->name }}</p>
                <div class="mt-3 flex items-center justify-between">
                    <span class="text-sm font-semibold text-dc-teal-dark">
                        {{ $product->price_visible && $product->price ? number_format($product->price, 0).' TZS' : 'Price on request' }}
                    </span>
                    <span class="dc-badge {{ $product->is_available ? 'bg-dc-mint text-dc-teal-dark' : 'bg-gray-100 text-dc-text-secondary' }}">
                        {{ $product->is_available ? 'In stock' : 'Unavailable' }}
                    </span>
                </div>
            </a>
        @empty
            <div class="dc-card col-span-full p-8 text-center text-sm text-dc-text-secondary">No products match your search yet.</div>
        @endforelse
    </div>
    <div class="mt-6">{{ $products->links() }}</div>
</x-layouts.dashboard>
