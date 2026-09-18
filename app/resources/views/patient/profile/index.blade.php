@php
    $initials = collect(explode(' ', $user->name))->map(fn ($p) => strtoupper(substr($p, 0, 1)))->take(2)->implode('');
@endphp
<x-layouts.patient-app title="My Profile" active="profile">
    <div x-data="{
            drawer: null,
            toast: null,
            name: {{ \Illuminate\Support\Js::from($user->name) }},
            phone: {{ \Illuminate\Support\Js::from($user->phone) }},
            email: {{ \Illuminate\Support\Js::from($user->email) }},
            notifyAppointments: true,
            notifyPromotions: false,
            initPrefs() {
                try {
                    const saved = JSON.parse(localStorage.getItem('dc_notification_prefs') || 'null');
                    if (saved) { this.notifyAppointments = saved.appointments; this.notifyPromotions = saved.promotions; }
                } catch (e) {}
            },
            savePrefs() {
                try { localStorage.setItem('dc_notification_prefs', JSON.stringify({ appointments: this.notifyAppointments, promotions: this.notifyPromotions })); } catch (e) {}
            },
            showToast(message) { this.toast = message; setTimeout(() => this.toast = null, 2500); },
        }"
        x-init="initPrefs()"
    >
        <div class="flex flex-col items-center text-center">
            <button type="button" @click="drawer = 'personal'" class="relative">
                <span class="flex h-20 w-20 items-center justify-center rounded-full bg-dc-mint text-2xl font-bold text-dc-teal-deep ring-4 ring-dc-mint-light">{{ $initials }}</span>
                <span class="absolute -bottom-1 -right-1 flex h-7 w-7 items-center justify-center rounded-full bg-dc-teal-deep text-white ring-2 ring-white">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"><path d="M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4L16.5 3.5z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
            </button>
            <h1 class="mt-4 text-xl font-extrabold" x-text="name"></h1>
            <p class="mt-1 text-sm text-dc-text-secondary">
                <span x-text="phone"></span><span x-show="phone && email"> · </span><span x-text="email"></span>
            </p>
            @if ($activeClinicPatient)
                <span class="dc-badge mt-3 bg-dc-mint text-dc-teal-deep">Active clinic: {{ $activeClinicPatient->clinic->name }}</span>
            @endif
        </div>

        <div class="mt-6 grid grid-cols-1 gap-3 lg:grid-cols-2">
            <button type="button" @click="drawer = 'personal'" class="dc-card flex items-center gap-3 p-4 text-left">
                <span class="dc-avatar-square h-10 w-10 shrink-0"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="9" r="3.2" stroke="currentColor" stroke-width="1.8"/><path d="M5.5 19.2c1.4-2.7 3.8-4.2 6.5-4.2s5.1 1.5 6.5 4.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold">Personal Information</p>
                    <p class="mt-0.5 text-xs text-dc-text-secondary">Name, phone, email and account details</p>
                </div>
                <span class="text-dc-text-secondary">&rsaquo;</span>
            </button>

            <a href="{{ route('clinics.index') }}" wire:navigate class="dc-card flex items-center gap-3 p-4">
                <span class="dc-avatar-square h-10 w-10 shrink-0"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 3l7 3.5v6c0 4.2-2.9 7.9-7 8.8-4.1-.9-7-4.6-7-8.8v-6L12 3z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg></span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold">My Clinic Enrollment</p>
                    <p class="mt-0.5 text-xs text-dc-text-secondary">View or enroll separately with another clinic</p>
                </div>
                <span class="text-dc-text-secondary">&rsaquo;</span>
            </a>

            <a href="{{ route('patient.appointments.index') }}" wire:navigate class="dc-card flex items-center gap-3 p-4">
                <span class="dc-avatar-square h-10 w-10 shrink-0"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="4" y="5" width="16" height="15" rx="2.5" stroke="currentColor" stroke-width="1.8"/><path d="M4 10h16" stroke="currentColor" stroke-width="1.8"/></svg></span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold">Appointment History</p>
                    <p class="mt-0.5 text-xs text-dc-text-secondary">Upcoming and previous appointments</p>
                </div>
                <span class="text-dc-text-secondary">&rsaquo;</span>
            </a>

            <button type="button" @click="drawer = 'notifications'" class="dc-card flex items-center gap-3 p-4 text-left">
                <span class="dc-avatar-square h-10 w-10 shrink-0"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 4a5 5 0 00-5 5v3.1c0 .6-.2 1.2-.6 1.6L5 15.5c-.7.8-.2 2 .8 2h12.4c1 0 1.5-1.2.8-2l-1.4-1.8a2.3 2.3 0 01-.6-1.6V9a5 5 0 00-5-5z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg></span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold">Notification Preferences</p>
                    <p class="mt-0.5 text-xs text-dc-text-secondary">Appointment and account alerts</p>
                </div>
                <span class="inline-flex h-6 w-11 items-center rounded-full p-0.5 transition" :class="notifyAppointments ? 'bg-dc-teal' : 'bg-dc-border'">
                    <span class="h-5 w-5 rounded-full bg-white shadow transition-transform" :class="notifyAppointments ? 'translate-x-5' : 'translate-x-0'"></span>
                </span>
            </button>

            <button type="button" @click="drawer = 'privacy'" class="dc-card flex items-center gap-3 p-4 text-left">
                <span class="dc-avatar-square h-10 w-10 shrink-0"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 3l7 3.5v6c0 4.2-2.9 7.9-7 8.8-4.1-.9-7-4.6-7-8.8v-6L12 3z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M9.5 12l1.8 1.8 3.2-3.6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold">Privacy &amp; Security</p>
                    <p class="mt-0.5 text-xs text-dc-text-secondary">Password, sessions and consent</p>
                </div>
                <span class="text-dc-text-secondary">&rsaquo;</span>
            </button>

            <a href="{{ route('contact') }}" wire:navigate class="dc-card flex items-center gap-3 p-4">
                <span class="dc-avatar-square h-10 w-10 shrink-0">?</span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold">Help &amp; Support</p>
                    <p class="mt-0.5 text-xs text-dc-text-secondary">Support request or complaint</p>
                </div>
                <span class="text-dc-text-secondary">&rsaquo;</span>
            </a>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-6 text-center">
            @csrf
            <button type="submit" class="text-sm font-bold text-dc-danger">Log out</button>
        </form>

        {{-- Drawers --}}
        <template x-teleport="body">
            <div x-show="drawer" x-cloak class="fixed inset-0 z-50">
                <div x-show="drawer" x-transition.opacity @click="drawer = null" class="absolute inset-0 bg-dc-teal-dark/30 backdrop-blur-sm"></div>

                <div x-show="drawer"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="translate-y-full md:translate-y-4 md:opacity-0"
                     x-transition:enter-end="translate-y-0 md:opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="translate-y-0 md:opacity-100"
                     x-transition:leave-end="translate-y-full md:translate-y-4 md:opacity-0"
                     class="absolute inset-x-0 bottom-0 max-h-[85vh] overflow-y-auto rounded-t-3xl bg-white p-6 shadow-2xl md:inset-x-auto md:bottom-auto md:left-1/2 md:top-1/2 md:w-[26rem] md:-translate-x-1/2 md:-translate-y-1/2 md:rounded-3xl">
                    <div class="mx-auto mb-4 h-1.5 w-10 rounded-full bg-dc-border md:hidden"></div>

                    <template x-if="drawer === 'personal'">
                        <div>
                            <p class="text-base font-extrabold">Personal Information</p>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="text-xs font-bold text-dc-text-secondary">Full name</label>
                                    <input type="text" x-model="name" class="dc-input mt-1">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-dc-text-secondary">Phone</label>
                                    <input type="text" x-model="phone" class="dc-input mt-1">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-dc-text-secondary">Email</label>
                                    <input type="email" x-model="email" class="dc-input mt-1">
                                </div>
                            </div>
                            <button type="button" @click="drawer = null; showToast('Profile updated')" class="dc-btn-primary mt-5 w-full">Save changes</button>
                        </div>
                    </template>

                    <template x-if="drawer === 'notifications'">
                        <div>
                            <p class="text-base font-extrabold">Notification Preferences</p>
                            <div class="mt-4 space-y-3">
                                <div class="flex items-center justify-between rounded-2xl border border-dc-border p-3">
                                    <div>
                                        <p class="text-sm font-bold">Appointment reminders</p>
                                        <p class="text-xs text-dc-text-secondary">Confirmations, reminders and status changes</p>
                                    </div>
                                    <button type="button" @click="notifyAppointments = !notifyAppointments; savePrefs()" class="inline-flex h-6 w-11 shrink-0 items-center rounded-full p-0.5 transition" :class="notifyAppointments ? 'bg-dc-teal' : 'bg-dc-border'">
                                        <span class="h-5 w-5 rounded-full bg-white shadow transition-transform" :class="notifyAppointments ? 'translate-x-5' : 'translate-x-0'"></span>
                                    </button>
                                </div>
                                <div class="flex items-center justify-between rounded-2xl border border-dc-border p-3">
                                    <div>
                                        <p class="text-sm font-bold">Platform news &amp; tips</p>
                                        <p class="text-xs text-dc-text-secondary">New clinics, features and offers</p>
                                    </div>
                                    <button type="button" @click="notifyPromotions = !notifyPromotions; savePrefs()" class="inline-flex h-6 w-11 shrink-0 items-center rounded-full p-0.5 transition" :class="notifyPromotions ? 'bg-dc-teal' : 'bg-dc-border'">
                                        <span class="h-5 w-5 rounded-full bg-white shadow transition-transform" :class="notifyPromotions ? 'translate-x-5' : 'translate-x-0'"></span>
                                    </button>
                                </div>
                            </div>
                            <button type="button" @click="drawer = null" class="dc-btn-secondary mt-5 w-full">Done</button>
                        </div>
                    </template>

                    <template x-if="drawer === 'privacy'">
                        <div>
                            <p class="text-base font-extrabold">Privacy &amp; Security</p>
                            <div class="mt-4 space-y-2">
                                <div class="flex items-center justify-between rounded-2xl border border-dc-border p-3">
                                    <p class="text-sm font-semibold">Password</p>
                                    <a href="{{ route('password.request') }}" wire:navigate class="text-sm font-bold text-dc-teal-deep">Change</a>
                                </div>
                                <div class="rounded-2xl border border-dc-border p-3">
                                    <p class="text-sm font-semibold">Data &amp; consent</p>
                                    <p class="mt-1 text-xs text-dc-text-secondary">Your clinic-scoped records are only visible to clinics you've enrolled with. Platform staff can access records only for verification and support.</p>
                                </div>
                            </div>
                            <button type="button" @click="drawer = null" class="dc-btn-secondary mt-5 w-full">Close</button>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        {{-- Toast --}}
        <div x-show="toast" x-cloak x-transition.opacity class="fixed inset-x-0 bottom-24 z-50 flex justify-center px-4 md:bottom-8">
            <div class="rounded-full bg-dc-text px-4 py-2.5 text-sm font-semibold text-white shadow-xl" x-text="toast"></div>
        </div>
    </div>
</x-layouts.patient-app>
