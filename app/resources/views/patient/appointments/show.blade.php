<x-layouts.patient-app title="Appointment">
    <a href="{{ route('patient.appointments.index') }}" class="text-sm font-semibold text-dc-teal-dark">&larr; Back</a>

    <div class="dc-card mt-4 p-5">
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold">{{ $appointment->clinic->name }}</h1>
            <span class="dc-badge bg-dc-mint text-dc-teal-dark">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span>
        </div>
        <dl class="mt-4 space-y-2 text-sm">
            <div class="flex justify-between"><dt class="text-dc-text-secondary">Date</dt><dd>{{ $appointment->preferred_date->format('D, j M Y') }}</dd></div>
            @if ($appointment->preferred_time)
                <div class="flex justify-between"><dt class="text-dc-text-secondary">Time</dt><dd>{{ $appointment->preferred_time }}</dd></div>
            @endif
            @if ($appointment->dentist)
                <div class="flex justify-between"><dt class="text-dc-text-secondary">Dentist</dt><dd>{{ $appointment->dentist->full_name }}</dd></div>
            @endif
            @if ($appointment->patient_note)
                <div><dt class="text-dc-text-secondary">Your note</dt><dd class="mt-1">{{ $appointment->patient_note }}</dd></div>
            @endif
        </dl>
    </div>

    <div class="mt-6">
        <h2 class="text-sm font-semibold text-dc-text-secondary">Status history</h2>
        <ol class="mt-3 space-y-3 border-l border-dc-border pl-4">
            @foreach ($appointment->statusHistory->sortByDesc('created_at') as $entry)
                <li class="text-sm">
                    <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $entry->to_status)) }}</p>
                    <p class="text-xs text-dc-text-secondary">{{ $entry->created_at->format('j M Y, H:i') }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</x-layouts.patient-app>
