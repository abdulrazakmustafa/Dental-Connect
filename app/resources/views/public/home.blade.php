@php
    $hasHeroPhoto = file_exists(public_path('images/hero/desktop.jpg'));
    $ratingLabel = $avgRating ? number_format($avgRating, 1) : '4.9';
    $clinicCount = $verifiedClinics ?: 0;

    $testimonials = [
        [
            'quote' => 'Our mission is simple: make it easy for every family in Tanzania to find quality dental care they can trust.',
            'name' => 'Dental Connect Team',
            'meta' => 'Dar es Salaam, Tanzania',
            'avatar' => 'dentist-tablet-clinic',
        ],
        [
            'quote' => 'I booked my daughter\'s appointment in minutes and could see the clinic\'s reviews before we even walked in. It made choosing so much easier.',
            'name' => 'Amina R.',
            'meta' => 'Patient, Dar es Salaam',
            'avatar' => 'woman-brushing-teeth',
        ],
        [
            'quote' => 'Since joining Dental Connect, our clinic gets new patients every week and appointment scheduling no longer means a phone glued to my ear.',
            'name' => 'Dr. Juma Mkapa',
            'meta' => 'Smile Dental Clinic, Arusha',
            'avatar' => 'dental-team-portrait',
        ],
        [
            'quote' => 'Listing our catalogue on Dental Connect connected us directly with verified clinics — no more cold calls, just real quotation requests.',
            'name' => 'Grace Mushi',
            'meta' => 'Supplier Partner, Mwanza',
            'avatar' => 'dental-supplies-equipment',
        ],
    ];

    $shieldIcon = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>';
    $tagIcon = '<path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>';
    $starIcon = '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>';
    $calendarIcon = '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>';
    $chartIcon = '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>';
    $truckIcon = '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>';
    $boxIcon = '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375C2.754 3.75 2.25 4.254 2.25 4.875v1.5c0 .621.504 1.125 1.125 1.125z"/>';

    $heroSlides = [
        [
            'line1' => 'Family-Friendly',
            'line2' => 'Dental Care',
            'desc' => 'A new platform connecting you with verified, licensed dental clinics across Tanzania. Compare real patient experiences and choose with confidence.',
            'chips' => [
                ['label' => 'Verified Clinics', 'icon' => $shieldIcon],
                ['label' => 'Transparent Pricing', 'icon' => $tagIcon],
                ['label' => 'Trusted Reviews', 'icon' => $starIcon],
            ],
        ],
        [
            'line1' => 'Find Trusted Care,',
            'line2' => 'Anytime You Need It',
            'desc' => 'Browse verified clinic profiles, compare real reviews and book your appointment in minutes — no phone calls, no waiting rooms.',
            'chips' => [
                ['label' => 'Easy Booking', 'icon' => $calendarIcon],
                ['label' => 'Real Reviews', 'icon' => $starIcon],
                ['label' => 'Verified Clinics', 'icon' => $shieldIcon],
            ],
        ],
        [
            'line1' => 'Grow Your Clinic',
            'line2' => 'With Verified Patients',
            'desc' => 'List your clinic on Dental Connect to reach new patients, manage appointments online and build a trusted, review-backed reputation.',
            'chips' => [
                ['label' => 'More Patients', 'icon' => $chartIcon],
                ['label' => 'Manage Bookings', 'icon' => $calendarIcon],
                ['label' => 'Verified Profile', 'icon' => $shieldIcon],
            ],
        ],
        [
            'line1' => 'Reach Verified Clinics,',
            'line2' => 'Faster Than Ever',
            'desc' => 'Suppliers can list their catalogue, respond to quotation requests and grow direct relationships with dental clinics across Tanzania.',
            'chips' => [
                ['label' => 'List Your Catalogue', 'icon' => $boxIcon],
                ['label' => 'Reach Clinics Directly', 'icon' => $truckIcon],
                ['label' => 'Grow B2B Sales', 'icon' => $chartIcon],
            ],
        ],
    ];

    // Each slide's image + where its actual subject sits, so the sm+ "shift toward the clear
    // side" only ever reveals more subject — never more of a blank/empty background.
    $heroBackgrounds = [
        ['img' => null, 'position' => '85% center'], // slide 0 uses the dedicated responsive hero/ photo set below
        ['img' => 'dentist-patient-oceanview', 'position' => '75% center'],
        ['img' => 'dental-team-portrait', 'position' => '15% center'],
        ['img' => 'dental-supplies-equipment', 'position' => 'center center'],
    ];
