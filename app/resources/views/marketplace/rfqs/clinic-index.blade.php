@php
    $nav = [
        ['route' => 'clinic.dashboard', 'label' => 'Dashboard', 'icon' => '🏠'],
        ['route' => 'clinic.patients.index', 'label' => 'My Patients', 'icon' => '🧑‍⚕️'],
        ['route' => 'clinic.appointments.index', 'label' => 'Appointments', 'icon' => '📅'],
        ['route' => 'marketplace.home', 'label' => 'Marketplace', 'icon' => '🛒'],
        ['route' => 'clinic.rfqs.index', 'label' => 'My RFQs', 'icon' => '✉️'],
    ];
@endphp
<x-layouts.dashboard title="My Quotation Requests" :nav="$nav">
    <div class="dc-card overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-dc-mint-light text-xs uppercase text-dc-text-secondary">
                <tr>
                    <th class="px-4 py-3">Supplier</th>
                    <th class="px-4 py-3">Product</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Sent</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dc-border">
                @forelse ($rfqs as $rfq)
                    <tr>
                        <td class="px-4 py-3"><a href="{{ route('marketplace.rfqs.show', $rfq) }}" class="font-medium text-dc-teal-dark">{{ $rfq->supplier->name }}</a></td>
                        <td class="px-4 py-3">{{ $rfq->product?->name ?? '-' }}</td>
                        <td class="px-4 py-3"><span class="dc-badge bg-dc-mint text-dc-teal-dark">{{ ucfirst($rfq->status) }}</span></td>
                        <td class="px-4 py-3">{{ $rfq->created_at->format('j M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-dc-text-secondary">No quotation requests yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $rfqs->links() }}</div>
</x-layouts.dashboard>
