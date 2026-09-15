<x-layouts.auth title="Set new password" :back="route('login')">
    <h1 class="text-2xl font-extrabold text-dc-text">Choose a new password</h1>

    <div class="dc-card mt-6 p-6">
        @if ($errors->any())
            <div class="mb-4 rounded-2xl border border-dc-danger/30 bg-dc-danger-bg p-3 text-sm text-dc-danger">
                <ul class="list-disc space-y-1 pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div>
                <label class="mb-1.5 block text-sm font-semibold" for="email">Email</label>
                <input class="dc-input" type="email" id="email" name="email" value="{{ old('email', $email) }}" required autofocus>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold" for="password">New password</label>
                <input class="dc-input" type="password" id="password" name="password" required>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold" for="password_confirmation">Confirm new password</label>
                <input class="dc-input" type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="dc-btn-primary w-full">Reset password</button>
        </form>
    </div>
</x-layouts.auth>
