@php
    $rating = $clinic->reviews_count > 0 ? number_format($clinic->reviews_avg_rating, 1) : null;
@endphp
<x-layouts.patient-app title="Clinic Profile" :back="route('clinics.index')" active="clinics">
    @if (session('status'))
        <div class="mb-4 rounded-2xl border border-dc-success/30 bg-dc-success-bg p-3 text-sm text-dc-success">{{ session('status') }}</div>
    @endif

    <div class="relative flex aspect-[3/1.6] items-center justify-center overflow-hidden rounded-3xl bg-gradient-to-br from-dc-mint-light to-dc-aqua">
        <span class="absolute right-4 top-4 dc-badge bg-white/80 text-dc-teal-deep">Verified Clinic</span>
        <svg width="90" height="90" viewBox="0 0 24 24" fill="none">
            <path d="M12 3.2c-1.1 0-1.9.55-2.85.8-.5.13-1 .2-1.55.2C5.5 4.2 4 5.9 4 8.1c0 2.35.55 4.55 1.3 6.75.45 1.35.85 3.1 1.85 3.2.75.07.9-1.35 1.1-2.5.2-1.2.55-2.55 1.75-2.55s1.55 1.35 1.75 2.55c.2 1.15.35 2.57 1.1 2.5 1-.1 1.4-1.85 1.85-3.2.75-2.2 1.3-4.4 1.3-6.75 0-2.2-1.5-3.9-3.6-3.9-.55 0-1.05-.07-1.55-.2-.95-.25-1.75-.8-2.85-.8Z" stroke="#14B8A6" stroke-width="1.3" stroke-linejoin="round"/>
        </svg>
    </div>

    <div class="mt-4 flex items-start justify-between">
        <div>
            <h1 class="text-xl font-extrabold">{{ $clinic->name }}</h1>
            <p class="mt-0.5 text-sm text-dc-text-secondary">{{ $clinic->primaryLocation?->city ?? 'Dar es Salaam' }}, Dar es Salaam</p>
        </div>
        @if ($rating)
            <span class="shrink-0 text-sm font-bold text-amber-500">★ {{ $rating }}</span>
        @endif
    </div>

    <div class="mt-3 flex flex-wrap gap-2">
        @foreach ($clinic->specialties->take(3) as $specialty)
            <span class="dc-badge bg-dc-mint text-dc-teal-deep">{{ $specialty->name }}</span>
        @endforeach
    </div>

    <div class="dc-card mt-5 p-5">
        <h2 class="font-bold">About clinic</h2>
        <p class="mt-1 text-sm text-dc-text-secondary">{{ $clinic->description ?? 'Modern dental care with verified professionals, clear services and a patient-centered approach.' }}</p>

        <div class="mt-4 grid grid-cols-3 divide-x divide-dc-border border-t border-dc-border pt-4 text-center">
            <div>
                <p class="font-bold">{{ $clinic->dentists->count() }}+</p>
                <p class="text-xs text-dc-text-secondary">Dentists</p>
            </div>
            <div>
                <p class="font-bold">Mon-Sat</p>
                <p class="text-xs text-dc-text-secondary">Open</p>
            </div>
            <div>
                <p class="font-bold">{{ $clinic->primaryLocation?->city ?? '—' }}</p>
                <p class="text-xs text-dc-text-secondary">Location</p>
            </div>
        </div>
    </div>

    @if ($clinic->services->isNotEmpty())
        <h2 class="mt-6 font-bold">Popular services</h2>
        <div class="mt-2 space-y-2">
            @foreach ($clinic->services->take(4) as $service)
                <div class="dc-card flex items-center justify-between p-4">
                    <div>
                        <p class="text-sm font-bold">{{ $service->name }}</p>
                        <p class="text-xs text-dc-text-secondary">Consultation and treatment</p>
                    </div>
                    @if ($service->pivot->price)
                        <span class="dc-badge bg-dc-info-bg text-dc-info">TZS {{ number_format($service->pivot->price) }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-6">
        @if ($clinicPatient)
            <a href="{{ route('patient.appointments.book', $clinic) }}" class="dc-btn-primary block w-full text-center">Book Appointment</a>
        @else
            <a href="{{ route('patient.clinics.enroll.form', $clinic) }}" class="dc-btn-primary block w-full text-center">Enroll / Book Appointment</a>
        @endif
    </div>
</x-layouts.patient-app>
