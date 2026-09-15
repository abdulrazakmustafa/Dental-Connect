<x-layouts.public :title="'Set new password — Dental Connect'">
    <section class="mx-auto max-w-md px-4 py-16 sm:px-6">
        <div class="dc-card p-8">
            <h1 class="text-2xl font-bold">Choose a new password</h1>

            @if ($errors->any())
                <div class="mt-6 rounded-xl border border-dc-danger/30 bg-red-50 p-4 text-sm text-dc-danger">
                    <ul class="list-disc space-y-1 pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div>
                    <label class="mb-1 block text-sm font-medium" for="email">Email</label>
                    <input class="dc-input" type="email" id="email" name="email" value="{{ old('email', $email) }}" required autofocus>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium" for="password">New password</label>
                    <input class="dc-input" type="password" id="password" name="password" required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium" for="password_confirmation">Confirm new password</label>
                    <input class="dc-input" type="password" id="password_confirmation" name="password_confirmation" required>
                </div>
                <button type="submit" class="dc-btn-primary w-full">Reset password</button>
            </form>
        </div>
    </section>
</x-layouts.public>
