@php
    $today = now()->toDateString();
    $initialTime = $appointment->preferred_time ? substr($appointment->preferred_time, 0, 5) : null;
@endphp
<x-layouts.patient-app title="Reschedule" :back="route('patient.appointments.show', $appointment)" active="appointments">
    @if ($errors->any())
        <div class="mb-4 rounded-2xl border border-dc-danger/30 bg-dc-danger-bg p-3 text-sm text-dc-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <div x-data="{ ...dcDatePicker('{{ $today }}', '{{ $appointment->preferred_date->toDateString() }}', {{ $initialTime ? "'{$initialTime}'" : 'null' }}) }">
        <p class="text-sm text-dc-text-secondary">Choose a new date and time for your {{ $appointment->service?->name ?? 'appointment' }} at {{ $appointment->clinic->name }}. The clinic will need to reconfirm.</p>

        <div class="mt-4">
            @include('patient.appointments._date-time-picker')
        </div>

        <form method="POST" action="{{ route('patient.appointments.reschedule.update', $appointment) }}" class="mt-6">
            @csrf @method('PATCH')
            <input type="hidden" name="preferred_date" :value="date">
            <input type="hidden" name="preferred_time" :value="time">
            <button type="submit" class="dc-btn-primary w-full" :disabled="!date || !time">Confirm New Time</button>
        </form>
    </div>
</x-layouts.patient-app>
