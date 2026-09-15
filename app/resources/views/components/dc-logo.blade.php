@props(['size' => 40, 'iconOnly' => false, 'tagline' => null])
<div class="flex items-center gap-2.5">
    <span class="flex shrink-0 items-center justify-center rounded-xl bg-dc-mint" style="width: {{ $size }}px; height: {{ $size }}px;">
        <svg width="{{ round($size * 0.55) }}" height="{{ round($size * 0.55) }}" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 3.2c-1.1 0-1.9.55-2.85.8-.5.13-1 .2-1.55.2C5.5 4.2 4 5.9 4 8.1c0 2.35.55 4.55 1.3 6.75.45 1.35.85 3.1 1.85 3.2.75.07.9-1.35 1.1-2.5.2-1.2.55-2.55 1.75-2.55s1.55 1.35 1.75 2.55c.2 1.15.35 2.57 1.1 2.5 1-.1 1.4-1.85 1.85-3.2.75-2.2 1.3-4.4 1.3-6.75 0-2.2-1.5-3.9-3.6-3.9-.55 0-1.05-.07-1.55-.2-.95-.25-1.75-.8-2.85-.8Z"
                  stroke="#0F766E" stroke-width="1.5" stroke-linejoin="round"/>
            <path d="M9.3 10.4h5.4" stroke="#14B8A6" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M12 8.1v4.6" stroke="#14B8A6" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
    </span>
    @unless ($iconOnly)
        <span class="leading-tight">
            <span class="block text-lg font-bold text-dc-text">Dental Connect</span>
            @if ($tagline)
                <span class="block text-xs text-dc-text-secondary">{{ $tagline }}</span>
            @endif
        </span>
    @endunless
</div>
