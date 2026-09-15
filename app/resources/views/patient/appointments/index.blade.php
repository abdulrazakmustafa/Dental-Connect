<x-layouts.patient-app title="My Appointments">
    <h1 class="text-lg font-semibold">My Appointments</h1>

    @if ($appointments->isEmpty())
        <div class="dc-card mt-4 p-6 text-center text-sm text-dc-text-secondary">
            No appointments yet.
            <a href="{{ route('clinics.index') }}" class="mt-2 block font-semibold text-dc-teal-dark">Find a clinic</a>
        </div>
    @else
        <div class="mt-4 space-y-3">
            @foreach ($appointments as $appointment)
                <a href="{{ route('patient.appointments.show', $appointment) }}" class="dc-card block p-4">
                    <div class="flex items-center justify-between">
                        <p class="font-medium">{{ $appointment->clinic->name }}</p>
                        <span class="dc-badge bg-dc-mint text-dc-teal-dark">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span>
                    </div>
                    <p class="mt-1 text-sm text-dc-text-secondary">{{ $appointment->preferred_date->format('D, j M Y') }}</p>
                </a>
            @endforeach
        </div>
        <div class="mt-6">{{ $appointments->links() }}</div>
    @endif
</x-layouts.patient-app>
