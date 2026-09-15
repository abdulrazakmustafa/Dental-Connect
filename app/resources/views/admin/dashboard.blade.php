@php
    $nav = [
        ['route' => 'admin.dashboard', 'label' => 'Overview', 'icon' => '🏠'],
        ['route' => 'admin.clinics.verification.index', 'label' => 'Clinic Verification', 'icon' => '✅'],
        ['route' => 'admin.products.moderation.index', 'label' => 'Product Moderation', 'icon' => '🛒'],
    ];
@endphp
<x-layouts.dashboard title="Platform Overview" :nav="$nav" brand="Dental Connect Admin" :charts="true">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">Patients</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $totalPatients }}</p>
        </div>
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">Clinics</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $totalClinics }}</p>
            <p class="mt-1 text-xs text-dc-warning">{{ $clinicsPendingVerification }} pending verification</p>
        </div>
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">Suppliers</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $totalSuppliers }}</p>
            <p class="mt-1 text-xs text-dc-warning">{{ $suppliersPendingVerification }} pending verification</p>
        </div>
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">Products pending moderation</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $productsPendingModeration }}</p>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="dc-card p-5 lg:col-span-2">
            <h2 class="font-semibold">Platform signups — last 14 days</h2>
            <div class="mt-4 h-64">
                <canvas id="signupTrendChart"></canvas>
            </div>
        </div>
        <div class="dc-card p-5">
            <h2 class="font-semibold">Appointments by status</h2>
            <div class="mt-4 h-64">
                <canvas id="appointmentStatusChart"></canvas>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <a href="{{ route('admin.clinics.verification.index') }}" class="dc-card flex flex-col justify-center p-5 text-center">
            <p class="text-sm font-semibold text-dc-teal-dark">Review clinic verification queue &rarr;</p>
        </a>
        <a href="{{ route('admin.products.moderation.index') }}" class="dc-card flex flex-col justify-center p-5 text-center">
            <p class="text-sm font-semibold text-dc-teal-dark">Review product moderation queue &rarr;</p>
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const teal = '#14B8A6';

            new Chart(document.getElementById('signupTrendChart'), {
                type: 'line',
                data: {
                    labels: @json($signupTrend['labels']),
                    datasets: [{
                        label: 'New accounts',
                        data: @json($signupTrend['data']),
                        borderColor: teal,
                        backgroundColor: 'rgba(20, 184, 166, 0.12)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 2,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
                },
            });

            new Chart(document.getElementById('appointmentStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($appointmentStatusBreakdown['labels']),
                    datasets: [{
                        data: @json($appointmentStatusBreakdown['data']),
                        backgroundColor: ['#D97706', teal, '#2563EB', '#9CA3AF', '#DC2626', '#DC2626'],
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
