@props(['title' => 'Dashboard', 'nav' => [], 'brand' => 'Dental Connect'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — Dental Connect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-dc-bg text-dc-text antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <aside
            class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full border-r border-dc-border bg-white transition-transform lg:static lg:translate-x-0"
            :class="{ '!translate-x-0': sidebarOpen }"
        >
            <div class="flex h-16 items-center gap-2 border-b border-dc-border px-5 font-bold text-dc-teal-dark">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-dc-teal text-sm text-white">DC</span>
                {{ $brand }}
            </div>
            <nav class="space-y-1 px-3 py-4">
                @foreach ($nav as $item)
                    <a
                        href="{{ $item['route'] ? route($item['route']) : '#' }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium {{ $item['route'] && request()->routeIs($item['route'].'*') ? 'bg-dc-mint-light text-dc-teal-dark' : 'text-dc-text-secondary hover:bg-dc-mint-light' }}"
                    >
                        <span>{{ $item['icon'] ?? '•' }}</span>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
        </aside>

        <div class="flex flex-1 flex-col lg:pl-0">
            <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-dc-border bg-white/90 px-4 backdrop-blur-md sm:px-6">
                <button class="lg:hidden" @click="sidebarOpen = !sidebarOpen" aria-label="Toggle navigation">☰</button>
                <h1 class="text-lg font-semibold">{{ $title }}</h1>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm font-medium text-dc-text-secondary" type="submit">Log out</button>
                </form>
            </header>

            @if (session('status'))
                <div class="mx-4 mt-4 rounded-xl border border-dc-success/30 bg-green-50 p-3 text-sm text-dc-success sm:mx-6">
                    {{ session('status') }}
                </div>
            @endif

            <main class="flex-1 px-4 py-6 sm:px-6">
                {{ $slot }}
            </main>
        </div>
    </div>
    @livewireScripts
</body>
</html>
