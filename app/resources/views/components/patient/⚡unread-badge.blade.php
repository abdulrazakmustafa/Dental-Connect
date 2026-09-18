<?php

use App\Modules\Notification\Models\PatientNotification;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public string $variant = 'dot';
    public bool $active = false;

    public function getCountProperty(): int
    {
        return PatientNotification::where('user_id', auth()->id())->whereNull('read_at')->count();
    }

    public function getClassesProperty(): string
    {
        if ($this->count === 0) {
            return $this->variant === 'pill' ? 'ml-auto hidden' : 'hidden';
        }

        return match ($this->variant) {
            'pill' => 'ml-auto flex h-5 min-w-5 items-center justify-center rounded-full '.($this->active ? 'bg-white/25 text-white' : 'bg-dc-teal text-white').' px-1.5 text-[10px] font-bold',
            'micro' => 'absolute -right-1.5 -top-1.5 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-dc-teal text-[8px] font-bold text-white ring-2 ring-white',
            default => 'absolute -right-0.5 -top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-dc-teal px-1 text-[9px] font-bold text-white ring-2 ring-white',
        };
    }

    #[On('notifications-changed')]
    public function refresh(): void
    {
        // Re-render only; computed properties are recalculated fresh on every render.
    }
}
?>

<span wire:poll.20s class="{{ $this->classes }}">{{ $this->variant === 'micro' ? min($this->count, 9) : ($this->count > 9 ? '9+' : $this->count) }}</span>
