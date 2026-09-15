<x-layouts.patient-app title="Appointment Status" :back="route('patient.dashboard')" active="appointments">
    <div class="flex flex-col items-center text-center">
        <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-dc-success-bg">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="#22C55E" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
        <h1 class="mt-4 text-2xl font-extrabold">Request sent!</h1>
        <p class="mt-2 max-w-xs text-sm text-dc-text-secondary">
            {{ $appointment->clinic->name }} received your request. You'll be notified when the clinic confirms or proposes a new time.
        </p>
    </div>

    <div class="dc-card mt-6 p-5">
        <div class="flex items-center justify-between">
            <p class="font-bold">{{ $appointment->service?->name ?? 'General appointment' }}</p>
            <span class="dc-badge bg-dc-warning-bg text-dc-warning">Requested</span>
        </div>
        <p class="mt-0.5 text-sm text-dc-text-secondary">{{ $appointment->clinic->name }}</p>

        <div class="mt-4 grid grid-cols-2 gap-4 border-t border-dc-border pt-4 text-sm">
            <div>
                <p class="text-xs text-dc-text-secondary">Date</p>
                <p class="mt-0.5 font-bold">{{ $appointment->preferred_date->format('j M Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-dc-text-secondary">Preferred time</p>
                <p class="mt-0.5 font-bold">{{ $appointment->formattedTime() ?? 'Flexible' }}</p>
            </div>
        </div>
    </div>

    <a href="{{ route('patient.appointments.index') }}" class="dc-btn-primary mt-6 block w-full text-center">View My Appointments</a>
    <a href="{{ route('patient.dashboard') }}" class="dc-btn-secondary mt-3 block w-full text-center">Back to Home</a>
</x-layouts.patient-app>
