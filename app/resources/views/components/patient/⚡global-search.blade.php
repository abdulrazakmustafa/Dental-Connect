<?php

use App\Modules\Appointment\Models\Appointment;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Clinic\Models\Dentist;
use App\Modules\Clinic\Models\Service;
use App\Modules\Notification\Models\PatientNotification;
use Illuminate\Support\HtmlString;
use Livewire\Component;

new class extends Component
{
    public string $query = '';

    public function runSearch(): void
    {
        // Intentionally empty: any Livewire action flushes the debounced query first, so the
        // search button / Enter key gets results immediately instead of waiting out the debounce.
    }

    public function mark(string $text): HtmlString
    {
        $safe = e($text);
        $q = trim($this->query);

        if ($q === '') {
            return new HtmlString($safe);
        }

        return new HtmlString(preg_replace(
            '/('.preg_quote(e($q), '/').')/iu',
            '<mark class="rounded-sm bg-dc-mint text-dc-teal-deep">$1</mark>',
            $safe
        ) ?? $safe);
    }

    public function getOwnClinicsProperty()
    {
        return Clinic::whereIn('id', auth()->user()->clinicPatients()->pluck('clinic_id'))->get(['id', 'public_id', 'name']);
    }

    public function getResultsProperty(): array
    {
        $q = trim($this->query);

        if (mb_strlen($q) < 2) {
            $none = collect();

            return ['pages' => $none, 'clinics' => $none, 'services' => $none, 'dentists' => $none, 'visits' => $none, 'messages' => $none];
        }

        // Catalog-sized tables (clinics, services, dentists) + the patient's own rows only, so a
        // contains-match is cheap here; every list is capped.
        $like = '%'.addcslashes($q, '%_\\').'%';
        $userId = auth()->id();

        // Patients belong to one clinic, so clinic/service/dentist results are limited to it.
        $clinicIds = $this->ownClinics->pluck('id');

        $clinics = Clinic::query()->whereIn('id', $clinicIds)
            ->where(fn ($w) => $w->where('name', 'like', $like)
                ->orWhereHas('services', fn ($s) => $s->where('services.name', 'like', $like))
                ->orWhereHas('specialties', fn ($s) => $s->where('specialties.name', 'like', $like)))
            ->with('primaryLocation:id,clinic_id,city,area')
            ->limit(2)->get(['id', 'public_id', 'name']);

        $services = Service::where('is_active', true)->where('name', 'like', $like)
            ->whereHas('clinics', fn ($c) => $c->whereIn('clinics.id', $clinicIds))
            ->limit(4)->get(['id', 'name']);

        $dentists = Dentist::where('status', 'active')->where('full_name', 'like', $like)
            ->whereIn('clinic_id', $clinicIds)->limit(4)->get(['id', 'clinic_id', 'full_name']);

        $visits = Appointment::whereHas('clinicPatient', fn ($c) => $c->where('user_id', $userId))
            ->where(fn ($w) => $w->whereHas('service', fn ($s) => $s->where('name', 'like', $like))
                ->orWhereHas('clinic', fn ($c) => $c->where('name', 'like', $like))
                ->orWhereHas('dentist', fn ($d) => $d->where('full_name', 'like', $like)))
            ->with(['clinic:id,name', 'service:id,name'])->latest('preferred_date')->limit(4)->get();

        $messages = PatientNotification::where('user_id', $userId)
            ->where(fn ($w) => $w->where('title', 'like', $like)->orWhere('body', 'like', $like))
            ->latest('created_at')->limit(3)->get();

        $pages = collect([
            ['Profile & settings', 'patient.profile.index', 'profile account personal information password privacy security notification settings'],
            ['Inbox', 'patient.notifications.index', 'inbox messages notifications alerts'],
            ['My visits', 'patient.appointments.index', 'visits appointments history bookings upcoming completed cancelled'],
            ['Support & clinic details', 'patient.support', 'support clinic details contact phone address about app help'],
            ['Contact Dental Connect', 'contact', 'contact complaint issue platform report'],
        ])->filter(fn ($p) => stripos($p[0].' '.$p[2], $q) !== false)->take(3)->values();

        return compact('pages', 'clinics', 'services', 'dentists', 'visits', 'messages');
    }
}
?>

