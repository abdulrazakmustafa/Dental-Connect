<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dental Connect' }}</title>
    <meta name="description" content="Dental Connect helps patients discover verified clinics and manage appointments, while clinics and suppliers operate through secure professional workspaces built for the dental ecosystem.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen text-dc-text antialiased">
    <header class="sticky top-0 z-40 border-b border-dc-border/70 bg-white/80 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}"><x-dc-logo :size="36" tagline="Connected dental care for Tanzania" /></a>

            <nav class="hidden items-center gap-7 text-sm font-medium text-dc-text sm:flex">
                <a href="{{ route('clinics.index') }}" class="hover:text-dc-teal-deep">Find Clinics</a>
                <a href="{{ route('home') }}#how-it-works" class="hover:text-dc-teal-deep">How It Works</a>
                <a href="{{ route('for-clinics') }}" class="hover:text-dc-teal-deep">For Clinics</a>
                <a href="{{ route('for-suppliers') }}" class="hover:text-dc-teal-deep">For Suppliers</a>
                <a href="{{ route('about') }}" class="hover:text-dc-teal-deep">About</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="hidden rounded-full border border-dc-border bg-white px-5 py-2 text-sm font-semibold text-dc-text hover:bg-dc-mint-light sm:inline-flex">Log in</a>
                <a href="{{ route('register') }}" class="dc-btn-primary !px-5 !py-2.5">Get Started</a>
            </div>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="mt-16 border-t border-dc-border/70">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <p class="text-sm text-dc-text-secondary">&copy; Dental Connect &middot; dentalconnect.co.tz</p>
                <div class="flex items-center gap-4 text-sm text-dc-text-secondary">
                    <a href="{{ route('for-patients') }}" class="hover:text-dc-teal-deep">Patients</a>
                    <span>&middot;</span>
                    <a href="{{ route('for-clinics') }}" class="hover:text-dc-teal-deep">Clinics</a>
                    <span>&middot;</span>
                    <a href="{{ route('for-suppliers') }}" class="hover:text-dc-teal-deep">Suppliers</a>
                    <span>&middot;</span>
                    <a href="{{ route('admin.login') }}" class="hover:text-dc-teal-deep">Administration</a>
                </div>
            </div>
        </div>
    </footer>
    @livewireScripts
</body>
</html>
