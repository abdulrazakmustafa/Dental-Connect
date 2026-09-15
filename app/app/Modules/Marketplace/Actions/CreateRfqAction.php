<?php

namespace App\Modules\Marketplace\Actions;

use App\Models\User;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Marketplace\Models\Product;
use App\Modules\Marketplace\Models\Rfq;
use Illuminate\Support\Facades\DB;

/**
 * Clinic submits a quotation request to a supplier (PRD §26 "Supplier RFQ").
 * The RFQ + its first message commit together, before any notification job
 * is queued.
 */
class CreateRfqAction
{
    public function execute(Clinic $clinic, User $requestedBy, Product $product, array $data): Rfq
    {
        return DB::transaction(function () use ($clinic, $requestedBy, $product, $data) {
            $rfq = Rfq::create([
                'clinic_id' => $clinic->id,
                'supplier_id' => $product->supplier_id,
                'product_id' => $product->id,
                'requested_by' => $requestedBy->id,
                'quantity' => $data['quantity'] ?? null,
                'message' => $data['message'],
                'status' => 'open',
            ]);

            $rfq->messages()->create([
                'sender_id' => $requestedBy->id,
                'message' => $data['message'],
                'created_at' => now(),
            ]);

            return $rfq;
        });
    }
}
