@php
    $timeSlots = ['09:00', '10:30', '11:30', '13:00', '14:30', '16:00'];
@endphp
{{-- Shared date/time picker markup for booking + reschedule. Relies on the parent element's
     x-data scope (see resources/js/app.js `dcDatePicker`) for date/time/showFullCalendar/etc. --}}
<h2 class="font-bold">Choose date</h2>

<div class="mt-3 -mx-5 flex gap-2 overflow-x-auto px-5 pb-1" style="scrollbar-width: none;">
    <template x-for="day in next14Days()" :key="day.iso">
        <button type="button" @click="selectDate(day.iso)"
                class="flex w-14 shrink-0 flex-col items-center gap-1 rounded-2xl border py-3 transition"
                :class="date === day.iso ? 'border-dc-teal bg-gradient-to-b from-dc-teal to-dc-teal-deep text-white shadow-md shadow-dc-teal/30' : 'border-dc-border bg-white text-dc-text hover:bg-dc-mint-light'">
            <span class="text-[10px] font-semibold uppercase opacity-80" x-text="day.dayName"></span>
            <span class="text-base font-extrabold" x-text="day.dayNum"></span>
        </button>
    </template>
</div>

<button type="button" @click="showFullCalendar = !showFullCalendar" class="mt-2 flex items-center gap-1 text-xs font-bold text-dc-teal-deep">
    <span x-text="showFullCalendar ? 'Hide full calendar' : 'Pick a date further out'"></span>
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" class="transition-transform" :class="showFullCalendar ? 'rotate-180' : ''"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
</button>

<div x-show="showFullCalendar" x-collapse x-cloak class="dc-card mt-3 p-4">
    <div class="flex items-center justify-between">
        <button type="button" @click="prevMonth()" class="flex h-7 w-7 items-center justify-center rounded-full text-dc-text-secondary hover:bg-dc-mint-light">&lsaquo;</button>
        <p class="text-sm font-bold" x-text="monthLabel()"></p>
        <button type="button" @click="nextMonth()" class="flex h-7 w-7 items-center justify-center rounded-full text-dc-text-secondary hover:bg-dc-mint-light">&rsaquo;</button>
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
                @click="selectDate(isoFor(day))"
                class="aspect-square rounded-full text-sm"
                :class="day && date === isoFor(day) ? 'bg-dc-teal text-white font-bold' : (day && isPast(day) ? 'text-dc-border' : 'text-dc-text hover:bg-dc-mint-light')"
            ></button>
        </template>
    </div>
</div>

<h2 class="mt-5 font-bold">Available time</h2>
<div class="mt-3 flex flex-wrap gap-2">
    @foreach ($timeSlots as $slot)
        <button type="button" @click="time = '{{ $slot }}'" class="dc-pill-tab" :class="time === '{{ $slot }}' ? 'dc-pill-tab-active' : 'dc-pill-tab-inactive'">{{ $slot }}</button>
    @endforeach
</div>
