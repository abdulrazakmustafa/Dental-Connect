@php
    $nav = include resource_path('views/clinic/_nav.php');
@endphp
<x-layouts.dashboard title="My Patients" :nav="$nav">
    <form method="GET" class="mb-4">
        <input class="dc-input max-w-sm" type="text" name="q" value="{{ request('q') }}" placeholder="Search by name or patient number">
    </form>

    <div class="dc-card overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-dc-mint-light text-xs uppercase text-dc-text-secondary">
                <tr>
                    <th class="px-4 py-3">Patient #</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3">Dentist</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dc-border">
                @forelse ($patients as $patient)
                    <tr class="hover:bg-dc-mint-light/50">
                        <td class="px-4 py-3">
                            <a href="{{ route('clinic.patients.show', $patient) }}" class="font-medium text-dc-teal-dark">{{ $patient->patient_number }}</a>
                        </td>
                        <td class="px-4 py-3">{{ $patient->fullName() }}</td>
                        <td class="px-4 py-3">{{ $patient->phone ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $patient->assignedDentist?->full_name ?? '—' }}</td>
                        <td class="px-4 py-3"><span class="dc-badge bg-dc-mint text-dc-teal-dark">{{ ucfirst($patient->status) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-dc-text-secondary">No patients enrolled yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $patients->links() }}</div>
</x-layouts.dashboard>
