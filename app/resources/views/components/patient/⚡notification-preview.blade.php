<?php

use App\Modules\Notification\Models\PatientNotification;
use Livewire\Component;

new class extends Component
{
    public function getNotificationsProperty()
    {
        return PatientNotification::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();
    }

    public function markRead(string $publicId): void
    {
        PatientNotification::where('user_id', auth()->id())
            ->where('public_id', $publicId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $this->dispatch('notifications-changed');
    }

    public function markAllRead(): void
    {
        PatientNotification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $this->dispatch('notifications-changed');
    }
}
?>

<div wire:poll.15s.visible class="max-h-[26rem] overflow-y-auto">
    <div class="flex items-center justify-between px-4 pb-2 pt-4">
        <p class="text-sm font-extrabold">Notifications</p>
        @if ($this->notifications->whereNull('read_at')->count() > 0)
            <button wire:click="markAllRead" class="text-xs font-bold text-dc-teal-deep hover:underline">Mark all read</button>
        @endif
    </div>

    <div class="space-y-1 px-2 pb-2">
        @forelse ($this->notifications as $notification)
            <button wire:click="markRead('{{ $notification->public_id }}')"
                    class="flex w-full items-start gap-3 rounded-2xl px-3 py-2.5 text-left transition hover:bg-white/60 {{ $notification->read_at ? '' : 'bg-white/50' }}">
                <span class="mt-1 h-2 w-2 shrink-0 rounded-full {{ $notification->read_at ? 'bg-transparent' : 'bg-dc-teal' }}"></span>
                <span class="min-w-0 flex-1">
                    <span class="block truncate text-sm font-bold">{{ $notification->title }}</span>
                    <span class="mt-0.5 block truncate text-xs text-dc-text-secondary">{{ $notification->body }}</span>
                </span>
                <span class="shrink-0 pt-0.5 text-[10px] text-dc-text-secondary">{{ $notification->created_at->diffForHumans(null, true) }}</span>
            </button>
        @empty
            <p class="px-3 py-6 text-center text-sm text-dc-text-secondary">You're all caught up.</p>
        @endforelse
    </div>

    <a href="{{ route('patient.notifications.index') }}" wire:navigate
       class="block border-t border-white/40 px-4 py-3 text-center text-sm font-bold text-dc-teal-deep hover:bg-white/50">
        View all
    </a>
</div>
