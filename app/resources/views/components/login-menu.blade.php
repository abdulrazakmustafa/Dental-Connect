@props(['transparent' => false])
<div class="relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
    @if ($transparent)
        <button type="button" @click="open = !open"
                :class="scrolled ? 'border-dc-border bg-white text-dc-text hover:bg-dc-mint-light' : 'border-white/40 bg-white/10 text-white hover:bg-white/20'"
                class="inline-flex rounded-full border px-5 py-2 text-sm font-semibold backdrop-blur-sm transition">
            Log in
        </button>
    @else
        <button type="button" @click="open = !open"
                class="inline-flex rounded-full border border-dc-border bg-white px-5 py-2 text-sm font-semibold text-dc-text transition hover:bg-dc-mint-light">
            Log in
        </button>
    @endif

    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 -translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 top-full z-50 mt-5 w-80 origin-top-right rounded-2xl border border-white/30 bg-white/40 p-5 text-dc-text shadow-xl backdrop-blur-2xl">
        <p class="text-base font-bold text-dc-text">Welcome back</p>
        <p class="mt-0.5 text-xs text-dc-text-secondary">Log in to your Dental Connect account.</p>

        @if ($errors->any())
            <div class="mt-3 rounded-xl border border-dc-danger/30 bg-dc-danger-bg p-2.5 text-xs text-dc-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="mt-4 space-y-3">
            @csrf
            <div>
                <label class="mb-1 block text-xs font-semibold" for="popup-login">Phone number or email</label>
                <input class="dc-input !py-2 text-sm" type="text" id="popup-login" name="login" placeholder="Enter phone or email" required>
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold" for="popup-password">Password</label>
                <input class="dc-input !py-2 text-sm" type="password" id="popup-password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-1.5 text-dc-text-secondary">
                    <input type="checkbox" name="remember" class="rounded border-dc-border text-dc-teal focus:ring-dc-teal">
                    Remember me
                </label>
                <a class="font-semibold text-dc-teal-deep hover:underline" href="{{ route('password.request') }}">Forgot password?</a>
            </div>
            <button type="submit" class="dc-btn-primary w-full !py-2.5 text-sm">Log in</button>
        </form>

        <p class="mt-4 text-center text-xs text-dc-text-secondary">
            New to Dental Connect?
            <a href="{{ route('register') }}" class="font-semibold text-dc-teal-deep hover:underline">Get started</a>
        </p>
    </div>
</div>
