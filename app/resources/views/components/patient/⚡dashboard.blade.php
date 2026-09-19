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
    public int $weekOffset = 0;

    public function getClinicPatientsProperty()
    {
        return auth()->user()->clinicPatients()->with('clinic:id,public_id,name,slug,logo_path')->orderBy('id')->get();
    }

    public function getActiveClinicPatientProperty(): ?ClinicPatient
    {
        // One clinic per patient.
        return $this->clinicPatients->first();
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

    public function getNextAppointmentPriceProperty(): ?int
    {
        $appointment = $this->nextAppointment;

        if (! $appointment || ! $appointment->service_id) {
            return null;
        }

        return $appointment->clinic->services()
            ->where('services.id', $appointment->service_id)
            ->first()?->pivot?->price;
    }

    public function getClinicRatingProperty(): ?float
    {
        $clinicId = $this->nextAppointment?->clinic_id ?? $this->activeClinicPatient?->clinic_id;

        if (! $clinicId) {
            return null;
        }

        $avg = \App\Modules\TrustSupport\Models\Review::where('clinic_id', $clinicId)
            ->where('moderation_status', 'published')
            ->avg('rating');

        return $avg ? round($avg, 1) : null;
    }

    public function getWeekStripProperty()
    {
        $start = now()->startOfWeek(\Carbon\Carbon::MONDAY)->addWeeks($this->weekOffset);
        $end = $start->copy()->addDays(6);

        $user = auth()->user();
        $activeClinicPatient = $this->activeClinicPatient;

        $appointments = Appointment::whereHas('clinicPatient', fn ($q) => $q->where('user_id', $user->id))
            ->when($activeClinicPatient, fn ($q) => $q->where('clinic_patient_id', $activeClinicPatient->id))
            ->whereIn('status', [Appointment::STATUS_REQUESTED, Appointment::STATUS_CONFIRMED, Appointment::STATUS_RESCHEDULE_PROPOSED])
            ->whereBetween('preferred_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->get(['id', 'public_id', 'preferred_date', 'preferred_time'])
            ->keyBy(fn ($a) => $a->preferred_date->format('Y-m-d'));

        return collect(range(0, 6))->map(function ($i) use ($start, $appointments) {
            $date = $start->copy()->addDays($i);
            $iso = $date->format('Y-m-d');

            return [
                'iso' => $iso,
                'dayName' => $date->format('D'),
                'dayNum' => $date->format('j'),
                'isToday' => $iso === now()->format('Y-m-d'),
                'appointment' => $appointments->get($iso),
            ];
        });
    }

    public function getRecentActivityProperty()
    {
        return PatientNotification::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
    }

    public function prevWeek(): void
    {
        $this->weekOffset--;
    }

    public function nextWeek(): void
    {
        $this->weekOffset++;
    }

    public function thisWeek(): void
    {
        $this->weekOffset = 0;
    }
}
?>

<div>
    <x-slot:header>
        @php $hour = now()->hour; $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening'); @endphp
        <div>
            <p class="text-lg font-extrabold leading-tight md:text-2xl">{{ $greeting }}, {{ explode(' ', auth()->user()->name)[0] }}</p>
            <p class="text-xs font-semibold text-dc-text-secondary md:text-sm">{{ now()->format('j F, Y') }}</p>
        </div>
    </x-slot:header>

    @if (session('status'))
        <div class="mb-4 rounded-2xl border border-dc-success/30 bg-dc-success-bg p-3 text-sm text-dc-success">{{ session('status') }}</div>
    @endif

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3 lg:items-start">
        {{-- Main column --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- Section label to match the "Top Doctors / See all" pattern --}}
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold text-dc-text-secondary">{{ $this->nextAppointment ? 'Upcoming' : 'Get started' }}</h2>
                <a href="{{ route('patient.appointments.index') }}" wire:navigate class="text-xs font-bold text-dc-teal-deep">See all</a>
            </div>

            {{-- Next appointment hero, styled after the reference "doctor card": rating + favorite
                 top corners, professional/service identity, then an inline availability strip. --}}
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

                <div class="flex items-start justify-between">
                    @if ($this->clinicRating)
                        <span class="flex items-center gap-1 rounded-full bg-white/20 px-2.5 py-1 text-xs font-bold text-white">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" class="text-amber-300"><path d="M12 2.5l2.9 6 6.6.9-4.8 4.6 1.1 6.6L12 17.6l-5.8 3 1.1-6.6-4.8-4.6 6.6-.9L12 2.5z"/></svg>
                            {{ $this->clinicRating }}
                        </span>
                    @else
                        <span></span>
                    @endif
                </div>

                @php
                    $weekDays = $this->weekStrip;
                    $weekLabel = \Carbon\Carbon::parse($weekDays->first()['iso'])->format('j M').' – '.\Carbon\Carbon::parse($weekDays->last()['iso'])->format('j M Y');
                    $heroClinic = $this->nextAppointment?->clinic ?? $this->activeClinicPatient?->clinic;
                @endphp

                @if ($this->nextAppointment)
                    <p class="mt-3 text-xs font-semibold uppercase tracking-wide text-white/80">{{ $this->nextAppointment->dentist?->specialties->first()?->name ?? 'General Dentistry' }}</p>
                    <h2 class="mt-1 text-xl font-extrabold md:text-2xl">{{ $this->nextAppointment->dentist?->full_name ?? $this->nextAppointment->clinic->name }}</h2>
                    <p class="mt-1 text-sm text-white/90">
                        {{ $this->nextAppointment->service?->name ?? 'General appointment' }}
                        @if ($this->nextAppointmentPrice)
                            &middot; TZS {{ number_format($this->nextAppointmentPrice) }}
                        @endif
                    </p>
                @else
                    <p class="mt-3 text-xs font-semibold uppercase tracking-wide text-white/80">No upcoming appointments</p>
                    <h2 class="mt-1 text-xl font-extrabold md:text-2xl">Book your next visit</h2>
                    <p class="mt-1 text-sm text-white/90">
                        @if ($heroClinic)
                            Request an appointment with {{ $heroClinic->name }} in a few taps.
                        @else
                            Find a verified clinic and request an appointment in minutes.
                        @endif
                    </p>
                @endif

                {{-- Availability strip: browse week by week; days with a visit are filled solid white --}}
                <div class="mt-4 rounded-2xl bg-white/10 px-3 pb-3 pt-2.5">
                    <div class="mb-2 flex items-center justify-between text-xs font-semibold text-white/85">
                        <button type="button" wire:click="prevWeek" aria-label="Previous week" class="flex h-6 w-6 items-center justify-center rounded-full transition hover:bg-white/20">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <button type="button" wire:click="thisWeek" class="transition hover:text-white" title="Back to this week">{{ $weekLabel }}</button>
                        <button type="button" wire:click="nextWeek" aria-label="Next week" class="flex h-6 w-6 items-center justify-center rounded-full transition hover:bg-white/20">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        @foreach ($weekDays as $day)
                            <div class="flex flex-col items-center gap-1.5" wire:key="day-{{ $day['iso'] }}">
                                <span class="text-[10px] font-semibold uppercase text-white/70">{{ $day['dayName'] }}</span>
                                @if ($day['appointment'])
                                    <a href="{{ route('patient.appointments.show', $day['appointment']) }}" wire:navigate
                                       class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-xs font-bold text-dc-teal-deep shadow-sm">
                                        {{ $day['dayNum'] }}
                                    </a>
                                @else
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-semibold text-white/80 {{ $day['isToday'] ? 'ring-1 ring-white/60' : '' }}">
                                        {{ $day['dayNum'] }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                @if ($this->nextAppointment)
                    <div class="mt-4 flex items-center gap-3 text-xs text-white/80">
                        <span x-show="label" x-cloak x-text="label" class="rounded-full bg-white/20 px-2.5 py-1 font-bold text-white"></span>
                        <span>{{ $this->nextAppointment->preferred_date->format('d M Y') }}@if($this->nextAppointment->preferred_time) &middot; {{ $this->nextAppointment->formattedTime() }} @endif</span>
                    </div>
                    <a href="{{ route('patient.appointments.show', $this->nextAppointment) }}" wire:navigate class="dc-btn-white mt-4 inline-flex">View appointment</a>
                @elseif ($heroClinic)
                    <a href="{{ route('patient.appointments.book', $heroClinic) }}" wire:navigate class="dc-btn-white mt-4 inline-flex">Book appointment</a>
                @else
                    <a href="{{ route('clinics.index') }}" wire:navigate class="dc-btn-white mt-4 inline-flex">Find a clinic</a>
                @endif
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
            @if ($this->activeClinicPatient)
                <a href="{{ route('patient.support') }}" wire:navigate class="dc-card block p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                    <h3 class="text-sm font-bold">Your clinic</h3>
                    <div class="mt-3 flex items-center gap-3">
                        <span class="dc-avatar-square h-10 w-10 shrink-0 text-sm">{{ strtoupper(substr($this->activeClinicPatient->clinic->name, 0, 1)) }}</span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate text-sm font-bold">{{ $this->activeClinicPatient->clinic->name }}</span>
                            <span class="block text-xs text-dc-text-secondary">Details, contact &amp; app info</span>
                        </span>
                        <span class="text-dc-text-secondary">&rsaquo;</span>
                    </div>
                </a>
            @endif

            <div class="dc-card overflow-hidden p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-dc-teal-deep">Tip</p>
                <p class="mt-1 text-sm font-bold">Keep your profile up to date</p>
                <p class="mt-1 text-sm text-dc-text-secondary">Clinics use your contact details to confirm and remind you about appointments.</p>
                <a href="{{ route('patient.profile.index') }}" wire:navigate class="mt-3 inline-flex text-sm font-bold text-dc-teal-deep hover:underline">Update profile &rarr;</a>
            </div>
        </div>
    </div>
</div>
