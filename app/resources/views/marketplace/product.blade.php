@php
    $isClinic = auth()->user()->hasAnyRole(['clinic_owner', 'clinic_admin', 'clinic_staff']);
    $isSupplier = auth()->user()->hasAnyRole(['supplier_owner', 'supplier_admin', 'supplier_staff']);
    $dashboardRoute = $isClinic ? 'clinic.dashboard' : ($isSupplier ? 'supplier.dashboard' : 'admin.dashboard');
    $nav = [
        ['route' => $dashboardRoute, 'label' => 'Dashboard', 'icon' => '🏠'],
        ['route' => 'marketplace.home', 'label' => 'Marketplace', 'icon' => '🛒'],
    ];
@endphp
<x-layouts.dashboard :title="$product->name" :nav="$nav">
    <a href="{{ route('marketplace.home') }}" class="text-sm font-semibold text-dc-teal-dark">&larr; Marketplace</a>

    <div class="mt-4 grid grid-cols-1 gap-8 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="dc-card p-6">
                <p class="text-xs font-semibold uppercase text-dc-text-secondary">{{ $product->category?->name ?? 'Dental supplies' }}</p>
                <h1 class="mt-1 text-2xl font-bold">{{ $product->name }}</h1>
                <p class="mt-1 text-sm text-dc-text-secondary">Supplied by {{ $product->supplier->name }} &middot; {{ $product->supplier->city }}</p>

                <p class="mt-4 text-lg font-semibold text-dc-teal-dark">
                    {{ $product->price_visible && $product->price ? number_format($product->price, 0).' TZS'.($product->price_unit ? ' / '.$product->price_unit : '') : 'Price on request' }}
                </p>

                @if ($product->description)
                    <p class="mt-4 text-sm text-dc-text-secondary">{{ $product->description }}</p>
                @endif

                @if ($product->specifications)
                    <h2 class="mt-6 font-semibold">Specifications</h2>
                    <p class="mt-1 text-sm text-dc-text-secondary">{{ $product->specifications }}</p>
                @endif
            </div>
        </div>

        <div>
            @if ($isClinic)
                <div class="dc-card p-5">
                    <p class="text-sm font-semibold">Request a quotation</p>
                    @if (session('status'))
                        <p class="mt-2 text-sm text-dc-success">{{ session('status') }}</p>
                    @endif
                    <form method="POST" action="{{ route('marketplace.rfqs.store', $product) }}" class="mt-4 space-y-3">
                        @csrf
                        <div>
                            <label class="mb-1 block text-xs font-medium">Quantity</label>
                            <input class="dc-input" type="number" min="1" name="quantity">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium">Message</label>
                            <textarea class="dc-input" name="message" rows="4" required placeholder="Tell the supplier what you need..."></textarea>
                        </div>
                        <button type="submit" class="dc-btn-primary w-full">Send request</button>
                    </form>
                </div>
            @else
                <div class="dc-card p-5 text-center text-sm text-dc-text-secondary">
                    Only clinic accounts can request quotations.
                </div>
            @endif
        </div>
    </div>
</x-layouts.dashboard>
