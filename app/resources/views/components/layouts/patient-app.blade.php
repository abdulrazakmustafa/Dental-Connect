@props(['title' => null, 'back' => null, 'active' => null])
@php
    $user = auth()->user();
    $initials = $user ? collect(explode(' ', $user->name))->map(fn ($p) => strtoupper(substr($p, 0, 1)))->take(2)->implode('') : '';

    $navItems = [
        ['key' => 'home', 'route' => 'patient.dashboard', 'label' => 'Home'],
        ['key' => 'clinics', 'route' => 'clinics.index', 'label' => 'Clinics'],
        ['key' => 'appointments', 'route' => 'patient.appointments.index', 'label' => 'Visits'],
        ['key' => 'messages', 'route' => 'patient.notifications.index', 'label' => 'Inbox'],
        ['key' => 'profile', 'route' => 'patient.profile.index', 'label' => 'Profile'],
    ];
    $activeKey = $active ?? 'home';

    $navIcon = function (string $key, bool $isActive) {
        $w = $isActive ? 2.2 : 1.8;
        return match ($key) {
            'home' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M4 11.5L12 4l8 7.5M6 10v9a1 1 0 001 1h4v-5h2v5h4a1 1 0 001-1v-9" stroke="currentColor" stroke-width="'.$w.'" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'clinics' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 3l7 3.5v6c0 4.2-2.9 7.9-7 8.8-4.1-.9-7-4.6-7-8.8v-6L12 3z" stroke="currentColor" stroke-width="'.$w.'" stroke-linejoin="round"/><path d="M12 8v5M9.5 10.5h5" stroke="currentColor" stroke-width="'.$w.'" stroke-linecap="round"/></svg>',
            'appointments' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><rect x="4" y="5" width="16" height="15" rx="2.5" stroke="currentColor" stroke-width="'.$w.'"/><path d="M4 10h16M9 3v3M15 3v3" stroke="currentColor" stroke-width="'.$w.'" stroke-linecap="round"/></svg>',
            'messages' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M4 6.5A2.5 2.5 0 016.5 4h11A2.5 2.5 0 0120 6.5V15a2.5 2.5 0 01-2.5 2.5H10l-4.5 3.5v-3.5h-1A2.5 2.5 0 012 15V6.5z" stroke="currentColor" stroke-width="'.$w.'" stroke-linejoin="round"/></svg>',
            'profile' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="9" r="3.2" stroke="currentColor" stroke-width="'.$w.'"/><path d="M5.5 19.2c1.4-2.7 3.8-4.2 6.5-4.2s5.1 1.5 6.5 4.2" stroke="currentColor" stroke-width="'.$w.'" stroke-linecap="round"/></svg>',
            default => '',
        };
    };

    // Same liquid-circle mobile nav component/behaviour as the marketing site
    // (resources/views/components/mobile-nav.blade.php), just pointed at patient destinations.
    $patientMobileTabs = [
        [
            'ref' => 'tabClinics',
            'href' => route('clinics.index'),
            'route' => 'clinics.index',
            'label' => 'Clinics',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>',
        ],
        [
            'ref' => 'tabAppointments',
            'href' => route('patient.appointments.index'),
            'route' => 'patient.appointments.index',
            'label' => 'Visits',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>',
        ],
        [
            'ref' => 'tabHome',
            'href' => route('patient.dashboard'),
            'route' => 'patient.dashboard',
            'label' => 'Home',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>',
        ],
        [
            'ref' => 'tabInbox',
            'href' => route('patient.notifications.index'),
            'route' => 'patient.notifications.index',
            'label' => 'Inbox',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/>',
        ],
        [
            'ref' => 'tabProfile',
            'href' => route('patient.profile.index'),
            'route' => 'patient.profile.index',
            'label' => 'Profile',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/>',
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dental Connect' }} | Dental Connect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-dc-bg text-dc-text antialiased selection:bg-dc-mint">
    {{-- ============ DESKTOP SIDEBAR (lg+) ============ --}}
    <aside class="fixed inset-y-0 left-0 z-40 hidden w-72 flex-col border-r border-dc-border bg-white/80 backdrop-blur-xl lg:flex">
        <div class="flex h-20 items-center px-6">
            <x-dc-logo :size="34" />
        </div>

        <nav class="flex-1 space-y-1 px-4">
            @foreach ($navItems as $item)
                @php $isActive = $activeKey === $item['key']; @endphp
                <a href="{{ route($item['route']) }}" wire:navigate
                   class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition
                          {{ $isActive ? 'bg-gradient-to-r from-dc-teal to-dc-teal-deep text-white shadow-sm shadow-dc-teal/30' : 'text-dc-text-secondary hover:bg-dc-mint-light hover:text-dc-teal-deep' }}">
                    <span class="{{ $isActive ? 'text-white' : 'text-dc-text-secondary group-hover:text-dc-teal-deep' }}">{!! $navIcon($item['key'], $isActive) !!}</span>
                    {{ $item['label'] }}
                    @if ($item['key'] === 'messages')
                        <livewire:patient.unread-badge variant="pill" :active="$isActive" :key="'badge-sidebar'" />
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="p-4">
            <a href="{{ route('clinics.index') }}" wire:navigate class="dc-btn-primary w-full">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
                Book appointment
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-3 text-sm font-semibold text-dc-text-secondary transition hover:bg-dc-danger-bg hover:text-dc-danger">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M15 17l5-5-5-5M20 12H9M12 19H6a2 2 0 01-2-2V7a2 2 0 012-2h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Sign out
                </button>
            </form>
        </div>
    </aside>

    {{-- ============ TABLET RAIL (md to lg) ============ --}}
    <aside class="fixed inset-y-0 left-0 z-40 hidden w-20 flex-col items-center border-r border-dc-border bg-white/80 py-6 backdrop-blur-xl md:flex lg:hidden">
        <a href="{{ route('patient.dashboard') }}" wire:navigate class="mb-6"><x-dc-logo :size="30" icon-only /></a>
        <nav class="flex flex-1 flex-col items-center gap-2">
            @foreach ($navItems as $item)
                @php $isActive = $activeKey === $item['key']; @endphp
                <a href="{{ route($item['route']) }}" wire:navigate aria-label="{{ $item['label'] }}"
                   class="relative flex h-12 w-12 items-center justify-center rounded-2xl transition
                          {{ $isActive ? 'bg-gradient-to-br from-dc-teal to-dc-teal-deep text-white shadow-sm shadow-dc-teal/30' : 'text-dc-text-secondary hover:bg-dc-mint-light hover:text-dc-teal-deep' }}">
                    {!! $navIcon($item['key'], $isActive) !!}
                    @if ($item['key'] === 'messages')
                        <livewire:patient.unread-badge :key="'badge-rail'" />
                    @endif
                </a>
            @endforeach
        </nav>
    </aside>

    {{-- ============ MAIN COLUMN ============ --}}
    <div class="md:pl-20 lg:pl-72">
        {{-- Top chrome bar: back/title + global bell & avatar popovers. Always present so every
             patient page (custom header slot or default title bar) gets consistent live chrome. --}}
        <header class="sticky top-0 z-30 border-b border-transparent bg-dc-bg/80 backdrop-blur-md md:border-dc-border">
            <div class="mx-auto flex h-16 max-w-6xl items-center gap-3 px-5 md:h-20 md:px-8">
                @if ($back)
                    <a href="{{ $back }}" wire:navigate class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full hover:bg-white/60" aria-label="Back">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                @endif

                <div class="min-w-0 flex-1">
                    @if (isset($header))
                        {{ $header }}
                    @elseif ($title)
                        <h1 class="truncate text-base font-extrabold md:text-lg">{{ $title }}</h1>
                    @endif
                </div>

                {{-- Notification bell popover --}}
                <div class="relative shrink-0" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
                    <button @click="open = !open" type="button" aria-label="Notifications"
                            class="relative flex h-10 w-10 items-center justify-center rounded-full bg-white/70 text-dc-text-secondary shadow-sm ring-1 ring-dc-border transition hover:text-dc-teal-deep">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 4a5 5 0 00-5 5v3.1c0 .6-.2 1.2-.6 1.6L5 15.5c-.7.8-.2 2 .8 2h12.4c1 0 1.5-1.2.8-2l-1.4-1.8a2.3 2.3 0 01-.6-1.6V9a5 5 0 00-5-5z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M9.5 19a2.5 2.5 0 005 0" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                        <livewire:patient.unread-badge :poll="true" :key="'badge-bell'" />
                    </button>

                    <div x-show="open" x-transition.origin.top.right
                         x-cloak
                         class="absolute right-0 z-40 mt-3 w-80 max-w-[85vw] overflow-hidden rounded-3xl border border-white/30 bg-white/40 shadow-2xl shadow-dc-teal-deep/10 backdrop-blur-2xl">
                        {{-- Not lazy: lazy-loading would add its own extra AJAX round-trip on
                             every single page load/navigation for content that's hidden by
                             default anyway — a plain eager render costs nothing extra since its
                             query is small and rides along in the same response. --}}
                        <livewire:patient.notification-preview />
                    </div>
                </div>

                {{-- Avatar / account popover --}}
                <div class="relative shrink-0" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
                    <button @click="open = !open" type="button" aria-label="Account"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-dc-mint text-sm font-bold text-dc-teal-deep ring-2 ring-white transition hover:ring-dc-teal/40">
                        {{ $initials }}
                    </button>

                    <div x-show="open" x-transition.origin.top.right
                         x-cloak
                         class="absolute right-0 z-40 mt-3 w-64 overflow-hidden rounded-3xl border border-white/30 bg-white/40 p-2 shadow-2xl shadow-dc-teal-deep/10 backdrop-blur-2xl">
                        <div class="rounded-2xl px-3 py-2.5">
                            <p class="truncate text-sm font-bold">{{ $user?->name }}</p>
                            <p class="truncate text-xs text-dc-text-secondary">{{ $user?->email }}</p>
                        </div>
                        <div class="my-1 h-px bg-dc-border/70"></div>
                        <a href="{{ route('patient.profile.index') }}" wire:navigate class="flex items-center gap-2.5 rounded-2xl px-3 py-2.5 text-sm font-semibold text-dc-text transition hover:bg-white/60">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="9" r="3.2" stroke="currentColor" stroke-width="1.8"/><path d="M5.5 19.2c1.4-2.7 3.8-4.2 6.5-4.2s5.1 1.5 6.5 4.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                            My profile
                        </a>
                        <a href="{{ route('patient.appointments.index') }}" wire:navigate class="flex items-center gap-2.5 rounded-2xl px-3 py-2.5 text-sm font-semibold text-dc-text transition hover:bg-white/60">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="4" y="5" width="16" height="15" rx="2.5" stroke="currentColor" stroke-width="1.8"/><path d="M4 10h16" stroke="currentColor" stroke-width="1.8"/></svg>
                            My appointments
                        </a>
                        <div class="my-1 h-px bg-dc-border/70"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2.5 rounded-2xl px-3 py-2.5 text-left text-sm font-semibold text-dc-danger transition hover:bg-dc-danger-bg">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M15 17l5-5-5-5M20 12H9M12 19H6a2 2 0 01-2-2V7a2 2 0 012-2h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-5 pb-28 pt-1 md:px-8 md:pb-12">
            {{ $slot }}
        </main>
    </div>

    {{-- Same liquid-circle mobile nav as the marketing site (resources/views/components/mobile-nav.blade.php),
         pointed at patient destinations, in SPA mode (Livewire client-side navigate, no full reload).
         live-badge-ref lights up a live unread-count dot on the Inbox tab via the generic
         @livewire() hook the shared component exposes (see mobile-nav.blade.php).
         The component itself only hides at `lg:` (matching the marketing site, which has no separate
         tablet rail); the patient shell DOES have one, so this wrapper hides the bottom nav starting
         at `md:` to avoid both showing at once on tablet widths. --}}
    <div class="md:hidden">
        <x-mobile-nav :nav-tabs="$patientMobileTabs" :more-items="[]" :spa="true"
                      live-badge-ref="tabInbox" live-badge-component="patient.unread-badge" />
    </div>

    @livewireScripts
</body>
</html>
