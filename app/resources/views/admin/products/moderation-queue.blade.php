@php
    $nav = [
        ['route' => 'admin.dashboard', 'label' => 'Overview', 'icon' => '🏠'],
        ['route' => 'admin.clinics.verification.index', 'label' => 'Clinic Verification', 'icon' => '✅'],
        ['route' => 'admin.products.moderation.index', 'label' => 'Product Moderation', 'icon' => '🛒'],
    ];
@endphp
<x-layouts.dashboard title="Product Moderation Queue" :nav="$nav" brand="Dental Connect Admin">
    <div class="space-y-4">
        @forelse ($products as $product)
            <div class="dc-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold">{{ $product->name }}</p>
                        <p class="text-xs text-dc-text-secondary">{{ $product->supplier->name }}</p>
                    </div>
                    <span class="dc-badge bg-amber-100 text-dc-warning">Pending</span>
                </div>
                @if ($product->description)
                    <p class="mt-3 text-sm text-dc-text-secondary">{{ $product->description }}</p>
                @endif
                <div class="mt-4 flex gap-2">
                    <form method="POST" action="{{ route('admin.products.moderation.update', $product) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="decision" value="approve">
                        <button class="dc-btn-secondary !px-3 !py-1.5 !text-xs" type="submit">Approve</button>
                    </form>
                    <form method="POST" action="{{ route('admin.products.moderation.update', $product) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="decision" value="unpublish">
                        <button class="dc-btn-secondary !px-3 !py-1.5 !text-xs" type="submit">Unpublish</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="dc-card p-8 text-center text-sm text-dc-text-secondary">Nothing pending review.</div>
        @endforelse
    </div>
    <div class="mt-6">{{ $products->links() }}</div>
</x-layouts.dashboard>
