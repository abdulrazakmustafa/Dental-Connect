<x-layouts.public :title="'Login — Dental Connect'">
    <section class="mx-auto max-w-md px-4 py-16 sm:px-6">
        <div class="dc-card p-8">
            <h1 class="text-2xl font-bold">Welcome back</h1>
            <p class="mt-1 text-sm text-dc-text-secondary">Log in to your Dental Connect account.</p>

            @if (session('status'))
                <div class="mt-6 rounded-xl border border-dc-success/30 bg-green-50 p-4 text-sm text-dc-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-6 rounded-xl border border-dc-danger/30 bg-red-50 p-4 text-sm text-dc-danger">
                    <ul class="list-disc space-y-1 pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-medium" for="email">Email</label>
                    <input class="dc-input" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium" for="password">Password</label>
                    <input class="dc-input" type="password" id="password" name="password" required>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="rounded border-dc-border text-dc-teal focus:ring-dc-teal">
                        Remember me
                    </label>
                    <a class="font-semibold text-dc-teal-dark" href="{{ route('password.request') }}">Forgot password?</a>
                </div>
                <button type="submit" class="dc-btn-primary w-full">Log in</button>
            </form>

            <p class="mt-6 text-center text-sm text-dc-text-secondary">
                Don't have an account?
                <a class="font-semibold text-dc-teal-dark" href="{{ route('register') }}">Register</a>
            </p>
        </div>
    </section>
</x-layouts.public>
