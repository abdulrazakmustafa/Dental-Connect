<x-layouts.patient-app title="Rate Your Experience" :back="route('patient.appointments.show', $appointment)" active="appointments">
    <div class="flex flex-col items-center text-center">
        <span class="dc-avatar-square h-16 w-16 text-2xl">{{ strtoupper(substr($appointment->clinic->name, 0, 1)) }}</span>
        <h1 class="mt-4 text-lg font-extrabold">{{ $appointment->clinic->name }}</h1>
        <p class="mt-0.5 text-sm text-dc-text-secondary">
            {{ $appointment->service?->name ?? 'Appointment' }} &middot; Completed {{ $appointment->completed_at?->format('j M Y') ?? $appointment->preferred_date->format('j M Y') }}
        </p>
    </div>

    <div class="dc-card mt-6 p-6" x-data="{ rating: 0, hover: 0 }">
        <form method="POST" action="{{ route('patient.reviews.store', $appointment) }}">
            @csrf
            <p class="text-center text-sm font-bold">How was your experience?</p>
            <div class="mt-4 flex justify-center gap-2">
                @for ($i = 1; $i <= 5; $i++)
                    <button type="button" @click="rating = {{ $i }}" @mouseenter="hover = {{ $i }}" @mouseleave="hover = 0" class="text-3xl leading-none" :class="(hover || rating) >= {{ $i }} ? 'text-amber-400' : 'text-dc-border'">★</button>
                @endfor
            </div>
            <input type="hidden" name="rating" x-bind:value="rating" required>
            <p class="mt-2 text-center text-xs text-dc-text-secondary">Tap a star to rate the clinic</p>

            <label class="mt-6 block text-sm font-bold">Share feedback</label>
            <textarea name="comment" rows="5" class="dc-input mt-2" placeholder="Tell us about your visit..."></textarea>

            <p class="mt-2 text-xs text-dc-text-secondary">Reviews are linked to completed appointments and may be moderated before publication.</p>

            <button type="submit" class="dc-btn-primary mt-6 w-full" :disabled="rating === 0">Submit Review</button>
        </form>
    </div>
</x-layouts.patient-app>
