<?php

namespace Tests\Feature\Identity;

use App\Models\User;
use App\Modules\Clinic\Models\Clinic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationAndLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    public function test_patient_can_register(): void
    {
        $clinic = Clinic::factory()->create([
            'verification_status' => Clinic::STATUS_APPROVED,
            'is_active' => true,
        ]);

        $response = $this->post(route('register.store'), [
            'role' => 'patient',
            'name' => 'Grace Mwakasege',
            'clinic_id' => $clinic->public_id,
            'phone' => '+255712345678',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('patient.dashboard'));
        $user = User::where('phone', '+255712345678')->first();
        $this->assertTrue($user->hasRole('patient'));
        $this->assertDatabaseHas('clinic_patients', ['clinic_id' => $clinic->id, 'user_id' => $user->id]);
    }

    public function test_patient_registration_requires_a_clinic(): void
    {
        $response = $this->post(route('register.store'), [
            'role' => 'patient',
            'name' => 'Grace Mwakasege',
            'phone' => '+255712345678',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => '1',
        ]);

        $response->assertSessionHasErrors('clinic_id');
        $this->assertDatabaseMissing('users', ['phone' => '+255712345678']);
    }

    public function test_clinic_registration_creates_draft_clinic_and_redirects_to_onboarding(): void
    {
        $response = $this->post(route('register.store'), [
            'role' => 'clinic',
            'name' => 'Amina Suleiman',
            'organization_name' => 'Smile Dental Clinic',
            'phone' => '+255700000099',
            'email' => 'amina@smiledental.example',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('clinic.onboarding'));
        $this->assertDatabaseHas('clinics', ['name' => 'Smile Dental Clinic', 'verification_status' => Clinic::STATUS_DRAFT]);
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create(['password' => bcrypt('Password123!')]);
        $user->assignRole('patient');

        $this->post(route('login.store'), ['login' => $user->email, 'password' => 'Password123!'])
            ->assertRedirect(route('patient.dashboard'));

        $this->actingAs($user)->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('Password123!')]);

        $this->post(route('login.store'), ['login' => $user->email, 'password' => 'wrong'])
            ->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_suspended_account_cannot_login(): void
    {
        $user = User::factory()->create(['password' => bcrypt('Password123!'), 'status' => 'suspended']);

        $this->post(route('login.store'), ['login' => $user->email, 'password' => 'Password123!'])
            ->assertSessionHasErrors('login');
        $this->assertGuest();
    }
}
