@php
    $nav = include resource_path('views/clinic/_nav.php');
@endphp
@php
    $editing = $dentist->exists;
    $selectedSpecialtyIds = old('specialties', $dentist->specialties->pluck('id')->all() ?? []);
@endphp
<x-layouts.dashboard :title="$editing ? 'Edit Dentist' : 'Add Dentist'" :nav="$nav">
    <div class="mx-auto max-w-xl">
        <div class="dc-card p-6">
            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-dc-danger/30 bg-red-50 p-4 text-sm text-dc-danger">
                    <ul class="list-disc space-y-1 pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ $editing ? route('clinic.dentists.update', $dentist) : route('clinic.dentists.store') }}" class="space-y-4">
                @csrf
                @if ($editing) @method('PUT') @endif

                <div>
                    <label class="mb-1 block text-sm font-medium">Full name</label>
                    <input class="dc-input" type="text" name="full_name" value="{{ old('full_name', $dentist->full_name) }}" placeholder="Dr. Jane Doe" required>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">License number</label>
                    <input class="dc-input" type="text" name="license_number" value="{{ old('license_number', $dentist->license_number) }}">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Bio</label>
                    <textarea class="dc-input" name="bio" rows="3">{{ old('bio', $dentist->bio) }}</textarea>
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

                <div>
                    <label class="mb-1 block text-sm font-medium">Status</label>
                    <select class="dc-input" name="status">
                        <option value="active" @selected(old('status', $dentist->status) === 'active')>Active</option>
                        <option value="inactive" @selected(old('status', $dentist->status) === 'inactive')>Inactive</option>
                    </select>
                </div>

                <button type="submit" class="dc-btn-primary w-full">{{ $editing ? 'Save changes' : 'Add dentist' }}</button>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
