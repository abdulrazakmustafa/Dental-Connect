<?php

namespace App\Modules\Supplier\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Marketplace\Models\Product;
use App\Modules\Marketplace\Models\ProductCategory;
use App\Modules\Supplier\Http\Requests\ProductRequest;
use App\Modules\Supplier\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Supplier-owned product catalogue (PRD §7.5/§43). Every query is scoped to
 * the caller's own supplier(s); ProductPolicy re-checks ownership on write.
 */
class SupplierProductController extends Controller
{
    public function index(Request $request): View
    {
        $supplier = $this->currentSupplier($request);

        $products = $supplier->products()
            ->with('category:id,name')
            ->orderByDesc('updated_at')
            ->paginate(15);

        return view('supplier.products.index', ['supplier' => $supplier, 'products' => $products]);
    }

    public function create(Request $request): View
    {
        $supplier = $this->currentSupplier($request);

        return view('supplier.products.form', [
            'supplier' => $supplier,
            'product' => new Product,
            'categories' => ProductCategory::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $supplier = $this->currentSupplier($request);

        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']).'-'.Str::lower(Str::random(5));
        $data['supplier_id'] = $supplier->id;
        $data['status'] = 'active';
        $data['moderation_status'] = 'pending';

        Product::create($data);

        return redirect()->route('supplier.products.index')->with('status', 'Product added and pending moderation.');
    }

    public function edit(Request $request, Product $product): View
    {
        $this->authorize('update', $product);

        return view('supplier.products.form', [
            'supplier' => $product->supplier,
            'product' => $product,
            'categories' => ProductCategory::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $product->update($request->validated());

        return redirect()->route('supplier.products.index')->with('status', 'Product updated.');
    }

    public function toggleAvailability(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $product->update(['is_available' => ! $product->is_available]);

        return back()->with('status', $product->is_available ? 'Product marked available.' : 'Product marked unavailable.');
    }

    private function currentSupplier(Request $request): Supplier
    {
        $supplier = $request->user()->ownedSuppliers()->first()
            ?? $request->user()->supplierStaffMemberships()->with('supplier')->first()?->supplier;

        abort_if(! $supplier, 404, 'No supplier company is associated with this account yet.');

        return $supplier;
    }
}