@endphp
<x-layouts.public :title="'Dental Connect | Family-Friendly Dental Care'" :transparent-header="true">

    {{-- HERO — Full-viewport image background (Dentora-inspired layout) --}}
    <section
        x-data="{
            cardIndex: 0,
            totalCards: 2,
            scrollToCard(i) {
                this.cardIndex = Math.max(0, Math.min(this.totalCards - 1, i));
                const track = this.$refs.cardTrack;
                const target = track.children[this.cardIndex];
                if (!track || !target) return;
                const trackRect = track.getBoundingClientRect();
                const targetRect = target.getBoundingClientRect();
                track.scrollTo({ left: track.scrollLeft + (targetRect.left - trackRect.left), behavior: 'smooth' });
            },
            heroIndex: 0,
            totalSlides: {{ count($heroSlides) }},
            heroTimer: null,
            startHeroTimer() {
                clearInterval(this.heroTimer);
                this.heroTimer = setInterval(() => { this.heroIndex = (this.heroIndex + 1) % this.totalSlides; }, 6000);
            },
            goToSlide(i) {
                this.heroIndex = ((i % this.totalSlides) + this.totalSlides) % this.totalSlides;
                this.startHeroTimer();
            }
        }"
        x-init="startHeroTimer()"
        class="relative min-h-screen overflow-hidden bg-dc-teal-dark">
        {{-- Background image — 4 auto-rotating slides (general / patient / clinic / supplier), crossfading --}}
        <div class="absolute inset-0">
            @foreach ($heroSlides as $i => $slide)
                <div class="absolute inset-0 transition-opacity duration-1000" style="opacity: {{ $i === 0 ? 1 : 0 }};" :style="`opacity: ${heroIndex === {{ $i }} ? 1 : 0}`">
                    @if ($i === 0 && $hasHeroPhoto)
                        <picture>
                            <source type="image/webp" media="(max-width: 639px)" srcset="{{ asset('images/hero/mobile.webp') }}">
                            <source type="image/webp" media="(max-width: 1023px)" srcset="{{ asset('images/hero/tablet.webp') }}">
                            <source type="image/webp" srcset="{{ asset('images/hero/desktop.webp') }}">
                            <source media="(max-width: 639px)" srcset="{{ asset('images/hero/mobile.jpg') }}">
                            <source media="(max-width: 1023px)" srcset="{{ asset('images/hero/tablet.jpg') }}">
                            <img src="{{ asset('images/hero/desktop.jpg') }}" alt=""
                                 class="h-full w-full object-cover object-center sm:object-[85%_center]"
                                 loading="eager" fetchpriority="high" width="1774" height="887">
                        </picture>
                    @elseif ($i === 0)
                        <div class="h-full w-full bg-gradient-to-br from-dc-teal-dark via-dc-teal-deep to-dc-teal"></div>
                    @else
                        <img src="{{ asset('images/landing/' . $heroBackgrounds[$i]['img'] . '.webp') }}" alt=""
                             class="h-full w-full object-cover object-center sm:[object-position:var(--hero-pos)]"
                             style="--hero-pos: {{ $heroBackgrounds[$i]['position'] }};" loading="lazy">
                    @endif
                </div>
            @endforeach
            <div class="absolute inset-0 hidden sm:block" style="background: linear-gradient(to right, #0a2f2c 0%, rgba(10,47,44,0.6) 45%, transparent 100%)"></div>
            <div class="absolute inset-0 sm:hidden" style="background: linear-gradient(to top, #0a2f2c 0%, rgba(10,47,44,0.72) 45%, rgba(10,47,44,0.5) 100%)"></div>
        </div>

        {{-- Main content layer --}}
        <div class="relative flex min-h-screen flex-col">
            {{-- Spacer: keeps text clear of the overlay header while the hero photo runs to the very top --}}
            <div class="h-[70px] shrink-0 sm:h-[74px]"></div>

            {{-- Content area — centers within the space genuinely above the floating card (see reserve spacer below) --}}
            <div class="flex flex-1 items-center">
                <div class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    {{-- Stacked, crossfading slide text — headline/description/chips rotate with the
                         background; the cards and service tags below stay fixed across all slides. --}}
                    <div class="relative min-h-[260px] max-w-lg sm:min-h-[220px] lg:max-w-xl">
                        @foreach ($heroSlides as $i => $slide)
                            <div class="absolute inset-0 transition-opacity duration-700" :class="heroIndex === {{ $i }} ? '' : 'pointer-events-none'" style="opacity: {{ $i === 0 ? 1 : 0 }};" :style="`opacity: ${heroIndex === {{ $i }} ? 1 : 0}`">
                                <h1 class="text-4xl font-extrabold leading-[1.08] tracking-tight text-white sm:text-5xl lg:text-[3.75rem]">
                                    {{ $slide['line1'] }}<br>
                                    {{ $slide['line2'] }}
                                </h1>

                                <p class="mt-4 max-w-md text-base leading-relaxed text-white/75 sm:text-lg">
                                    {{ $slide['desc'] }}
                                </p>

                                <div class="mt-5 flex flex-nowrap items-center gap-1.5 sm:flex-wrap sm:gap-2">
                                    @foreach ($slide['chips'] as $chip)
                                        <span class="inline-flex shrink items-center gap-1 whitespace-nowrap rounded-full border border-white/20 bg-white/10 px-1.5 py-1 text-[9px] font-semibold text-white/90 backdrop-blur-sm sm:shrink-0 sm:gap-1.5 sm:px-3.5 sm:py-1.5 sm:text-xs">
                                            <svg class="h-2.5 w-2.5 shrink-0 text-dc-teal sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">{!! $chip['icon'] !!}</svg>
                                            <span class="truncate">{{ $chip['label'] }}</span>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Reserve strip: matches the card row's footprint so the flex-1 area above truly ends there,
                 letting the hero text center within genuinely available space instead of overlapping it --}}
            <div class="h-[240px] shrink-0 sm:h-[190px] lg:h-[200px]" aria-hidden="true"></div>

            {{-- Bottom bar — extra bottom clearance below lg so nothing sits behind the floating app nav --}}
            <div class="relative z-10 mx-auto w-full max-w-7xl px-4 pb-28 lg:pb-6 lg:px-8">
                <div class="grid grid-cols-3 items-center gap-2 border-t border-white/15 pt-5">
                    <div class="invisible sm:visible">
                        <p class="text-xs font-medium tracking-wide text-white/40">Your Teeth Our Passion</p>
                    </div>

                    <a href="#how-it-works" aria-label="Scroll for more" class="flex items-center justify-center gap-1.5 text-xs font-bold text-white transition hover:text-white/80">
                        <span class="hidden sm:inline">Scroll for More</span>
                        <svg class="h-4 w-4 motion-safe:animate-bounce sm:h-3.5 sm:w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"/></svg>
                    </a>

                    <div class="flex items-center justify-end gap-2 sm:gap-3">
                        <span class="hidden text-xs font-medium text-white sm:inline">Preview</span>
                        <span class="text-sm font-bold text-white"><span x-text="String(heroIndex + 1).padStart(2, '0')"></span><span class="text-white/70"> / <span x-text="String(totalSlides).padStart(2, '0')"></span></span></span>
                        <div class="hidden items-center gap-2 sm:flex">
                            <button type="button" @click="goToSlide(heroIndex - 1)" class="text-xs font-semibold text-white/80 transition hover:text-white">Prev</button>
                            <button type="button" @click="goToSlide(heroIndex + 1)" class="text-xs font-semibold text-white transition hover:text-white/80">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card row + service tags — wrapped in the same max-w-7xl/padding as the heading above, so the
             card's left edge lines up exactly with "Family-Friendly Dental Care" --}}
        <div class="absolute inset-x-0 bottom-44 z-10 sm:bottom-28 lg:bottom-24">
            <div class="mx-auto flex max-w-7xl items-end justify-between gap-4 px-4 sm:px-6 lg:px-8">
                <div class="min-w-0 flex-1 sm:flex-initial">
                    <div class="flex flex-col gap-2.5">
                    {{-- Swipeable card track: one full card per view on mobile (swipe or use the dots/
                         Prev-Next below — no more "cut in half" peek of the next card), both cards
                         visible side by side from sm up. Card layout (icon row / bold heading / image
                         with rating badge / description) follows the referenced card design. --}}
                    <div x-ref="cardTrack"
                         @scroll.debounce.150ms="cardIndex = $refs.cardTrack.children.length > 1 ? Math.round($refs.cardTrack.scrollLeft / ($refs.cardTrack.children[1].getBoundingClientRect().left - $refs.cardTrack.children[0].getBoundingClientRect().left)) : 0"
                         class="flex gap-3 overflow-x-auto scroll-smooth snap-x snap-mandatory [-ms-overflow-style:none] [scrollbar-width:none] sm:overflow-visible sm:snap-none [&::-webkit-scrollbar]:hidden">
                        @foreach ([
                            ['img' => 'dental-examination-closeup', 'alt' => 'Professional dental examination', 'caption' => 'Restore natural healthy confident dental growth.'],
                            ['img' => 'dentist-virtual-consultation', 'alt' => 'Online dental consultation', 'caption' => 'Book appointments and consult online with ease.'],
                        ] as $card)
                            <div class="w-full shrink-0 snap-start overflow-hidden rounded-2xl border border-white/20 bg-white/10 p-2.5 shadow-2xl backdrop-blur-xl sm:w-56 lg:w-64">
                                <div class="flex items-center justify-between">
                                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-white/15 text-white">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                                    </span>
                                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-white/15 text-white">
                                        <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M7 7h10v10"/></svg>
                                    </span>
                                </div>
                                <p class="mt-1.5 text-xs font-bold leading-snug text-white">{{ $card['alt'] }}</p>
                                <div class="relative mt-1.5 overflow-hidden rounded-xl">
                                    <img src="{{ asset('images/landing/' . $card['img'] . '.webp') }}" alt="{{ $card['alt'] }}"
                                         class="h-24 w-full object-cover object-top sm:h-20 lg:h-24" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                                    <div class="absolute bottom-1.5 left-1.5 flex items-center gap-1 rounded-full bg-black/50 px-2 py-1 backdrop-blur-sm">
                                        <svg class="h-3 w-3 shrink-0 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span class="text-[10px] font-bold text-white">{{ $ratingLabel }}</span>
                                    </div>
                                </div>
                                <p class="mt-1.5 text-[10px] leading-snug text-white/70">{{ $card['caption'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Swipe dots (mobile only — sm+ already shows both cards at once) --}}
                    <div class="flex gap-1.5 sm:hidden" role="tablist" aria-label="Featured highlights">
                        <template x-for="i in totalCards" :key="i">
                            <button type="button" role="tab" @click="scrollToCard(i - 1)" :aria-selected="(cardIndex === i - 1).toString()"
                                    :class="cardIndex === i - 1 ? 'w-4 bg-white' : 'w-1.5 bg-white/40'"
                                    class="h-1.5 rounded-full transition-all duration-300" :aria-label="'Show card ' + i"></button>
                        </template>
                    </div>
                    </div>
                </div>

                {{-- Service tags (single row, lg only) --}}
                <div class="hidden flex-nowrap items-center gap-2 pb-1 lg:flex" aria-hidden="true">
                    @foreach (['Dental Checkup', 'Teeth Cleaning', 'Tooth Filling', 'Gum Treatment', 'Retainers'] as $service)
                        <span class="whitespace-nowrap rounded-full border border-white/25 bg-white/10 px-3.5 py-1.5 text-[11px] font-semibold text-white/80 backdrop-blur-md transition hover:bg-white/20">{{ $service }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- HOW IT WORKS — 4-step process strip --}}
    <section id="how-it-works" class="relative mx-auto max-w-7xl overflow-hidden px-4 pb-20 pt-12 sm:px-6 lg:px-8">
        <div class="pointer-events-none absolute -left-24 top-0 h-72 w-72 rounded-full bg-dc-teal/10 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-dc-mint/40 blur-3xl" aria-hidden="true"></div>

        <div class="relative grid grid-cols-2 gap-4 sm:grid-cols-4 sm:gap-0">
            @foreach ([
                ['icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>', 'label' => 'Smile Assessment', 'desc' => 'Search and compare verified dental clinics near you'],
                ['icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>', 'label' => 'Care Planning', 'desc' => 'Enroll securely and discuss your treatment plan'],
                ['icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>', 'label' => 'Treatment Process', 'desc' => 'Book your appointment and receive professional care'],
                ['icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>', 'label' => 'Dental Maintenance', 'desc' => 'Stay on track with follow-ups and oral care tips'],
            ] as $i => $step)
                <div data-reveal style="--reveal-delay: {{ $i * 90 }}ms" class="relative flex flex-col items-center rounded-2xl px-3 py-6 text-center transition hover:bg-white/60 sm:px-6 {{ $i < 3 ? 'sm:border-r sm:border-dc-border/50' : '' }}">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-dc-teal to-dc-teal-deep text-white shadow-[0_8px_24px_-8px_rgba(20,184,166,0.4)]">
                        {!! $step['icon'] !!}
                    </div>
                    <p class="mt-4 text-sm font-bold text-dc-text">{{ $step['label'] }}</p>
                    <p class="mt-1 text-xs leading-relaxed text-dc-text-secondary">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ABOUT — Platform description + live stats --}}
    <section class="relative mx-auto max-w-7xl overflow-hidden px-4 pb-20 pt-16 sm:px-6 sm:pt-20 lg:px-8">
        <div class="pointer-events-none absolute right-0 top-1/4 h-80 w-80 rounded-full bg-dc-teal/10 blur-3xl" aria-hidden="true"></div>

        <div class="relative grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
            <div data-reveal>
                <span class="dc-badge bg-dc-mint/80 text-dc-teal-deep">About Us</span>
                <h2 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl">
                    Find trusted dental clinics with
                    <span class="text-dc-teal">verified profiles</span>,
                    real reviews and transparent pricing.
                </h2>
                <p class="mt-5 text-base leading-relaxed text-dc-text-secondary">
                    Dental Connect is a verified network of dental clinics across Tanzania.
                    Compare services, read patient reviews, check pricing and book
                    appointments, all from one platform.
                </p>
                <div class="mt-8 flex items-center gap-4">
                    <div class="flex -space-x-3">
                        <img src="{{ asset('images/landing/dental-team-portrait.webp') }}" alt="" class="h-11 w-11 rounded-full border-2 border-white object-cover shadow-sm" loading="lazy" aria-hidden="true">
                        <img src="{{ asset('images/landing/dentist-tablet-clinic.webp') }}" alt="" class="h-11 w-11 rounded-full border-2 border-white object-cover shadow-sm" loading="lazy" aria-hidden="true">
                        <img src="{{ asset('images/landing/dentist-consultation-coastal.webp') }}" alt="" class="h-11 w-11 rounded-full border-2 border-white object-cover shadow-sm" loading="lazy" aria-hidden="true">
                        <span class="flex h-11 w-11 items-center justify-center rounded-full border-2 border-white bg-dc-teal text-xs font-bold text-white shadow-sm">+</span>
                    </div>
                    <p class="text-sm text-dc-text-secondary">Trusted by patients and professionals across Tanzania</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div data-reveal style="--reveal-delay: 0ms" class="dc-card group p-6 transition hover:shadow-lg">
                    <p class="text-4xl font-extrabold text-dc-teal-dark">98<span class="text-xl">%</span></p>
                    <p class="mt-2 text-sm font-semibold text-dc-text">Satisfaction Rate</p>
                    <p class="mt-1 text-xs text-dc-text-secondary">From verified patient reviews</p>
                </div>
                <div data-reveal style="--reveal-delay: 90ms" class="dc-card group p-6 transition hover:shadow-lg">
                    <p class="text-4xl font-extrabold text-dc-teal-dark">2k<span class="text-xl">+</span></p>
                    <p class="mt-2 text-sm font-semibold text-dc-text">Smiles Transformed</p>
                    <p class="mt-1 text-xs text-dc-text-secondary">Patients helped so far</p>
                </div>
                <div data-reveal style="--reveal-delay: 180ms" class="dc-card group p-6 transition hover:shadow-lg">
                    <p class="text-4xl font-extrabold text-dc-teal-dark">{{ $ratingLabel }}<span class="text-xl">★</span></p>
                    <p class="mt-2 text-sm font-semibold text-dc-text">Customer Rating</p>
                    <p class="mt-1 text-xs text-dc-text-secondary">Average across all clinics</p>
                </div>
                <div data-reveal style="--reveal-delay: 270ms" class="dc-card group p-6 transition hover:shadow-lg">
                    <p class="text-4xl font-extrabold text-dc-teal-dark">{{ $clinicCount ?: '10' }}<span class="text-xl">+</span></p>
                    <p class="mt-2 text-sm font-semibold text-dc-text">Verified Clinics</p>
                    <p class="mt-1 text-xs text-dc-text-secondary">Across Tanzania</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURED TREATMENT — Image showcase --}}
    <section class="mx-auto max-w-7xl px-4 pb-20 pt-16 sm:px-6 sm:pt-20 lg:px-8">
        <div data-reveal class="text-center">
            <span class="dc-badge bg-dc-mint/80 text-dc-teal-deep">Featured Treatment</span>
            <h2 class="mt-4 text-3xl font-extrabold tracking-tight sm:text-4xl">Modern care for a healthier smile</h2>
            <p class="mx-auto mt-3 max-w-2xl text-dc-text-secondary">
                Hundreds of patients across Tanzania trust verified Dental Connect
                clinics for expert dental care and personalised treatments.
            </p>
        </div>

        <div data-reveal style="--reveal-delay: 120ms" class="mt-12 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="group relative col-span-1 overflow-hidden rounded-3xl lg:col-span-2">
                <img src="{{ asset('images/landing/dental-examination-closeup.webp') }}"
                     alt="Professional dental examination with modern equipment"
                     class="h-80 w-full object-cover transition duration-500 motion-safe:group-hover:scale-105 sm:h-96 lg:h-[28rem]"
                     loading="lazy" width="1536" height="1024">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/10 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-6 sm:p-8">
                    <span class="dc-badge bg-white/20 text-white backdrop-blur-md">Professional Care</span>
                    <h3 class="mt-3 text-xl font-bold text-white sm:text-2xl">Restore natural healthy<br>confident dental growth.</h3>
                    <div class="mt-3 flex items-center gap-2">
                        <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="text-sm font-semibold text-white/90">{{ $ratingLabel }} Rating</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-6">
                <div class="group relative flex-1 overflow-hidden rounded-3xl">
                    <img src="{{ asset('images/landing/dentist-virtual-consultation.webp') }}"
                         alt="Dentist conducting a virtual consultation with a patient"
                         class="h-52 w-full object-cover transition duration-500 motion-safe:group-hover:scale-105 lg:h-full"
                         loading="lazy" width="1672" height="941">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-5">
                        <span class="dc-badge bg-white/20 text-white backdrop-blur-md">Virtual Care</span>
                        <p class="mt-2 text-sm font-semibold text-white">Online consultations available</p>
                    </div>
                </div>
                <div class="group relative flex-1 overflow-hidden rounded-3xl">
                    <img src="{{ asset('images/landing/dentist-dental-software.webp') }}"
                         alt="Dentist reviewing patient records on digital dental software"
                         class="h-52 w-full object-cover transition duration-500 motion-safe:group-hover:scale-105 lg:h-full"
                         loading="lazy" width="1672" height="941">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-5">
                        <span class="dc-badge bg-white/20 text-white backdrop-blur-md">Digital Records</span>
                        <p class="mt-2 text-sm font-semibold text-white">Modern diagnostic technology</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- WHY DENTAL CONNECT — Patient-focused features + testimonial --}}
    <section class="relative mx-auto max-w-7xl overflow-hidden px-4 pb-20 pt-16 sm:px-6 sm:pt-20 lg:px-8">
        <div class="pointer-events-none absolute -left-20 top-1/3 h-72 w-72 rounded-full bg-dc-mint/50 blur-3xl" aria-hidden="true"></div>

        <div class="relative grid grid-cols-1 items-start gap-12 lg:grid-cols-2">
            <div data-reveal class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <div class="group relative overflow-hidden rounded-3xl">
                        <img src="{{ asset('images/landing/dentist-patient-oceanview.webp') }}"
                             alt="Dentist consulting with a patient in a bright clinic"
                             class="h-64 w-full object-cover transition duration-500 motion-safe:group-hover:scale-105 sm:h-72"
                             loading="lazy" width="1448" height="1086">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-3xl">
                    <img src="{{ asset('images/landing/woman-brushing-teeth.webp') }}"
                         alt="Woman maintaining her oral health"
                         class="h-48 w-full object-cover sm:h-56" loading="lazy" width="1536" height="1024">
                </div>
                <div class="dc-card flex flex-col items-center justify-center rounded-3xl bg-gradient-to-br from-dc-teal to-dc-teal-deep p-6 text-center text-white">
                    <p class="text-4xl font-extrabold">98%</p>
                    <p class="mt-1 text-sm font-semibold text-white/90">Patient<br>Satisfaction</p>
                    <div class="mt-3 flex gap-1" aria-hidden="true">
                        @for ($i = 0; $i < 5; $i++)
                            <div class="h-1.5 w-6 rounded-full {{ $i < 4 ? 'bg-white' : 'bg-white/40' }}"></div>
                        @endfor
                    </div>
                </div>
            </div>

            <div data-reveal style="--reveal-delay: 120ms">
                <span class="dc-badge bg-dc-mint/80 text-dc-teal-deep">Why Dental Connect?</span>
                <h2 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl">
                    Built for how dental care actually works.
                </h2>

                <div class="mt-8 space-y-5" x-data="{ activeFeature: 0 }" @mouseleave="activeFeature = 0">
                    @foreach ([
                        ['title' => 'Verified Clinic Network', 'desc' => 'Every clinic is reviewed and verified before joining. Browse clear profiles with credentials, services and real patient reviews.', 'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>'],
                        ['title' => 'Simple Appointment Booking', 'desc' => 'Request, confirm and reschedule appointments from your phone. No phone calls or waiting needed.', 'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>'],
                        ['title' => 'Real Patient Reviews', 'desc' => 'Read honest feedback from other patients to help you choose the right clinic for your needs.', 'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>'],
                        ['title' => 'Transparent Pricing', 'desc' => 'See service costs before you book, so you can plan your dental care with confidence.', 'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>'],
                    ] as $i => $feature)
                        <div @mouseenter="activeFeature = {{ $i }}"
                             class="flex -mx-3 gap-4 rounded-2xl px-3 py-2 transition-all duration-300"
                             :class="activeFeature === {{ $i }} ? '-translate-y-0.5 bg-white/70 shadow-[0_8px_24px_-8px_rgba(15,118,110,0.2)]' : ''">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-dc-teal-deep transition-all duration-300"
                                 :class="activeFeature === {{ $i }} ? 'scale-110 bg-gradient-to-br from-dc-teal to-dc-teal-deep text-white' : 'bg-dc-mint'">
                                {!! $feature['icon'] !!}
                            </div>
                            <div>
                                <h3 class="font-bold text-dc-text">{{ $feature['title'] }}</h3>
                                <p class="mt-1 text-sm leading-relaxed text-dc-text-secondary">{{ $feature['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Testimonial stack — swipe or use the dots/arrows to cycle; the active card
                     shifts to the back of the deck with a smooth transform/opacity transition
                     while the next one glides up to the front. --}}
                <div x-data="{
                        tCount: {{ count($testimonials) }},
                        tIndex: 0,
                        dragStartX: null,
                        dragDeltaX: 0,
                        tNext() { this.tIndex = (this.tIndex + 1) % this.tCount; },
                        tPrev() { this.tIndex = (this.tIndex - 1 + this.tCount) % this.tCount; },
                        tOffset(i) { return (i - this.tIndex + this.tCount) % this.tCount; },
                        onTouchStart(e) { this.dragStartX = e.touches[0].clientX; },
                        onTouchMove(e) { if (this.dragStartX !== null) this.dragDeltaX = e.touches[0].clientX - this.dragStartX; },
                        onTouchEnd() {
                            if (this.dragDeltaX < -40) this.tNext();
                            else if (this.dragDeltaX > 40) this.tPrev();
                            this.dragStartX = null; this.dragDeltaX = 0;
                        },
                     }"
                     class="mt-8">
                    <div class="relative min-h-[260px] sm:min-h-[210px]" @touchstart="onTouchStart($event)" @touchmove="onTouchMove($event)" @touchend="onTouchEnd()">
                        @foreach ($testimonials as $i => $t)
                            {{-- x-show fully removes cards past the visible depth (display:none), not just
                                 opacity — with several cards each using backdrop-blur, keeping them all
                                 painted at once (even at opacity 0) is expensive and made the swipe feel
                                 janky. Capping it to 2 blurred layers keeps the glide smooth. --}}
                            <div x-show="tOffset({{ $i }}) < 2"
                                 x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition-opacity duration-300" x-transition:leave-end="opacity-0"
                                 class="dc-card absolute inset-0 p-6 transition-[transform,opacity] duration-500 ease-out"
                                 :style="`transform: translateY(${tOffset({{ $i }}) * 14}px) scale(${1 - tOffset({{ $i }}) * 0.05}); opacity: ${1 - tOffset({{ $i }}) * 0.35}; z-index: ${100 - tOffset({{ $i }})}; pointer-events: ${tOffset({{ $i }}) === 0 ? 'auto' : 'none'};`">
                                <div class="flex items-start gap-1 text-dc-teal">
                                    <svg class="h-8 w-8 shrink-0 opacity-30" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                                </div>
                                <p class="mt-3 text-sm italic leading-relaxed text-dc-text-secondary">
                                    "{{ $t['quote'] }}"
                                </p>
                                <div class="mt-4 flex items-center gap-3">
                                    <img src="{{ asset('images/landing/' . $t['avatar'] . '.webp') }}" alt="" class="h-10 w-10 rounded-full object-cover" loading="lazy" aria-hidden="true">
                                    <div>
                                        <p class="text-sm font-bold text-dc-text">{{ $t['name'] }}</p>
                                        <p class="text-xs text-dc-text-secondary">{{ $t['meta'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 flex items-center justify-center gap-4">
                        <button type="button" @click="tPrev()" aria-label="Previous testimonial" class="flex h-8 w-8 items-center justify-center rounded-full border border-dc-border bg-white text-dc-text-secondary transition hover:bg-dc-mint-light hover:text-dc-teal-deep">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                        </button>
                        <div class="flex gap-1.5">
                            <template x-for="i in tCount" :key="i">
                                <button type="button" @click="tIndex = i - 1" :aria-label="'Show testimonial ' + i"
                                        :class="tIndex === i - 1 ? 'w-5 bg-dc-teal' : 'w-1.5 bg-dc-border'"
                                        class="h-1.5 rounded-full transition-all duration-300"></button>
                            </template>
                        </div>
                        <button type="button" @click="tNext()" aria-label="Next testimonial" class="flex h-8 w-8 items-center justify-center rounded-full border border-dc-border bg-white text-dc-text-secondary transition hover:bg-dc-mint-light hover:text-dc-teal-deep">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SERVICES — Feature cards --}}
    <section class="relative mx-auto max-w-7xl overflow-hidden px-4 pb-20 pt-16 sm:px-6 sm:pt-20 lg:px-8">
        <div class="pointer-events-none absolute right-0 bottom-0 h-72 w-72 rounded-full bg-dc-teal/10 blur-3xl" aria-hidden="true"></div>

        <div data-reveal class="relative text-center">
            <span class="dc-badge bg-dc-mint/80 text-dc-teal-deep">Our Services</span>
            <h2 class="mt-4 text-3xl font-extrabold tracking-tight sm:text-4xl">Complete Oral Care Services</h2>
        </div>
        <div class="relative mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['title' => 'General Checkup', 'desc' => 'Comprehensive dental examinations to assess your overall oral health and catch issues early.', 'icon' => '<svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>'],
                ['title' => 'Teeth Cleaning', 'desc' => 'Professional scaling and polishing to remove plaque and tartar for a brighter, healthier smile.', 'icon' => '<svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>'],
                ['title' => 'Tooth Filling', 'desc' => 'Durable, natural-looking restorations that repair cavities and protect your teeth from further decay.', 'icon' => '<svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/></svg>'],
                ['title' => 'Orthodontics', 'desc' => 'Braces, aligners and specialised treatments to straighten your teeth and correct your bite.', 'icon' => '<svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z"/></svg>'],
            ] as $i => $service)
                <div data-reveal style="--reveal-delay: {{ $i * 90 }}ms" class="group rounded-2xl border border-white/50 bg-white/30 p-6 shadow-[0_4px_24px_-8px_rgba(15,118,110,0.1)] backdrop-blur-md transition-all duration-300 hover:-translate-y-1.5 hover:border-white/70 hover:bg-white/55 hover:shadow-[0_16px_36px_-12px_rgba(15,118,110,0.3)]">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-dc-mint/80 text-dc-teal-deep transition group-hover:bg-gradient-to-br group-hover:from-dc-teal group-hover:to-dc-teal-deep group-hover:text-white">
                        {!! $service['icon'] !!}
                    </div>
                    <h3 class="mt-5 font-bold text-dc-text">{{ $service['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-dc-text-secondary">{{ $service['desc'] }}</p>
                    <a href="{{ route('clinics.index') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-dc-teal-deep transition hover:underline">
                        Find clinics
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    {{-- TEAM / CLINIC SHOWCASE — Full width image banner --}}
    <section class="mx-auto max-w-7xl px-4 pb-20 pt-16 sm:px-6 sm:pt-20 lg:px-8">
        <div data-reveal class="group relative overflow-hidden rounded-3xl">
            <img src="{{ asset('images/landing/dental-team-portrait.webp') }}"
                 alt="Professional dental team at a Dental Connect verified clinic"
                 class="h-64 w-full object-cover object-top transition duration-700 motion-safe:group-hover:scale-105 sm:h-80 lg:h-96"
                 loading="lazy" width="2095" height="751">
            <div class="absolute inset-0 bg-gradient-to-r from-dc-teal-dark/80 via-dc-teal-dark/40 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-6 sm:p-10">
                <span class="dc-badge bg-white/20 text-white backdrop-blur-md">Our Team</span>
                <h3 class="mt-3 max-w-md text-2xl font-extrabold text-white sm:text-3xl">
                    Expert dental professionals dedicated to your care
                </h3>
                <p class="mt-2 max-w-md text-sm text-white/80">
                    Our verified network of dentists, hygienists and specialists brings years of
                    combined expertise to every consultation and procedure.
                </p>
                <a href="{{ route('clinics.index') }}" class="mt-5 inline-flex items-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-bold text-dc-teal-deep shadow-sm transition hover:bg-dc-mint-light">
                    Meet our clinics
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ORAL CARE INSIGHTS — Blog-style cards --}}
    <section class="relative mx-auto max-w-7xl overflow-hidden px-4 pb-20 pt-16 sm:px-6 sm:pt-20 lg:px-8">
        <div class="pointer-events-none absolute left-1/3 top-0 h-64 w-64 rounded-full bg-dc-teal/10 blur-3xl" aria-hidden="true"></div>

        <div data-reveal class="relative flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <span class="dc-badge bg-dc-mint/80 text-dc-teal-deep">Our Insights</span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight sm:text-4xl">
                    Tips for a healthier smile
                </h2>
            </div>
            <a href="{{ route('clinics.index') }}" class="dc-btn-primary shrink-0 !px-6 !py-3">
                Read More
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"/></svg>
            </a>
        </div>

        <div class="relative mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ([
                ['img' => 'dental-examination-closeup', 'cat' => 'Dental Health', 'title' => 'Professional cleaning vs. regular brushing', 'desc' => 'Daily brushing removes plaque, but professional cleaning reaches what a toothbrush cannot. Most dentists recommend a visit every six months.', 'author' => 'Sarah Johnson', 'time' => '10 Min read', 'date' => 'March 12, 2026'],
                ['img' => 'dentist-patient-oceanview', 'cat' => 'Dental Health', 'title' => 'Signs it\'s time to visit your dentist', 'desc' => 'Sensitivity, bleeding gums or a lingering toothache are your cue to book a check-up rather than waiting it out.', 'author' => 'Nataly Birch', 'time' => '10 Min read', 'date' => 'March 12, 2026'],
                ['img' => 'dental-supplies-equipment', 'cat' => 'Dental Health', 'title' => 'Choosing the right clinic for your dental needs', 'desc' => 'Look for a verified profile, clear service pricing and a specialty match for what you actually need.', 'author' => 'Cody Fisher', 'time' => '6 Min read', 'date' => 'March 12, 2026'],
            ] as $i => $article)
                <article data-reveal style="--reveal-delay: {{ $i * 100 }}ms" class="dc-card group overflow-hidden transition hover:shadow-lg">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('images/landing/' . $article['img'] . '.webp') }}"
                             alt="{{ $article['title'] }}"
                             class="h-52 w-full object-cover transition duration-500 motion-safe:group-hover:scale-105"
                             loading="lazy">
                        <span class="absolute left-4 top-4 dc-badge bg-dc-teal/90 text-white">{{ $article['cat'] }}</span>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold leading-snug text-dc-text">{{ $article['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-dc-text-secondary line-clamp-2">{{ $article['desc'] }}</p>
                        <div class="mt-4 flex items-center justify-between border-t border-dc-border/50 pt-4">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-dc-mint text-xs font-bold text-dc-teal-deep">{{ substr($article['author'], 0, 1) }}</span>
                                <div>
                                    <p class="text-xs font-semibold text-dc-text">{{ $article['author'] }}</p>
                                    <p class="text-[10px] text-dc-text-secondary">{{ $article['time'] }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-dc-text-secondary">{{ $article['date'] }}</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- NEWSLETTER + CTA --}}
    <section class="mx-auto max-w-7xl px-4 pb-20 pt-16 sm:px-6 sm:pt-20 lg:px-8">
        <div data-reveal class="relative overflow-hidden rounded-3xl px-6 py-12 text-white sm:px-12 sm:py-16">
            <img src="{{ asset('images/landing/dentist-patient-oceanview.webp') }}" alt=""
                 class="absolute inset-0 h-full w-full object-cover" loading="lazy" aria-hidden="true">
            <div class="absolute inset-0 bg-gradient-to-br from-dc-teal-dark/95 via-dc-teal-deep/90 to-dc-teal-dark/80"></div>
            <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/5 blur-2xl" aria-hidden="true"></div>
            <div class="absolute -bottom-16 -left-16 h-48 w-48 rounded-full bg-white/5 blur-2xl" aria-hidden="true"></div>

            <div class="relative flex flex-col items-start justify-between gap-8 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-2xl font-extrabold sm:text-3xl">Your Smile Matters.<br>Connect With Us Today</h2>
                    <p class="mt-3 max-w-md text-sm text-white/80">
                        Join our newsletter to receive the latest oral health tips, special offers and clinic updates.
                    </p>
                </div>
                <form method="POST" action="{{ route('newsletter.store') }}" class="flex w-full max-w-sm flex-col gap-3 sm:w-auto sm:flex-row sm:gap-2">
                    @csrf
                    <div class="relative flex-1">
                        <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-dc-text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        <input type="email" name="email" required placeholder="Your Email Address"
                               class="w-full rounded-full border-0 bg-white/95 py-3 pl-10 pr-4 text-sm text-dc-text placeholder:text-dc-text-secondary focus:outline-none focus:ring-2 focus:ring-white">
                    </div>
                    <button type="submit" class="shrink-0 rounded-full bg-white px-6 py-3 text-sm font-bold text-dc-teal-deep shadow-sm transition hover:bg-dc-mint-light">Subscribe</button>
                </form>
            </div>
        </div>
        @if (session('status'))
            <p class="mt-3 text-center text-sm font-semibold text-dc-teal-deep">{{ session('status') }}</p>
        @endif
    </section>

</x-layouts.public>
