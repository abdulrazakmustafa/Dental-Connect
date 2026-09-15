<?php

namespace App\Modules\Clinic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Per-clinic service pricing (PRD §7.3 "services and specialty selection",
 * mockup P07 "Popular services"). Which services a clinic offers is chosen
 * during onboarding (ClinicOnboardingController); this controller only
 * manages the price shown to patients for each selected service.
 */
class ClinicServiceController extends Controller
{
    public function index(Request $request): View
    {
        $clinic = $request->user()->ownedClinics()->firstOrFail();
        $clinic->load('services');

        return view('clinic.services.index', ['clinic' => $clinic]);
    }

    public function update(Request $request): RedirectResponse
    {
        $clinic = $request->user()->ownedClinics()->firstOrFail();

        $data = $request->validate([
            'prices' => ['array'],
            'prices.*' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
        ]);

        $ownedServiceIds = $clinic->services()->pluck('services.id')->all();

        foreach ($data['prices'] ?? [] as $serviceId => $price) {
            if (! in_array((int) $serviceId, $ownedServiceIds, true)) {
                continue; // never write a price for a service this clinic hasn't selected
            }

            $clinic->services()->updateExistingPivot($serviceId, ['price' => $price !== '' ? $price : null]);
        }

        return redirect()->route('clinic.services.index')->with('status', 'Pricing updated.');
    }
}
