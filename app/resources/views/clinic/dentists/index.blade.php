@php
    $nav = include resource_path('views/clinic/_nav.php');
@endphp
<x-layouts.dashboard title="Dentists" :nav="$nav">
    @if (session('status'))
        <div class="mb-4 rounded-xl border border-dc-success/30 bg-dc-success-bg p-4 text-sm text-dc-success">{{ session('status') }}</div>
    @endif

    <div class="mb-4 flex justify-end">
        <a href="{{ route('clinic.dentists.create') }}" class="dc-btn-primary">Add dentist</a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($dentists as $dentist)
            <a href="{{ route('clinic.dentists.edit', $dentist) }}" class="dc-card p-5">
                <div class="flex items-center gap-3">
                    <span class="dc-avatar h-11 w-11">{{ collect(explode(' ', str_replace('Dr. ', '', $dentist->full_name)))->map(fn($p) => strtoupper(substr($p,0,1)))->implode('') }}</span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-semibold">{{ $dentist->full_name }}</p>
                        <p class="truncate text-xs text-dc-text-secondary">{{ $dentist->specialties->pluck('name')->implode(', ') ?: 'General Dentist' }}</p>
                    </div>
                    <span class="dc-badge shrink-0 {{ $dentist->status === 'active' ? 'bg-dc-mint text-dc-teal-deep' : 'bg-gray-100 text-dc-text-secondary' }}">{{ ucfirst($dentist->status) }}</span>
                </div>
                @if ($dentist->license_number)
                    <p class="mt-3 text-xs text-dc-text-secondary">License: {{ $dentist->license_number }}</p>
                @endif
            </a>
        @empty
            <div class="dc-card col-span-full p-8 text-center text-sm text-dc-text-secondary">No dentists added yet.</div>
        @endforelse
    </div>
</x-layouts.dashboard>
