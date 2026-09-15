@php
    $labels = ['patient' => 'patient', 'clinic' => 'clinic', 'supplier' => 'supplier account'];
    $ctaLabel = match ($role) {
        'patient' => 'Continue to Clinic Selection',
        'clinic' => 'Create Clinic Account',
        'supplier' => 'Create Supplier Account',
    };
@endphp
<x-layouts.auth title="Create account" :back="route('home')" header-title="Create account">
    <h1 class="text-3xl font-extrabold text-dc-text">Join Dental Connect</h1>
    <p class="mt-1 text-sm text-dc-text-secondary">
        @if ($role === 'patient')
            Your login can be reused, while every clinic creates its own patient record.
        @else
            Register your {{ $labels[$role] }} — you'll complete your profile and submit it for verification next.
        @endif
    </p>

    <div class="mt-4 grid grid-cols-3 gap-2" role="tablist" aria-label="Account type">
        @foreach (['patient' => 'Patient', 'clinic' => 'Clinic', 'supplier' => 'Supplier'] as $value => $label)
            <a
                href="{{ route('register', ['role' => $value]) }}"
                role="tab"
                aria-selected="{{ $role === $value ? 'true' : 'false' }}"
                class="dc-pill-tab justify-center {{ $role === $value ? 'dc-pill-tab-active' : 'dc-pill-tab-inactive' }}"
            >{{ $label }}</a>
        @endforeach
    </div>

    <div class="dc-card mt-4 p-6">
        @if ($errors->any())
            <div class="mb-4 rounded-2xl border border-dc-danger/30 bg-dc-danger-bg p-3 text-sm text-dc-danger">
                <ul class="list-disc space-y-1 pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="role" value="{{ $role }}">

            <div>
                <label class="mb-1.5 block text-sm font-semibold" for="name">
                    {{ $role === 'patient' ? 'Full name' : 'Your name' }}
                </label>
                <input class="dc-input" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required>
            </div>

            @if ($role !== 'patient')
                <div>
                    <label class="mb-1.5 block text-sm font-semibold" for="organization_name">
                        {{ $role === 'clinic' ? 'Clinic name' : 'Company name' }}
                    </label>
                    <input class="dc-input" type="text" id="organization_name" name="organization_name" value="{{ old('organization_name') }}" required>
                </div>
            @endif

            <div>
                <label class="mb-1.5 block text-sm font-semibold" for="phone">Phone number</label>
                <input class="dc-input" type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+255 7XX XXX XXX" required>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-semibold" for="email">
                    Email address <span class="font-normal text-dc-text-secondary">(optional)</span>
                </label>
                <input class="dc-input" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-semibold" for="password">Password</label>
                <input class="dc-input" type="password" id="password" name="password" placeholder="Create a secure password" required>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-semibold" for="password_confirmation">Confirm password</label>
                <input class="dc-input" type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <p class="rounded-xl bg-dc-mint-light px-3 py-2 text-xs text-dc-teal-deep">
                ✓ 8+ chars &nbsp; ✓ number &nbsp; ✓ uppercase &nbsp; ✓ special character
            </p>

            <label class="flex items-start gap-2 text-sm text-dc-text-secondary">
                <input type="checkbox" name="terms" value="1" class="mt-0.5 rounded border-dc-border text-dc-teal focus:ring-dc-teal" required>
                I agree to the Terms &amp; Privacy Policy
            </label>

            <button type="submit" class="dc-btn-primary w-full">{{ $ctaLabel }}</button>
        </form>
    </div>

    <p class="mt-5 text-center text-sm text-dc-text-secondary">
        Already have an account?
        <a class="font-semibold text-dc-teal-deep" href="{{ route('login') }}">Log in</a>
    </p>
</x-layouts.auth>
