@php $today = now()->toDateString(); @endphp
<x-layouts.patient-app title="Reschedule" :back="route('patient.appointments.show', $appointment)" active="appointments">
    @if ($errors->any())
        <div class="mb-4 rounded-2xl border border-dc-danger/30 bg-dc-danger-bg p-3 text-sm text-dc-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <div
        x-data="{
            date: '{{ $appointment->preferred_date->toDateString() }}',
            time: '{{ $appointment->preferred_time }}',
            viewMonth: new Date('{{ $appointment->preferred_date->toDateString() }}').getMonth(),
            viewYear: new Date('{{ $appointment->preferred_date->toDateString() }}').getFullYear(),
            monthLabel() { return new Date(this.viewYear, this.viewMonth, 1).toLocaleString('en-US', { month: 'long', year: 'numeric' }); },
            prevMonth() { this.viewMonth--; if (this.viewMonth < 0) { this.viewMonth = 11; this.viewYear--; } },
            nextMonth() { this.viewMonth++; if (this.viewMonth > 11) { this.viewMonth = 0; this.viewYear++; } },
            daysGrid() {
                const first = new Date(this.viewYear, this.viewMonth, 1);
                const startOffset = (first.getDay() + 6) % 7;
                const daysInMonth = new Date(this.viewYear, this.viewMonth + 1, 0).getDate();
                const cells = [];
                for (let i = 0; i < startOffset; i++) cells.push(null);
                for (let d = 1; d <= daysInMonth; d++) cells.push(d);
                return cells;
            },
            isoFor(day) { return `${this.viewYear}-${String(this.viewMonth + 1).padStart(2,'0')}-${String(day).padStart(2,'0')}`; },
            isPast(day) { return this.isoFor(day) < '{{ $today }}'; },
        }"
    >
        <p class="text-sm text-dc-text-secondary">Choose a new date and time for your {{ $appointment->service?->name ?? 'appointment' }} at {{ $appointment->clinic->name }}. The clinic will need to reconfirm.</p>

        <div class="dc-card mt-4 p-4">
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

        <form method="POST" action="{{ route('patient.appointments.reschedule.update', $appointment) }}" class="mt-6">
            @csrf @method('PATCH')
            <input type="hidden" name="preferred_date" :value="date">
            <input type="hidden" name="preferred_time" :value="time">
            <button type="submit" class="dc-btn-primary w-full">Confirm New Time</button>
        </form>
    </div>
</x-layouts.patient-app>
