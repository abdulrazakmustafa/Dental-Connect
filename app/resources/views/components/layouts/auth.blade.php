@props(['title' => 'Dental Connect', 'back' => null, 'headerTitle' => null, 'badge' => null])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | Dental Connect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen text-dc-text antialiased">
    <div class="mx-auto flex min-h-screen max-w-md flex-col px-6 py-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if ($back)
                    <a href="{{ $back }}" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-dc-text hover:bg-white/60" aria-label="Back">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                @endif
                <x-dc-logo :size="30" icon-only />
                <span class="font-bold text-dc-text">{{ $headerTitle ?? 'Dental Connect' }}</span>
            </div>

            @if ($badge)
                <span class="dc-badge shrink-0 bg-dc-mint text-dc-teal-deep">{{ $badge }}</span>
            @endif
        </div>

        <div class="mt-6 flex-1">
            {{ $slot }}
        </div>
    </div>
    @livewireScripts
</body>
</html>
