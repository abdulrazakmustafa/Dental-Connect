<?php

namespace App\Modules\Supplier\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $supplier = $request->user()->ownedSuppliers()->first()
            ?? $request->user()->supplierStaffMemberships()->with('supplier')->first()?->supplier;

        abort_if(! $supplier, 404, 'No supplier company is associated with this account yet.');

        return view('supplier.dashboard', [
            'supplier' => $supplier,
            'activeProducts' => $supplier->products()->where('status', 'active')->count(),
            'openRfqs' => $supplier->rfqs()->where('status', 'open')->count(),
        ]);
    }
}
