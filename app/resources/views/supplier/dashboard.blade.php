@php
    $nav = [
        ['route' => 'supplier.dashboard', 'label' => 'Dashboard', 'icon' => '🏠'],
        ['route' => 'supplier.products.index', 'label' => 'Products', 'icon' => '📦'],
        ['route' => 'supplier.rfqs.index', 'label' => 'RFQs', 'icon' => '✉️'],
        ['route' => 'marketplace.home', 'label' => 'Marketplace', 'icon' => '🛒'],
        ['route' => 'supplier.onboarding', 'label' => 'Profile & Verification', 'icon' => '⚙️'],
    ];
@endphp
<x-layouts.dashboard title="Dashboard" :nav="$nav">
    @if (! $supplier->isVerified())
        <div class="mb-6 rounded-xl border border-dc-warning/30 bg-amber-50 p-4 text-sm text-dc-warning">
            Your company is <strong>{{ str_replace('_', ' ', $supplier->verification_status) }}</strong>.
            <a href="{{ route('supplier.onboarding') }}" class="font-semibold underline">Complete your profile</a> to get verified.
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">Active products</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $activeProducts }}</p>
        </div>
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">Open RFQs</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $openRfqs }}</p>
        </div>
    </div>
</x-layouts.dashboard>
