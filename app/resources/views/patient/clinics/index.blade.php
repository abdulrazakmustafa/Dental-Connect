@php
    $title = $isChoosingFirstClinic ? 'Choose your clinic' : 'Find Dental Clinics';
@endphp
<x-layouts.patient-app :title="$title" active="clinics">
    @if ($isChoosingFirstClinic)
        <div class="dc-hero">
            <div class="absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/10"></div>
            <span class="dc-badge bg-white/20 text-white">Clinic-specific patient record</span>
            <h1 class="mt-3 text-xl font-extrabold leading-snug">Where would you like to receive care?</h1>
            <p class="mt-2 text-sm text-white/90">Choosing another clinic creates a separate private patient record for that clinic.</p>
        </div>
    @endif

    <form method="GET" class="mt-4">
        <div class="flex items-center gap-2 rounded-full border border-dc-border bg-white px-4 py-3 shadow-sm">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="shrink-0 text-dc-text-secondary"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/><path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ $isChoosingFirstClinic ? 'Search clinic, service or area' : 'Search clinic, service or area' }}" class="flex-1 border-0 bg-transparent text-sm focus:outline-none focus:ring-0">
        </div>
    </form>

    @unless ($isChoosingFirstClinic)
        <div class="mt-3 flex gap-2 overflow-x-auto pb-1">
            <a href="{{ route('clinics.index') }}" class="dc-pill-tab shrink-0 {{ ! request('service') && ! request('specialty') ? 'dc-pill-tab-active' : 'dc-pill-tab-inactive' }}">Near me</a>
            @foreach ($services->take(2) as $service)
                <a href="{{ route('clinics.index', ['service' => $service->id]) }}" class="dc-pill-tab shrink-0 {{ request('service') == $service->id ? 'dc-pill-tab-active' : 'dc-pill-tab-inactive' }}">{{ $service->name }}</a>
            @endforeach
        </div>
    @endif

    <div class="mt-4 grid grid-cols-1 gap-3 lg:grid-cols-2">
        @forelse ($clinics as $clinic)
            {{-- Stretched-link pattern: the <a> is an inset overlay so it never nests inside/around
                 the favorite <button> (invalid HTML that also let Livewire's capture-phase
                 wire:navigate handler fire before Alpine's own @click.stop could run). --}}
            <div class="dc-card relative flex items-center gap-3 p-4 transition hover:-translate-y-0.5 hover:shadow-md">
                <a href="{{ route('clinics.show', $clinic) }}" wire:navigate class="absolute inset-0 z-0" aria-label="{{ $clinic->name }}"></a>
                <span class="dc-avatar-square h-12 w-12 shrink-0 text-lg">{{ strtoupper(substr($clinic->name, 0, 1)) }}</span>
                <div class="pointer-events-none min-w-0 flex-1">
                    <p class="pr-6 text-sm font-bold">{{ $clinic->name }}</p>
                    <p class="mt-0.5 truncate pr-6 text-xs text-dc-text-secondary">
                        {{ $clinic->primaryLocation?->area ?? $clinic->primaryLocation?->city ?? 'Dar es Salaam' }}
                        @if ($clinic->services->isNotEmpty())
                            &middot; {{ $clinic->services->take(2)->pluck('name')->implode(' · ') }}
                        @endif
                    </p>
                    @if ($clinic->reviews_count > 0)
                        <p class="mt-1 text-xs text-amber-500">★ {{ number_format($clinic->reviews_avg_rating, 1) }} <span class="text-dc-text-secondary">({{ $clinic->reviews_count }})</span></p>
                    @endif
                </div>
                <span class="dc-badge pointer-events-none shrink-0 bg-dc-mint text-dc-teal-deep">Verified</span>

                <button type="button" x-data="{ saved: isFavoriteClinic('{{ $clinic->public_id }}') }"
                        @click="saved = toggleFavoriteClinic('{{ $clinic->public_id }}')"
                        class="relative z-10 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white/80 shadow-sm transition"
                        :class="saved ? 'text-dc-danger' : 'text-dc-text-secondary hover:text-dc-danger'"
                        aria-label="Save clinic">
                    <svg width="15" height="15" viewBox="0 0 24 24" :fill="saved ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="1.8"><path d="M12 20.5s-7.5-4.6-9.7-9.1C.7 8 2.3 4.7 5.6 4.1c2-.4 3.9.5 5 2.1 1.1-1.6 3-2.5 5-2.1 3.3.6 4.9 3.9 3.3 7.3-2.2 4.5-9.7 9.1-9.7 9.1z" stroke-linejoin="round"/></svg>
                </button>
            </div>
        @empty
            <div class="dc-card p-6 text-center text-sm text-dc-text-secondary lg:col-span-2">No verified clinics match your search yet.</div>
        @endforelse
    </div>

    @if ($isChoosingFirstClinic)
        <div class="dc-card mt-4 p-4 text-center text-xs text-dc-text-secondary">
            Already registered at another clinic? Use the same login and enroll separately here.
        </div>
    @endif

    <div class="mt-4">{{ $clinics->links() }}</div>
</x-layouts.patient-app>
