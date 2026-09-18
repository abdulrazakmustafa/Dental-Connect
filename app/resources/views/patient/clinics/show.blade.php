@php
    $rating = $clinic->reviews_count > 0 ? number_format($clinic->reviews_avg_rating, 1) : null;
    $recentReviews = $clinic->reviews()->where('moderation_status', 'published')->latest()->take(3)->get();
@endphp
<x-layouts.patient-app title="Clinic Profile" :back="route('clinics.index')" active="clinics">
    <div x-data="{ saved: isFavoriteClinic('{{ $clinic->public_id }}') }">
        @if (session('status'))
            <div class="mb-4 rounded-2xl border border-dc-success/30 bg-dc-success-bg p-3 text-sm text-dc-success">{{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3 lg:items-start">
            <div class="space-y-5 lg:col-span-2">
                <div class="relative flex aspect-[3/1.6] items-center justify-center overflow-hidden rounded-3xl bg-gradient-to-br from-dc-mint-light to-dc-aqua">
                    <span class="absolute left-4 top-4 dc-badge bg-white/80 text-dc-teal-deep">Verified Clinic</span>
                    <button type="button" @click="saved = toggleFavoriteClinic('{{ $clinic->public_id }}')"
                            class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full bg-white/80 shadow-sm transition"
                            :class="saved ? 'text-dc-danger' : 'text-dc-text-secondary hover:text-dc-danger'" aria-label="Save clinic">
                        <svg width="17" height="17" viewBox="0 0 24 24" :fill="saved ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="1.8"><path d="M12 20.5s-7.5-4.6-9.7-9.1C.7 8 2.3 4.7 5.6 4.1c2-.4 3.9.5 5 2.1 1.1-1.6 3-2.5 5-2.1 3.3.6 4.9 3.9 3.3 7.3-2.2 4.5-9.7 9.1-9.7 9.1z" stroke-linejoin="round"/></svg>
                    </button>
                    <svg width="90" height="90" viewBox="0 0 24 24" fill="none">
                        <path d="M12 3.2c-1.1 0-1.9.55-2.85.8-.5.13-1 .2-1.55.2C5.5 4.2 4 5.9 4 8.1c0 2.35.55 4.55 1.3 6.75.45 1.35.85 3.1 1.85 3.2.75.07.9-1.35 1.1-2.5.2-1.2.55-2.55 1.75-2.55s1.55 1.35 1.75 2.55c.2 1.15.35 2.57 1.1 2.5 1-.1 1.4-1.85 1.85-3.2.75-2.2 1.3-4.4 1.3-6.75 0-2.2-1.5-3.9-3.6-3.9-.55 0-1.05-.07-1.55-.2-.95-.25-1.75-.8-2.85-.8Z" stroke="#14B8A6" stroke-width="1.3" stroke-linejoin="round"/>
                    </svg>
                </div>

                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-xl font-extrabold">{{ $clinic->name }}</h1>
                        <p class="mt-0.5 text-sm text-dc-text-secondary">{{ $clinic->primaryLocation?->area ? $clinic->primaryLocation->area.', ' : '' }}{{ $clinic->primaryLocation?->city ?? 'Dar es Salaam' }}</p>
                    </div>
                    @if ($rating)
                        <span class="shrink-0 text-sm font-bold text-amber-500">★ {{ $rating }} <span class="font-normal text-dc-text-secondary">({{ $clinic->reviews_count }})</span></span>
                    @endif
                </div>

                <div class="flex flex-wrap gap-2">
                    @foreach ($clinic->specialties->take(4) as $specialty)
                        <span class="dc-badge bg-dc-mint text-dc-teal-deep">{{ $specialty->name }}</span>
                    @endforeach
                </div>

                <div class="dc-card p-5">
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
                            <p class="font-bold">{{ $clinic->primaryLocation?->city ?? '-' }}</p>
                            <p class="text-xs text-dc-text-secondary">Location</p>
                        </div>
                    </div>
                </div>

                @if ($clinic->services->isNotEmpty())
                    <div>
                        <h2 class="font-bold">Popular services</h2>
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
                    </div>
                @endif

                @if ($clinic->dentists->isNotEmpty())
                    <div>
                        <h2 class="font-bold">Our dentists</h2>
                        <div class="mt-2 flex gap-3 overflow-x-auto pb-1">
                            @foreach ($clinic->dentists as $dentist)
                                <div class="dc-card w-40 shrink-0 p-4 text-center">
                                    <span class="dc-avatar mx-auto h-12 w-12 text-sm">{{ collect(explode(' ', str_replace('Dr. ', '', $dentist->full_name)))->map(fn($p) => strtoupper(substr($p,0,1)))->implode('') }}</span>
                                    <p class="mt-2 truncate text-sm font-bold">{{ $dentist->full_name }}</p>
                                    <p class="truncate text-xs text-dc-text-secondary">{{ $dentist->specialties->first()?->name ?? 'General Dentist' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($recentReviews->isNotEmpty())
                    <div>
                        <h2 class="font-bold">Patient reviews</h2>
                        <div class="mt-2 space-y-2">
                            @foreach ($recentReviews as $review)
                                <div class="dc-card p-4">
                                    <div class="flex items-center gap-0.5 text-amber-500">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="{{ $i <= $review->rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5"><path d="M12 2.5l2.9 6 6.6.9-4.8 4.6 1.1 6.6L12 17.6l-5.8 3 1.1-6.6-4.8-4.6 6.6-.9L12 2.5z" stroke-linejoin="round"/></svg>
                                        @endfor
                                    </div>
                                    @if ($review->comment)
                                        <p class="mt-2 text-sm text-dc-text-secondary">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Desktop CTA lives inline; mobile gets a sticky bottom bar instead (see below). --}}
                <div class="hidden lg:block">
                    @if ($clinicPatient)
                        <a href="{{ route('patient.appointments.book', $clinic) }}" wire:navigate class="dc-btn-primary block w-full text-center">Book Appointment</a>
                    @else
                        <a href="{{ route('patient.clinics.enroll.form', $clinic) }}" wire:navigate class="dc-btn-primary block w-full text-center">Enroll / Book Appointment</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="fixed inset-x-0 bottom-[calc(4.5rem+env(safe-area-inset-bottom))] z-30 px-4 md:bottom-4 md:left-24 md:right-4 lg:hidden">
            <div class="mx-auto max-w-lg rounded-3xl border border-white/40 bg-white/90 p-3 shadow-2xl shadow-dc-teal-deep/15 backdrop-blur-xl">
                @if ($clinicPatient)
                    <a href="{{ route('patient.appointments.book', $clinic) }}" wire:navigate class="dc-btn-primary block w-full text-center">Book Appointment</a>
                @else
                    <a href="{{ route('patient.clinics.enroll.form', $clinic) }}" wire:navigate class="dc-btn-primary block w-full text-center">Enroll / Book Appointment</a>
                @endif
            </div>
        </div>
    </div>
</x-layouts.patient-app>
