<x-layouts.auth title="Create account" :back="route('home')" header-title="Create account">
    <div x-data="{
            role: '{{ $role }}',
            clinicId: '{{ old('clinic_id', '') }}',
            clinicQuery: '',
            clinics: {{ \Illuminate\Support\Js::from($clinics->map(fn ($c) => [
                'id' => $c->public_id,
                'name' => $c->name,
                'area' => $c->primaryLocation?->area ?? $c->primaryLocation?->city ?? 'Dar es Salaam',
            ])) }},
            get filteredClinics() {
                const q = this.clinicQuery.trim().toLowerCase();
                if (!q) return this.clinics;
                return this.clinics.filter(c => c.name.toLowerCase().includes(q) || c.area.toLowerCase().includes(q));
            },
            get clinicName() { return this.clinics.find(c => c.id === this.clinicId)?.name ?? ''; },
        }">
        <h1 class="text-3xl font-extrabold text-dc-text">Join Dental Connect</h1>
        <p class="mt-1 text-sm text-dc-text-secondary">
            <span x-show="role === 'patient'">Choose the clinic you'd like to be a patient of. You can enroll with another clinic later from inside the app.</span>
            <span x-show="role !== 'patient'" x-cloak>
                Register your <span x-text="role === 'clinic' ? 'clinic' : 'supplier account'"></span>. You'll complete your profile and submit it for verification next.
            </span>
        </p>

        <div class="mt-4 grid grid-cols-3 gap-2" role="tablist" aria-label="Account type">
            @foreach (['patient' => 'Patient', 'clinic' => 'Clinic', 'supplier' => 'Supplier'] as $value => $label)
                <button
                    type="button"
                    @click="role = '{{ $value }}'"
                    role="tab"
                    :aria-selected="(role === '{{ $value }}').toString()"
                    :class="role === '{{ $value }}' ? 'dc-pill-tab-active' : 'dc-pill-tab-inactive'"
                    class="dc-pill-tab justify-center"
                >{{ $label }}</button>
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
                <input type="hidden" name="role" :value="role">
                <input type="hidden" name="clinic_id" :value="clinicId">

                <div x-show="role === 'patient'" x-cloak>
                    <label class="mb-1.5 block text-sm font-semibold">Choose your clinic</label>

                    <template x-if="!clinicId">
                        <div>
                            <div class="flex items-center gap-2 rounded-full border border-dc-border bg-white px-4 py-2.5 shadow-sm">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" class="shrink-0 text-dc-text-secondary"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/><path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                                <input type="text" x-model="clinicQuery" placeholder="Search clinic or area" class="flex-1 border-0 bg-transparent text-sm focus:outline-none focus:ring-0">
                            </div>

                            <div class="mt-2 max-h-52 space-y-1.5 overflow-y-auto">
                                <template x-for="clinic in filteredClinics" :key="clinic.id">
                                    <button type="button" @click="clinicId = clinic.id"
                                            class="flex w-full items-center gap-3 rounded-2xl border border-dc-border p-3 text-left transition hover:border-dc-teal hover:bg-dc-mint-light">
                                        <span class="dc-avatar-square h-9 w-9 shrink-0 text-xs" x-text="clinic.name.charAt(0).toUpperCase()"></span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block truncate text-sm font-bold" x-text="clinic.name"></span>
                                            <span class="block text-xs text-dc-text-secondary" x-text="clinic.area"></span>
                                        </span>
                                    </button>
                                </template>
                                <template x-if="filteredClinics.length === 0">
                                    <p class="p-3 text-center text-sm text-dc-text-secondary">No verified clinics match your search.</p>
                                </template>
                            </div>

                            @if ($clinics->isEmpty())
                                <p class="mt-2 rounded-xl bg-dc-warning-bg px-3 py-2 text-xs text-dc-warning">No verified clinics are available yet. Please check back soon.</p>
                            @endif
                        </div>
                    </template>

                    <template x-if="clinicId">
                        <button type="button" @click="clinicId = ''"
                                class="flex w-full items-center gap-3 rounded-2xl border border-dc-teal bg-dc-mint-light p-3 text-left">
                            <span class="dc-avatar-square h-9 w-9 shrink-0 text-xs" x-text="clinicName.charAt(0).toUpperCase()"></span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-sm font-bold" x-text="clinicName"></span>
                                <span class="block text-xs text-dc-teal-deep">Selected &middot; tap to change</span>
                            </span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="shrink-0 text-dc-teal-deep"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </template>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold" for="name">
                        <span x-text="role === 'patient' ? 'Full name' : 'Your name'"></span>
                    </label>
                    <input class="dc-input" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required>
                </div>

                <div x-show="role !== 'patient'" x-cloak>
                    <label class="mb-1.5 block text-sm font-semibold" for="organization_name">
                        <span x-text="role === 'clinic' ? 'Clinic name' : 'Company name'"></span>
                    </label>
                    <input class="dc-input" type="text" id="organization_name" name="organization_name" value="{{ old('organization_name') }}" :required="role !== 'patient'">
                </div>

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

                <button type="submit" class="dc-btn-primary w-full" :disabled="role === 'patient' && !clinicId">
                    <span x-text="role === 'patient' ? 'Create Account' : (role === 'clinic' ? 'Create Clinic Account' : 'Create Supplier Account')"></span>
                </button>
            </form>
        </div>

        <p class="mt-5 text-center text-sm text-dc-text-secondary">
            Already have an account?
            <a class="font-semibold text-dc-teal-deep" href="{{ route('login') }}">Log in</a>
        </p>
    </div>
</x-layouts.auth>
