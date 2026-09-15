<?php

namespace App\Modules\Supplier\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Supplier\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SupplierOnboardingController extends Controller
{
    public function create(Request $request): View
    {
        $supplier = $request->user()->ownedSuppliers()->firstOrFail();

        return view('supplier.onboarding', ['supplier' => $supplier]);
    }

    public function store(Request $request): RedirectResponse
    {
        $supplier = $request->user()->ownedSuppliers()->firstOrFail();

        $data = $request->validate([
            'description' => ['nullable', 'string', 'max:2000'],
            'region' => ['required', 'string', 'max:80'],
            'city' => ['required', 'string', 'max:80'],
        ]);

        DB::transaction(function () use ($supplier, $data) {
            $supplier->update([
                'description' => $data['description'] ?? null,
                'region' => $data['region'],
                'city' => $data['city'],
                'verification_status' => 'submitted',
            ]);

            $supplier->verificationSubmissions()->create([
                'verifiable_type' => Supplier::class,
                'verifiable_id' => $supplier->id,
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);
        });

        return redirect()->route('supplier.dashboard')->with('status', 'Your company profile was submitted for verification.');
    }
}
