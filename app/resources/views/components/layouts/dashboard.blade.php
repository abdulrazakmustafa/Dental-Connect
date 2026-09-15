@props(['title' => 'Dashboard', 'nav' => [], 'brand' => 'Dental Connect', 'charts' => false])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — Dental Connect</title>
    @vite(array_filter(['resources/css/app.css', 'resources/js/app.js', $charts ? 'resources/js/charts.js' : null]))
    @livewireStyles
</head>
<body class="min-h-screen text-dc-text antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <aside
            class="dc-glass fixed inset-y-0 left-0 z-40 w-64 -translate-x-full !rounded-none border-r border-white/50 transition-transform lg:static lg:translate-x-0"
            :class="{ '!translate-x-0': sidebarOpen }"
        >
            <a href="{{ route('home') }}" class="flex h-16 items-center gap-2 border-b border-white/50 px-5">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-dc-teal text-sm font-bold text-white">DC</span>
                <span class="font-bold text-dc-text">{{ $brand }}</span>
            </a>
            <nav class="space-y-1 px-3 py-4">
                @foreach ($nav as $item)
                    <a
                        href="{{ $item['route'] ? route($item['route']) : '#' }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ $item['route'] && request()->routeIs($item['route'].'*') ? 'bg-white/70 text-dc-teal-deep shadow-sm' : 'text-dc-text-secondary hover:bg-white/50' }}"
                    >
                        <span>{{ $item['icon'] ?? '•' }}</span>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
        </aside>

        <div class="flex flex-1 flex-col lg:pl-0">
            <header class="dc-glass sticky top-0 z-30 flex h-16 items-center justify-between !rounded-none border-x-0 border-t-0 px-4 sm:px-6">
                <button class="lg:hidden" @click="sidebarOpen = !sidebarOpen" aria-label="Toggle navigation">☰</button>
                <h1 class="text-lg font-semibold">{{ $title }}</h1>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm font-medium text-dc-text-secondary hover:text-dc-teal-deep" type="submit">Log out</button>
                </form>
            </header>

            @if (session('status'))
                <div class="mx-4 mt-4 rounded-xl border border-dc-success/30 bg-dc-success-bg/90 backdrop-blur p-3 text-sm text-dc-success sm:mx-6">
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
