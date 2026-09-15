<x-layouts.public :title="'Dental Connect — Better Dentistry. Brighter Lives.'">
    {{-- Hero --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-dc-aqua via-dc-mint-light to-transparent"></div>
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
            <div class="mx-auto max-w-3xl text-center">
                <span class="dc-badge bg-dc-mint text-dc-teal-dark">Dar es Salaam, Tanzania</span>
                <h1 class="mt-6 text-4xl font-extrabold tracking-tight text-dc-text sm:text-5xl lg:text-6xl">
                    Better Dentistry.<br>
                    <span class="text-dc-teal">Brighter Lives.</span>
                </h1>
                <p class="mx-auto mt-6 max-w-2xl text-lg text-dc-text-secondary">
                    Dental Connect connects patients with trusted dental clinics, helps clinics manage
                    their patients and operations, and connects clinics with verified dental suppliers
                    through one secure ecosystem.
                </p>
            </div>

            <div class="mx-auto mt-12 grid max-w-4xl grid-cols-1 gap-6 sm:grid-cols-3">
                <a href="{{ route('register', ['role' => 'patient']) }}" class="dc-glass group rounded-2xl p-6 text-center transition hover:shadow-md">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-dc-teal text-white">🦷</div>
                    <p class="mt-4 font-semibold">I'm a Patient</p>
                    <p class="mt-1 text-sm text-dc-text-secondary">Find a clinic and book care</p>
                </a>
                <a href="{{ route('register', ['role' => 'clinic']) }}" class="dc-glass group rounded-2xl p-6 text-center transition hover:shadow-md">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-dc-teal text-white">🏥</div>
                    <p class="mt-4 font-semibold">I'm a Clinic</p>
                    <p class="mt-1 text-sm text-dc-text-secondary">Manage patients & operations</p>
                </a>
                <a href="{{ route('register', ['role' => 'supplier']) }}" class="dc-glass group rounded-2xl p-6 text-center transition hover:shadow-md">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-dc-teal text-white">📦</div>
                    <p class="mt-4 font-semibold">I'm a Supplier</p>
                    <p class="mt-1 text-sm text-dc-text-secondary">Reach verified clinics</p>
                </a>
            </div>
        </div>
    </section>

    {{-- Value props --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
            <div class="dc-card p-6">
                <h3 class="font-semibold text-dc-teal-dark">For Patients</h3>
                <p class="mt-2 text-sm text-dc-text-secondary">
                    Discover verified clinics by location, service and specialty, enroll securely, and
                    request appointments — all from one simple, mobile-friendly account.
                </p>
            </div>
            <div class="dc-card p-6">
                <h3 class="font-semibold text-dc-teal-dark">For Clinics</h3>
                <p class="mt-2 text-sm text-dc-text-secondary">
                    Get verified, manage your own patients and appointments, and connect with trusted
                    dental suppliers through a private, permission-based marketplace.
                </p>
            </div>
            <div class="dc-card p-6">
                <h3 class="font-semibold text-dc-teal-dark">For Suppliers</h3>
                <p class="mt-2 text-sm text-dc-text-secondary">
                    Reach verified dental clinics across Tanzania, list your catalogue and respond to
                    quotation requests through a dedicated B2B workspace.
                </p>
            </div>
        </div>
    </section>
</x-layouts.public>
