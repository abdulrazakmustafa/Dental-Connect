<?php

namespace App\Modules\Marketplace\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Marketplace\Models\Product;
use App\Modules\Marketplace\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * B2B marketplace browsing (PRD §44/§45). Reachable only through routes
 * guarded by the marketplace.access middleware — clinics, suppliers and
 * authorized admin, never patients or guests.
 */
class MarketplaceController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->select(['id', 'public_id', 'supplier_id', 'category_id', 'name', 'slug', 'brand', 'price', 'price_visible', 'is_available', 'status', 'moderation_status'])
            ->where('status', 'active')
            ->where('moderation_status', 'approved')
            ->with(['supplier:id,name,slug', 'category:id,name'])
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->integer('category')))
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', $request->string('q').'%'))
            ->orderByDesc('updated_at')
            ->paginate(12)
            ->withQueryString();

        return view('marketplace.home', [
            'products' => $products,
            'categories' => ProductCategory::whereNull('parent_id')->where('is_active', true)->with(['children' => fn ($q) => $q->where('is_active', true)])->orderBy('sort_order')->get(),
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->status === 'active' && $product->moderation_status === 'approved', 404);

        $product->load(['supplier:id,name,slug,region,city', 'category:id,name', 'images']);

        return view('marketplace.product', ['product' => $product]);
    }
}
