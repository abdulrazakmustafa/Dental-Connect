<x-layouts.auth title="Reset password" :back="route('login')">
    <div class="flex flex-col items-center text-center">
        <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-dc-mint-light">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none"><path d="M13 3L4 14h6l-1 7 9-11h-6l1-7z" stroke="#14B8A6" stroke-width="1.6" stroke-linejoin="round"/></svg>
        </span>
        <h1 class="mt-5 text-2xl font-extrabold text-dc-text">Reset your password</h1>
        <p class="mt-2 max-w-xs text-sm text-dc-text-secondary">
            Enter the phone number or email linked to your account. We'll send secure recovery instructions.
        </p>
    </div>

    <div class="dc-card mt-6 p-6">
        @if (session('status'))
            <div class="mb-4 rounded-2xl border border-dc-success/30 bg-dc-success-bg p-3 text-sm text-dc-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-2xl border border-dc-danger/30 bg-dc-danger-bg p-3 text-sm text-dc-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <div>
                <label class="mb-1.5 block text-sm font-semibold" for="email">Phone number or email</label>
                <input class="dc-input" type="text" id="email" name="email" value="{{ old('email') }}" placeholder="Enter phone or email" required autofocus>
            </div>
            <button type="submit" class="dc-btn-primary w-full">Send Recovery Link</button>
        </form>
    </div>

    <p class="mt-5 text-center text-sm text-dc-text-secondary">
        Remembered it?
        <a class="font-semibold text-dc-teal-deep" href="{{ route('login') }}">Back to login</a>
    </p>
</x-layouts.auth>
