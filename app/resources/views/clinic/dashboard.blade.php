@php
    $nav = [
        ['route' => 'clinic.dashboard', 'label' => 'Dashboard', 'icon' => '🏠'],
        ['route' => 'clinic.patients.index', 'label' => 'My Patients', 'icon' => '🧑‍⚕️'],
        ['route' => 'clinic.appointments.index', 'label' => 'Appointments', 'icon' => '📅'],
        ['route' => 'marketplace.home', 'label' => 'Marketplace', 'icon' => '🛒'],
        ['route' => 'clinic.onboarding', 'label' => 'Profile & Verification', 'icon' => '⚙️'],
    ];
@endphp
<x-layouts.dashboard title="Dashboard" :nav="$nav">
    @if (! $clinic->isVerified())
        <div class="mb-6 rounded-xl border border-dc-warning/30 bg-amber-50 p-4 text-sm text-dc-warning">
            Your clinic is <strong>{{ str_replace('_', ' ', $clinic->verification_status) }}</strong>.
            @if (in_array($clinic->verification_status, ['draft', 'changes_requested']))
                <a href="{{ route('clinic.onboarding') }}" class="font-semibold underline">Complete your profile</a> to get verified.
            @endif
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">Today's appointments</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $todaysAppointments }}</p>
        </div>
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">Pending requests</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $pendingRequests }}</p>
        </div>
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">Total patients</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $totalPatients }}</p>
        </div>
    </div>
</x-layouts.dashboard>
