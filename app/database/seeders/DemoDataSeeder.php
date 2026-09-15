<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Appointment\Models\Appointment;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Clinic\Models\ClinicLocation;
use App\Modules\Clinic\Models\Dentist;
use App\Modules\Clinic\Models\Service;
use App\Modules\Clinic\Models\Specialty;
use App\Modules\Marketplace\Models\Product;
use App\Modules\Marketplace\Models\ProductCategory;
use App\Modules\Notification\Models\PatientNotification;
use App\Modules\Patient\Models\ClinicPatient;
use App\Modules\Supplier\Models\Supplier;
use App\Modules\TrustSupport\Models\Review;
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

        $servicePrices = [
            'General Checkup' => 50000,
            'Teeth Cleaning' => 80000,
            'Tooth Extraction' => 60000,
            'Dental Filling' => 70000,
        ];

        foreach ($servicePrices as $name => $price) {
            $service = Service::where('name', $name)->first();
            if ($service) {
                $clinic->services()->syncWithoutDetaching([$service->id => ['price' => $price]]);
            }
        }

        $clinic->specialties()->syncWithoutDetaching(Specialty::whereIn('name', ['General Dentistry', 'Cosmetic Dentistry', 'Orthodontics'])->pluck('id'));

        $dentist = Dentist::firstOrCreate(
            ['clinic_id' => $clinic->id, 'full_name' => 'Dr. Sarah Johnson'],
            ['status' => 'active', 'bio' => 'General dentist with 10 years of experience.']
        );
        $generalSpecialty = Specialty::where('name', 'General Dentistry')->first();
        if ($generalSpecialty) {
            $dentist->specialties()->syncWithoutDetaching([$generalSpecialty->id]);
        }

        $patientUser = User::firstOrCreate(
            ['email' => 'patient@example.com'],
            ['public_id' => (string) Str::ulid(), 'name' => 'Aisha Hassan', 'phone' => '+255712345678', 'password' => Hash::make('password'), 'status' => 'active', 'email_verified_at' => now()]
        );
        $patientUser->syncRoles(['patient']);

        $clinicPatient = ClinicPatient::firstOrCreate(
            ['clinic_id' => $clinic->id, 'user_id' => $patientUser->id],
            [
                'patient_number' => 'SDC-000001',
                'first_name' => 'Aisha',
                'last_name' => 'Hassan',
                'phone' => $patientUser->phone,
                'email' => $patientUser->email,
                'assigned_dentist_id' => $dentist->id,
                'status' => 'active',
            ]
        );

        $checkupService = Service::where('name', 'General Checkup')->first();
        $cleaningService = Service::where('name', 'Teeth Cleaning')->first();

        Appointment::firstOrCreate(
            ['clinic_id' => $clinic->id, 'clinic_patient_id' => $clinicPatient->id, 'preferred_date' => now()->addDays(3)->toDateString()],
            [
                'dentist_id' => $dentist->id,
                'service_id' => $checkupService?->id,
                'preferred_time' => '10:30',
                'status' => Appointment::STATUS_CONFIRMED,
                'confirmed_at' => now(),
                'patient_note' => 'Routine checkup and cleaning.',
                'clinic_note' => 'Please arrive 10 minutes early and bring any recent dental reports if available.',
            ]
        );

        $completedAppointment = Appointment::firstOrCreate(
            ['clinic_id' => $clinic->id, 'clinic_patient_id' => $clinicPatient->id, 'preferred_date' => now()->subMonth()->toDateString()],
            [
                'dentist_id' => $dentist->id,
                'service_id' => $cleaningService?->id,
                'preferred_time' => '11:00',
                'status' => Appointment::STATUS_COMPLETED,
                'completed_at' => now()->subMonth(),
            ]
        );

        Review::firstOrCreate(
            ['appointment_id' => $completedAppointment->id, 'clinic_patient_id' => $clinicPatient->id],
            ['clinic_id' => $clinic->id, 'rating' => 5, 'comment' => 'Friendly staff and very professional care.', 'moderation_status' => 'published']
        );

        PatientNotification::firstOrCreate(
            ['user_id' => $patientUser->id, 'type' => 'appointment.confirmed', 'title' => $clinic->name],
            ['body' => 'Your appointment for '.now()->addDays(3)->format('j M').' is confirmed.', 'created_at' => now()->subHours(2)]
        );
        PatientNotification::firstOrCreate(
            ['user_id' => $patientUser->id, 'type' => 'clinic.note', 'title' => 'Dr. Sarah Johnson'],
            ['body' => 'Please arrive 10 minutes early.', 'created_at' => now()->subDay()]
        );
        PatientNotification::firstOrCreate(
            ['user_id' => $patientUser->id, 'type' => 'enrollment.active', 'title' => 'Dental Connect'],
            ['body' => 'Your clinic enrollment is active.', 'created_at' => now()->subDays(2)]
        );

        $supplierOwner = User::firstOrCreate(
            ['email' => 'supplier.owner@example.com'],
            ['public_id' => (string) Str::ulid(), 'name' => 'Fatima Kessy', 'password' => Hash::make('password'), 'status' => 'active', 'email_verified_at' => now()]
        );
        $supplierOwner->syncRoles(['supplier_owner']);

        $supplier = Supplier::firstOrCreate(
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

        $demoProducts = [
            ['name' => 'Nitrile Examination Gloves (Box of 100)', 'category' => 'Gloves & PPE', 'price' => 18000, 'unit' => 'box'],
            ['name' => 'Lidocaine 2% Local Anesthetic (Box of 50)', 'category' => 'Anesthetics', 'price' => 95000, 'unit' => 'box'],
            ['name' => 'Alginate Impression Material 1kg', 'category' => 'Impression Materials', 'price' => 32000, 'unit' => 'unit'],
            ['name' => 'High-Speed Dental Handpiece', 'category' => 'Handpieces', 'price' => 450000, 'unit' => null],
        ];

        foreach ($demoProducts as $item) {
            $category = ProductCategory::where('name', $item['category'])->first();

            Product::firstOrCreate(
                ['supplier_id' => $supplier->id, 'slug' => Str::slug($item['name'])],
                [
                    'category_id' => $category?->id,
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'price_unit' => $item['unit'],
                    'price_visible' => true,
                    'status' => 'active',
                    'moderation_status' => 'approved',
                    'is_available' => true,
                ]
            );
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@dentalconnect.co.tz'],
            ['public_id' => (string) Str::ulid(), 'name' => 'Platform Admin', 'password' => Hash::make('password'), 'status' => 'active', 'email_verified_at' => now()]
        );
        $admin->syncRoles(['super_admin']);
    }
}
