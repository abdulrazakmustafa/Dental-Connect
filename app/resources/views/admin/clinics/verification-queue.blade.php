@php
    $nav = [
        ['route' => 'admin.dashboard', 'label' => 'Overview', 'icon' => '🏠'],
        ['route' => 'admin.clinics.verification.index', 'label' => 'Clinic Verification', 'icon' => '✅'],
        ['route' => 'admin.products.moderation.index', 'label' => 'Product Moderation', 'icon' => '🛒'],
    ];
@endphp
<x-layouts.dashboard title="Clinic Verification Queue" :nav="$nav" brand="Dental Connect Admin">
    <div class="space-y-4">
        @forelse ($clinics as $clinic)
            <div class="dc-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold">{{ $clinic->name }}</p>
                        <p class="text-xs text-dc-text-secondary">{{ $clinic->owner->name }} &middot; {{ $clinic->owner->email }}</p>
                    </div>
                    <span class="dc-badge bg-dc-mint text-dc-teal-dark">{{ str_replace('_', ' ', $clinic->verification_status) }}</span>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach (['approve' => 'Approve', 'request_changes' => 'Request changes', 'reject' => 'Reject'] as $decision => $label)
                        <form method="POST" action="{{ route('admin.clinics.verification.update', $clinic) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="decision" value="{{ $decision }}">
                            <button class="dc-btn-secondary !px-3 !py-1.5 !text-xs" type="submit">{{ $label }}</button>
                        </form>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="dc-card p-8 text-center text-sm text-dc-text-secondary">Nothing pending review.</div>
        @endforelse
    </div>
    <div class="mt-6">{{ $clinics->links() }}</div>
</x-layouts.dashboard>
