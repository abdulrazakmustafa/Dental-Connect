@php
    $nav = include resource_path('views/clinic/_nav.php');
@endphp
@php
    $nextStatuses = [
        'requested' => ['confirmed' => 'Confirm', 'declined' => 'Decline'],
        'reschedule_proposed' => ['confirmed' => 'Confirm', 'declined' => 'Decline'],
        'confirmed' => ['completed' => 'Mark completed', 'no_show' => 'No-show', 'cancelled' => 'Cancel'],
    ];
@endphp
<x-layouts.dashboard title="Appointments" :nav="$nav">
    <div class="dc-card overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-dc-mint-light text-xs uppercase text-dc-text-secondary">
                <tr>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Patient</th>
                    <th class="px-4 py-3">Dentist</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dc-border">
                @forelse ($appointments as $appointment)
                    <tr>
                        <td class="px-4 py-3">{{ $appointment->preferred_date->format('j M Y') }}</td>
                        <td class="px-4 py-3">{{ $appointment->clinicPatient->fullName() }}</td>
                        <td class="px-4 py-3">{{ $appointment->dentist?->full_name ?? '—' }}</td>
                        <td class="px-4 py-3"><span class="dc-badge bg-dc-mint text-dc-teal-dark">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span></td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                @foreach ($nextStatuses[$appointment->status] ?? [] as $status => $label)
                                    <form method="POST" action="{{ route('clinic.appointments.status', $appointment) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $status }}">
                                        <button class="dc-btn-secondary !px-3 !py-1.5 !text-xs" type="submit">{{ $label }}</button>
                                    </form>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-dc-text-secondary">No appointments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $appointments->links() }}</div>
</x-layouts.dashboard>
