@php
    $statusStyle = [
        'requested' => 'bg-white text-dc-teal-deep',
        'reschedule_proposed' => 'bg-white text-dc-teal-deep',
        'confirmed' => 'bg-white text-dc-teal-deep',
        'completed' => 'bg-white text-dc-teal-deep',
        'cancelled' => 'bg-white text-dc-text-secondary',
        'declined' => 'bg-white text-dc-danger',
        'no_show' => 'bg-white text-dc-danger',
    ];
    $canManage = in_array($appointment->status, [
        \App\Modules\Appointment\Models\Appointment::STATUS_REQUESTED,
        \App\Modules\Appointment\Models\Appointment::STATUS_CONFIRMED,
        \App\Modules\Appointment\Models\Appointment::STATUS_RESCHEDULE_PROPOSED,
    ], true);
    $timelineIcons = [
        'requested' => '<path d="M12 8v4l3 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.8"/>',
        'confirmed' => '<path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>',
        'reschedule_proposed' => '<path d="M4 4v5h5M20 20v-5h-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.5 9a7.5 7.5 0 0113-4.5M19.5 15a7.5 7.5 0 01-13 4.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>',
        'completed' => '<path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>',
        'cancelled' => '<path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'declined' => '<path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'no_show' => '<path d="M12 8v4M12 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.8"/>',
    ];
@endphp
<x-layouts.patient-app title="Appointment Details" :back="route('patient.appointments.index')" active="appointments">
    <div x-data="{ showCancel: false }">
        @if (session('status'))
            <div class="mb-4 rounded-2xl border border-dc-success/30 bg-dc-success-bg p-3 text-sm text-dc-success">{{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3 lg:items-start">
            <div class="space-y-5 lg:col-span-2">
                <div class="dc-hero">
                    <div class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-white/10"></div>
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase text-white/80">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }} appointment</p>
                            <h1 class="mt-1 text-xl font-extrabold">{{ $appointment->service?->name ?? 'General appointment' }}</h1>
                            <p class="mt-1 text-sm text-white/90">{{ $appointment->clinic->name }} &middot; {{ $appointment->clinic->primaryLocation?->area ?? $appointment->clinic->primaryLocation?->city }}</p>
                        </div>
                        <span class="dc-badge shrink-0 {{ $statusStyle[$appointment->status] ?? 'bg-white text-dc-teal-deep' }}">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span>
                    </div>
                </div>

                <div class="dc-card grid grid-cols-2 gap-4 p-5 text-sm">
                    <div>
                        <p class="text-xs text-dc-text-secondary">Date</p>
                        <p class="mt-0.5 font-bold">{{ $appointment->preferred_date->format('j M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-dc-text-secondary">Time</p>
                        <p class="mt-0.5 font-bold">{{ $appointment->formattedTime() ?? 'Flexible' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-dc-text-secondary">Dentist</p>
                        <p class="mt-0.5 font-bold">{{ $appointment->dentist?->full_name ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-dc-text-secondary">Status</p>
                        <p class="mt-0.5"><span class="dc-badge bg-dc-mint text-dc-teal-deep">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span></p>
                    </div>
                </div>

                @if ($appointment->clinic_note)
                    <div>
                        <h2 class="text-sm font-bold">Clinic note</h2>
                        <div class="dc-card mt-2 p-4 text-sm text-dc-text-secondary">{{ $appointment->clinic_note }}</div>
                    </div>
                @endif

                @if ($appointment->statusHistory->isNotEmpty())
                    <div>
                        <h2 class="text-sm font-bold">Status history</h2>
                        <div class="dc-card mt-2 p-5">
                            <ol class="space-y-5">
                                @foreach ($appointment->statusHistory->sortByDesc('created_at') as $event)
                                    <li class="relative flex gap-3 {{ !$loop->last ? 'pb-5 after:absolute after:left-[15px] after:top-8 after:h-full after:w-px after:bg-dc-border' : '' }}">
                                        <span class="relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-dc-mint-light text-dc-teal-deep">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none">{!! $timelineIcons[$event->to_status] ?? '<circle cx="12" cy="12" r="4" fill="currentColor"/>' !!}</svg>
                                        </span>
                                        <div class="min-w-0 flex-1 pt-0.5">
                                            <p class="text-sm font-bold">{{ ucfirst(str_replace('_', ' ', $event->to_status)) }}</p>
                                            @if ($event->note)
                                                <p class="mt-0.5 text-xs text-dc-text-secondary">{{ $event->note }}</p>
                                            @endif
                                            <p class="mt-0.5 text-[11px] text-dc-text-secondary">{{ $event->created_at->format('j M Y, g:i A') }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                @endif

                @if ($canManage)
                    <div class="flex gap-3">
                        <a href="{{ route('patient.appointments.reschedule', $appointment) }}" wire:navigate class="dc-btn-secondary flex-1 text-center !text-dc-teal-deep">Reschedule</a>
                        <button type="button" @click="showCancel = true" class="dc-btn-secondary flex-1 !border-dc-danger/30 !bg-dc-danger-bg !text-dc-danger">Cancel</button>
                    </div>
                @elseif ($appointment->status === \App\Modules\Appointment\Models\Appointment::STATUS_COMPLETED)
                    <a href="{{ route('patient.reviews.create', $appointment) }}" wire:navigate class="dc-btn-primary block w-full text-center">Rate Your Experience</a>
                @endif
            </div>
        </div>

        {{-- Cancel confirmation — bottom sheet on mobile, centered modal from md+ --}}
        <template x-teleport="body">
            <div x-show="showCancel" x-cloak class="fixed inset-0 z-50">
                <div x-show="showCancel" x-transition.opacity @click="showCancel = false" class="absolute inset-0 bg-dc-teal-dark/30 backdrop-blur-sm"></div>
                <div x-show="showCancel"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="translate-y-full md:translate-y-4 md:opacity-0"
                     x-transition:enter-end="translate-y-0 md:opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="translate-y-0 md:opacity-100"
                     x-transition:leave-end="translate-y-full md:translate-y-4 md:opacity-0"
                     class="absolute inset-x-0 bottom-0 rounded-t-3xl bg-white p-6 shadow-2xl md:inset-x-auto md:bottom-auto md:left-1/2 md:top-1/2 md:w-96 md:-translate-x-1/2 md:-translate-y-1/2 md:rounded-3xl">
                    <div class="mx-auto mb-4 h-1.5 w-10 rounded-full bg-dc-border md:hidden"></div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-dc-danger-bg text-dc-danger">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M12 9v4M12 17h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <p class="mt-3 text-base font-extrabold">Cancel this appointment?</p>
                    <p class="mt-1 text-sm text-dc-text-secondary">{{ $appointment->service?->name ?? 'This appointment' }} at {{ $appointment->clinic->name }} on {{ $appointment->preferred_date->format('j M Y') }} will be cancelled. This can't be undone.</p>
                    <div class="mt-5 flex gap-3">
                        <button type="button" @click="showCancel = false" class="dc-btn-secondary flex-1">Keep it</button>
                        <form method="POST" action="{{ route('patient.appointments.cancel', $appointment) }}" class="flex-1">
                            @csrf @method('PATCH')
                            <button type="submit" class="dc-btn-primary w-full !bg-dc-danger !bg-none">Yes, cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-layouts.patient-app>
