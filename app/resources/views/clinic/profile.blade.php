<x-layouts.public :title="$clinic->name.' | Dental Connect'">
    <section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 rounded-xl border border-dc-success/30 bg-green-50 p-4 text-sm text-dc-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="dc-glass rounded-2xl p-6">
            <div class="flex items-center gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-dc-teal text-2xl font-bold text-white">
                    {{ strtoupper(substr($clinic->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-2xl font-bold">{{ $clinic->name }}</h1>
                    <p class="text-sm text-dc-text-secondary">
                        {{ $clinic->primaryLocation?->address_line }}, {{ $clinic->primaryLocation?->city }}
                    </p>
                    <span class="dc-badge mt-1 bg-dc-mint text-dc-teal-dark">✓ Verified clinic</span>
                </div>
            </div>
            @if ($clinic->description)
                <p class="mt-4 text-sm text-dc-text-secondary">{{ $clinic->description }}</p>
            @endif
        </div>

        <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <h2 class="font-semibold">Services</h2>
                <div class="mt-2 flex flex-wrap gap-2">
                    @forelse ($clinic->services as $service)
                        <span class="dc-badge bg-dc-aqua text-dc-teal-dark">{{ $service->name }}</span>
                    @empty
                        <p class="text-sm text-dc-text-secondary">No services listed yet.</p>
                    @endforelse
                </div>

                <h2 class="mt-8 font-semibold">Dentists</h2>
                <div class="mt-2 space-y-2">
                    @forelse ($clinic->dentists as $dentist)
                        <div class="dc-card p-4">
                            <p class="font-medium">{{ $dentist->full_name }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-dc-text-secondary">No dentists listed yet.</p>
                    @endforelse
                </div>
            </div>

            <div>
                @auth
                    @if (auth()->user()->hasRole('patient'))
                        @if ($clinicPatient)
                            <div class="dc-card p-5">
                                <p class="text-sm font-semibold text-dc-teal-dark">You're enrolled</p>
                                <p class="mt-1 text-xs text-dc-text-secondary">Patient #{{ $clinicPatient->patient_number }}</p>
                                <form method="POST" action="{{ route('patient.appointments.store', $clinic) }}" class="mt-4 space-y-3">
                                    @csrf
                                    <div>
                                        <label class="mb-1 block text-xs font-medium">Preferred date</label>
                                        <input class="dc-input" type="date" name="preferred_date" required min="{{ now()->toDateString() }}">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium">Note (optional)</label>
                                        <textarea class="dc-input" name="patient_note" rows="3"></textarea>
                                    </div>
                                    <button type="submit" class="dc-btn-primary w-full">Request appointment</button>
                                </form>
                            </div>
                        @else
                            <div class="dc-card p-5">
                                <p class="text-sm font-semibold">Enroll with this clinic</p>
                                <form method="POST" action="{{ route('patient.clinics.enroll', $clinic) }}" class="mt-4 space-y-3">
                                    @csrf
                                    <input class="dc-input" type="text" name="first_name" placeholder="First name" required>
                                    <input class="dc-input" type="text" name="last_name" placeholder="Last name" required>
                                    <input class="dc-input" type="date" name="date_of_birth" placeholder="Date of birth">
                                    <button type="submit" class="dc-btn-primary w-full">Enroll</button>
                                </form>
                            </div>
                        @endif
                    @endif
                @else
                    <div class="dc-card p-5 text-center">
                        <p class="text-sm text-dc-text-secondary">Log in as a patient to enroll and book an appointment.</p>
                        <a href="{{ route('login') }}" class="dc-btn-primary mt-4 w-full">Log in</a>
                    </div>
                @endauth
            </div>
        </div>
    </section>
</x-layouts.public>
