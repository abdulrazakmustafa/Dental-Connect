@php
    $rating = $clinic->reviews_count > 0 ? number_format($clinic->reviews_avg_rating, 1) : null;
    $location = collect([$clinic->primaryLocation?->area, $clinic->primaryLocation?->city ?? 'Dar es Salaam'])->filter()->implode(', ');
    $initials = fn ($name) => collect(explode(' ', str_replace('Dr. ', '', $name)))->map(fn ($p) => strtoupper(substr($p, 0, 1)))->take(2)->implode('');
@endphp
<x-layouts.patient-app title="Support" :back="route('patient.dashboard')" active="support">
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3 lg:items-start">
        <div class="space-y-5 lg:col-span-2">
            {{-- Your clinic --}}
            <div class="dc-hero relative overflow-hidden md:p-8">
                <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/10"></div>
                <div class="flex items-start justify-between">
                    <span class="dc-badge bg-white/20 text-white">Your clinic &middot; Verified</span>
                    @if ($rating)
                        <span class="flex items-center gap-1 rounded-full bg-white/20 px-2.5 py-1 text-xs font-bold text-white">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" class="text-amber-300"><path d="M12 2.5l2.9 6 6.6.9-4.8 4.6 1.1 6.6L12 17.6l-5.8 3 1.1-6.6-4.8-4.6 6.6-.9L12 2.5z"/></svg>
                            {{ $rating }} <span class="font-normal text-white/80">({{ $clinic->reviews_count }})</span>
                        </span>
                    @endif
                </div>
                <h1 class="mt-4 text-2xl font-extrabold">{{ $clinic->name }}</h1>
                <p class="mt-1 text-sm text-white/90">{{ $location }} &middot; Patient #{{ $clinicPatient->patient_number }}</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($clinic->specialties->take(4) as $specialty)
                        <span class="dc-badge bg-white/20 text-white">{{ $specialty->name }}</span>
                    @endforeach
                </div>
                <a href="{{ route('patient.appointments.book', $clinic) }}" wire:navigate class="dc-btn-white mt-5 inline-flex">Book appointment</a>
            </div>

            {{-- Contact --}}
            <div class="dc-card divide-y divide-white/60 p-2">
                @if ($clinic->phone)
                    <a href="tel:{{ $clinic->phone }}" class="flex items-center gap-3 rounded-2xl p-3 transition hover:bg-white/50">
                        <span class="dc-avatar-square h-10 w-10 shrink-0"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 005 5L15 13l5 2v4a2 2 0 01-2 2A16 16 0 013 6a2 2 0 012-2z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg></span>
                        <span class="min-w-0 flex-1"><span class="block text-xs text-dc-text-secondary">Call the clinic</span><span class="block truncate text-sm font-bold">{{ $clinic->phone }}</span></span>
                    </a>
                @endif
                @if ($clinic->email)
                    <a href="mailto:{{ $clinic->email }}" class="flex items-center gap-3 rounded-2xl p-3 transition hover:bg-white/50">
                        <span class="dc-avatar-square h-10 w-10 shrink-0"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="3.5" y="5.5" width="17" height="13" rx="2" stroke="currentColor" stroke-width="1.7"/><path d="M4.5 7l7.5 6 7.5-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                        <span class="min-w-0 flex-1"><span class="block text-xs text-dc-text-secondary">Email the clinic</span><span class="block truncate text-sm font-bold">{{ $clinic->email }}</span></span>
                    </a>
                @endif
                <div class="flex items-center gap-3 rounded-2xl p-3">
                    <span class="dc-avatar-square h-10 w-10 shrink-0"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 21s7-5.4 7-11a7 7 0 10-14 0c0 5.6 7 11 7 11z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><circle cx="12" cy="10" r="2.4" stroke="currentColor" stroke-width="1.7"/></svg></span>
                    <span class="min-w-0 flex-1"><span class="block text-xs text-dc-text-secondary">Location &middot; Open Mon-Sat</span><span class="block truncate text-sm font-bold">{{ $location }}</span></span>
                </div>
            </div>

            <div class="dc-card p-5">
                <h2 class="font-bold">About the clinic</h2>
                <p class="mt-1 text-sm text-dc-text-secondary">{{ $clinic->description ?? 'Modern dental care with verified professionals, clear services and a patient-centered approach.' }}</p>
            </div>

            @if ($clinic->services->isNotEmpty())
                <div>
                    <h2 class="font-bold">Services &amp; prices</h2>
                    <div class="mt-2 grid grid-cols-1 gap-2 md:grid-cols-2">
                        @foreach ($clinic->services as $service)
                            <div class="dc-card flex items-center justify-between p-4">
                                <p class="text-sm font-bold">{{ $service->name }}</p>
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
                    <h2 class="font-bold">Your dentists</h2>
                    <div class="mt-2 grid grid-cols-2 gap-3 md:grid-cols-3">
                        @foreach ($clinic->dentists as $dentist)
                            <div class="dc-card p-4 text-center">
                                <span class="dc-avatar mx-auto h-12 w-12 text-sm">{{ $initials($dentist->full_name) }}</span>
                                <p class="mt-2 truncate text-sm font-bold">{{ $dentist->full_name }}</p>
                                <p class="truncate text-xs text-dc-text-secondary">{{ $dentist->specialties->first()?->name ?? 'General Dentist' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($reviews->isNotEmpty())
                <div>
                    <h2 class="font-bold">Patient reviews</h2>
                    <div class="mt-2 space-y-2">
                        @foreach ($reviews as $review)
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
        </div>

        {{-- About the app --}}
        <div class="space-y-5">
            <div class="dc-card p-5">
                <x-dc-logo :size="34" />
                <h2 class="mt-4 font-bold">About the app</h2>
                <p class="mt-1 text-sm text-dc-text-secondary">Dental Connect links you with your verified clinic: request and manage appointments, get updates from your clinic and leave reviews after a visit.</p>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-dc-text-secondary">Version</dt><dd class="font-semibold">1.0</dd></div>
                    <div class="flex justify-between"><dt class="text-dc-text-secondary">Your data</dt><dd class="font-semibold">Private to your clinic</dd></div>
                </dl>
            </div>

            <div class="dc-card divide-y divide-white/60 p-2">
                @foreach ([
                    ['How Dental Connect works', route('home').'#how-it-works'],
                    ['About us', route('about')],
                    ['Contact Dental Connect support', route('contact')],
                ] as [$label, $href])
                    <a href="{{ $href }}" class="flex items-center justify-between rounded-2xl p-3 text-sm font-semibold transition hover:bg-white/50">
                        {{ $label }} <span class="text-dc-text-secondary">&rsaquo;</span>
                    </a>
                @endforeach
            </div>

            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="text-sm font-bold text-dc-danger">Log out</button>
            </form>
        </div>
    </div>
</x-layouts.patient-app>
