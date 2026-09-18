@props(['title' => null, 'transparentHeader' => false])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dental Connect' }}</title>
    <meta name="description" content="Dental Connect helps patients discover verified clinics and manage appointments, while clinics and suppliers operate through secure professional workspaces built for the dental ecosystem.">
    <script>document.documentElement.classList.add('js-reveal');</script>
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
            :class="scrolled ? 'bg-white/85 backdrop-blur-xl border-b border-dc-border/40' : 'bg-transparent border-b border-white/15'"
            class="fixed inset-x-0 top-0 z-50 transition-colors duration-300">
            <div class="mx-auto grid max-w-7xl grid-cols-3 items-center px-4 py-3 sm:px-6 lg:flex lg:justify-between lg:px-8">
                {{-- Mobile-only icon row: notifications (left) / login (right), logo centered between them --}}
                <x-notification-menu :transparent="true" align="left" class="justify-self-start lg:hidden" />

                <a href="{{ route('home') }}"
                   @click="if (window.location.pathname === '/') { $event.preventDefault(); window.scrollTo({ top: 0, behavior: 'smooth' }); }"
                   class="flex items-center justify-center gap-2.5 justify-self-center lg:justify-self-auto">
                    {{-- Full readable wordmark lockup at every breakpoint, swapped white/color as the header crosses from transparent to solid --}}
                    <img :src="scrolled ? '{{ asset('images/logo/dental-connect-full-color.png') }}' : '{{ asset('images/logo/dental-connect-full-white.png') }}'"
                         alt="Dental Connect" class="h-8 w-auto shrink-0 object-contain sm:h-9">
                    <span :class="scrolled ? 'border-dc-border text-dc-text-secondary' : 'border-white/30 text-white/80'" class="hidden border-l pl-2.5 text-xs leading-tight transition-colors duration-300 lg:block">Connected dental care for Tanzania</span>
                </a>

                <x-account-menu :transparent="true" />

                <nav :class="scrolled ? 'text-dc-text' : 'text-white/90'" class="hidden items-center gap-5 text-sm font-medium transition-colors duration-300 lg:flex lg:gap-7">
                    <a href="{{ route('clinics.index') }}" class="transition hover:opacity-70">Find Clinics</a>
                    <a href="{{ route('home') }}#how-it-works" class="transition hover:opacity-70">How It Works</a>
                    <a href="{{ route('for-clinics') }}" class="transition hover:opacity-70">For Clinics</a>
                    <a href="{{ route('for-suppliers') }}" class="transition hover:opacity-70">For Suppliers</a>
                    <a href="{{ route('about') }}" class="transition hover:opacity-70">About</a>
                </nav>

                <div class="hidden items-center gap-3 lg:flex">
                    <x-notification-menu :transparent="true" />
                    <x-login-menu :transparent="true" />
                    <x-get-started-menu />
                </div>
            </div>
        </header>
    @else
        <header class="sticky top-0 z-40 border-b border-dc-border/40 bg-white/70 backdrop-blur-xl">
            <div class="mx-auto grid max-w-7xl grid-cols-3 items-center px-4 py-3 sm:px-6 lg:flex lg:justify-between lg:px-8">
                {{-- Mobile-only icon row: notifications (left) / login (right), logo centered between them --}}
                <x-notification-menu align="left" class="justify-self-start lg:hidden" />

                <a href="{{ route('home') }}"
                   x-data
                   @click="if (window.location.pathname === '/') { $event.preventDefault(); window.scrollTo({ top: 0, behavior: 'smooth' }); }"
                   class="flex items-center justify-center justify-self-center lg:justify-self-auto"><x-dc-logo :size="40" tagline="Connected dental care for Tanzania" /></a>

                <x-account-menu />

                <nav class="hidden items-center gap-7 text-sm font-medium text-dc-text lg:flex">
                    <a href="{{ route('clinics.index') }}" class="transition hover:text-dc-teal-deep">Find Clinics</a>
                    <a href="{{ route('home') }}#how-it-works" class="transition hover:text-dc-teal-deep">How It Works</a>
                    <a href="{{ route('for-clinics') }}" class="transition hover:text-dc-teal-deep">For Clinics</a>
                    <a href="{{ route('for-suppliers') }}" class="transition hover:text-dc-teal-deep">For Suppliers</a>
                    <a href="{{ route('about') }}" class="transition hover:text-dc-teal-deep">About</a>
                </nav>

                <div class="hidden items-center gap-3 lg:flex">
                    <x-notification-menu />
                    <x-login-menu />
                    <x-get-started-menu />
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
    <x-mobile-nav />

    @livewireScripts
</body>
</html>
