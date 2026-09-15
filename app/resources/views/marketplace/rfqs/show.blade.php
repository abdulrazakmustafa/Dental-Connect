@php
    $isSupplierSide = auth()->user()->hasAnyRole(['supplier_owner', 'supplier_admin', 'supplier_staff']);
    $nav = $isSupplierSide
        ? [
            ['route' => 'supplier.dashboard', 'label' => 'Dashboard', 'icon' => '🏠'],
            ['route' => 'supplier.rfqs.index', 'label' => 'RFQs', 'icon' => '✉️'],
        ]
        : [
            ['route' => 'clinic.dashboard', 'label' => 'Dashboard', 'icon' => '🏠'],
            ['route' => 'clinic.rfqs.index', 'label' => 'My RFQs', 'icon' => '✉️'],
        ];
@endphp
<x-layouts.dashboard title="Quotation Request" :nav="$nav">
    <div class="dc-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="font-semibold">{{ $rfq->clinic->name }} &rarr; {{ $rfq->supplier->name }}</p>
                <p class="text-xs text-dc-text-secondary">{{ $rfq->product?->name ?? 'General enquiry' }} @if($rfq->quantity) &middot; Qty {{ $rfq->quantity }} @endif</p>
            </div>
            <span class="dc-badge bg-dc-mint text-dc-teal-dark">{{ ucfirst($rfq->status) }}</span>
        </div>

        <div class="mt-6 space-y-4">
            @foreach ($rfq->messages as $message)
                <div class="rounded-xl border border-dc-border p-4">
                    <p class="text-xs font-semibold text-dc-text-secondary">{{ $message->sender->name }} &middot; {{ $message->created_at->format('j M Y, H:i') }}</p>
                    <p class="mt-1 text-sm">{{ $message->message }}</p>
                </div>
            @endforeach
        </div>

        @if ($isSupplierSide && $rfq->status === 'open')
            <form method="POST" action="{{ route('marketplace.rfqs.respond', $rfq) }}" class="mt-6 space-y-3 border-t border-dc-border pt-6">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-medium">Your response</label>
                    <textarea class="dc-input" name="message" rows="4" required></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" name="status" value="responded" class="dc-btn-primary">Send response</button>
                    <button type="submit" name="status" value="closed" class="dc-btn-secondary">Send &amp; close</button>
                </div>
            </form>
        @endif
    </div>
</x-layouts.dashboard>
