@props(['transparent' => false])
<div class="relative justify-self-end lg:hidden" x-data="{ open: false }" @click.outside="open = false">
    @if ($transparent)
        <button type="button" @click="open = !open"
                :class="scrolled ? 'border-dc-border/60 bg-white text-dc-text' : 'border-white/30 bg-white/10 text-white'"
                class="flex h-9 w-9 items-center justify-center justify-self-end rounded-full border backdrop-blur-sm transition"
                aria-label="Account" aria-haspopup="true" :aria-expanded="open.toString()">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
        </button>
    @else
        <button type="button" @click="open = !open"
                class="flex h-9 w-9 items-center justify-center justify-self-end rounded-full border border-dc-border/60 bg-white text-dc-text transition hover:bg-dc-mint-light"
                aria-label="Account" aria-haspopup="true" :aria-expanded="open.toString()">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
        </button>
    @endif

    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 -translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 top-full z-50 mt-5 w-56 origin-top-right rounded-2xl border border-white/30 bg-white/40 p-1.5 text-dc-text shadow-xl backdrop-blur-2xl">
        <a href="{{ route('login') }}" @click="open = false" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-semibold transition hover:bg-dc-mint-light">
            <svg class="h-4 w-4 shrink-0 text-dc-teal-deep" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3 3m0 0l-3 3m3-3H3"/></svg>
            Log in
        </a>

        <p class="mb-0.5 mt-2 px-3 text-[10px] font-bold uppercase tracking-wider text-dc-text-secondary">Sign up as</p>

        <a href="{{ route('register', ['role' => 'patient']) }}" @click="open = false" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-semibold transition hover:bg-dc-mint-light">
            <svg class="h-4 w-4 shrink-0 text-dc-teal-deep" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
            Patient
        </a>
        <a href="{{ route('register', ['role' => 'clinic']) }}" @click="open = false" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-semibold transition hover:bg-dc-mint-light">
            <svg class="h-4 w-4 shrink-0 text-dc-teal-deep" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
            Clinic
        </a>
        <a href="{{ route('register', ['role' => 'supplier']) }}" @click="open = false" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-semibold transition hover:bg-dc-mint-light">
            <svg class="h-4 w-4 shrink-0 text-dc-teal-deep" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375C2.754 3.75 2.25 4.254 2.25 4.875v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
            Supplier
        </a>
    </div>
</div>
