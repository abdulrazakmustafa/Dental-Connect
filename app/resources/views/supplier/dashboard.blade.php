@php
    $nav = [
        ['route' => 'supplier.dashboard', 'label' => 'Dashboard', 'icon' => '🏠'],
        ['route' => 'supplier.products.index', 'label' => 'Products', 'icon' => '📦'],
        ['route' => 'supplier.rfqs.index', 'label' => 'RFQs', 'icon' => '✉️'],
        ['route' => 'marketplace.home', 'label' => 'Marketplace', 'icon' => '🛒'],
        ['route' => 'supplier.onboarding', 'label' => 'Profile & Verification', 'icon' => '⚙️'],
    ];
@endphp
<x-layouts.dashboard title="Dashboard" :nav="$nav" :charts="true">
    @if (! $supplier->isVerified())
        <div class="mb-6 rounded-xl border border-dc-warning/30 bg-amber-50 p-4 text-sm text-dc-warning">
            Your company is <strong>{{ str_replace('_', ' ', $supplier->verification_status) }}</strong>.
            <a href="{{ route('supplier.onboarding') }}" class="font-semibold underline">Complete your profile</a> to get verified.
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">Active products</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $activeProducts }}</p>
        </div>
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">Open RFQs</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $openRfqs }}</p>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="dc-card p-5 lg:col-span-2">
            <h2 class="font-semibold">RFQs received (last 14 days)</h2>
            <div class="mt-4 h-64">
                <canvas id="rfqTrendChart"></canvas>
            </div>
        </div>
        <div class="dc-card p-5">
            <h2 class="font-semibold">Product moderation</h2>
            <div class="mt-4 h-64">
                <canvas id="productStatusChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const teal = '#14B8A6';

            new Chart(document.getElementById('rfqTrendChart'), {
                type: 'bar',
                data: {
                    labels: @json($rfqTrend['labels']),
                    datasets: [{
                        label: 'RFQs',
                        data: @json($rfqTrend['data']),
                        backgroundColor: 'rgba(20, 184, 166, 0.75)',
                        borderRadius: 4,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
                },
            });

            new Chart(document.getElementById('productStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($productStatusBreakdown['labels']),
                    datasets: [{
                        data: @json($productStatusBreakdown['data']),
                        backgroundColor: ['#D97706', teal, '#DC2626'],
                        borderColor: 'rgba(255,255,255,0.8)',
                        borderWidth: 2,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } },
                },
            });
        });
    </script>
</x-layouts.dashboard>