<div x-data="{
        focused: false,
        open: false,
        hint: 0,
        hints: ['Search your clinic…', 'Search dentists…', 'Search services…', 'Search your visits…', 'Search messages…'],
    }"
    x-init="setInterval(() => { if (!focused && !$wire.query) hint = (hint + 1) % hints.length }, 3200)"
    @click.outside="open = false; focused = false"
    @keydown.escape.window="open = false; $refs.field.blur()"
    class="relative">

    <form @submit.prevent="$wire.runSearch(); open = true"
          class="relative flex items-center rounded-full bg-white p-1 shadow-sm ring-1 ring-dc-border transition-all duration-500 ease-[cubic-bezier(.22,1,.36,1)]"
          :class="focused ? 'shadow-lg shadow-dc-teal/15 ring-2 !ring-dc-teal' : ''">

        <div class="relative min-w-0 flex-1">
            {{-- Rotating hint: cross-fades/slides through what's searchable while idle --}}
            <div class="pointer-events-none absolute inset-y-0 left-4 right-2 flex items-center overflow-hidden text-sm text-dc-text-secondary">
                <template x-for="(h, i) in hints" :key="i">
                    <span x-show="hint === i && !focused && !$wire.query"
                          x-transition:enter="transition duration-500 ease-out" x-transition:enter-start="translate-y-3 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
                          x-transition:leave="transition duration-300 ease-in" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-3 opacity-0"
                          class="absolute" x-text="h"></span>
                </template>
            </div>
            <input type="text" x-ref="field" wire:model.live.debounce.300ms="query" autocomplete="off"
                   @focus="focused = true; open = true" @blur="setTimeout(() => { if (!$wire.query) focused = false }, 150)"
                   :placeholder="focused ? 'Your clinic, dentists, services, visits, messages…' : ''"
                   class="h-9 w-full border-0 bg-transparent pl-4 pr-2 text-sm text-dc-text placeholder:text-dc-text-secondary focus:outline-none focus:ring-0" aria-label="Search">
        </div>

        <button type="button" x-show="$wire.query" x-cloak x-transition.opacity
                @click="$wire.query = ''; $refs.field.focus()" aria-label="Clear search"
                class="mr-1 flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-dc-text-secondary transition hover:bg-dc-mint-light">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
        </button>

        {{-- Search button: icon + label at rest, squeezes down to just the icon once typing starts --}}
        <button type="submit" aria-label="Search"
                class="flex h-9 shrink-0 items-center rounded-full bg-gradient-to-r from-dc-teal to-dc-teal-deep text-white shadow-sm shadow-dc-teal/30 transition-all duration-500 ease-[cubic-bezier(.22,1,.36,1)] hover:brightness-110 active:scale-95">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center transition-transform duration-500" :class="focused ? 'scale-110' : ''">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/><path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </span>
            <span class="overflow-hidden whitespace-nowrap text-sm font-bold transition-all duration-500 ease-[cubic-bezier(.22,1,.36,1)]"
                  :class="focused ? 'max-w-0 opacity-0' : 'max-w-[6rem] opacity-100'">
                <span class="block pr-4">Search</span>
            </span>
        </button>

        <span wire:loading.delay wire:target="query,runSearch" class="absolute inset-x-6 -bottom-px h-0.5 overflow-hidden rounded-full">
            <span class="dc-search-bar block h-full w-1/3 rounded-full bg-dc-teal"></span>
        </span>
    </form>

    @php $results = $this->results; $total = collect($results)->sum(fn ($g) => $g->count()); @endphp

    <div x-show="open && $wire.query.trim().length >= 2" x-cloak
         x-transition:enter="transition duration-300 ease-[cubic-bezier(.22,1,.36,1)]" x-transition:enter-start="-translate-y-2 scale-[.98] opacity-0" x-transition:enter-end="translate-y-0 scale-100 opacity-100"
         x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="-translate-y-1 opacity-0"
         class="absolute inset-x-0 top-full z-20 mt-3 max-h-[70vh] origin-top overflow-y-auto rounded-3xl border border-dc-border bg-white p-2 shadow-2xl shadow-dc-teal-deep/15">

        @if ($total === 0 && mb_strlen(trim($query)) >= 2)
            <div class="px-4 py-8 text-center">
                <p class="text-sm font-bold">No results for “{{ $query }}”</p>
                <p class="mt-1 text-xs text-dc-text-secondary">Try a clinic, dentist, service or a word from a message.</p>
            </div>
        @else
            @php
                $groups = [
                    ['Go to', 'pages', 'M13 7l5 5-5 5M6 12h12'],
                    ['My clinic', 'clinics', 'M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15'],
                    ['Services', 'services', 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['Dentists', 'dentists', 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.1a7.5 7.5 0 0115 0'],
                    ['My visits', 'visits', 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5A2.25 2.25 0 015.25 5.25h13.5A2.25 2.25 0 0121 7.5v11.25M3 18.75A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75M3 11.25h18'],
                    ['Messages', 'messages', 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0l-9.75 6.75L2.25 6.75'],
                ];
                $i = 0;
            @endphp

            @foreach ($groups as [$label, $key, $iconPath])
                @if ($results[$key]->isNotEmpty())
                    <p class="px-3 pb-1 pt-3 text-[10px] font-bold uppercase tracking-wider text-dc-text-secondary">{{ $label }}</p>
                    @foreach ($results[$key] as $item)
                        @php
                            [$href, $title, $sub] = match ($key) {
                                'pages' => [route($item[1]), $item[0], 'Open page'],
                                'clinics' => [route('patient.support'), $item->name, $item->primaryLocation?->area ?? $item->primaryLocation?->city ?? 'Your clinic'],
                                'services' => [route('patient.appointments.book', $this->ownClinics->first()), $item->name, 'Book this service'],
                                'dentists' => [route('patient.support'), $item->full_name, $this->ownClinics->firstWhere('id', $item->clinic_id)?->name],
                                'visits' => [route('patient.appointments.show', $item), $item->service?->name ?? 'Appointment', $item->clinic->name.' · '.$item->preferred_date->format('j M Y')],
                                'messages' => [route('patient.notifications.index'), $item->title, $item->body],
                            };
                        @endphp
                        <a href="{{ $href }}" wire:navigate wire:key="sr-{{ $key }}-{{ $loop->index }}" @click="open = false; focused = false"
                           style="animation-delay: {{ ($i++) * 45 }}ms"
                           class="dc-rise group flex items-center gap-3 rounded-2xl px-3 py-2.5 transition hover:bg-dc-mint-light">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-dc-mint-light text-dc-teal-deep transition group-hover:bg-white">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $iconPath }}"/></svg>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-sm font-bold">{{ $this->mark($title) }}</span>
                                <span class="block truncate text-xs text-dc-text-secondary">{{ $this->mark($sub) }}</span>
                            </span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="shrink-0 text-dc-text-secondary transition group-hover:translate-x-0.5 group-hover:text-dc-teal-deep"><path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                    @endforeach
                @endif
            @endforeach
        @endif
    </div>
</div>
