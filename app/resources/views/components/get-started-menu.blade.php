@php
    $roles = [
        'patient' => [
            'label' => 'Patient',
            'title' => 'Find and book verified clinics',
            'desc' => 'Compare clinic profiles, real patient reviews and transparent pricing, then book your appointment in minutes.',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>',
        ],
        'clinic' => [
            'label' => 'Clinic',
            'title' => 'Grow your clinic with verified patients',
            'desc' => 'Get verified, manage appointment requests and showcase your dentists and services to new patients.',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>',
        ],
        'supplier' => [
            'label' => 'Supplier',
            'title' => 'Reach verified clinics directly',
            'desc' => 'List your catalogue, respond to quotation requests and grow direct relationships with dental clinics.',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375C2.754 3.75 2.25 4.254 2.25 4.875v1.5c0 .621.504 1.125 1.125 1.125z"/>',
        ],
    ];
@endphp
<div class="relative" x-data="{ open: false, role: 'patient' }" @click.outside="open = false" @keydown.escape.window="open = false">
    <button type="button" @click="open = !open" class="dc-btn-primary !px-5 !py-2.5">
        Get Started
    </button>

    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 -translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 top-full z-50 mt-5 w-96 max-w-[92vw] origin-top-right rounded-2xl border border-white/30 bg-white/40 p-5 text-dc-text shadow-xl backdrop-blur-2xl">
        <p class="text-base font-bold text-dc-text">Join Dental Connect</p>
        <p class="mt-0.5 text-xs text-dc-text-secondary">Choose how you'd like to get started.</p>

        <div class="mt-4 grid grid-cols-3 gap-2" role="tablist" aria-label="Account type">
            @foreach ($roles as $value => $role)
                <button type="button" @click="role = '{{ $value }}'" role="tab" :aria-selected="(role === '{{ $value }}').toString()"
                        :class="role === '{{ $value }}' ? 'dc-pill-tab-active' : 'dc-pill-tab-inactive'"
                        class="dc-pill-tab justify-center text-xs">{{ $role['label'] }}</button>
            @endforeach
        </div>

        @foreach ($roles as $value => $role)
            <div x-show="role === '{{ $value }}'" x-cloak class="mt-4">
                <div class="flex items-start gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-dc-mint text-dc-teal-deep">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">{!! $role['icon'] !!}</svg>
                    </span>
                    <div>
                        <p class="text-sm font-bold text-dc-text">{{ $role['title'] }}</p>
                        <p class="mt-1 text-xs leading-relaxed text-dc-text-secondary">{{ $role['desc'] }}</p>
                    </div>
                </div>
                <a href="{{ route('register', ['role' => $value]) }}" class="dc-btn-primary mt-4 flex w-full !py-2.5 text-sm">
                    Continue as {{ $role['label'] }}
                </a>
            </div>
        @endforeach

        <p class="mt-4 text-center text-xs text-dc-text-secondary">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold text-dc-teal-deep hover:underline">Log in</a>
        </p>
    </div>
</div>
