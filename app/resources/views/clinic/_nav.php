<?php

// Plain PHP data file (not a Blade view) so `include` runs in the caller's
// own scope and `$nav` is actually available afterwards — a `@include` of a
// .blade.php partial renders in an isolated scope and does NOT export
// variables back to the parent, which silently broke this when it was one.

return [
    ['route' => 'clinic.dashboard', 'label' => 'Dashboard', 'icon' => '🏠'],
    ['route' => 'clinic.patients.index', 'label' => 'My Patients', 'icon' => '🧑‍⚕️'],
    ['route' => 'clinic.appointments.index', 'label' => 'Appointments', 'icon' => '📅'],
    ['route' => 'clinic.dentists.index', 'label' => 'Dentists', 'icon' => '🩺'],
    ['route' => 'clinic.services.index', 'label' => 'Services & Pricing', 'icon' => '💲'],
    ['route' => 'clinic.availability.index', 'label' => 'Availability', 'icon' => '🗓️'],
    ['route' => 'marketplace.home', 'label' => 'Marketplace', 'icon' => '🛒'],
    ['route' => 'clinic.rfqs.index', 'label' => 'RFQs', 'icon' => '✉️'],
    ['route' => 'clinic.staff.index', 'label' => 'Staff', 'icon' => '👥'],
    ['route' => 'clinic.onboarding', 'label' => 'Profile & Verification', 'icon' => '⚙️'],
];
