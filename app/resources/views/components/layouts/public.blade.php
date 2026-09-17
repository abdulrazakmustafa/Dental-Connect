@props(['title' => null, 'transparentHeader' => false])
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
    {{-- Header --}}
    @if ($transparentHeader)
        <header
            x-data="{ scrolled: false }"
            x-init="scrolled = window.scrollY > 40"
            @scroll.window="scrolled = window.scrollY > 40"
            :class="scrolled ? 'bg-white/85 backdrop-blur-xl border-b border-dc-border/40' : 'bg-transparent border-b border-transparent'"
            class="fixed inset-x-0 top-0 z-50 transition-colors duration-300">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center">
                    {{-- Icon-only mark on mobile, swapped white/color as the header crosses from transparent to solid --}}
                    <img :src="scrolled ? '{{ asset('images/logo/dental-connect-icon-color.png') }}' : '{{ asset('images/logo/dental-connect-icon-white.png') }}'"
                         alt="Dental Connect" class="h-9 w-9 shrink-0 object-contain sm:hidden">
                    {{-- Full wordmark lockup from sm and up --}}
                    <img :src="scrolled ? '{{ asset('images/logo/dental-connect-full-color.png') }}' : '{{ asset('images/logo/dental-connect-full-white.png') }}'"
                         alt="Dental Connect" class="hidden h-8 w-auto shrink-0 object-contain sm:block">
                </a>

                <nav :class="scrolled ? 'text-dc-text' : 'text-white/90'" class="hidden items-center gap-5 text-sm font-medium transition-colors duration-300 lg:flex lg:gap-7">
                    <a href="{{ route('clinics.index') }}" class="transition hover:opacity-70">Find Clinics</a>
                    <a href="{{ route('home') }}#how-it-works" class="transition hover:opacity-70">How It Works</a>
                    <a href="{{ route('for-clinics') }}" class="transition hover:opacity-70">For Clinics</a>
                    <a href="{{ route('for-suppliers') }}" class="transition hover:opacity-70">For Suppliers</a>
                    <a href="{{ route('about') }}" class="transition hover:opacity-70">About</a>
                </nav>

                <div class="hidden items-center gap-3 lg:flex">
                    <a href="{{ route('login') }}" class="inline-flex rounded-full border border-dc-border bg-white px-5 py-2 text-sm font-semibold text-dc-text transition hover:bg-dc-mint-light">Log in</a>
                    <a href="{{ route('register') }}" class="dc-btn-primary !px-5 !py-2.5">Get Started</a>
                </div>
            </div>
        </header>
    @else
        <header class="sticky top-0 z-40 border-b border-dc-border/40 bg-white/70 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}"><x-dc-logo :size="36" tagline="Connected dental care for Tanzania" /></a>

                <nav class="hidden items-center gap-7 text-sm font-medium text-dc-text lg:flex">
                    <a href="{{ route('clinics.index') }}" class="transition hover:text-dc-teal-deep">Find Clinics</a>
                    <a href="{{ route('home') }}#how-it-works" class="transition hover:text-dc-teal-deep">How It Works</a>
                    <a href="{{ route('for-clinics') }}" class="transition hover:text-dc-teal-deep">For Clinics</a>
                    <a href="{{ route('for-suppliers') }}" class="transition hover:text-dc-teal-deep">For Suppliers</a>
                    <a href="{{ route('about') }}" class="transition hover:text-dc-teal-deep">About</a>
                </nav>

                <div class="hidden items-center gap-3 lg:flex">
                    <a href="{{ route('login') }}" class="inline-flex rounded-full border border-dc-border bg-white px-5 py-2 text-sm font-semibold text-dc-text transition hover:bg-dc-mint-light">Log in</a>
                    <a href="{{ route('register') }}" class="dc-btn-primary !px-5 !py-2.5">Get Started</a>
                </div>
            </div>
        </header>
    @endif

    <main class="pb-24 lg:pb-0">
        {{ $slot }}
    </main>

    {{-- Desktop Footer --}}
    <footer class="relative mt-8 hidden overflow-hidden border-t border-dc-border/40 bg-white/50 backdrop-blur-md lg:block">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-8 sm:grid-cols-5">
                <div class="col-span-2">
                    <x-dc-logo :size="34" />
                    <p class="mt-3 max-w-xs text-sm leading-relaxed text-dc-text-secondary">Connecting patients, dental clinics and suppliers across Tanzania through one secure, verified ecosystem.</p>
                    <div class="mt-5 flex items-center gap-1.5 text-sm">
                        <svg class="h-4 w-4 text-dc-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        <a href="mailto:hello@dentalconnect.co.tz" class="font-semibold text-dc-teal-deep hover:underline">hello@dentalconnect.co.tz</a>
                    </div>
                    {{-- Social links --}}
                    <div class="mt-4 flex gap-3">
                        @foreach (['facebook' => 'M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z', 'instagram' => 'M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z', 'x' => 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z'] as $network => $path)
                            <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full border border-dc-border/60 bg-white/60 text-dc-text-secondary transition hover:bg-dc-mint hover:text-dc-teal-deep" aria-label="{{ $network }}">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $path }}"/></svg>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-dc-text-secondary">Company</p>
                    <ul class="mt-3 space-y-2.5 text-sm text-dc-text-secondary">
                        <li><a href="{{ route('home') }}" class="transition hover:text-dc-teal-deep">Home</a></li>
                        <li><a href="{{ route('about') }}" class="transition hover:text-dc-teal-deep">About Us</a></li>
                        <li><a href="{{ route('clinics.index') }}" class="transition hover:text-dc-teal-deep">Services</a></li>
                        <li><a href="{{ route('contact') }}" class="transition hover:text-dc-teal-deep">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-dc-text-secondary">Resources</p>
                    <ul class="mt-3 space-y-2.5 text-sm text-dc-text-secondary">
                        <li><a href="{{ route('for-patients') }}" class="transition hover:text-dc-teal-deep">For Patients</a></li>
                        <li><a href="{{ route('for-clinics') }}" class="transition hover:text-dc-teal-deep">For Clinics</a></li>
                        <li><a href="{{ route('for-suppliers') }}" class="transition hover:text-dc-teal-deep">For Suppliers</a></li>
                        <li><a href="{{ route('clinics.index') }}" class="transition hover:text-dc-teal-deep">Find Clinics</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-dc-text-secondary">Legal</p>
                    <ul class="mt-3 space-y-2.5 text-sm text-dc-text-secondary">
                        <li><a href="#" class="transition hover:text-dc-teal-deep">Privacy Policy</a></li>
                        <li><a href="#" class="transition hover:text-dc-teal-deep">Terms and Conditions</a></li>
                        <li><a href="{{ route('admin.login') }}" class="transition hover:text-dc-teal-deep">Administration</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-dc-border/40 pt-6 sm:flex-row">
                <p class="text-xs text-dc-text-secondary">&copy; {{ date('Y') }} Dental Connect. All Rights Reserved.</p>
                <div class="flex gap-4 text-xs text-dc-text-secondary">
                    <a href="#" class="transition hover:text-dc-teal-deep">Privacy & Policy</a>
                    <a href="#" class="transition hover:text-dc-teal-deep">Terms & Conditions</a>
                </div>
            </div>
        </div>

        <p class="pointer-events-none absolute -bottom-8 left-1/2 hidden -translate-x-1/2 select-none whitespace-nowrap text-[8rem] font-extrabold leading-none text-dc-teal/[0.04] sm:block" aria-hidden="true">Dental Connect</p>
    </footer>

    {{-- MOBILE APP-STYLE FOOTER NAVIGATION --}}
    @php
        $currentRoute = Route::currentRouteName();
    @endphp
    <div x-data="{ mobileMore: false }">
        <nav class="fixed inset-x-0 bottom-0 z-50 lg:hidden" aria-label="Mobile navigation">
            <div class="relative mx-3 mb-3">
                <div class="relative flex items-end justify-around rounded-[1.75rem] border border-white/40 bg-gradient-to-r from-dc-teal/90 via-dc-teal-deep/90 to-dc-teal-dark/85 px-2 pb-2.5 pt-2.5 shadow-[0_-4px_32px_-8px_rgba(15,118,110,0.35)] backdrop-blur-xl">

                    <a href="{{ route('home') }}" class="relative flex flex-col items-center gap-0.5 px-3 py-1 transition {{ $currentRoute === 'home' ? 'text-white' : 'text-white/60 hover:text-white/80' }}" {{ $currentRoute === 'home' ? 'aria-current=page' : '' }}>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                        <span class="text-[10px] font-medium">Home</span>
                        @if ($currentRoute === 'home')
                            <span class="absolute -bottom-0.5 h-1 w-5 rounded-full bg-white shadow-[0_0_6px_rgba(255,255,255,0.6)]"></span>
                        @endif
                    </a>

                    <a href="{{ route('clinics.index') }}" class="relative flex flex-col items-center gap-0.5 px-3 py-1 transition {{ $currentRoute === 'clinics.index' ? 'text-white' : 'text-white/60 hover:text-white/80' }}" {{ $currentRoute === 'clinics.index' ? 'aria-current=page' : '' }}>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                        <span class="text-[10px] font-medium">Clinics</span>
                        @if ($currentRoute === 'clinics.index')
                            <span class="absolute -bottom-0.5 h-1 w-5 rounded-full bg-white shadow-[0_0_6px_rgba(255,255,255,0.6)]"></span>
                        @endif
                    </a>

                    {{-- Center floating action button --}}
                    <div class="relative -mt-6 flex flex-col items-center">
                        <a href="{{ route('register') }}"
                           class="flex h-14 w-14 items-center justify-center rounded-full bg-white shadow-[0_4px_20px_-4px_rgba(15,118,110,0.4)] ring-4 ring-dc-teal/20 transition hover:scale-105 active:scale-95">
                            <svg class="h-6 w-6 text-dc-teal-deep" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        </a>
                        <span class="mt-0.5 text-[10px] font-medium text-white/60">Get Started</span>
                    </div>

                    <button type="button" @click="mobileMore = true" class="flex flex-col items-center gap-0.5 px-3 py-1 text-white/60 transition hover:text-white/80">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                        <span class="text-[10px] font-medium">More</span>
                    </button>

                    <a href="{{ route('login') }}" class="relative flex flex-col items-center gap-0.5 px-3 py-1 transition {{ $currentRoute === 'login' ? 'text-white' : 'text-white/60 hover:text-white/80' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        <span class="text-[10px] font-medium">Login</span>
                    </a>
                </div>
            </div>
        </nav>

        {{-- "More" bottom sheet — remaining navigation, reached without a header hamburger --}}
        <div x-show="mobileMore" x-cloak class="fixed inset-0 z-[60] lg:hidden" role="dialog" aria-modal="true" aria-label="More navigation">
            <div class="absolute inset-0 bg-dc-teal-dark/60 backdrop-blur-sm"
                 x-show="mobileMore" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="mobileMore = false"></div>
            <div class="absolute inset-x-0 bottom-0 rounded-t-3xl bg-white p-5 pb-8 shadow-2xl"
                 x-show="mobileMore"
                 x-transition:enter="transition ease-out duration-250" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                <div class="mx-auto mb-5 h-1.5 w-12 rounded-full bg-dc-border"></div>
                <p class="mb-3 text-xs font-bold uppercase tracking-wider text-dc-text-secondary">More</p>
                <nav class="grid grid-cols-2 gap-3 text-sm font-semibold text-dc-text">
                    <a href="{{ route('home') }}#how-it-works" @click="mobileMore = false" class="dc-card flex items-center gap-2 p-4 transition hover:shadow-md">How It Works</a>
                    <a href="{{ route('for-clinics') }}" @click="mobileMore = false" class="dc-card flex items-center gap-2 p-4 transition hover:shadow-md">For Clinics</a>
                    <a href="{{ route('for-suppliers') }}" @click="mobileMore = false" class="dc-card flex items-center gap-2 p-4 transition hover:shadow-md">For Suppliers</a>
                    <a href="{{ route('about') }}" @click="mobileMore = false" class="dc-card flex items-center gap-2 p-4 transition hover:shadow-md">About Us</a>
                    <a href="{{ route('contact') }}" @click="mobileMore = false" class="dc-card col-span-2 flex items-center justify-center gap-2 p-4 transition hover:shadow-md">Contact Us</a>
                </nav>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
