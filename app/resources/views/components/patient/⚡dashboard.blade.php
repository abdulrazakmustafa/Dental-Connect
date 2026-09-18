<?php

use App\Modules\Appointment\Models\Appointment;
use App\Modules\Notification\Models\PatientNotification;
use App\Modules\Patient\Models\ClinicPatient;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('components.layouts.patient-app', ['active' => 'home'])]
#[Title('Home')]
class extends Component
{
    public ?int $activeClinicPatientId = null;
    public int $calendarMonth;
    public int $calendarYear;

    public function mount(): void
    {
        $this->activeClinicPatientId = session('active_clinic_patient_id');
        $this->calendarMonth = (int) now()->format('n');
        $this->calendarYear = (int) now()->format('Y');
    }

    public function getClinicPatientsProperty()
    {
        return auth()->user()->clinicPatients()->with('clinic:id,public_id,name,slug,logo_path')->orderByDesc('id')->get();
    }

    public function getActiveClinicPatientProperty(): ?ClinicPatient
    {
        $clinicPatients = $this->clinicPatients;

        if ($this->activeClinicPatientId && $match = $clinicPatients->firstWhere('id', $this->activeClinicPatientId)) {
            return $match;
        }

        return $clinicPatients->first();
    }

    public function getNextAppointmentProperty()
    {
        $user = auth()->user();
        $activeClinicPatient = $this->activeClinicPatient;

        return Appointment::whereHas('clinicPatient', fn ($q) => $q->where('user_id', $user->id))
            ->when($activeClinicPatient, fn ($q) => $q->where('clinic_patient_id', $activeClinicPatient->id))
            ->whereIn('status', [Appointment::STATUS_REQUESTED, Appointment::STATUS_CONFIRMED, Appointment::STATUS_RESCHEDULE_PROPOSED])
            ->where('preferred_date', '>=', today())
            ->orderBy('preferred_date')
            ->with(['clinic:id,public_id,name,slug', 'dentist:id,full_name', 'service:id,name'])
            ->first();
    }

    public function getRecentActivityProperty()
    {
        return PatientNotification::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
    }

    public function getCalendarAppointmentsProperty()
    {
        $user = auth()->user();
        $activeClinicPatient = $this->activeClinicPatient;

        return Appointment::whereHas('clinicPatient', fn ($q) => $q->where('user_id', $user->id))
            ->when($activeClinicPatient, fn ($q) => $q->where('clinic_patient_id', $activeClinicPatient->id))
            ->whereIn('status', [Appointment::STATUS_REQUESTED, Appointment::STATUS_CONFIRMED, Appointment::STATUS_RESCHEDULE_PROPOSED])
            ->whereYear('preferred_date', $this->calendarYear)
            ->whereMonth('preferred_date', $this->calendarMonth)
            ->orderBy('preferred_date')
            ->get(['id', 'public_id', 'preferred_date', 'preferred_time'])
            ->keyBy(fn ($a) => $a->preferred_date->format('Y-m-d'));
    }

    public function calendarPrevMonth(): void
    {
        $this->calendarMonth--;
        if ($this->calendarMonth < 1) {
            $this->calendarMonth = 12;
            $this->calendarYear--;
        }
    }

    public function calendarNextMonth(): void
    {
        $this->calendarMonth++;
        if ($this->calendarMonth > 12) {
            $this->calendarMonth = 1;
            $this->calendarYear++;
        }
    }

    public function selectClinic(int $clinicPatientId): void
    {
        $owns = auth()->user()->clinicPatients()->whereKey($clinicPatientId)->exists();
        abort_unless($owns, 403);

        session(['active_clinic_patient_id' => $clinicPatientId]);
        $this->activeClinicPatientId = $clinicPatientId;

        $this->dispatch('clinic-switched');
    }
}
?>

