@props(['size' => 40, 'iconOnly' => false, 'tagline' => null])
<div class="flex items-center gap-2.5">
    @if ($iconOnly)
        <picture>
            <source srcset="{{ asset('images/logo/dental-connect-icon-color.webp') }}" type="image/webp">
            <img src="{{ asset('images/logo/dental-connect-icon-color.png') }}" alt="Dental Connect"
                 style="height: {{ $size }}px; width: {{ $size }}px;" class="shrink-0 object-contain">
        </picture>
    @else
        {{-- Readable full wordmark lockup at every breakpoint, including mobile --}}
        <picture>
            <source srcset="{{ asset('images/logo/dental-connect-full-color.webp') }}" type="image/webp">
            <img src="{{ asset('images/logo/dental-connect-full-color.png') }}" alt="Dental Connect"
                 style="height: {{ round($size * 0.8) }}px;" class="w-auto shrink-0 object-contain">
        </picture>
        @if ($tagline)
            <span class="hidden border-l border-dc-border pl-2.5 text-xs leading-tight text-dc-text-secondary lg:block">{{ $tagline }}</span>
        @endif
    @endif
</div>
