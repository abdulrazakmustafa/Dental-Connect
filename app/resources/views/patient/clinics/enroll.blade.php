<x-layouts.patient-app title="Enroll" :back="route('clinics.show', $clinic)" active="clinics">
    <div class="flex items-center gap-3">
        <span class="dc-avatar-square h-12 w-12 text-lg">{{ strtoupper(substr($clinic->name, 0, 1)) }}</span>
        <div>
            <h1 class="text-lg font-extrabold">Enroll with {{ $clinic->name }}</h1>
            <p class="text-xs text-dc-text-secondary">Creates a private patient record with this clinic only.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="mt-4 rounded-2xl border border-dc-danger/30 bg-dc-danger-bg p-3 text-sm text-dc-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="dc-card mt-5 p-5">
        <form method="POST" action="{{ route('patient.clinics.enroll', $clinic) }}" class="space-y-4">
            @csrf
            <div>
                <label class="mb-1.5 block text-sm font-semibold">First name</label>
                <input class="dc-input" type="text" name="first_name" value="{{ old('first_name', explode(' ', auth()->user()->name)[0] ?? '') }}" required>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Last name</label>
                <input class="dc-input" type="text" name="last_name" value="{{ old('last_name') }}" required>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Date of birth <span class="font-normal text-dc-text-secondary">(optional)</span></label>
                <input class="dc-input" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}">
            </div>
            <button type="submit" class="dc-btn-primary w-full">Enroll</button>
        </form>
    </div>

    <div class="dc-card mt-4 flex items-start gap-3 p-4">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-dc-mint text-dc-teal-deep">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
        <div>
            <p class="text-sm font-bold">Your clinic relationship stays private</p>
            <p class="mt-0.5 text-xs text-dc-text-secondary">Only {{ $clinic->name }} can see this enrollment. Enrolling with another clinic later creates a separate record.</p>
        </div>
    </div>
</x-layouts.patient-app>
