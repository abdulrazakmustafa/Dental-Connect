@php
    $hasHeroPhoto = file_exists(public_path('images/hero/desktop.jpg'));
@endphp
<x-layouts.public :title="'Dental Connect — Better Dentistry. Connected Care.'">
    {{-- Hero --}}
    <section class="mx-auto max-w-7xl px-4 pb-20 pt-14 sm:px-6 lg:px-8 lg:pt-20">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
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

                <form action="{{ route('clinics.index') }}" method="GET" class="mt-8 flex items-center gap-2 rounded-full border border-dc-border bg-white p-1.5 pl-5 shadow-sm sm:max-w-xl">
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
                <div class="relative aspect-[4/3] overflow-hidden rounded-[2rem] bg-gradient-to-br from-dc-mint-light to-dc-aqua shadow-[0_20px_60px_-20px_rgba(15,118,110,0.35)] sm:aspect-[5/4] lg:aspect-[4/5]">
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
                </div>

                <div class="dc-card absolute -bottom-6 left-4 w-56 p-4 sm:left-8">
                    <span class="dc-badge bg-dc-mint text-dc-teal-deep">Verified network</span>
                    <p class="mt-2 text-sm font-bold">Care you can trust</p>
                    <p class="mt-1 text-xs text-dc-text-secondary">Verified clinics, simple appointment requests and clinic-owned patient records.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Feature strip --}}
    <section id="how-it-works" class="mx-auto max-w-7xl px-4 pb-20 pt-10 sm:px-6 lg:px-8">
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
</x-layouts.public>
