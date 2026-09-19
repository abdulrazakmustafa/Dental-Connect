@php
    $statusStyle = [
        'requested' => 'bg-dc-warning-bg text-dc-warning',
        'reschedule_proposed' => 'bg-dc-warning-bg text-dc-warning',
        'confirmed' => 'bg-dc-success-bg text-dc-success',
        'completed' => 'bg-dc-info-bg text-dc-info',
        'cancelled' => 'bg-gray-100 text-dc-text-secondary',
        'declined' => 'bg-dc-danger-bg text-dc-danger',
        'no_show' => 'bg-dc-danger-bg text-dc-danger',
    ];
@endphp
<x-layouts.patient-app title="My Appointments" :back="route('patient.dashboard')" active="appointments">
    <div class="flex gap-2 overflow-x-auto pb-1">
        @foreach (['upcoming' => 'Upcoming', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $value => $label)
            <a href="{{ route('patient.appointments.index', ['tab' => $value]) }}" wire:navigate
               class="dc-pill-tab shrink-0 {{ $tab === $value ? 'dc-pill-tab-active' : 'dc-pill-tab-inactive' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="mt-4 grid grid-cols-1 gap-3 lg:grid-cols-2">
        @forelse ($appointments as $appointment)
            <a href="{{ route('patient.appointments.show', $appointment) }}" wire:navigate
               class="dc-card flex items-center gap-3 p-4 transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-2xl bg-dc-mint-light text-dc-teal-deep">
                    <span class="text-base font-extrabold leading-none">{{ $appointment->preferred_date->format('d') }}</span>
                    <span class="text-[10px] font-semibold uppercase leading-none">{{ $appointment->preferred_date->format('M') }}</span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-bold">{{ $appointment->service?->name ?? 'Appointment' }}</p>
                    <p class="mt-0.5 truncate text-xs text-dc-text-secondary">{{ $appointment->clinic->name }} @if($appointment->preferred_time) &middot; {{ $appointment->formattedTime() }} @endif</p>
                </div>
                <span class="dc-badge shrink-0 self-start {{ $statusStyle[$appointment->status] ?? 'bg-gray-100 text-dc-text-secondary' }}">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span>
            </a>
        @empty
            <div class="dc-card p-6 text-center text-sm text-dc-text-secondary lg:col-span-2">No appointments here yet.</div>
        @endforelse

        <a href="{{ route('clinics.index') }}" wire:navigate class="dc-card flex items-center gap-3 p-4 lg:col-span-2">
            <span class="dc-avatar h-9 w-9 text-sm">C</span>
            <div>
                <p class="text-sm font-bold">Want to visit another clinic?</p>
                <p class="mt-0.5 text-xs text-dc-text-secondary">Find a clinic and complete a separate enrollment.</p>
            </div>
        </a>
    </div>

    <div class="mt-4">{{ $appointments->links() }}</div>
</x-layouts.patient-app>
