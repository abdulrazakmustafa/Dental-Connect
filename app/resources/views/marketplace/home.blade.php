@php
    $nav = [
        ['route' => auth()->user()->hasAnyRole(['clinic_owner', 'clinic_admin', 'clinic_staff']) ? 'clinic.dashboard' : (auth()->user()->hasAnyRole(['supplier_owner', 'supplier_admin', 'supplier_staff']) ? 'supplier.dashboard' : 'admin.dashboard'), 'label' => 'Dashboard', 'icon' => '🏠'],
        ['route' => 'marketplace.home', 'label' => 'Marketplace', 'icon' => '🛒'],
    ];
@endphp
<x-layouts.dashboard title="Marketplace" :nav="$nav">
    <div class="dc-card p-8 text-center">
        <p class="text-lg font-semibold">Dental-material marketplace</p>
        <p class="mt-2 text-sm text-dc-text-secondary">
            Categories, product search, supplier catalogues and RFQs land here in the Marketplace
            milestone (PRD §5 MKT / §44-§46). Access to this page is already enforced server-side —
            only verified clinics, suppliers and authorized admins can reach it.
        </p>
    </div>
</x-layouts.dashboard>
