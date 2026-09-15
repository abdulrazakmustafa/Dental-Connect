<x-layouts.public :title="'Find a Clinic — Dental Connect'">
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold">Find a verified dental clinic</h1>

        <form method="GET" class="mt-6 flex flex-wrap gap-3">
            <select name="service" class="dc-input max-w-xs" onchange="this.form.submit()">
                <option value="">All services</option>
                @foreach ($services as $service)
                    <option value="{{ $service->id }}" @selected(request('service') == $service->id)>{{ $service->name }}</option>
                @endforeach
            </select>
            <select name="specialty" class="dc-input max-w-xs" onchange="this.form.submit()">
                <option value="">All specialties</option>
                @foreach ($specialties as $specialty)
                    <option value="{{ $specialty->id }}" @selected(request('specialty') == $specialty->id)>{{ $specialty->name }}</option>
                @endforeach
            </select>
        </form>

        <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($clinics as $clinic)
                <a href="{{ route('clinics.show', $clinic) }}" class="dc-card p-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-dc-mint text-lg font-bold text-dc-teal-dark">
                            {{ strtoupper(substr($clinic->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold">{{ $clinic->name }}</p>
                            <p class="text-xs text-dc-text-secondary">
                                {{ $clinic->primaryLocation?->city ?? 'Dar es Salaam' }}
                            </p>
                        </div>
                    </div>
                    <span class="dc-badge mt-4 bg-dc-mint text-dc-teal-dark">✓ Verified</span>
                </a>
            @empty
                <div class="dc-card col-span-full p-8 text-center text-sm text-dc-text-secondary">
                    No verified clinics match your filters yet.
                </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $clinics->links() }}</div>
    </section>
</x-layouts.public>
