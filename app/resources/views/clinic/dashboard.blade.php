@php
    $nav = include resource_path('views/clinic/_nav.php');
@endphp
<x-layouts.dashboard title="Dashboard" :nav="$nav" :charts="true">
    @if (! $clinic->isVerified())
        <div class="mb-6 rounded-xl border border-dc-warning/30 bg-amber-50 p-4 text-sm text-dc-warning">
            Your clinic is <strong>{{ str_replace('_', ' ', $clinic->verification_status) }}</strong>.
            @if (in_array($clinic->verification_status, ['draft', 'changes_requested']))
                <a href="{{ route('clinic.onboarding') }}" class="font-semibold underline">Complete your profile</a> to get verified.
            @endif
        </div>
    @endif

    @if (session('status'))
        <div class="mb-6 rounded-xl border border-dc-success/30 bg-dc-success-bg p-4 text-sm text-dc-success">{{ session('status') }}</div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 lg:grid-cols-6">
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">Today's appointments</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $todaysAppointments }}</p>
        </div>
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">Pending requests</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $pendingRequests }}</p>
        </div>
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">Total patients</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $totalPatients }}</p>
        </div>
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">New patients (month)</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $newPatientsThisMonth }}</p>
        </div>
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">Completion rate (30d)</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $completionRate !== null ? $completionRate.'%' : '—' }}</p>
        </div>
        <div class="dc-card p-5">
            <p class="text-xs font-semibold uppercase text-dc-text-secondary">Marketplace enquiries</p>
            <p class="mt-2 text-3xl font-bold text-dc-teal-dark">{{ $marketplaceEnquiries }}</p>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="dc-card p-5 lg:col-span-2">
            <h2 class="font-semibold">Appointment requests — last 14 days</h2>
            <div class="mt-4 h-64">
                <canvas id="appointmentTrendChart"></canvas>
            </div>
        </div>
        <div class="dc-card p-5">
            <h2 class="font-semibold">Appointments by status</h2>
            <div class="mt-4 h-64">
                <canvas id="statusBreakdownChart"></canvas>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold">Recent activity</h2>
            <a href="{{ route('clinic.appointments.index') }}" class="text-sm font-semibold text-dc-teal-dark">View all &rarr;</a>
        </div>
        <div class="dc-card-dense mt-3 overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-white/40 text-xs uppercase text-dc-text-secondary">
                    <tr>
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Requested</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/50">
                    @forelse ($recentAppointments as $appointment)
                        <tr>
                            <td class="px-4 py-3">{{ $appointment->clinicPatient->fullName() }}</td>
                            <td class="px-4 py-3">{{ $appointment->preferred_date->format('j M Y') }}</td>
                            <td class="px-4 py-3"><span class="dc-badge bg-dc-mint text-dc-teal-dark">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span></td>
                            <td class="px-4 py-3 text-dc-text-secondary">{{ $appointment->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-dc-text-secondary">No activity yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const teal = '#14B8A6';
            const tealDeep = '#0F766E';

            new Chart(document.getElementById('appointmentTrendChart'), {
                type: 'line',
                data: {
                    labels: @json($appointmentTrend['labels']),
                    datasets: [{
                        label: 'Requests',
                        data: @json($appointmentTrend['data']),
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

            new Chart(document.getElementById('statusBreakdownChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($statusBreakdown['labels']),
                    datasets: [{
                        data: @json($statusBreakdown['data']),
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
