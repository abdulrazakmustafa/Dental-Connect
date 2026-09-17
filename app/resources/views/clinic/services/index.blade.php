@php
    $nav = include resource_path('views/clinic/_nav.php');
@endphp
<x-layouts.dashboard title="Services & Pricing" :nav="$nav">
    @if (session('status'))
        <div class="mb-4 rounded-xl border border-dc-success/30 bg-dc-success-bg p-4 text-sm text-dc-success">{{ session('status') }}</div>
    @endif

    @if ($clinic->services->isEmpty())
        <div class="dc-card p-6 text-sm text-dc-text-secondary">
            You haven't selected any services yet. Choose which services you offer on the
            <a href="{{ route('clinic.onboarding') }}" class="font-semibold text-dc-teal-deep">Profile &amp; Verification</a> page, then set prices here.
        </div>
    @else
        <form method="POST" action="{{ route('clinic.services.update') }}">
            @csrf
            @method('PUT')
            <div class="dc-card overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-dc-mint-light text-xs uppercase text-dc-text-secondary">
                        <tr>
                            <th class="px-4 py-3">Service</th>
                            <th class="px-4 py-3">Price (TZS)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dc-border">
                        @foreach ($clinic->services as $service)
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $service->name }}</td>
                                <td class="px-4 py-3">
                                    <input class="dc-input max-w-xs" type="number" step="0.01" min="0" name="prices[{{ $service->id }}]" value="{{ $service->pivot->price }}" placeholder="Not set (shown as 'price on request')">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="submit" class="dc-btn-primary mt-4">Save pricing</button>
        </form>
    @endif
</x-layouts.dashboard>
