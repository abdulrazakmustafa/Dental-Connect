@php
    $today = now()->toDateString();
@endphp
<x-layouts.patient-app title="Book Appointment" :back="route('clinics.show', $clinic)" active="appointments">
    @if ($errors->any())
        <div class="mb-4 rounded-2xl border border-dc-danger/30 bg-dc-danger-bg p-3 text-sm text-dc-danger">
            {{ $errors->first() }} Please choose a different date, time or dentist.
        </div>
    @endif

    <div
        x-data="{
            step: 1,
            serviceId: null,
            serviceName: '',
            servicePrice: null,
            dentistId: null,
            dentistName: '',
            date: null,
            time: null,
            viewMonth: new Date().getMonth(),
            viewYear: new Date().getFullYear(),
            note: '',
            chooseService(id, name, price) { this.serviceId = id; this.serviceName = name; this.servicePrice = price; },
            chooseDentist(id, name) { this.dentistId = (this.dentistId === id ? null : id); this.dentistName = name; },
            monthLabel() { return new Date(this.viewYear, this.viewMonth, 1).toLocaleString('en-US', { month: 'long', year: 'numeric' }); },
            prevMonth() { this.viewMonth--; if (this.viewMonth < 0) { this.viewMonth = 11; this.viewYear--; } },
            nextMonth() { this.viewMonth++; if (this.viewMonth > 11) { this.viewMonth = 0; this.viewYear++; } },
            daysGrid() {
                const first = new Date(this.viewYear, this.viewMonth, 1);
                const startOffset = (first.getDay() + 6) % 7; // Monday-first
                const daysInMonth = new Date(this.viewYear, this.viewMonth + 1, 0).getDate();
                const cells = [];
                for (let i = 0; i < startOffset; i++) cells.push(null);
                for (let d = 1; d <= daysInMonth; d++) cells.push(d);
                return cells;
            },
            isoFor(day) {
                const m = String(this.viewMonth + 1).padStart(2, '0');
                const d = String(day).padStart(2, '0');
                return `${this.viewYear}-${m}-${d}`;
            },
            isPast(day) { return this.isoFor(day) < '{{ $today }}'; },
        }"
    >
        {{-- Step indicator --}}
        <div class="flex gap-2">
            <span class="dc-pill-tab" :class="step >= 1 ? 'dc-pill-tab-active' : 'dc-pill-tab-inactive'">1 Service</span>
            <span class="dc-pill-tab" :class="step >= 2 ? 'dc-pill-tab-active' : 'dc-pill-tab-inactive'">2 Date &amp; Time</span>
            <span class="dc-pill-tab" :class="step >= 3 ? 'dc-pill-tab-active' : 'dc-pill-tab-inactive'">3 Confirm</span>
        </div>

        {{-- Step 1: Service --}}
        <div x-show="step === 1" class="mt-5">
            <h2 class="font-bold">Select service</h2>
            <div class="mt-3 space-y-2">
                @forelse ($clinic->services as $service)
                    <button
                        type="button"
                        @click="chooseService({{ $service->id }}, '{{ addslashes($service->name) }}', {{ $service->pivot->price ?? 'null' }})"
                        class="dc-card flex w-full items-center justify-between p-4 text-left"
                        :class="serviceId === {{ $service->id }} ? 'ring-2 ring-dc-teal' : ''"
                    >
                        <div class="flex items-center gap-3">
                            <span class="dc-avatar h-9 w-9 text-xs" :class="serviceId === {{ $service->id }} ? '' : ''">{{ strtoupper(substr($service->name, 0, 1)) }}</span>
                            <div>
                                <p class="text-sm font-bold">{{ $service->name }}</p>
                                @if ($service->pivot->price)
                                    <p class="text-xs text-dc-text-secondary">TZS {{ number_format($service->pivot->price) }}</p>
                                @endif
                            </div>
                        </div>
                        <svg x-show="serviceId === {{ $service->id }}" width="18" height="18" viewBox="0 0 24 24" fill="none" class="text-dc-teal"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                @empty
                    <p class="text-sm text-dc-text-secondary">This clinic has not listed specific services yet — you can still request a general appointment.</p>
                @endforelse
            </div>

            <p class="mt-6 text-sm font-bold text-dc-text-secondary">Dentist <span class="font-normal">(optional)</span></p>
            <div class="mt-2 space-y-2">
                @foreach ($clinic->dentists as $dentist)
                    <button type="button" @click="chooseDentist({{ $dentist->id }}, '{{ addslashes($dentist->full_name) }}')" class="dc-card flex w-full items-center justify-between p-3 text-left" :class="dentistId === {{ $dentist->id }} ? 'ring-2 ring-dc-teal' : ''">
                        <div class="flex items-center gap-3">
                            <span class="dc-avatar h-9 w-9 text-xs">{{ collect(explode(' ', str_replace('Dr. ', '', $dentist->full_name)))->map(fn($p) => strtoupper(substr($p,0,1)))->implode('') }}</span>
                            <div>
                                <p class="text-sm font-bold">{{ $dentist->full_name }}</p>
                                <p class="text-xs text-dc-text-secondary">{{ $dentist->specialties->first()?->name ?? 'General Dentist' }}</p>
                            </div>
                        </div>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="text-dc-text-secondary"><path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                @endforeach
            </div>

            <button type="button" @click="step = 2" class="dc-btn-primary mt-6 w-full">Continue</button>
        </div>

        {{-- Step 2: Date & Time --}}
        <div x-show="step === 2" class="mt-5" x-cloak>
            <h2 class="font-bold">Choose date</h2>
            <div class="dc-card mt-3 p-4">
                <div class="flex items-center justify-between">
                    <button type="button" @click="prevMonth()" class="h-7 w-7 text-dc-text-secondary">&lsaquo;</button>
                    <p class="text-sm font-bold" x-text="monthLabel()"></p>
                    <button type="button" @click="nextMonth()" class="h-7 w-7 text-dc-text-secondary">&rsaquo;</button>
                </div>
                <div class="mt-3 grid grid-cols-7 gap-1 text-center text-xs text-dc-text-secondary">
                    <span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span><span>S</span>
                </div>
                <div class="mt-1 grid grid-cols-7 gap-1">
                    <template x-for="(day, i) in daysGrid()" :key="i">
                        <button
                            type="button"
                            x-show="day !== null"
                            x-text="day"
                            :disabled="day && isPast(day)"
                            @click="date = isoFor(day)"
                            class="aspect-square rounded-full text-sm"
                            :class="day && date === isoFor(day) ? 'bg-dc-teal text-white font-bold' : (day && isPast(day) ? 'text-dc-border' : 'text-dc-text hover:bg-dc-mint-light')"
                        ></button>
                    </template>
                </div>
            </div>

            <h2 class="mt-5 font-bold">Available time</h2>
            <div class="mt-3 flex flex-wrap gap-2">
                @foreach (['09:00', '10:30', '11:30', '13:00', '14:30', '16:00'] as $slot)
                    <button type="button" @click="time = '{{ $slot }}'" class="dc-pill-tab" :class="time === '{{ $slot }}' ? 'dc-pill-tab-active' : 'dc-pill-tab-inactive'">{{ $slot }}</button>
                @endforeach
            </div>

            <div class="mt-6 flex gap-3">
                <button type="button" @click="step = 1" class="dc-btn-secondary flex-1">Back</button>
                <button type="button" @click="step = 3" class="dc-btn-primary flex-1" :disabled="!date">Continue</button>
            </div>
        </div>

        {{-- Step 3: Confirm --}}
        <div x-show="step === 3" class="mt-5" x-cloak>
            <h2 class="font-bold">Review &amp; confirm</h2>
            <div class="dc-card mt-3 space-y-3 p-5 text-sm">
                <div class="flex justify-between"><span class="text-dc-text-secondary">Clinic</span><span class="font-bold">{{ $clinic->name }}</span></div>
                <div class="flex justify-between"><span class="text-dc-text-secondary">Service</span><span class="font-bold" x-text="serviceName || 'General appointment'"></span></div>
                <div class="flex justify-between" x-show="dentistName"><span class="text-dc-text-secondary">Dentist</span><span class="font-bold" x-text="dentistName"></span></div>
                <div class="flex justify-between"><span class="text-dc-text-secondary">Date</span><span class="font-bold" x-text="date"></span></div>
                <div class="flex justify-between" x-show="time"><span class="text-dc-text-secondary">Time</span><span class="font-bold" x-text="time"></span></div>
            </div>

            <label class="mt-4 block text-sm font-bold">Note for the clinic (optional)</label>
            <textarea x-model="note" rows="3" class="dc-input mt-2"></textarea>

            <form method="POST" action="{{ route('patient.appointments.store', $clinic) }}" class="mt-6">
                @csrf
                <input type="hidden" name="service_id" :value="serviceId">
                <input type="hidden" name="dentist_id" :value="dentistId">
                <input type="hidden" name="preferred_date" :value="date">
                <input type="hidden" name="preferred_time" :value="time">
                <input type="hidden" name="patient_note" :value="note">
                <div class="flex gap-3">
                    <button type="button" @click="step = 2" class="dc-btn-secondary flex-1">Back</button>
                    <button type="submit" class="dc-btn-primary flex-1">Confirm Request</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.patient-app>
