<?php

namespace App\Modules\AdminAnalytics\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\AdminAnalytics\Actions\DecideClinicVerificationAction;
use App\Modules\Clinic\Models\Clinic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClinicVerificationController extends Controller
{
    public function index(): View
    {
        $clinics = Clinic::query()
            ->whereIn('verification_status', ['submitted', 'under_review', 'changes_requested'])
            ->with('owner:id,name,email')
            ->orderBy('created_at')
            ->paginate(20);

        return view('admin.clinics.verification-queue', ['clinics' => $clinics]);
    }

    public function update(Request $request, Clinic $clinic, DecideClinicVerificationAction $action): RedirectResponse
    {
        $this->authorize('verify', $clinic);

        $data = $request->validate([
            'decision' => ['required', 'in:approve,reject,request_changes,suspend'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $action->execute($clinic, $request->user(), $data['decision'], $data['note'] ?? null);

        return back()->with('status', "Clinic {$clinic->name} updated.");
    }
}
