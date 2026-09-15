<?php

namespace App\Modules\Marketplace\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Marketplace\Actions\CreateRfqAction;
use App\Modules\Marketplace\Models\Product;
use App\Modules\Marketplace\Models\Rfq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RfqController extends Controller
{
    public function store(Request $request, Product $product, CreateRfqAction $action): RedirectResponse
    {
        $clinic = $request->user()->ownedClinics()->first()
            ?? $request->user()->clinicStaffMemberships()->with('clinic')->first()?->clinic;

        abort_if(! $clinic, 403, 'Only clinic accounts can request quotations.');

        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $rfq = $action->execute($clinic, $request->user(), $product, $data);

        return redirect()->route('marketplace.rfqs.show', $rfq)->with('status', 'Quotation request sent.');
    }

    public function clinicIndex(Request $request): View
    {
        $clinicIds = $request->user()->ownedClinics()->pluck('id')
            ->merge($request->user()->clinicStaffMemberships()->pluck('clinic_id'));

        $rfqs = Rfq::whereIn('clinic_id', $clinicIds)
            ->with(['supplier:id,name', 'product:id,name'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('marketplace.rfqs.clinic-index', ['rfqs' => $rfqs]);
    }

    public function supplierIndex(Request $request): View
    {
        $supplierIds = $request->user()->ownedSuppliers()->pluck('id')
            ->merge($request->user()->supplierStaffMemberships()->pluck('supplier_id'));

        $rfqs = Rfq::whereIn('supplier_id', $supplierIds)
            ->with(['clinic:id,name', 'product:id,name'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('marketplace.rfqs.supplier-index', ['rfqs' => $rfqs]);
    }

    public function show(Request $request, Rfq $rfq): View
    {
        $this->authorize('view', $rfq);

        $rfq->load(['clinic:id,name', 'supplier:id,name', 'product:id,name', 'messages.sender:id,name']);

        return view('marketplace.rfqs.show', ['rfq' => $rfq]);
    }

    public function respond(Request $request, Rfq $rfq): RedirectResponse
    {
        $this->authorize('respond', $rfq);

        $data = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'status' => ['required', 'in:responded,closed'],
        ]);

        $rfq->messages()->create([
            'sender_id' => $request->user()->id,
            'message' => $data['message'],
            'status_change' => $data['status'],
            'created_at' => now(),
        ]);

        $rfq->update(['status' => $data['status']]);

        return back()->with('status', 'Response sent.');
    }
}
