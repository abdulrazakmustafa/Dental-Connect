<x-layouts.public :title="'Register — Dental Connect'">
    <section class="mx-auto max-w-md px-4 py-16 sm:px-6">
        <div class="dc-card p-8">
            <h1 class="text-2xl font-bold">Create your account</h1>
            <p class="mt-1 text-sm text-dc-text-secondary">Choose the role that describes you.</p>

            <div class="mt-6 grid grid-cols-3 gap-2" role="tablist" aria-label="Account type">
                @foreach (['patient' => 'Patient', 'clinic' => 'Clinic', 'supplier' => 'Supplier'] as $value => $label)
                    <a
                        href="{{ route('register', ['role' => $value]) }}"
                        role="tab"
                        aria-selected="{{ $role === $value ? 'true' : 'false' }}"
                        class="rounded-xl border px-3 py-2 text-center text-sm font-semibold {{ $role === $value ? 'border-dc-teal bg-dc-mint-light text-dc-teal-dark' : 'border-dc-border text-dc-text-secondary hover:bg-dc-mint-light' }}"
                    >{{ $label }}</a>
                @endforeach
            </div>

            @if ($errors->any())
                <div class="mt-6 rounded-xl border border-dc-danger/30 bg-red-50 p-4 text-sm text-dc-danger">
                    <ul class="list-disc space-y-1 pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" class="mt-6 space-y-4">
                @csrf
                <input type="hidden" name="role" value="{{ $role }}">

                <div>
                    <label class="mb-1 block text-sm font-medium" for="name">
                        {{ $role === 'patient' ? 'Full name' : 'Your name' }}
                    </label>
                    <input class="dc-input" type="text" id="name" name="name" value="{{ old('name') }}" required>
                </div>

                @if ($role !== 'patient')
                    <div>
                        <label class="mb-1 block text-sm font-medium" for="organization_name">
                            {{ $role === 'clinic' ? 'Clinic name' : 'Company name' }}
                        </label>
                        <input class="dc-input" type="text" id="organization_name" name="organization_name" value="{{ old('organization_name') }}" required>
                    </div>
                @endif

                <div>
                    <label class="mb-1 block text-sm font-medium" for="email">Email</label>
                    <input class="dc-input" type="email" id="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium" for="phone">Phone (optional)</label>
                    <input class="dc-input" type="tel" id="phone" name="phone" value="{{ old('phone') }}">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium" for="password">Password</label>
                    <input class="dc-input" type="password" id="password" name="password" required>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium" for="password_confirmation">Confirm password</label>
                    <input class="dc-input" type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="dc-btn-primary w-full">Create account</button>
            </form>

            <p class="mt-6 text-center text-sm text-dc-text-secondary">
                Already have an account?
                <a class="font-semibold text-dc-teal-dark" href="{{ route('login') }}">Log in</a>
            </p>
        </div>
    </section>
</x-layouts.public>