<div>
    <x-slot:header>
        @php $hour = now()->hour; $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening'); @endphp
        <div>
            <p class="text-xs font-semibold text-dc-text-secondary md:text-sm">{{ $greeting }}</p>
            <p class="text-lg font-extrabold leading-tight md:text-2xl">{{ explode(' ', auth()->user()->name)[0] }} 👋</p>
        </div>
    </x-slot:header>

    @if (session('status'))
        <div class="mb-4 rounded-2xl border border-dc-success/30 bg-dc-success-bg p-3 text-sm text-dc-success">{{ session('status') }}</div>
    @endif

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3 lg:items-start">
        {{-- Main column --}}
        <div class="space-y-6 lg:col-span-2">
            @if ($this->clinicPatients->count() > 1)
                <div x-data="{ open: false }" class="relative flex items-center justify-between">
                    <span class="dc-badge bg-dc-mint text-dc-teal-deep">Active clinic</span>
                    <button @click="open = true" class="flex items-center gap-1.5 text-sm font-bold text-dc-teal-deep">
                        {{ $this->activeClinicPatient?->clinic->name }}
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>

                    {{-- Clinic switcher: bottom sheet on mobile, centered modal from md+ --}}
                    <template x-teleport="body">
                        <div x-show="open" x-cloak class="fixed inset-0 z-50" @clinic-switched.window="open = false">
                            <div x-show="open" x-transition.opacity @click="open = false" class="absolute inset-0 bg-dc-teal-dark/30 backdrop-blur-sm"></div>

                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="translate-y-full md:translate-y-4 md:opacity-0"
                                 x-transition:enter-end="translate-y-0 md:opacity-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="translate-y-0 md:opacity-100"
                                 x-transition:leave-end="translate-y-full md:translate-y-4 md:opacity-0"
                                 class="absolute inset-x-0 bottom-0 rounded-t-3xl bg-white p-5 shadow-2xl md:inset-x-auto md:bottom-auto md:left-1/2 md:top-1/2 md:w-96 md:-translate-x-1/2 md:-translate-y-1/2 md:rounded-3xl">
                                <div class="mx-auto mb-4 h-1.5 w-10 rounded-full bg-dc-border md:hidden"></div>
                                <p class="mb-3 text-base font-extrabold">Switch clinic</p>
                                <div class="space-y-2">
                                    @foreach ($this->clinicPatients as $cp)
                                        <button wire:click="selectClinic({{ $cp->id }})"
                                                class="flex w-full items-center gap-3 rounded-2xl border p-3 text-left transition
                                                       {{ $this->activeClinicPatient?->id === $cp->id ? 'border-dc-teal bg-dc-mint-light' : 'border-dc-border hover:bg-dc-mint-light/60' }}">
                                            <span class="dc-avatar-square h-10 w-10 shrink-0">{{ strtoupper(substr($cp->clinic->name, 0, 1)) }}</span>
                                            <span class="min-w-0 flex-1">
                                                <span class="block truncate text-sm font-bold">{{ $cp->clinic->name }}</span>
                                                <span class="block text-xs text-dc-text-secondary">Patient #{{ $cp->patient_number }}</span>
                                            </span>
                                            @if ($this->activeClinicPatient?->id === $cp->id)
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" class="text-dc-teal-deep" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            @endif

            {{-- Next appointment hero --}}
            <div class="dc-hero relative overflow-hidden md:p-8" x-data="{
                    target: {{ $this->nextAppointment ? $this->nextAppointment->preferred_date->copy()->setTimeFromTimeString($this->nextAppointment->preferred_time ?? '00:00')->timestamp * 1000 : 'null' }},
                    label: '',
                    tick() {
                        if (!this.target) return;
                        const diff = this.target - Date.now();
                        if (diff <= 0) { this.label = 'Today'; return; }
                        const days = Math.floor(diff / 86400000);
                        const hours = Math.floor((diff % 86400000) / 3600000);
                        this.label = days > 0 ? `in ${days}d ${hours}h` : `in ${hours}h`;
                    },
                }" x-init="tick(); setInterval(() => tick(), 60000)">
                <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/10"></div>
                <div class="absolute -bottom-10 -left-10 hidden h-40 w-40 rounded-full bg-white/5 md:block"></div>

                @if ($this->nextAppointment)
                    <div class="flex items-start justify-between">
                        <p class="text-xs font-semibold uppercase tracking-wide text-white/80">Your next appointment</p>
                        <span x-show="label" x-cloak x-text="label" class="rounded-full bg-white/20 px-2.5 py-1 text-[11px] font-bold text-white"></span>
                    </div>
                    <h2 class="mt-1 text-xl font-extrabold md:text-2xl">{{ $this->nextAppointment->service?->name ?? 'General appointment' }}</h2>
                    <p class="mt-1 text-sm text-white/90">{{ $this->nextAppointment->clinic->name }} @if($this->nextAppointment->dentist) &middot; {{ $this->nextAppointment->dentist->full_name }} @endif</p>
                    <div class="mt-3 flex items-center gap-4 text-sm text-white/90">
                        <span class="flex items-center gap-1.5">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><rect x="4" y="5" width="16" height="15" rx="2" stroke="white" stroke-width="1.6"/><path d="M4 10h16" stroke="white" stroke-width="1.6"/></svg>
                            {{ $this->nextAppointment->preferred_date->format('d M Y') }}
                        </span>
                        @if ($this->nextAppointment->preferred_time)
                            <span class="flex items-center gap-1.5">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8" stroke="white" stroke-width="1.6"/><path d="M12 8v4l3 2" stroke="white" stroke-width="1.6" stroke-linecap="round"/></svg>
                                {{ $this->nextAppointment->formattedTime() }}
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('patient.appointments.show', $this->nextAppointment) }}" wire:navigate class="dc-btn-white mt-4 inline-flex">View appointment</a>
                @else
                    <p class="text-xs font-semibold uppercase tracking-wide text-white/80">No upcoming appointments</p>
                    <h2 class="mt-1 text-xl font-extrabold md:text-2xl">Book your next visit</h2>
                    <p class="mt-1 text-sm text-white/90">Find a verified clinic and request an appointment in minutes.</p>
                    <a href="{{ route('clinics.index') }}" wire:navigate class="dc-btn-white mt-4 inline-flex">Find a clinic</a>
                @endif
            </div>

            {{-- Appointment calendar: marks every day this month with a confirmed/requested visit --}}
            @php
                $calStart = \Carbon\Carbon::createFromDate($calendarYear, $calendarMonth, 1);
                $calDaysInMonth = $calStart->daysInMonth;
                $calStartOffset = $calStart->dayOfWeekIso - 1;
                $calTodayIso = now()->format('Y-m-d');
            @endphp
            <div class="dc-card p-5">
                <div class="flex items-center justify-between">
                    <button wire:click="calendarPrevMonth" type="button" class="flex h-7 w-7 items-center justify-center rounded-full text-dc-text-secondary transition hover:bg-dc-mint-light" aria-label="Previous month">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <p class="text-sm font-bold">{{ $calStart->format('F Y') }}</p>
                    <button wire:click="calendarNextMonth" type="button" class="flex h-7 w-7 items-center justify-center rounded-full text-dc-text-secondary transition hover:bg-dc-mint-light" aria-label="Next month">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>
                <div class="mt-3 grid grid-cols-7 gap-1 text-center text-[10px] font-semibold text-dc-text-secondary">
                    <span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span><span>S</span>
                </div>
                <div class="mt-1 grid grid-cols-7 gap-1">
                    @for ($i = 0; $i < $calStartOffset; $i++)
                        <span></span>
                    @endfor
                    @for ($d = 1; $d <= $calDaysInMonth; $d++)
                        @php
                            $iso = $calStart->copy()->day($d)->format('Y-m-d');
                            $appt = $this->calendarAppointments->get($iso);
                            $isToday = $iso === $calTodayIso;
                        @endphp
                        @if ($appt)
                            <a href="{{ route('patient.appointments.show', $appt) }}" wire:navigate
                               class="flex aspect-square items-center justify-center rounded-full bg-dc-teal text-sm font-bold text-white shadow-sm shadow-dc-teal/40 transition hover:bg-dc-teal-deep {{ $isToday ? 'ring-2 ring-offset-2 ring-dc-teal-deep' : '' }}"
                               title="{{ $appt->formattedTime() ?? 'Appointment' }}">
                                {{ $d }}
                            </a>
                        @else
                            <span class="flex aspect-square items-center justify-center rounded-full text-sm {{ $isToday ? 'font-extrabold text-dc-teal-deep ring-2 ring-dc-teal' : 'text-dc-text' }}">{{ $d }}</span>
                        @endif
                    @endfor
                </div>
                @if ($this->calendarAppointments->isNotEmpty())
                    <p class="mt-3 flex items-center gap-1.5 text-xs text-dc-text-secondary">
                        <span class="h-2.5 w-2.5 rounded-full bg-dc-teal"></span> Days with a scheduled visit
                    </p>
                @endif
            </div>

            {{-- Quick actions: icon-only, no titles --}}
            <div>
                <h2 class="text-sm font-bold text-dc-text-secondary">Quick actions</h2>
                <div class="mt-3 grid grid-cols-4 gap-3 lg:grid-cols-4">
                    @php
                        $quickActions = [
                            [
                                'label' => 'Find Clinic',
                                'route' => 'clinics.index',
                                'icon' => '<circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/><path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>',
                            ],
                            [
                                'label' => 'Appointments',
                                'route' => 'patient.appointments.index',
                                'icon' => '<rect x="4" y="5" width="16" height="15" rx="2.5" stroke="currentColor" stroke-width="1.8"/><path d="M4 10h16M9 3v3M15 3v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>',
                            ],
                            [
                                'label' => 'Inbox',
                                'route' => 'patient.notifications.index',
                                'icon' => '<rect x="3.5" y="5.5" width="17" height="13" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M4.5 7l7.5 6 7.5-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
                            ],
                            [
                                'label' => 'Support',
                                'route' => 'contact',
                                'icon' => '<circle cx="12" cy="12" r="8.25" stroke="currentColor" stroke-width="1.8"/><path d="M9.5 9.3a2.5 2.5 0 014.9.7c0 1.7-2.4 1.9-2.4 3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><circle cx="12" cy="16.6" r="0.9" fill="currentColor"/>',
                            ],
                        ];
                    @endphp
                    @foreach ($quickActions as $action)
                        <a href="{{ route($action['route']) }}" wire:navigate aria-label="{{ $action['label'] }}"
                           class="dc-card flex items-center justify-center p-4 transition hover:-translate-y-0.5 hover:shadow-md">
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-dc-mint-light text-dc-teal-deep">
                                <svg width="21" height="21" viewBox="0 0 24 24" fill="none">{!! $action['icon'] !!}</svg>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Recent activity (auto-refreshes quietly) --}}
            <div wire:poll.20s>
                <h2 class="text-sm font-bold text-dc-text-secondary">Recent activity</h2>
                <div class="mt-3 space-y-2">
                    @forelse ($this->recentActivity as $activity)
                        <div wire:key="activity-{{ $activity->id }}" class="dc-card flex items-center gap-3 p-4">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-dc-mint-light text-dc-teal-deep">
                                @if (str_starts_with($activity->type, 'appointment.confirmed'))
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                @else
                                    <span class="h-2 w-2 rounded-full bg-dc-teal"></span>
                                @endif
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-bold">{{ $activity->title }}</p>
                                <p class="mt-0.5 truncate text-xs text-dc-text-secondary">{{ $activity->body }}</p>
                            </div>
                            <span class="shrink-0 text-xs text-dc-text-secondary">{{ $activity->created_at->diffForHumans(null, true) }}</span>
                        </div>
                    @empty
                        <div class="dc-card p-4 text-center text-sm text-dc-text-secondary">No activity yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar column (lg+ only) --}}
        <div class="hidden space-y-5 lg:block">
            <div class="dc-card p-5">
                <h3 class="text-sm font-bold">Your clinics</h3>
                <div class="mt-3 space-y-2">
                    @forelse ($this->clinicPatients as $cp)
                        <div class="flex items-center gap-3 rounded-2xl p-2 {{ $this->activeClinicPatient?->id === $cp->id ? 'bg-dc-mint-light' : '' }}">
                            <span class="dc-avatar-square h-9 w-9 shrink-0 text-xs">{{ strtoupper(substr($cp->clinic->name, 0, 1)) }}</span>
                            <span class="min-w-0 flex-1 truncate text-sm font-semibold">{{ $cp->clinic->name }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-dc-text-secondary">You're not enrolled with a clinic yet.</p>
                    @endforelse
                </div>
                <a href="{{ route('clinics.index') }}" wire:navigate class="dc-btn-secondary mt-4 w-full">Find more clinics</a>
            </div>

            <div class="dc-card overflow-hidden p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-dc-teal-deep">Tip</p>
                <p class="mt-1 text-sm font-bold">Keep your profile up to date</p>
                <p class="mt-1 text-sm text-dc-text-secondary">Clinics use your contact details to confirm and remind you about appointments.</p>
                <a href="{{ route('patient.profile.index') }}" wire:navigate class="mt-3 inline-flex text-sm font-bold text-dc-teal-deep hover:underline">Update profile &rarr;</a>
            </div>
        </div>
    </div>
</div>
