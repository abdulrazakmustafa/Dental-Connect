@php
    $nav = [
        ['route' => 'admin.dashboard', 'label' => 'Overview', 'icon' => '🏠'],
        ['route' => 'admin.clinics.verification.index', 'label' => 'Clinic Verification', 'icon' => '✅'],
    ];
@endphp
<x-layouts.dashboard title="Platform Overview" :nav="$nav" brand="Dental Connect Admin">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">Patients</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $totalPatients }}</p>
        </div>
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">Clinics</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $totalClinics }}</p>
            <p class="mt-1 text-xs text-dc-warning">{{ $clinicsPendingVerification }} pending verification</p>
        </div>
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">Suppliers</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $totalSuppliers }}</p>
            <p class="mt-1 text-xs text-dc-warning">{{ $suppliersPendingVerification }} pending verification</p>
        </div>
        <a href="{{ route('admin.clinics.verification.index') }}" class="dc-card flex flex-col justify-center p-5 text-center">
            <p class="text-sm font-semibold text-dc-teal-dark">Review verification queue &rarr;</p>
        </a>
    </div>
</x-layouts.dashboard>
