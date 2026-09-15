@php
    $nav = include resource_path('views/clinic/_nav.php');
@endphp
@php
    $selectedServiceIds = old('services', $clinic->services->pluck('id')->all());
    $selectedSpecialtyIds = old('specialties', $clinic->specialties->pluck('id')->all());
    $primaryLocation = $clinic->primaryLocation;
@endphp
<x-layouts.dashboard :title="$isSubmission ? 'Complete your profile' : 'Edit clinic profile'" :nav="$nav">
    <div class="mx-auto max-w-2xl">
        <div class="dc-card p-6">
            @if ($isSubmission)
                <h2 class="text-lg font-semibold">Submit {{ $clinic->name }} for verification</h2>
                <p class="mt-1 text-sm text-dc-text-secondary">This information is reviewed by the Dental Connect team before your clinic becomes publicly visible.</p>
            @else
                <h2 class="text-lg font-semibold">Edit {{ $clinic->name }}'s profile</h2>
                <p class="mt-1 text-sm text-dc-text-secondary">Your clinic is verified — these changes go live immediately and do not require re-verification.</p>
            @endif

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
                    <input class="dc-input" type="text" name="address_line" value="{{ old('address_line', $primaryLocation?->address_line) }}" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium">Region</label>
                        <input class="dc-input" type="text" name="region" value="{{ old('region', $primaryLocation?->region ?? 'Dar es Salaam') }}" required>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">City</label>
                        <input class="dc-input" type="text" name="city" value="{{ old('city', $primaryLocation?->city ?? 'Dar es Salaam') }}" required>
                    </div>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium">Services offered</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($services as $service)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="services[]" value="{{ $service->id }}" @checked(in_array($service->id, $selectedServiceIds)) class="rounded border-dc-border text-dc-teal">
                                {{ $service->name }}
                            </label>
                        @endforeach
                    </div>
                    @if (! $isSubmission)
                        <p class="mt-2 text-xs text-dc-text-secondary">Set prices for your selected services on the <a href="{{ route('clinic.services.index') }}" class="font-semibold text-dc-teal-deep">Services &amp; Pricing</a> page.</p>
                    @endif
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium">Specialties</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($specialties as $specialty)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="specialties[]" value="{{ $specialty->id }}" @checked(in_array($specialty->id, $selectedSpecialtyIds)) class="rounded border-dc-border text-dc-teal">
                                {{ $specialty->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <button type="submit" class="dc-btn-primary w-full">{{ $isSubmission ? 'Submit for verification' : 'Save changes' }}</button>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
