@props(['navTabs' => null, 'moreItems' => null, 'spa' => false, 'liveBadgeRef' => null, 'liveBadgeComponent' => null])
@php
    $currentRoute = Route::currentRouteName();

    // Callers (e.g. the patient app shell) can pass their own $navTabs/$moreItems to get this
    // exact liquid-circle nav with a different menu — the marketing set below is only the default.
    $navTabs = $navTabs ?? [
        [
            'ref' => 'tabClinics',
            'href' => route('clinics.index'),
            'route' => 'clinics.index',
            'label' => 'Clinics',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>',
        ],
        [
            'ref' => 'tabForClinics',
            'href' => route('for-clinics'),
            'route' => 'for-clinics',
            'label' => 'Partners',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>',
        ],
        [
            'ref' => 'tabHome',
            'href' => route('home'),
            'route' => 'home',
            'label' => 'Home',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>',
        ],
        [
            'ref' => 'tabMore',
            'href' => null,
            'route' => null,
            'label' => 'More',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>',
        ],
        [
            'ref' => 'tabForSuppliers',
            'href' => route('for-suppliers'),
            'route' => 'for-suppliers',
            'label' => 'Suppliers',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>',
        ],
    ];

    $pageRef = collect($navTabs)->first(fn ($t) => $t['route'] === $currentRoute)['ref'] ?? ($navTabs[0]['ref'] ?? 'tabHome');

    $moreItems = $moreItems ?? [
        ['label' => 'How It Works', 'href' => route('home') . '#how-it-works', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>'],
        ['label' => 'About Us', 'href' => route('about'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>'],
        ['label' => 'Contact Us', 'href' => route('contact'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/>'],
    ];
    $moreCount = count($moreItems);
@endphp
<div
    x-data="{
        mobileMore: false,
        navHidden: false,
        lastScrollY: 0,
        spa: {{ $spa ? 'true' : 'false' }},
        pageRef: '{{ $pageRef }}',
        activeRef: '{{ $pageRef }}',
        circleX: 0,
        moreX: 0,
        ready: false,
        moveCircleTo(ref) {
            // Sets the target value only — both the circle's transform and the bar's notch
            // are bound to this same circleX and each carry an identical CSS transition
            // (same duration/easing), so the browser animates them together in lockstep
            // without any JS-driven per-frame tweening (which this environment throttles).
            const el = this.$refs[ref];
            if (!el || !this.$refs.navRow) return;
            const rowRect = this.$refs.navRow.getBoundingClientRect();
            const elRect = el.getBoundingClientRect();
            this.circleX = Math.round((elRect.left - rowRect.left) + elRect.width / 2);
        },
        moveMoreAnchor() {
            // The radial More-menu fan is anchored to the More button's own x position (not
            // the bar's center) so it genuinely fans out from that side of the bar.
            const el = this.$refs.tabMore;
            if (!el || !this.$refs.navRow) return;
            const rowRect = this.$refs.navRow.getBoundingClientRect();
            const elRect = el.getBoundingClientRect();
            this.moreX = Math.round((elRect.left - rowRect.left) + elRect.width / 2);
        },
        select(ref, event) {
            if (ref === 'tabMore') {
                this.mobileMore = !this.mobileMore;
                this.activeRef = this.mobileMore ? 'tabMore' : this.pageRef;
                this.moveCircleTo(this.activeRef);
                return;
            }
            event.preventDefault();
            this.mobileMore = false;
            this.activeRef = ref;
            this.moveCircleTo(ref);
            // Short delay, matched to the shared 260ms transition, so the glide is actually
            // visible before the page unloads — long enough to see, short enough not to feel
            // like a wait.
            const href = event.currentTarget.href;
            setTimeout(() => {
                // SPA mode (patient app): hand off to Livewire's client-side navigation instead
                // of a hard reload, so the shell/chrome never flashes — same glide, no full load.
                if (this.spa && window.Livewire) { window.Livewire.navigate(href); }
                else { window.location.href = href; }
            }, 280);
        },
        closeMore() {
            if (!this.mobileMore) return;
            this.mobileMore = false;
            this.activeRef = this.pageRef;
            this.moveCircleTo(this.pageRef);
        },
    }"
    x-init="
        $nextTick(() => {
            moveCircleTo(pageRef); moveMoreAnchor();
            // Place the circle with transitions OFF, then enable them two frames later — otherwise it
            // glides in from x=0 on every page render, so the nav looks like it 'reacts' to any
            // same-page navigation (e.g. switching Inbox filters) instead of staying still.
            requestAnimationFrame(() => requestAnimationFrame(() => ready = true));
        });
        window.addEventListener('resize', () => { moveCircleTo(activeRef); moveMoreAnchor(); });
        lastScrollY = window.scrollY;
        window.addEventListener('scroll', () => {
            const y = Math.max(0, window.scrollY);
            navHidden = y > lastScrollY && y > 80;
            lastScrollY = y;
        }, { passive: true });
    "
>
    <nav :class="navHidden ? 'translate-y-[130%]' : 'translate-y-0'" class="fixed inset-x-0 bottom-0 z-50 transition-transform duration-300 ease-out lg:hidden" aria-label="Mobile navigation">
        <div class="relative mx-3 mb-3">
            {{-- Notched pill background: a real transparent cut-out (CSS mask), its center tracking the
                 liquid circle's current x position, so whatever is behind the bar always shows through. --}}
            {{-- The notch snaps to its target instantly (no CSS transition) rather than
                 animating the gradient itself — repainting a radial-gradient mask every frame
                 is expensive and can visibly lag behind the cheap, GPU-composited circle
                 transform, causing the two to drift out of sync mid-glide. Snapping removes
                 any chance of that: the hole is always exactly where the circle is heading,
                 and the circle glides into it. --}}
            <div x-ref="navRow" class="relative flex items-center rounded-[1.75rem] border border-white/40 bg-gradient-to-r from-dc-teal/90 via-dc-teal-deep/90 to-dc-teal-dark/85 px-1 py-2.5 shadow-[0_-4px_32px_-8px_rgba(15,118,110,0.35)] backdrop-blur-xl"
                 :style="`mask-image: radial-gradient(circle 32px at ${circleX}px 0px, transparent 99%, #000 100%); -webkit-mask-image: radial-gradient(circle 32px at ${circleX}px 0px, transparent 99%, #000 100%);`">

                @foreach ($navTabs as $tab)
                    @if ($tab['ref'] === 'tabMore')
                        <button type="button" x-ref="{{ $tab['ref'] }}" @click="select('{{ $tab['ref'] }}', $event)"
                                class="relative z-10 flex flex-1 flex-col items-center justify-center gap-0.5 py-1 transition-colors duration-200"
                                :class="activeRef === '{{ $tab['ref'] }}' ? 'text-white' : 'text-white/60 hover:text-white/80'">
                            <span class="flex h-5 w-5 items-center justify-center transition-opacity duration-200" :class="activeRef === '{{ $tab['ref'] }}' ? 'opacity-0' : 'opacity-100'">
                                <svg class="h-5 w-5 transition-transform duration-300" :class="mobileMore ? 'rotate-45' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">{!! $tab['icon'] !!}</svg>
                            </span>
                            <span class="text-[10px] font-medium">{{ $tab['label'] }}</span>
                        </button>
                    @else
                        <a x-ref="{{ $tab['ref'] }}" href="{{ $tab['href'] }}" @click="select('{{ $tab['ref'] }}', $event)"
                           class="relative z-10 flex flex-1 flex-col items-center justify-center gap-0.5 py-1 transition-colors duration-200"
                           :class="activeRef === '{{ $tab['ref'] }}' ? 'text-white' : 'text-white/60 hover:text-white/80'"
                           {{ $tab['route'] === $currentRoute ? 'aria-current=page' : '' }}>
                            <span class="relative flex h-5 w-5 items-center justify-center transition-opacity duration-200" :class="activeRef === '{{ $tab['ref'] }}' ? 'opacity-0' : 'opacity-100'">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">{!! $tab['icon'] !!}</svg>
                                @if ($liveBadgeRef === $tab['ref'] && $liveBadgeComponent)
                                    @livewire($liveBadgeComponent, ['variant' => 'micro'], 'mobilenav-live-badge')
                                @endif
                            </span>
                            <span class="text-[10px] font-medium">{{ $tab['label'] }}</span>
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- Liquid active-tab indicator: a single solid circle (no colored ring/border) that
                 glides to whichever tab is selected, carrying that tab's icon up out of the bar
                 into the notch's open space. --}}
            <div class="pointer-events-none absolute inset-x-0 -top-7 flex justify-start will-change-transform" :class="ready ? 'transition-transform duration-[260ms] ease-out' : ''" :style="`transform: translateX(${circleX}px)`">
                <div class="pointer-events-none -ml-7 flex h-14 w-14 items-center justify-center rounded-full bg-white text-dc-teal-deep shadow-[0_4px_20px_-4px_rgba(15,118,110,0.4)]">
                    @foreach ($navTabs as $tab)
                        <template x-if="activeRef === '{{ $tab['ref'] }}'">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">{!! $tab['icon'] !!}</svg>
                        </template>
                    @endforeach
                </div>
            </div>

            {{-- Radial "More" menu — items fan out in a half-moon arc above the More button,
                 each hanging as its own separate circular bubble, staggered in on open. --}}
            <div class="pointer-events-none absolute inset-x-0 bottom-0" role="menu" aria-label="More navigation">
                @foreach ($moreItems as $i => $item)
                    @php
                        $angle = 180 - ($i * (180 / ($moreCount - 1)));
                        $radius = 62;
                        $x = round($radius * cos(deg2rad($angle)));
                        $y = round(-$radius * sin(deg2rad($angle)));
                    @endphp
                    {{-- Positioning lives on this wrapper (reactive :style, no x-show) so it never
                         fights with the inner element's own x-show display toggling. --}}
                    <div class="pointer-events-none absolute" :style="`left: ${moreX + ({{ $x }})}px; bottom: {{ 66 - $y }}px; margin-left: -24px; width: 48px;`">
                        <a href="{{ $item['href'] }}"
                           @click="closeMore()"
                           x-show="mobileMore"
                           x-cloak
                           x-transition:enter="transition ease-out duration-300"
                           x-transition:enter-start="opacity-0 scale-0"
                           x-transition:enter-end="opacity-100 scale-100"
                           x-transition:leave="transition ease-in duration-150"
                           x-transition:leave-start="opacity-100 scale-100"
                           x-transition:leave-end="opacity-0 scale-0"
                           style="transition-delay: {{ $i * 40 }}ms;"
                           class="pointer-events-auto flex flex-col items-center gap-1">
                            <span class="flex h-11 w-11 items-center justify-center rounded-full border border-white/30 bg-dc-teal-deep text-white shadow-lg">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $item['icon'] !!}</svg>
                            </span>
                            <span class="whitespace-nowrap rounded-full bg-black/60 px-1.5 py-0.5 text-[9px] font-medium text-white">{{ $item['label'] }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </nav>

    {{-- Backdrop for the radial menu — tap anywhere outside to close --}}
    <div x-show="mobileMore" x-cloak x-transition.opacity @click="closeMore()" class="fixed inset-0 z-40 bg-dc-teal-dark/30 backdrop-blur-[2px] lg:hidden" aria-hidden="true"></div>
</div>
