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
        $response = $this->post(route('register.store'), [
            'role' => 'patient',
            'name' => 'Grace Mwakasege',
            'phone' => '+255712345678',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('clinics.index'));
        $this->assertTrue(User::where('phone', '+255712345678')->first()->hasRole('patient'));
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
