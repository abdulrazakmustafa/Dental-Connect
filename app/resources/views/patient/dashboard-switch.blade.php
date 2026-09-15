<x-layouts.patient-app title="Switch Clinic" :back="route('patient.dashboard')" active="home">
    <p class="text-sm text-dc-text-secondary">Choose which clinic relationship you'd like to view on your dashboard.</p>

    <form method="POST" action="{{ route('patient.clinics.switch.store') }}" class="mt-4 space-y-3">
        @csrf
        @foreach ($clinicPatients as $cp)
            <label class="dc-card flex cursor-pointer items-center gap-3 p-4">
                <input type="radio" name="clinic_patient_id" value="{{ $cp->id }}" class="text-dc-teal focus:ring-dc-teal" {{ session('active_clinic_patient_id', $clinicPatients->first()?->id) == $cp->id ? 'checked' : '' }}>
                <span class="dc-avatar-square h-10 w-10">{{ strtoupper(substr($cp->clinic->name, 0, 1)) }}</span>
                <div>
                    <p class="text-sm font-bold">{{ $cp->clinic->name }}</p>
                    <p class="text-xs text-dc-text-secondary">Patient #{{ $cp->patient_number }}</p>
                </div>
            </label>
        @endforeach

        <button type="submit" class="dc-btn-primary mt-4 w-full">Switch</button>
    </form>

    <a href="{{ route('clinics.index') }}" class="dc-card mt-4 flex items-center gap-3 p-4">
        <span class="dc-avatar h-9 w-9 text-sm">+</span>
        <p class="text-sm font-bold">Enroll with another clinic</p>
    </a>
</x-layouts.patient-app>
