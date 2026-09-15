<x-layouts.patient-app title="Your Review" :back="route('patient.appointments.show', $appointment)" active="appointments">
    <div class="flex flex-col items-center text-center">
        <span class="dc-avatar-square h-16 w-16 text-2xl">{{ strtoupper(substr($appointment->clinic->name, 0, 1)) }}</span>
        <h1 class="mt-4 text-lg font-extrabold">{{ $appointment->clinic->name }}</h1>
        <div class="mt-3 flex gap-1 text-2xl text-amber-400">
            @for ($i = 1; $i <= 5; $i++)
                <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
            @endfor
        </div>
        @if ($review->comment)
            <p class="dc-card mt-6 p-4 text-left text-sm text-dc-text-secondary">{{ $review->comment }}</p>
        @endif
        <p class="mt-4 text-xs text-dc-text-secondary">Thanks for sharing your feedback!</p>
    </div>
</x-layouts.patient-app>
