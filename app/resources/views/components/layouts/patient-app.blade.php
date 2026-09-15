@props(['title' => 'Dental Connect'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — Dental Connect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-dc-bg pb-24 text-dc-text antialiased">
    <header class="sticky top-0 z-30 flex items-center justify-between border-b border-dc-border bg-white/90 px-4 py-3 backdrop-blur-md">
        <a href="{{ route('patient.dashboard') }}" class="flex items-center gap-2 font-bold text-dc-teal-dark">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-dc-teal text-sm text-white">DC</span>
            {{ $title }}
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-sm font-medium text-dc-text-secondary" type="submit">Log out</button>
        </form>
    </header>

    @if (session('status'))
        <div class="mx-4 mt-4 rounded-xl border border-dc-success/30 bg-green-50 p-3 text-sm text-dc-success">
            {{ session('status') }}
        </div>
    @endif

    <main class="mx-auto max-w-lg px-4 py-6">
        {{ $slot }}
    </main>

    <nav class="fixed inset-x-0 bottom-0 z-30 mx-auto grid max-w-lg grid-cols-5 border-t border-dc-border bg-white/95 backdrop-blur-md">
        @php
            $navItems = [
                ['route' => 'patient.dashboard', 'label' => 'Home', 'icon' => '🏠'],
                ['route' => 'clinics.index', 'label' => 'Clinics', 'icon' => '🔍'],
                ['route' => 'patient.appointments.index', 'label' => 'Appts', 'icon' => '📅'],
                ['route' => null, 'label' => 'Records', 'icon' => '📄'],
                ['route' => null, 'label' => 'Profile', 'icon' => '👤'],
            ];
        @endphp
        @foreach ($navItems as $item)
            <a
                href="{{ $item['route'] ? route($item['route']) : '#' }}"
                class="flex flex-col items-center gap-1 py-2.5 text-xs font-medium {{ $item['route'] && request()->routeIs($item['route']) ? 'text-dc-teal-dark' : 'text-dc-text-secondary' }}"
            >
                <span class="text-lg leading-none">{{ $item['icon'] }}</span>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>
    @livewireScripts
</body>
</html>
