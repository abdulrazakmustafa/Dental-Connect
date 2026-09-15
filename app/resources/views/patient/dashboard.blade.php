<x-layouts.patient-app title="Home">
    <p class="text-sm text-dc-text-secondary">Welcome back, {{ auth()->user()->name }}.</p>

    <div class="dc-glass mt-4 rounded-2xl p-5">
        <p class="text-xs font-semibold uppercase tracking-wide text-dc-teal-dark">Next appointment</p>
        @if ($nextAppointment)
            <p class="mt-2 text-lg font-semibold">{{ $nextAppointment->clinic->name }}</p>
            <p class="text-sm text-dc-text-secondary">
                {{ $nextAppointment->preferred_date->format('D, j M Y') }}
                @if ($nextAppointment->preferred_time) at {{ $nextAppointment->preferred_time }} @endif
                &middot; <span class="dc-badge bg-dc-mint text-dc-teal-dark">{{ ucfirst(str_replace('_', ' ', $nextAppointment->status)) }}</span>
            </p>
            <a href="{{ route('patient.appointments.show', $nextAppointment) }}" class="mt-3 inline-block text-sm font-semibold text-dc-teal-dark">View details &rarr;</a>
        @else
            <p class="mt-2 text-sm text-dc-text-secondary">You have no upcoming appointments.</p>
            <a href="{{ route('clinics.index') }}" class="dc-btn-primary mt-4">Find a clinic</a>
        @endif
    </div>

    <div class="mt-8">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-semibold">My clinics</h2>
            <a href="{{ route('clinics.index') }}" class="text-sm font-semibold text-dc-teal-dark">Find more</a>
        </div>

        @if ($clinicPatients->isEmpty())
            <div class="dc-card mt-3 p-6 text-center text-sm text-dc-text-secondary">
                You haven't enrolled with a clinic yet.
                <a href="{{ route('clinics.index') }}" class="mt-2 block font-semibold text-dc-teal-dark">Browse verified clinics</a>
            </div>
        @else
            <div class="mt-3 space-y-3">
                @foreach ($clinicPatients as $cp)
                    <a href="{{ route('clinics.show', $cp->clinic) }}" class="dc-card flex items-center justify-between p-4">
                        <div>
                            <p class="font-medium">{{ $cp->clinic->name }}</p>
                            <p class="text-xs text-dc-text-secondary">Patient #{{ $cp->patient_number }}</p>
                        </div>
                        <span class="text-dc-teal-dark">&rarr;</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.patient-app>
