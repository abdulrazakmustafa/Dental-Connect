@php
    $initials = collect(explode(' ', $user->name))->map(fn ($p) => strtoupper(substr($p, 0, 1)))->take(2)->implode('');
@endphp
<x-layouts.patient-app title="My Profile" active="profile">
    <div class="flex flex-col items-center text-center">
        <span class="flex h-20 w-20 items-center justify-center rounded-full bg-dc-mint text-2xl font-bold text-dc-teal-deep ring-4 ring-dc-mint-light">{{ $initials }}</span>
        <h1 class="mt-4 text-xl font-extrabold">{{ $user->name }}</h1>
        <p class="mt-1 text-sm text-dc-text-secondary">
            {{ $user->phone }}{{ $user->phone && $user->email ? ' · ' : '' }}{{ $user->email }}
        </p>
        @if ($activeClinicPatient)
            <span class="dc-badge mt-3 bg-dc-mint text-dc-teal-deep">Active clinic: {{ $activeClinicPatient->clinic->name }}</span>
        @endif
    </div>

    <div class="mt-6 space-y-3">
        @php
            $items = [
                ['icon' => 'P', 'title' => 'Personal Information', 'desc' => 'Name, phone, email and account details'],
                ['icon' => 'C', 'title' => 'My Clinic Enrollment', 'desc' => 'View or enroll separately with another clinic', 'route' => 'clinics.index'],
                ['icon' => '▤', 'title' => 'Appointment History', 'desc' => 'Upcoming and previous appointments', 'route' => 'patient.appointments.index'],
            ];
        @endphp
        @foreach ($items as $item)
            <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}" class="dc-card flex items-center gap-3 p-4">
                <span class="dc-avatar-square h-10 w-10 shrink-0">{{ $item['icon'] }}</span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold">{{ $item['title'] }}</p>
                    <p class="mt-0.5 text-xs text-dc-text-secondary">{{ $item['desc'] }}</p>
                </div>
                <span class="text-dc-text-secondary">&rsaquo;</span>
            </a>
        @endforeach

        <div class="dc-card flex items-center gap-3 p-4">
            <span class="dc-avatar-square h-10 w-10 shrink-0">○</span>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold">Notification Preferences</p>
                <p class="mt-0.5 text-xs text-dc-text-secondary">Appointment and account alerts</p>
            </div>
            <span class="inline-flex h-6 w-11 items-center rounded-full bg-dc-teal p-0.5">
                <span class="h-5 w-5 rounded-full bg-white shadow"></span>
            </span>
        </div>

        <a href="#" class="dc-card flex items-center gap-3 p-4">
            <span class="dc-avatar-square h-10 w-10 shrink-0">◆</span>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold">Privacy &amp; Security</p>
                <p class="mt-0.5 text-xs text-dc-text-secondary">Password, sessions and consent</p>
            </div>
            <span class="text-dc-text-secondary">&rsaquo;</span>
        </a>

        <a href="{{ route('contact') }}" class="dc-card flex items-center gap-3 p-4">
            <span class="dc-avatar-square h-10 w-10 shrink-0">?</span>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold">Help &amp; Support</p>
                <p class="mt-0.5 text-xs text-dc-text-secondary">Support request or complaint</p>
            </div>
            <span class="text-dc-text-secondary">&rsaquo;</span>
        </a>
    </div>

    <form method="POST" action="{{ route('logout') }}" class="mt-6 text-center">
        @csrf
        <button type="submit" class="text-sm font-bold text-dc-danger">Log out</button>
    </form>
</x-layouts.patient-app>
