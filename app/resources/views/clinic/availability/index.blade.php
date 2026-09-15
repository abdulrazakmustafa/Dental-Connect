@php
    $nav = include resource_path('views/clinic/_nav.php');
@endphp
<x-layouts.dashboard title="Availability" :nav="$nav">
    @if (session('status'))
        <div class="mb-4 rounded-xl border border-dc-success/30 bg-dc-success-bg p-4 text-sm text-dc-success">{{ session('status') }}</div>
    @endif

    <h2 class="font-semibold">Working hours</h2>
    <form method="POST" action="{{ route('clinic.availability.update') }}" class="mt-3">
        @csrf
        @method('PUT')
        <div class="dc-card overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-dc-mint-light text-xs uppercase text-dc-text-secondary">
                    <tr>
                        <th class="px-4 py-3">Day</th>
                        <th class="px-4 py-3">Closed</th>
                        <th class="px-4 py-3">Opens</th>
                        <th class="px-4 py-3">Closes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dc-border">
                    @foreach ($days as $index => $day)
                        @php $hour = $hours->get($index); @endphp
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $day }}</td>
                            <td class="px-4 py-3">
                                <input type="checkbox" name="days[{{ $index }}][closed]" value="1" @checked($hour?->is_closed) class="rounded border-dc-border text-dc-teal">
                            </td>
                            <td class="px-4 py-3">
                                <input class="dc-input" type="time" name="days[{{ $index }}][opens_at]" value="{{ $hour?->opens_at ? \Illuminate\Support\Carbon::parse($hour->opens_at)->format('H:i') : '' }}">
                            </td>
                            <td class="px-4 py-3">
                                <input class="dc-input" type="time" name="days[{{ $index }}][closes_at]" value="{{ $hour?->closes_at ? \Illuminate\Support\Carbon::parse($hour->closes_at)->format('H:i') : '' }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button type="submit" class="dc-btn-primary mt-4">Save hours</button>
    </form>

    <h2 class="mt-8 font-semibold">Blackout dates</h2>
    <p class="text-sm text-dc-text-secondary">Days the whole clinic is closed (holidays, maintenance) — shown to patients as unavailable.</p>

    <form method="POST" action="{{ route('clinic.availability.blackout.store') }}" class="mt-3 flex flex-wrap items-end gap-3">
        @csrf
        <div>
            <label class="mb-1 block text-xs font-medium">Date</label>
            <input class="dc-input" type="date" name="date" min="{{ now()->toDateString() }}" required>
        </div>
        <div class="flex-1">
            <label class="mb-1 block text-xs font-medium">Reason (optional)</label>
            <input class="dc-input" type="text" name="reason" placeholder="e.g. Public holiday">
        </div>
        <button type="submit" class="dc-btn-secondary">Add</button>
    </form>

    <div class="mt-4 space-y-2">
        @forelse ($blackoutDates as $blackout)
            <div class="dc-card flex items-center justify-between p-4">
                <div>
                    <p class="text-sm font-bold">{{ $blackout->date->format('j M Y') }}</p>
                    @if ($blackout->reason)
                        <p class="text-xs text-dc-text-secondary">{{ $blackout->reason }}</p>
                    @endif
                </div>
                <form method="POST" action="{{ route('clinic.availability.blackout.destroy', $blackout) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm font-semibold text-dc-danger">Remove</button>
                </form>
            </div>
        @empty
            <p class="text-sm text-dc-text-secondary">No blackout dates set.</p>
        @endforelse
    </div>
</x-layouts.dashboard>
