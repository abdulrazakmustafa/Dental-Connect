<x-layouts.auth title="Log in">
    <h1 class="text-3xl font-extrabold text-dc-text">Welcome back</h1>
    <p class="mt-1 text-sm text-dc-text-secondary">Log in to your Dental Connect account.</p>

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

        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="mb-1.5 block text-sm font-semibold" for="login">Phone number or email</label>
                <input class="dc-input" type="text" id="login" name="login" value="{{ old('login') }}" placeholder="Enter phone or email" required autofocus>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold" for="password">Password</label>
                <input class="dc-input" type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 text-dc-text-secondary">
                    <input type="checkbox" name="remember" class="rounded border-dc-border text-dc-teal focus:ring-dc-teal">
                    Remember me
                </label>
                <a class="font-semibold text-dc-teal-deep" href="{{ route('password.request') }}">Forgot password?</a>
            </div>
            <button type="submit" class="dc-btn-primary w-full">Log in</button>
        </form>
    </div>

    <p class="mt-5 text-center text-sm text-dc-text-secondary">
        New to Dental Connect?
        <a class="font-semibold text-dc-teal-deep" href="{{ route('register') }}">Create account</a>
    </p>

    <div class="dc-card mt-6 flex items-start gap-3 p-4">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-dc-mint text-dc-teal-deep">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
        <div>
            <p class="text-sm font-bold">Your clinic relationship stays private</p>
            <p class="mt-0.5 text-xs text-dc-text-secondary">Only the clinic you enroll with can access its patient record.</p>
        </div>
    </div>
</x-layouts.auth>
