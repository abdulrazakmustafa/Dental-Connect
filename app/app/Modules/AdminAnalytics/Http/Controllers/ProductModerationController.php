<?php

namespace App\Modules\AdminAnalytics\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Audit\Services\AuditLogger;
use App\Modules\Marketplace\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductModerationController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->where('moderation_status', 'pending')
            ->with('supplier:id,name')
            ->orderBy('created_at')
            ->paginate(20);

        return view('admin.products.moderation-queue', ['products' => $products]);
    }

    public function update(Request $request, Product $product, AuditLogger $audit): RedirectResponse
    {
        $this->authorize('moderate', $product);

        $data = $request->validate(['decision' => ['required', 'in:approve,unpublish']]);

        $before = ['moderation_status' => $product->moderation_status];
        $product->update(['moderation_status' => $data['decision'] === 'approve' ? 'approved' : 'unpublished']);

        $audit->log(
            action: "product.{$data['decision']}",
            entity: $product,
            actor: $request->user(),
            before: $before,
            after: ['moderation_status' => $product->moderation_status],
        );

        return back()->with('status', "Product {$product->name} updated.");
    }
}
