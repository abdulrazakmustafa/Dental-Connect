@props(['title' => null, 'back' => null, 'active' => null])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dental Connect' }} — Dental Connect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen pb-28 text-dc-text antialiased">
    <div class="mx-auto max-w-lg">
        @if (isset($header))
            {{ $header }}
        @elseif ($title)
            <header class="flex items-center justify-between px-5 pt-6">
                @if ($back)
                    <a href="{{ $back }}" class="flex h-9 w-9 items-center justify-center rounded-full hover:bg-white/60" aria-label="Back">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                @else
                    <span class="w-9"></span>
                @endif
                <h1 class="text-base font-bold">{{ $title }}</h1>
                <span class="w-9">{{ $headerAction ?? '' }}</span>
            </header>
        @endif

        <div class="px-5 pt-5">
            {{ $slot }}
        </div>
    </div>

    <nav class="fixed inset-x-0 bottom-0 z-30 border-t border-dc-border bg-white/95 backdrop-blur-md">
        <div class="mx-auto grid max-w-lg grid-cols-5">
            @php
                $navItems = [
                    ['key' => 'home', 'route' => 'patient.dashboard', 'label' => 'Home'],
                    ['key' => 'clinics', 'route' => 'clinics.index', 'label' => 'Clinics'],
                    ['key' => 'appointments', 'route' => 'patient.appointments.index', 'label' => 'Appointments'],
                    ['key' => 'messages', 'route' => 'patient.notifications.index', 'label' => 'Messages'],
                    ['key' => 'profile', 'route' => 'patient.profile.index', 'label' => 'Profile'],
                ];
                $activeKey = $active ?? 'home';
            @endphp
            @foreach ($navItems as $item)
                @php $isActive = $activeKey === $item['key']; @endphp
                <a href="{{ route($item['route']) }}" class="flex flex-col items-center gap-1 py-2.5 text-[11px] font-medium {{ $isActive ? 'text-dc-teal-deep' : 'text-dc-text-secondary' }}">
                    <span class="{{ $isActive ? 'text-dc-teal-deep' : 'text-dc-text-secondary' }}">
                        @switch($item['key'])
                            @case('home')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M4 11.5L12 4l8 7.5M6 10v9a1 1 0 001 1h4v-5h2v5h4a1 1 0 001-1v-9" stroke="currentColor" stroke-width="{{ $isActive ? 2.2 : 1.8 }}" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                @break
                            @case('clinics')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="{{ $isActive ? 2.4 : 1.8 }}" stroke-linecap="round"/></svg>
                                @break
                            @case('appointments')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><rect x="4" y="5" width="16" height="15" rx="2.5" stroke="currentColor" stroke-width="{{ $isActive ? 2.2 : 1.8 }}"/><path d="M4 10h16M9 3v3M15 3v3" stroke="currentColor" stroke-width="{{ $isActive ? 2.2 : 1.8 }}" stroke-linecap="round"/></svg>
                                @break
                            @case('messages')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="{{ $isActive ? 2.2 : 1.8 }}"/></svg>
                                @break
                            @case('profile')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="9" r="3.2" stroke="currentColor" stroke-width="{{ $isActive ? 2.2 : 1.8 }}"/><path d="M5.5 19.2c1.4-2.7 3.8-4.2 6.5-4.2s5.1 1.5 6.5 4.2" stroke="currentColor" stroke-width="{{ $isActive ? 2.2 : 1.8 }}" stroke-linecap="round"/></svg>
                        @endswitch
                    </span>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </nav>
    @livewireScripts
</body>
</html>
