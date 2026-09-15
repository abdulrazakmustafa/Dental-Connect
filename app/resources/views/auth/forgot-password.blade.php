<x-layouts.public :title="'Reset password — Dental Connect'">
    <section class="mx-auto max-w-md px-4 py-16 sm:px-6">
        <div class="dc-card p-8">
            <h1 class="text-2xl font-bold">Reset your password</h1>
            <p class="mt-1 text-sm text-dc-text-secondary">We'll email you a secure reset link.</p>

            @if (session('status'))
                <div class="mt-6 rounded-xl border border-dc-success/30 bg-green-50 p-4 text-sm text-dc-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-6 rounded-xl border border-dc-danger/30 bg-red-50 p-4 text-sm text-dc-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-medium" for="email">Email</label>
                    <input class="dc-input" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                <button type="submit" class="dc-btn-primary w-full">Send reset link</button>
            </form>
        </div>
    </section>
</x-layouts.public>
