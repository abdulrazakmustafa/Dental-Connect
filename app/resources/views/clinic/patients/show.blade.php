@php
    $nav = [
        ['route' => 'clinic.dashboard', 'label' => 'Dashboard', 'icon' => '🏠'],
        ['route' => 'clinic.patients.index', 'label' => 'My Patients', 'icon' => '🧑‍⚕️'],
        ['route' => 'clinic.appointments.index', 'label' => 'Appointments', 'icon' => '📅'],
        ['route' => 'marketplace.home', 'label' => 'Marketplace', 'icon' => '🛒'],
        ['route' => 'clinic.rfqs.index', 'label' => 'RFQs', 'icon' => '✉️'],
    ];
@endphp
<x-layouts.dashboard title="Patient — {{ $clinicPatient->patient_number }}" :nav="$nav">
    <a href="{{ route('clinic.patients.index') }}" class="text-sm font-semibold text-dc-teal-dark">&larr; My Patients</a>

    <div class="dc-card mt-4 p-6">
        <h2 class="text-lg font-semibold">{{ $clinicPatient->fullName() }}</h2>
        <p class="text-sm text-dc-text-secondary">Patient #{{ $clinicPatient->patient_number }}</p>
        <dl class="mt-4 grid grid-cols-2 gap-4 text-sm">
            <div><dt class="text-dc-text-secondary">Phone</dt><dd>{{ $clinicPatient->phone ?? '—' }}</dd></div>
            <div><dt class="text-dc-text-secondary">Email</dt><dd>{{ $clinicPatient->email ?? '—' }}</dd></div>
            <div><dt class="text-dc-text-secondary">Date of birth</dt><dd>{{ $clinicPatient->date_of_birth?->format('j M Y') ?? '—' }}</dd></div>
            <div><dt class="text-dc-text-secondary">Assigned dentist</dt><dd>{{ $clinicPatient->assignedDentist?->full_name ?? '—' }}</dd></div>
        </dl>
    </div>

    <div class="mt-6">
        <h3 class="font-semibold">Appointments</h3>
        <div class="mt-3 space-y-2">
            @forelse ($clinicPatient->appointments as $appointment)
                <div class="dc-card flex items-center justify-between p-4">
                    <span>{{ $appointment->preferred_date->format('D, j M Y') }}</span>
                    <span class="dc-badge bg-dc-mint text-dc-teal-dark">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span>
                </div>
            @empty
                <p class="text-sm text-dc-text-secondary">No appointments yet.</p>
            @endforelse
        </div>
    </div>
</x-layouts.dashboard>
