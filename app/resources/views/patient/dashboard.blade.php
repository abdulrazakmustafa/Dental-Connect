@php
    $initials = collect(explode(' ', auth()->user()->name))->map(fn ($p) => strtoupper(substr($p, 0, 1)))->take(2)->implode('');
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
@endphp
<x-layouts.patient-app title="Home" active="home">
    <x-slot:header>
        <header class="flex items-center justify-between px-5 pt-6">
            <div>
                <p class="text-sm text-dc-text-secondary">{{ $greeting }}</p>
                <p class="text-xl font-extrabold">{{ explode(' ', auth()->user()->name)[0] }} 👋</p>
            </div>
            <a href="{{ route('patient.profile.index') }}" class="flex h-10 w-10 items-center justify-center rounded-full bg-dc-mint text-sm font-bold text-dc-teal-deep ring-2 ring-white">{{ $initials }}</a>
        </header>
    </x-slot:header>

    @if (session('status'))
        <div class="mb-4 rounded-2xl border border-dc-success/30 bg-dc-success-bg p-3 text-sm text-dc-success">{{ session('status') }}</div>
    @endif

    @if ($activeClinicPatient)
        <div class="flex items-center justify-between">
            <span class="dc-badge bg-dc-mint text-dc-teal-deep">Active clinic</span>
            <span class="text-sm font-bold">{{ $activeClinicPatient->clinic->name }}</span>
            @if ($clinicPatients->count() > 1)
                <a href="{{ route('patient.clinics.switch') }}" class="text-sm font-bold text-dc-teal-deep">Switch</a>
            @else
                <span class="w-0"></span>
            @endif
        </div>
    @endif

    <div class="dc-hero mt-4">
        <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/10"></div>
        @if ($nextAppointment)
            <p class="text-xs font-semibold uppercase text-white/80">Your next appointment</p>
            <h2 class="mt-1 text-xl font-extrabold">{{ $nextAppointment->service?->name ?? 'General appointment' }}</h2>
            <p class="mt-1 text-sm text-white/90">{{ $nextAppointment->clinic->name }} @if($nextAppointment->dentist) &middot; {{ $nextAppointment->dentist->full_name }} @endif</p>
            <div class="mt-3 flex items-center gap-4 text-sm text-white/90">
                <span class="flex items-center gap-1.5">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><rect x="4" y="5" width="16" height="15" rx="2" stroke="white" stroke-width="1.6"/><path d="M4 10h16" stroke="white" stroke-width="1.6"/></svg>
                    {{ $nextAppointment->preferred_date->format('d M Y') }}
                </span>
                @if ($nextAppointment->preferred_time)
                    <span class="flex items-center gap-1.5">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8" stroke="white" stroke-width="1.6"/><path d="M12 8v4l3 2" stroke="white" stroke-width="1.6" stroke-linecap="round"/></svg>
                        {{ $nextAppointment->formattedTime() }}
                    </span>
                @endif
            </div>
            <a href="{{ route('patient.appointments.show', $nextAppointment) }}" class="dc-btn-white mt-4 inline-flex">View appointment</a>
        @else
            <p class="text-xs font-semibold uppercase text-white/80">No upcoming appointments</p>
            <h2 class="mt-1 text-xl font-extrabold">Book your next visit</h2>
            <p class="mt-1 text-sm text-white/90">Find a verified clinic and request an appointment in minutes.</p>
            <a href="{{ route('clinics.index') }}" class="dc-btn-white mt-4 inline-flex">Find a clinic</a>
        @endif
    </div>

    <h2 class="mt-6 text-sm font-bold text-dc-text-secondary">Quick actions</h2>
    <div class="mt-3 grid grid-cols-4 gap-3">
        @php
            $quickActions = [
                ['icon' => '🔍', 'label' => 'Find Clinic', 'route' => 'clinics.index'],
                ['icon' => '▤', 'label' => 'Appointments', 'route' => 'patient.appointments.index'],
                ['icon' => '○', 'label' => 'Messages', 'route' => 'patient.notifications.index'],
                ['icon' => '?', 'label' => 'Support', 'route' => 'contact'],
            ];
        @endphp
        @foreach ($quickActions as $action)
            <a href="{{ route($action['route']) }}" class="dc-card flex flex-col items-center gap-2 p-3 text-center">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-dc-mint-light text-lg">{{ $action['icon'] }}</span>
                <span class="text-[11px] font-semibold leading-tight">{{ $action['label'] }}</span>
            </a>
        @endforeach
    </div>

    <h2 class="mt-6 text-sm font-bold text-dc-text-secondary">Recent activity</h2>
    <div class="mt-3 space-y-2">
        @forelse ($recentActivity as $activity)
            <div class="dc-card flex items-center gap-3 p-4">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-dc-mint-light text-dc-teal-deep">
                    @if (str_starts_with($activity->type, 'appointment.confirmed'))
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    @else
                        <span class="h-2 w-2 rounded-full bg-dc-teal"></span>
                    @endif
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold">{{ $activity->title }}</p>
                    <p class="mt-0.5 truncate text-xs text-dc-text-secondary">{{ $activity->body }}</p>
                </div>
                <span class="shrink-0 text-xs text-dc-text-secondary">{{ $activity->created_at->diffForHumans(null, true) }}</span>
            </div>
        @empty
            <div class="dc-card p-4 text-center text-sm text-dc-text-secondary">No activity yet.</div>
        @endforelse
    </div>
</x-layouts.patient-app>
