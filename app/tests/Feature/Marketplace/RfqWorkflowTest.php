<?php

namespace Tests\Feature\Marketplace;

use App\Models\User;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Marketplace\Models\Product;
use App\Modules\Marketplace\Models\Rfq;
use App\Modules\Supplier\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** PRD §26 "Supplier RFQ" golden path + tenant isolation on RFQ threads. */
class RfqWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    public function test_clinic_can_request_a_quotation_and_supplier_can_respond(): void
    {
        $clinicOwner = User::factory()->create();
        $clinicOwner->assignRole('clinic_owner');
        $clinic = Clinic::factory()->create(['owner_user_id' => $clinicOwner->id]);

        $supplierOwner = User::factory()->create();
        $supplierOwner->assignRole('supplier_owner');
        $supplier = Supplier::factory()->create(['owner_user_id' => $supplierOwner->id]);
        $product = Product::factory()->create(['supplier_id' => $supplier->id, 'moderation_status' => 'approved']);

        $this->actingAs($clinicOwner)->post(route('marketplace.rfqs.store', $product), [
            'quantity' => 10,
            'message' => 'Please quote for 10 boxes with delivery to Dar es Salaam.',
        ])->assertRedirect();

        $rfq = Rfq::where('clinic_id', $clinic->id)->where('supplier_id', $supplier->id)->firstOrFail();
        $this->assertSame('open', $rfq->status);

        $this->actingAs($supplierOwner)
            ->post(route('marketplace.rfqs.respond', $rfq), ['message' => 'We can do 9,500 TZS per box.', 'status' => 'responded'])
            ->assertRedirect();

        $this->assertSame('responded', $rfq->fresh()->status);
        $this->assertSame(2, $rfq->messages()->count());
    }

    public function test_another_clinic_cannot_view_someone_elses_rfq_thread(): void
    {
        $supplier = Supplier::factory()->create();
        $product = Product::factory()->create(['supplier_id' => $supplier->id]);

        $clinicA = User::factory()->create();
        $clinicA->assignRole('clinic_owner');
        $ownerAClinic = Clinic::factory()->create(['owner_user_id' => $clinicA->id]);
        $rfq = Rfq::factory()->create([
            'clinic_id' => $ownerAClinic->id,
            'supplier_id' => $supplier->id,
            'product_id' => $product->id,
            'requested_by' => $clinicA->id,
        ]);

        $clinicB = User::factory()->create();
        $clinicB->assignRole('clinic_owner');
        Clinic::factory()->create(['owner_user_id' => $clinicB->id]);

        $this->actingAs($clinicB)->get(route('marketplace.rfqs.show', $rfq))->assertForbidden();
    }

    public function test_supplier_cannot_respond_to_another_suppliers_rfq(): void
    {
        $supplierA = Supplier::factory()->create();
        $ownerB = User::factory()->create();
        $ownerB->assignRole('supplier_owner');
        Supplier::factory()->create(['owner_user_id' => $ownerB->id]);

        $clinicOwner = User::factory()->create();
        $clinicOwner->assignRole('clinic_owner');
        $clinic = Clinic::factory()->create(['owner_user_id' => $clinicOwner->id]);

        $rfq = Rfq::factory()->create([
            'clinic_id' => $clinic->id,
            'supplier_id' => $supplierA->id,
            'requested_by' => $clinicOwner->id,
        ]);

        $this->actingAs($ownerB)
            ->post(route('marketplace.rfqs.respond', $rfq), ['message' => 'Hijack attempt', 'status' => 'responded'])
            ->assertForbidden();
    }
}
