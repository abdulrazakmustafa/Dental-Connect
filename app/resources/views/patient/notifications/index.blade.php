<x-layouts.patient-app title="Messages" active="messages">
    <div class="flex gap-2">
        @foreach (['all' => 'All', 'clinic' => 'Clinic', 'system' => 'System'] as $value => $label)
            <a href="{{ route('patient.notifications.index', ['filter' => $value]) }}" class="dc-pill-tab {{ $filter === $value ? 'dc-pill-tab-active' : 'dc-pill-tab-inactive' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="mt-4 space-y-3">
        @forelse ($notifications as $notification)
            <div class="dc-card flex items-start gap-3 p-4">
                <span class="dc-avatar h-9 w-9 shrink-0 text-sm">{{ strtoupper(substr($notification->title, 0, 1)) }}</span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold">{{ $notification->title }}</p>
                    <p class="mt-0.5 truncate text-sm text-dc-text-secondary">{{ $notification->body }}</p>
                </div>
                <span class="shrink-0 text-xs text-dc-text-secondary">{{ $notification->created_at->diffForHumans(null, true) }}</span>
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
