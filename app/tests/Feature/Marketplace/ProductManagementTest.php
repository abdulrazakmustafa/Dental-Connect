<?php

namespace Tests\Feature\Marketplace;

use App\Models\User;
use App\Modules\Marketplace\Models\Product;
use App\Modules\Supplier\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    public function test_supplier_owner_can_create_a_product(): void
    {
        $owner = User::factory()->create();
        $owner->assignRole('supplier_owner');
        Supplier::factory()->create(['owner_user_id' => $owner->id]);

        $this->actingAs($owner)->post(route('supplier.products.store'), [
            'name' => 'Dental Floss (Pack of 50)',
            'price' => 5000,
        ])->assertRedirect(route('supplier.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Dental Floss (Pack of 50)',
            'moderation_status' => 'pending',
        ]);
    }

    public function test_supplier_owner_cannot_edit_another_suppliers_product(): void
    {
        $ownerA = User::factory()->create();
        $ownerA->assignRole('supplier_owner');
        Supplier::factory()->create(['owner_user_id' => $ownerA->id]);

        $ownerB = User::factory()->create();
        $supplierB = Supplier::factory()->create(['owner_user_id' => $ownerB->id]);
        $productOfB = Product::factory()->create(['supplier_id' => $supplierB->id]);

        $this->actingAs($ownerA)
            ->put(route('supplier.products.update', $productOfB), ['name' => 'Hijacked'])
            ->assertForbidden();
    }

    public function test_pending_product_is_not_visible_on_public_marketplace(): void
    {
        $viewer = User::factory()->create();
        $viewer->assignRole('admin');

        $supplier = Supplier::factory()->create();
        Product::factory()->create(['supplier_id' => $supplier->id, 'name' => 'Pending Product', 'moderation_status' => 'pending']);
        Product::factory()->create(['supplier_id' => $supplier->id, 'name' => 'Approved Product', 'moderation_status' => 'approved']);

        $response = $this->actingAs($viewer)->get(route('marketplace.home'));

        $response->assertSee('Approved Product');
        $response->assertDontSee('Pending Product');
    }

    public function test_only_authorized_admin_can_approve_a_product(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $supplier = Supplier::factory()->create();
        $product = Product::factory()->create(['supplier_id' => $supplier->id, 'moderation_status' => 'pending']);

        $this->actingAs($admin)
            ->patch(route('admin.products.moderation.update', $product), ['decision' => 'approve'])
            ->assertRedirect();

        $this->assertSame('approved', $product->fresh()->moderation_status);
    }

    public function test_clinic_cannot_approve_a_product(): void
    {
        $owner = User::factory()->create();
        $owner->assignRole('clinic_owner');

        $supplier = Supplier::factory()->create();
        $product = Product::factory()->create(['supplier_id' => $supplier->id, 'moderation_status' => 'pending']);

        $this->actingAs($owner)
            ->patch(route('admin.products.moderation.update', $product), ['decision' => 'approve'])
            ->assertForbidden();
    }
}
