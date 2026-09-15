@php
    $nav = [
        ['route' => 'supplier.dashboard', 'label' => 'Dashboard', 'icon' => '🏠'],
        ['route' => 'marketplace.home', 'label' => 'Marketplace', 'icon' => '🛒'],
        ['route' => 'supplier.onboarding', 'label' => 'Profile & Verification', 'icon' => '⚙️'],
    ];
@endphp
<x-layouts.dashboard title="Complete your profile" :nav="$nav">
    <div class="mx-auto max-w-2xl">
        <div class="dc-card p-6">
            <h2 class="text-lg font-semibold">Submit {{ $supplier->name }} for verification</h2>

            @if ($errors->any())
                <div class="mt-4 rounded-xl border border-dc-danger/30 bg-red-50 p-4 text-sm text-dc-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('supplier.onboarding.store') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-medium">About the company</label>
                    <textarea class="dc-input" name="description" rows="4">{{ old('description', $supplier->description) }}</textarea>
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
                <button type="submit" class="dc-btn-primary w-full">Submit for verification</button>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
