@php
    $hasHeroPhoto = file_exists(public_path('images/hero/desktop.jpg'));
    $ratingLabel = $avgRating ? number_format($avgRating, 1) : '—';
@endphp
<x-layouts.public :title="'Dental Connect — Better Dentistry. Connected Care.'">
    {{-- Hero --}}
    <section class="mx-auto max-w-7xl px-4 pb-24 pt-14 sm:px-6 lg:px-8 lg:pt-20">
        <div class="grid grid-cols-1 items-center gap-14 lg:grid-cols-2">
            <div>
                <span class="dc-badge bg-dc-mint text-dc-teal-deep">Tanzania's connected dental-care platform</span>

                <h1 class="mt-6 text-4xl font-extrabold tracking-tight text-dc-text sm:text-5xl">
                    Better dentistry.<br>
                    <span class="text-dc-teal">Connected care.</span>
                </h1>

                <p class="mt-6 max-w-lg text-lg text-dc-text-secondary">
                    Dental Connect helps patients discover verified clinics and manage appointments, while
                    clinics and suppliers operate through secure professional workspaces built for the
                    dental ecosystem.
                </p>

                <form action="{{ route('clinics.index') }}" method="GET" class="mt-8 flex items-center gap-2 rounded-full border border-dc-border bg-white/80 p-1.5 pl-5 shadow-sm backdrop-blur sm:max-w-xl">
                    <input type="text" name="q" placeholder="Search clinics, services or location" class="min-w-0 flex-1 border-0 bg-transparent text-sm text-dc-text placeholder:text-dc-text-secondary focus:outline-none focus:ring-0">
                    <button type="submit" class="dc-btn-primary shrink-0 !px-6 !py-3">Find a Clinic</button>
                </form>

                <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <a href="{{ route('register', ['role' => 'patient']) }}" class="dc-card p-4 transition hover:shadow-md">
                        <span class="dc-avatar h-9 w-9 text-sm">P</span>
                        <p class="mt-3 text-sm font-bold">I'm a Patient</p>
                        <p class="mt-0.5 text-xs text-dc-text-secondary">Find a clinic and manage appointments</p>
                    </a>
                    <a href="{{ route('register', ['role' => 'clinic']) }}" class="dc-card p-4 transition hover:shadow-md">
                        <span class="dc-avatar h-9 w-9 text-sm">C</span>
                        <p class="mt-3 text-sm font-bold">I'm a Clinic</p>
                        <p class="mt-0.5 text-xs text-dc-text-secondary">Manage patients, dentists and care</p>
                    </a>
                    <a href="{{ route('register', ['role' => 'supplier']) }}" class="dc-card p-4 transition hover:shadow-md">
                        <span class="dc-avatar h-9 w-9 text-sm">S</span>
                        <p class="mt-3 text-sm font-bold">I'm a Supplier</p>
                        <p class="mt-0.5 text-xs text-dc-text-secondary">Products, RFQs and clinic reach</p>
                    </a>
                </div>

                <p class="mt-5 text-sm text-dc-text-secondary">
                    Authorized platform staff?
                    <a href="{{ route('admin.login') }}" class="font-semibold text-dc-teal-deep">Admin login &rarr;</a>
                </p>
            </div>

            {{-- Hero visual: your clinic photos once dropped in public/images/hero/
                 (see the README there); falls back to the brand illustration. --}}
            <div class="relative">
                <div class="relative aspect-[4/5] overflow-hidden rounded-[2rem] bg-gradient-to-br from-dc-mint-light to-dc-aqua shadow-[0_20px_60px_-20px_rgba(15,118,110,0.35)] sm:aspect-[5/4] lg:aspect-[4/5]">
                    @if ($hasHeroPhoto)
                        <picture>
                            <source media="(max-width: 639px)" srcset="{{ asset('images/hero/mobile.jpg') }}">
                            <source media="(max-width: 1023px)" srcset="{{ asset('images/hero/tablet.jpg') }}">
                            <img src="{{ asset('images/hero/desktop.jpg') }}" alt="A dentist caring for a smiling patient at a Dental Connect clinic" class="h-full w-full object-cover">
                        </picture>
                        <div class="absolute inset-0 bg-gradient-to-t from-dc-teal-dark/25 via-transparent to-transparent"></div>
                    @else
                        <div class="absolute right-10 top-10 h-24 w-24 rounded-full bg-dc-mint/60 blur-sm"></div>
                        <div class="absolute bottom-16 left-10 h-16 w-16 rounded-full bg-dc-aqua"></div>
                        <div class="flex h-full items-center justify-center">
                            <svg width="200" height="200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 3.2c-1.1 0-1.9.55-2.85.8-.5.13-1 .2-1.55.2C5.5 4.2 4 5.9 4 8.1c0 2.35.55 4.55 1.3 6.75.45 1.35.85 3.1 1.85 3.2.75.07.9-1.35 1.1-2.5.2-1.2.55-2.55 1.75-2.55s1.55 1.35 1.75 2.55c.2 1.15.35 2.57 1.1 2.5 1-.1 1.4-1.85 1.85-3.2.75-2.2 1.3-4.4 1.3-6.75 0-2.2-1.5-3.9-3.6-3.9-.55 0-1.05-.07-1.55-.2-.95-.25-1.75-.8-2.85-.8Z"
                                      stroke="#14B8A6" stroke-width="1.2" stroke-linejoin="round"/>
                                <path d="M9 10.5h6M12 7.5v6" stroke="#14B8A6" stroke-width="1.2" stroke-linecap="round"/>
                            </svg>
                        </div>
                    @endif

                    <div class="absolute left-4 top-4 flex items-center gap-2 rounded-full bg-white/85 py-1.5 pl-1.5 pr-4 shadow-sm backdrop-blur">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-dc-teal text-xs font-bold text-white">DC</span>
                        <span class="text-xs font-bold text-dc-text-secondary">Verified clinics only</span>
                    </div>

                    <div class="absolute right-4 top-4 flex flex-col items-end gap-2">
                        <span class="dc-badge bg-white/85 text-dc-teal-deep backdrop-blur">General Checkup</span>
                        <span class="dc-badge bg-white/85 text-dc-teal-deep backdrop-blur">Teeth Cleaning</span>
                        <span class="dc-badge bg-white/85 text-dc-teal-deep backdrop-blur">Orthodontics</span>
                    </div>
                </div>

                <div class="dc-card absolute -bottom-8 left-4 w-60 p-4 sm:left-8">
                    <span class="dc-badge bg-dc-mint text-dc-teal-deep">Verified network</span>
                    <p class="mt-2 text-sm font-bold">Care you can trust</p>
                    <p class="mt-1 text-xs text-dc-text-secondary">Verified clinics, simple appointment requests and clinic-owned patient records.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- How it works strip --}}
    <section id="how-it-works" class="mx-auto max-w-7xl px-4 pb-20 pt-4 sm:px-6 lg:px-8">
        <div class="dc-card grid grid-cols-2 gap-6 p-6 sm:grid-cols-4">
            @foreach ([
                ['n' => '01', 'label' => 'Find a verified clinic'],
                ['n' => '02', 'label' => 'Enroll securely'],
                ['n' => '03', 'label' => 'Request an appointment'],
                ['n' => '04', 'label' => 'Get confirmed care'],
            ] as $step)
                <div>
                    <p class="text-xs font-bold text-dc-teal">{{ $step['n'] }}</p>
                    <p class="mt-1 text-sm font-semibold">{{ $step['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- About / platform stats --}}
    <section class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
            <div>
                <span class="dc-badge bg-dc-mint text-dc-teal-deep">About Dental Connect</span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight sm:text-4xl">
                    One secure ecosystem for patients, clinics and suppliers.
                </h2>
                <p class="mt-4 text-dc-text-secondary">
                    We built Dental Connect around one rule: your data belongs to the clinic you chose it
                    with. Every clinic keeps its own private patient records, every supplier relationship
                    stays business-to-business, and every account is protected by role-based access from
                    day one.
                </p>
                <div class="mt-6 flex -space-x-3">
                    @foreach (['P', 'C', 'S', 'A'] as $letter)
                        <span class="dc-avatar h-10 w-10 border-2 border-white text-sm">{{ $letter }}</span>
                    @endforeach
                    <span class="ml-4 flex items-center text-sm text-dc-text-secondary">Patients · Clinics · Suppliers · Admins — one platform</span>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div class="dc-card col-span-3 p-6 sm:col-span-1">
                    <p class="text-3xl font-extrabold text-dc-teal-dark">{{ $verifiedClinics }}</p>
                    <p class="mt-1 text-xs font-semibold uppercase text-dc-text-secondary">Verified clinics</p>
                </div>
                <div class="dc-card col-span-3 p-6 sm:col-span-1">
                    <p class="text-3xl font-extrabold text-dc-teal-dark">{{ $registeredDentists }}</p>
                    <p class="mt-1 text-xs font-semibold uppercase text-dc-text-secondary">Registered dentists</p>
                </div>
                <div class="dc-card col-span-3 p-6 sm:col-span-1">
                    <p class="text-3xl font-extrabold text-dc-teal-dark">{{ $ratingLabel }}<span class="text-lg">★</span></p>
                    <p class="mt-1 text-xs font-semibold uppercase text-dc-text-secondary">Avg. patient rating</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Feature strip --}}
    <section class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="dc-card p-6">
                <span class="dc-avatar-square h-10 w-10">✓</span>
                <h3 class="mt-4 font-bold">Verified Clinics</h3>
                <p class="mt-1 text-sm text-dc-text-secondary">Clear profiles, services, locations and professional details.</p>
            </div>
            <div class="dc-card p-6">
                <span class="dc-avatar-square h-10 w-10">▤</span>
                <h3 class="mt-4 font-bold">Easy Appointments</h3>
                <p class="mt-1 text-sm text-dc-text-secondary">Request, confirm, reschedule and track visits from one patient app.</p>
            </div>
            <div class="dc-card p-6">
                <span class="dc-avatar-square h-10 w-10">P</span>
                <h3 class="mt-4 font-bold">Clinic-owned Patients</h3>
                <p class="mt-1 text-sm text-dc-text-secondary">Each clinic maintains its own private patient relationship and history.</p>
            </div>
            <div class="dc-card p-6">
                <span class="dc-avatar-square h-10 w-10">◆</span>
                <h3 class="mt-4 font-bold">Secure by Design</h3>
                <p class="mt-1 text-sm text-dc-text-secondary">Role-based access, protected records and privacy-aware workflows.</p>
            </div>
        </div>
    </section>

    {{-- Why Dental Connect — image + highlight card + illustrative quote --}}
    <section class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-2">
            <div class="relative">
                <div class="aspect-[4/3] overflow-hidden rounded-3xl bg-gradient-to-br from-dc-aqua to-dc-mint-light shadow-sm">
                    <div class="flex h-full items-center justify-center">
                        <svg width="120" height="120" viewBox="0 0 24 24" fill="none">
                            <path d="M12 3.2c-1.1 0-1.9.55-2.85.8-.5.13-1 .2-1.55.2C5.5 4.2 4 5.9 4 8.1c0 2.35.55 4.55 1.3 6.75.45 1.35.85 3.1 1.85 3.2.75.07.9-1.35 1.1-2.5.2-1.2.55-2.55 1.75-2.55s1.55 1.35 1.75 2.55c.2 1.15.35 2.57 1.1 2.5 1-.1 1.4-1.85 1.85-3.2.75-2.2 1.3-4.4 1.3-6.75 0-2.2-1.5-3.9-3.6-3.9-.55 0-1.05-.07-1.55-.2-.95-.25-1.75-.8-2.85-.8Z" stroke="#14B8A6" stroke-width="1.2" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                <div class="dc-card absolute -right-4 -top-4 w-44 p-4">
                    <p class="text-2xl font-extrabold text-dc-teal-dark">{{ $ratingLabel }}★</p>
                    <p class="mt-1 text-xs text-dc-text-secondary">Average rating from published patient reviews</p>
                </div>
            </div>

            <div>
                <span class="dc-badge bg-dc-mint text-dc-teal-deep">Why Dental Connect</span>
                <h2 class="mt-4 text-2xl font-extrabold sm:text-3xl">Built for how dental care actually works.</h2>
                <ul class="mt-5 space-y-3 text-sm text-dc-text-secondary">
                    <li class="flex gap-3"><span class="mt-0.5 text-dc-teal">✓</span> Each clinic owns and controls its own patient records — nothing is shared automatically.</li>
                    <li class="flex gap-3"><span class="mt-0.5 text-dc-teal">✓</span> Appointment requests, confirmations and reschedules are tracked with a full audit trail.</li>
                    <li class="flex gap-3"><span class="mt-0.5 text-dc-teal">✓</span> Clinics and suppliers trade through a private marketplace patients never see.</li>
                    <li class="flex gap-3"><span class="mt-0.5 text-dc-teal">✓</span> Every account is protected by role-based permissions from day one.</li>
                </ul>

                <div class="dc-card mt-6 p-5">
                    <p class="text-sm italic text-dc-text-secondary">"Enrolling a patient and booking their first visit takes minutes, and I never worry about seeing another clinic's records."</p>
                    <p class="mt-3 text-xs font-bold text-dc-text">— Illustrative clinic-staff workflow, Dar es Salaam</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Oral-care tips --}}
    <section class="mx-auto max-w-7xl px-4 pb-24 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-extrabold">Oral-care basics</h2>
        </div>
        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-3">
            @foreach ([
                ['title' => 'Cleaning vs. professional care', 'body' => 'Daily brushing removes plaque, but a professional cleaning reaches what a toothbrush can\'t — most dentists recommend a check-up every six months.'],
                ['title' => 'Signs it\'s time for a visit', 'body' => 'Sensitivity, bleeding gums or a lingering toothache are your cue to book a check-up rather than wait it out.'],
                ['title' => 'Choosing the right clinic', 'body' => 'Look for a verified profile, clear service pricing and a specialty match for what you actually need.'],
            ] as $tip)
                <div class="dc-card p-6">
                    <span class="dc-badge bg-dc-mint text-dc-teal-deep">Dental health</span>
                    <h3 class="mt-3 font-bold">{{ $tip['title'] }}</h3>
                    <p class="mt-2 text-sm text-dc-text-secondary">{{ $tip['body'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Newsletter + footer --}}
    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
        <div class="dc-hero flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-2xl font-extrabold">Your smile matters. Connect with us today.</h2>
                <p class="mt-2 text-sm text-white/90">Occasional updates on new clinics and platform features — no spam.</p>
            </div>
            <form method="POST" action="{{ route('newsletter.store') }}" class="flex w-full max-w-sm gap-2 sm:w-auto">
                @csrf
                <input type="email" name="email" required placeholder="Your email address" class="min-w-0 flex-1 rounded-full border-0 bg-white/95 px-4 py-2.5 text-sm text-dc-text placeholder:text-dc-text-secondary focus:outline-none focus:ring-2 focus:ring-white">
                <button type="submit" class="dc-btn-white shrink-0">Subscribe</button>
            </form>
        </div>
        @if (session('status'))
            <p class="mt-3 text-sm font-semibold text-dc-teal-deep">{{ session('status') }}</p>
        @endif
    </section>
</x-layouts.public>
