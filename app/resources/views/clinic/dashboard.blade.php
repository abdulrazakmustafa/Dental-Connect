@php
    $nav = include resource_path('views/clinic/_nav.php');
@endphp
<x-layouts.dashboard title="Dashboard" :nav="$nav">
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

    <div class="mt-8">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold">Recent activity</h2>
            <a href="{{ route('clinic.appointments.index') }}" class="text-sm font-semibold text-dc-teal-dark">View all &rarr;</a>
        </div>
        <div class="dc-card mt-3 overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-dc-mint-light text-xs uppercase text-dc-text-secondary">
                    <tr>
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Requested</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dc-border">
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
</x-layouts.dashboard>
