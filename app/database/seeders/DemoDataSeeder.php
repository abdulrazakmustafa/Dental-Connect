<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Appointment\Models\Appointment;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Clinic\Models\ClinicLocation;
use App\Modules\Clinic\Models\Dentist;
use App\Modules\Clinic\Models\Service;
use App\Modules\Patient\Models\ClinicPatient;
use App\Modules\Supplier\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Non-production demo data (PRD §100 — never runs automatically in
 * production). Gives the team a realistic walkthrough of the golden path:
 * a verified clinic with dentists, an enrolled patient, and a requested
 * appointment.
 */
class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $clinicOwner = User::firstOrCreate(
            ['email' => 'clinic.owner@example.com'],
            ['public_id' => (string) Str::ulid(), 'name' => 'Amina Suleiman', 'password' => Hash::make('password'), 'status' => 'active', 'email_verified_at' => now()]
        );
        $clinicOwner->syncRoles(['clinic_owner']);

        $clinic = Clinic::firstOrCreate(
            ['slug' => 'smile-dental-clinic'],
            [
                'name' => 'Smile Dental Clinic',
                'description' => 'A modern dental clinic in the heart of Dar es Salaam offering general and cosmetic dentistry.',
                'email' => 'info@smiledental.example',
                'phone' => '+255700000001',
                'owner_user_id' => $clinicOwner->id,
                'verification_status' => Clinic::STATUS_APPROVED,
                'verified_at' => now(),
                'is_active' => true,
                'profile_completion_percent' => 100,
            ]
        );

        ClinicLocation::firstOrCreate(
            ['clinic_id' => $clinic->id, 'is_primary' => true],
            ['address_line' => 'Samora Avenue', 'region' => 'Dar es Salaam', 'city' => 'Dar es Salaam', 'area' => 'Kariakoo']
        );

        $clinic->services()->syncWithoutDetaching(Service::inRandomOrder()->limit(4)->pluck('id'));

        $dentist = Dentist::firstOrCreate(
            ['clinic_id' => $clinic->id, 'full_name' => 'Dr. John Mushi'],
            ['status' => 'active', 'bio' => 'General dentist with 10 years of experience.']
        );

        $patientUser = User::firstOrCreate(
            ['email' => 'patient@example.com'],
            ['public_id' => (string) Str::ulid(), 'name' => 'Grace Mwakasege', 'password' => Hash::make('password'), 'status' => 'active', 'email_verified_at' => now()]
        );
        $patientUser->syncRoles(['patient']);

        $clinicPatient = ClinicPatient::firstOrCreate(
            ['clinic_id' => $clinic->id, 'user_id' => $patientUser->id],
            [
                'patient_number' => 'SDC-000001',
                'first_name' => 'Grace',
                'last_name' => 'Mwakasege',
                'phone' => $patientUser->phone,
                'email' => $patientUser->email,
                'assigned_dentist_id' => $dentist->id,
                'status' => 'active',
            ]
        );

        Appointment::firstOrCreate(
            ['clinic_id' => $clinic->id, 'clinic_patient_id' => $clinicPatient->id, 'preferred_date' => now()->addDays(3)->toDateString()],
            ['dentist_id' => $dentist->id, 'status' => Appointment::STATUS_REQUESTED, 'patient_note' => 'Routine checkup and cleaning.']
        );

        $supplierOwner = User::firstOrCreate(
            ['email' => 'supplier.owner@example.com'],
            ['public_id' => (string) Str::ulid(), 'name' => 'Fatima Kessy', 'password' => Hash::make('password'), 'status' => 'active', 'email_verified_at' => now()]
        );
        $supplierOwner->syncRoles(['supplier_owner']);

        Supplier::firstOrCreate(
            ['slug' => 'dental-supplies-tz'],
            [
                'name' => 'Dental Supplies TZ',
                'description' => 'Wholesale dental consumables and equipment supplier.',
                'email' => 'sales@dentalsuppliestz.example',
                'owner_user_id' => $supplierOwner->id,
                'verification_status' => 'approved',
                'verified_at' => now(),
                'is_active' => true,
                'region' => 'Dar es Salaam',
                'city' => 'Dar es Salaam',
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@dentalconnect.co.tz'],
            ['public_id' => (string) Str::ulid(), 'name' => 'Platform Admin', 'password' => Hash::make('password'), 'status' => 'active', 'email_verified_at' => now()]
        );
        $admin->syncRoles(['super_admin']);
    }
}
