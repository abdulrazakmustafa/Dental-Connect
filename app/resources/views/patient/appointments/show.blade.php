@php
    $statusStyle = [
        'requested' => 'bg-white text-dc-teal-deep',
        'reschedule_proposed' => 'bg-white text-dc-teal-deep',
        'confirmed' => 'bg-white text-dc-teal-deep',
        'completed' => 'bg-white text-dc-teal-deep',
        'cancelled' => 'bg-white text-dc-text-secondary',
        'declined' => 'bg-white text-dc-danger',
        'no_show' => 'bg-white text-dc-danger',
    ];
    $canManage = in_array($appointment->status, ['requested', 'confirmed', 'reschedule_proposed'], true);
@endphp
<x-layouts.patient-app title="Appointment Details" :back="route('patient.appointments.index')" active="appointments">
    @if (session('status'))
        <div class="mb-4 rounded-2xl border border-dc-success/30 bg-dc-success-bg p-3 text-sm text-dc-success">{{ session('status') }}</div>
    @endif

    <div class="dc-hero">
        <div class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-white/10"></div>
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold uppercase text-white/80">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }} appointment</p>
                <h1 class="mt-1 text-xl font-extrabold">{{ $appointment->service?->name ?? 'General appointment' }}</h1>
                <p class="mt-1 text-sm text-white/90">{{ $appointment->clinic->name }} &middot; {{ $appointment->clinic->primaryLocation?->area ?? $appointment->clinic->primaryLocation?->city }}</p>
            </div>
            <span class="dc-badge shrink-0 {{ $statusStyle[$appointment->status] ?? 'bg-white text-dc-teal-deep' }}">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span>
        </div>
    </div>

    <div class="dc-card mt-4 grid grid-cols-2 gap-4 p-5 text-sm">
        <div>
            <p class="text-xs text-dc-text-secondary">Date</p>
            <p class="mt-0.5 font-bold">{{ $appointment->preferred_date->format('j M Y') }}</p>
        </div>
        <div>
            <p class="text-xs text-dc-text-secondary">Time</p>
            <p class="mt-0.5 font-bold">{{ $appointment->formattedTime() ?? 'Flexible' }}</p>
        </div>
        <div>
            <p class="text-xs text-dc-text-secondary">Dentist</p>
            <p class="mt-0.5 font-bold">{{ $appointment->dentist?->full_name ?? 'Unassigned' }}</p>
        </div>
        <div>
            <p class="text-xs text-dc-text-secondary">Status</p>
            <p class="mt-0.5"><span class="dc-badge bg-dc-mint text-dc-teal-deep">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span></p>
        </div>
    </div>

    @if ($appointment->clinic_note)
        <h2 class="mt-6 text-sm font-bold">Clinic note</h2>
        <div class="dc-card mt-2 p-4 text-sm text-dc-text-secondary">{{ $appointment->clinic_note }}</div>
    @endif

    @if ($canManage)
        <div class="mt-6 flex gap-3">
            <a href="{{ route('patient.appointments.reschedule', $appointment) }}" class="dc-btn-secondary flex-1 text-center !text-dc-teal-deep">Reschedule</a>
            <form method="POST" action="{{ route('patient.appointments.cancel', $appointment) }}" class="flex-1" onsubmit="return confirm('Cancel this appointment?')">
                @csrf @method('PATCH')
                <button type="submit" class="dc-btn-secondary w-full !border-dc-danger/30 !bg-dc-danger-bg !text-dc-danger">Cancel</button>
            </form>
        </div>
    @elseif ($appointment->status === 'completed')
        <a href="{{ route('patient.reviews.create', $appointment) }}" class="dc-btn-primary mt-6 block w-full text-center">Rate Your Experience</a>
    @endif
</x-layouts.patient-app>
