@php
    $nav = [
        ['route' => 'supplier.dashboard', 'label' => 'Dashboard', 'icon' => '🏠'],
        ['route' => 'supplier.products.index', 'label' => 'Products', 'icon' => '📦'],
        ['route' => 'supplier.rfqs.index', 'label' => 'RFQs', 'icon' => '✉️'],
        ['route' => 'marketplace.home', 'label' => 'Marketplace', 'icon' => '🛒'],
        ['route' => 'supplier.onboarding', 'label' => 'Profile & Verification', 'icon' => '⚙️'],
    ];
@endphp
<x-layouts.dashboard title="My Products" :nav="$nav">
    <div class="mb-4 flex justify-end">
        <a href="{{ route('supplier.products.create') }}" class="dc-btn-primary">Add product</a>
    </div>

    <div class="dc-card overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-dc-mint-light text-xs uppercase text-dc-text-secondary">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Moderation</th>
                    <th class="px-4 py-3">Available</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dc-border">
                @forelse ($products as $product)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                        <td class="px-4 py-3">{{ $product->category?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $product->price ? number_format($product->price, 0).' TZS' : '-' }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'dc-badge' => true,
                                'bg-dc-mint text-dc-teal-dark' => $product->moderation_status === 'approved',
                                'bg-amber-100 text-dc-warning' => $product->moderation_status === 'pending',
                                'bg-red-100 text-dc-danger' => $product->moderation_status === 'unpublished',
                            ])>{{ ucfirst($product->moderation_status) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('supplier.products.availability', $product) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="dc-badge {{ $product->is_available ? 'bg-dc-mint text-dc-teal-dark' : 'bg-gray-100 text-dc-text-secondary' }}">
                                    {{ $product->is_available ? 'Available' : 'Unavailable' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('supplier.products.edit', $product) }}" class="font-semibold text-dc-teal-dark">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-dc-text-secondary">No products yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
</x-layouts.dashboard>
