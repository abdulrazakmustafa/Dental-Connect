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

    <footer class="relative mt-8 overflow-hidden border-t border-dc-border/70 bg-white/60 backdrop-blur">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-8 sm:grid-cols-5">
                <div class="col-span-2">
                    <x-dc-logo :size="34" />
                    <p class="mt-3 max-w-xs text-sm text-dc-text-secondary">Connecting patients, dental clinics and suppliers across Tanzania through one secure, verified ecosystem.</p>
                    <p class="mt-4 text-sm text-dc-text-secondary">
                        <a href="mailto:hello@dentalconnect.co.tz" class="font-semibold text-dc-teal-deep hover:underline">hello@dentalconnect.co.tz</a>
                    </p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase text-dc-text-secondary">Platform</p>
                    <ul class="mt-3 space-y-2 text-sm text-dc-text-secondary">
                        <li><a href="{{ route('for-patients') }}" class="hover:text-dc-teal-deep">For Patients</a></li>
                        <li><a href="{{ route('for-clinics') }}" class="hover:text-dc-teal-deep">For Clinics</a></li>
                        <li><a href="{{ route('for-suppliers') }}" class="hover:text-dc-teal-deep">For Suppliers</a></li>
                        <li><a href="{{ route('clinics.index') }}" class="hover:text-dc-teal-deep">Find Clinics</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase text-dc-text-secondary">Company</p>
                    <ul class="mt-3 space-y-2 text-sm text-dc-text-secondary">
                        <li><a href="{{ route('about') }}" class="hover:text-dc-teal-deep">About</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-dc-teal-deep">Contact</a></li>
                        <li><a href="{{ route('admin.login') }}" class="hover:text-dc-teal-deep">Administration</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase text-dc-text-secondary">Account</p>
                    <ul class="mt-3 space-y-2 text-sm text-dc-text-secondary">
                        <li><a href="{{ route('login') }}" class="hover:text-dc-teal-deep">Log in</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-dc-teal-deep">Register</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-dc-border/70 pt-6 sm:flex-row">
                <p class="text-xs text-dc-text-secondary">&copy; {{ date('Y') }} Dental Connect &middot; dentalconnect.co.tz &middot; Dar es Salaam, Tanzania</p>
            </div>
        </div>

        <p class="pointer-events-none absolute -bottom-6 left-1/2 hidden -translate-x-1/2 select-none text-[7rem] font-extrabold leading-none text-dc-teal/5 sm:block" aria-hidden="true">Dental Connect</p>
    </footer>
    @livewireScripts
</body>
</html>
