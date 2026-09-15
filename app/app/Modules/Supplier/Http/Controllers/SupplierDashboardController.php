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
            'rfqTrend' => $this->rfqTrend($supplier->id),
            'productStatusBreakdown' => $this->productStatusBreakdown($supplier->id),
        ]);
    }

    private function rfqTrend(int $supplierId): array
    {
        $counts = \App\Modules\Marketplace\Models\Rfq::where('supplier_id', $supplierId)
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->selectRaw('DATE(created_at) as day, count(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $labels = [];
        $data = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('j M');
            $data[] = (int) ($counts[$date->toDateString()] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function productStatusBreakdown(int $supplierId): array
    {
        $counts = \App\Modules\Marketplace\Models\Product::where('supplier_id', $supplierId)
            ->selectRaw('moderation_status, count(*) as total')
            ->groupBy('moderation_status')
            ->pluck('total', 'moderation_status');

        $statuses = ['pending', 'approved', 'unpublished'];

        return [
            'labels' => array_map('ucfirst', $statuses),
            'data' => array_map(fn ($s) => (int) ($counts[$s] ?? 0), $statuses),
        ];
    }
}
