@php
    $grouped = $notifications->groupBy(fn ($n) => $n->created_at->isToday() ? 'Today' : ($n->created_at->isYesterday() ? 'Yesterday' : $n->created_at->format('j M Y')));
@endphp
<x-layouts.patient-app title="Inbox" active="messages">
    <div class="flex gap-2 overflow-x-auto pb-1">
        @foreach (['all' => 'All', 'clinic' => 'Clinic', 'system' => 'System'] as $value => $label)
            <a href="{{ route('patient.notifications.index', ['filter' => $value]) }}" wire:navigate
               class="dc-pill-tab shrink-0 {{ $filter === $value ? 'dc-pill-tab-active' : 'dc-pill-tab-inactive' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="mt-4 space-y-5">
        @forelse ($grouped as $day => $items)
            <div>
                <p class="mb-2 text-xs font-bold uppercase tracking-wide text-dc-text-secondary">{{ $day }}</p>
                <div class="space-y-2">
                    @foreach ($items as $notification)
                        <div class="dc-card flex items-start gap-3 p-4">
                            <span class="dc-avatar h-9 w-9 shrink-0 text-sm">
                                @if (str_starts_with($notification->type, 'appointment.'))
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="4" y="5" width="16" height="15" rx="2.5" stroke="currentColor" stroke-width="1.8"/><path d="M4 10h16" stroke="currentColor" stroke-width="1.8"/></svg>
                                @else
                                    {{ strtoupper(substr($notification->title, 0, 1)) }}
                                @endif
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-bold">{{ $notification->title }}</p>
                                <p class="mt-0.5 text-sm text-dc-text-secondary">{{ $notification->body }}</p>
                            </div>
                            <span class="shrink-0 text-xs text-dc-text-secondary">{{ $notification->created_at->format('g:i A') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="dc-card p-4">
                <div class="flex items-start gap-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-dc-mint text-dc-teal-deep">?</span>
                    <div>
                        <p class="text-sm font-bold">Need help?</p>
                        <p class="mt-0.5 text-xs text-dc-text-secondary">Contact support or report an issue.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</x-layouts.patient-app>
