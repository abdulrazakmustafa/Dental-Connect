@props(['transparent' => false, 'align' => 'right'])
@php
    // General, platform-wide announcements — visible to every visitor, logged in or not.
    $generalNotifications = [
        ['title' => 'Welcome to Dental Connect', 'body' => 'Browse verified clinics, compare real reviews and book appointments — all in one place.'],
        ['title' => 'New clinics just joined', 'body' => 'More verified dental clinics were added across Dar es Salaam and Arusha this month.'],
        ['title' => 'Tip: check the reviews', 'body' => 'Every verified clinic profile now shows real patient ratings and pricing before you book.'],
    ];

    $isPatient = auth()->check() && auth()->user()->hasRole('patient');
    $personalNotifications = $isPatient
        ? \App\Modules\Notification\Models\PatientNotification::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
        : collect();
    $unreadCount = $isPatient ? $personalNotifications->whereNull('read_at')->count() : 0;
@endphp
<div {{ $attributes->merge(['class' => 'relative']) }} x-data="{ open: false }" @click.outside="open = false">
    @if ($transparent)
        <button type="button" @click="open = !open"
                :class="scrolled ? 'border-dc-border/60 bg-white text-dc-text' : 'border-white/30 bg-white/10 text-white'"
                class="relative flex h-9 w-9 items-center justify-center rounded-full border backdrop-blur-sm transition"
                aria-label="Notifications" aria-haspopup="true" :aria-expanded="open.toString()">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
            @if ($unreadCount > 0)
                <span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-dc-danger ring-2 ring-dc-teal-dark"></span>
            @endif
        </button>
    @else
        <button type="button" @click="open = !open"
                class="relative flex h-9 w-9 items-center justify-center rounded-full border border-dc-border/60 bg-white text-dc-text transition hover:bg-dc-mint-light"
                aria-label="Notifications" aria-haspopup="true" :aria-expanded="open.toString()">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
            @if ($unreadCount > 0)
                <span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-dc-danger ring-2 ring-white"></span>
            @endif
        </button>
    @endif

    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 -translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="absolute {{ $align === 'right' ? 'right-0 origin-top-right' : 'left-0 origin-top-left' }} top-full z-50 mt-5 w-80 max-w-[90vw] overflow-hidden rounded-2xl border border-white/30 bg-white/40 shadow-xl backdrop-blur-2xl">
        <div class="flex items-center justify-between border-b border-white/50 px-4 py-3">
            <p class="text-sm font-bold text-dc-text">Notifications</p>
            @if ($unreadCount > 0)
                <span class="dc-badge bg-dc-teal/15 text-dc-teal-deep">{{ $unreadCount }} new</span>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto">
            @if ($personalNotifications->isNotEmpty())
                @foreach ($personalNotifications as $notification)
                    <div class="flex gap-3 border-b border-white/40 px-4 py-3 {{ $notification->read_at ? '' : 'bg-white/40' }}">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/70 text-dc-teal-deep">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                        </span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-dc-text">{{ $notification->title }}</p>
                            <p class="mt-0.5 line-clamp-2 text-xs text-dc-text-secondary">{{ $notification->body }}</p>
                            <p class="mt-1 text-[10px] text-dc-text-secondary">{{ $notification->created_at?->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
                <p class="border-b border-white/40 bg-white/30 px-4 py-1.5 text-[10px] font-bold uppercase tracking-wider text-dc-text-secondary">Platform updates</p>
            @endif

            @foreach ($generalNotifications as $notification)
                <div class="flex gap-3 border-b border-white/40 px-4 py-3 last:border-0">
                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/70 text-dc-teal-deep">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-dc-text">{{ $notification['title'] }}</p>
                        <p class="mt-0.5 line-clamp-2 text-xs text-dc-text-secondary">{{ $notification['body'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($isPatient)
            <a href="{{ route('patient.notifications.index') }}" class="block border-t border-white/50 px-4 py-3 text-center text-sm font-semibold text-dc-teal-deep transition hover:bg-white/40">
                View all notifications
            </a>
        @endif
    </div>
</div>
