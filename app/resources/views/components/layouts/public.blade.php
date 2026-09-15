<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dental Connect' }}</title>
    <meta name="description" content="Dental Connect — better dentistry, brighter lives. Connecting patients, clinics and dental suppliers across Tanzania.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-dc-bg text-dc-text antialiased">
    <header class="sticky top-0 z-40 border-b border-dc-border bg-white/80 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-bold text-dc-teal-dark">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-dc-teal text-white">DC</span>
                Dental Connect
            </a>

            <nav class="hidden items-center gap-6 text-sm font-medium text-dc-text-secondary md:flex">
                <a href="{{ route('home') }}" class="hover:text-dc-teal-dark">Home</a>
                <a href="{{ route('for-patients') }}" class="hover:text-dc-teal-dark">For Patients</a>
                <a href="{{ route('for-clinics') }}" class="hover:text-dc-teal-dark">For Clinics</a>
                <a href="{{ route('for-suppliers') }}" class="hover:text-dc-teal-dark">For Suppliers</a>
                <a href="{{ route('about') }}" class="hover:text-dc-teal-dark">About</a>
                <a href="{{ route('contact') }}" class="hover:text-dc-teal-dark">Contact</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="hidden text-sm font-semibold text-dc-text-secondary hover:text-dc-teal-dark sm:inline">Login</a>
                <a href="{{ route('register') }}" class="dc-btn-primary">Register</a>
            </div>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="mt-24 border-t border-dc-border bg-white">
        <div class="mx-auto max-w-7xl px-4 py-10 text-sm text-dc-text-secondary sm:px-6 lg:px-8">
            <div class="flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
                <div class="flex items-center gap-2 font-semibold text-dc-teal-dark">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-dc-teal text-xs text-white">DC</span>
                    Dental Connect
                </div>
                <p>&copy; {{ date('Y') }} Dental Connect. Dar es Salaam, Tanzania.</p>
                <a href="{{ route('admin.login') }}" class="text-xs text-dc-text-secondary/70 hover:text-dc-text-secondary">Platform admin</a>
            </div>
        </div>
    </footer>
    @livewireScripts
</body>
</html>
