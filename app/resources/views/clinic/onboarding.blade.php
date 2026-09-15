@php
    $nav = [
        ['route' => 'clinic.dashboard', 'label' => 'Dashboard', 'icon' => '🏠'],
        ['route' => 'clinic.patients.index', 'label' => 'My Patients', 'icon' => '🧑‍⚕️'],
        ['route' => 'clinic.appointments.index', 'label' => 'Appointments', 'icon' => '📅'],
        ['route' => 'marketplace.home', 'label' => 'Marketplace', 'icon' => '🛒'],
    ];
@endphp
<x-layouts.dashboard title="Complete your profile" :nav="$nav">
    <div class="mx-auto max-w-2xl">
        <div class="dc-card p-6">
            <h2 class="text-lg font-semibold">Submit {{ $clinic->name }} for verification</h2>
            <p class="mt-1 text-sm text-dc-text-secondary">This information is reviewed by the Dental Connect team before your clinic becomes publicly visible.</p>

            @if ($errors->any())
                <div class="mt-4 rounded-xl border border-dc-danger/30 bg-red-50 p-4 text-sm text-dc-danger">
                    <ul class="list-disc space-y-1 pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('clinic.onboarding.store') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-medium">About the clinic</label>
                    <textarea class="dc-input" name="description" rows="4">{{ old('description', $clinic->description) }}</textarea>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Address</label>
                    <input class="dc-input" type="text" name="address_line" value="{{ old('address_line') }}" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium">Region</label>
                        <input class="dc-input" type="text" name="region" value="{{ old('region', 'Dar es Salaam') }}" required>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">City</label>
                        <input class="dc-input" type="text" name="city" value="{{ old('city', 'Dar es Salaam') }}" required>
                    </div>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium">Services offered</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($services as $service)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="services[]" value="{{ $service->id }}" class="rounded border-dc-border text-dc-teal">
                                {{ $service->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium">Specialties</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($specialties as $specialty)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="specialties[]" value="{{ $specialty->id }}" class="rounded border-dc-border text-dc-teal">
                                {{ $specialty->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <button type="submit" class="dc-btn-primary w-full">Submit for verification</button>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
