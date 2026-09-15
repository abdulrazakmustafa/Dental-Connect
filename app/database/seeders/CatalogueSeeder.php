<?php

namespace Database\Seeders;

use App\Modules\Clinic\Models\Service;
use App\Modules\Clinic\Models\Specialty;
use Illuminate\Database\Seeder;

class CatalogueSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            'General Checkup', 'Teeth Cleaning', 'Tooth Extraction', 'Root Canal',
            'Dental Filling', 'Braces / Orthodontics', 'Teeth Whitening', 'Dental Implants',
            'Crowns & Bridges', 'Pediatric Dentistry', 'Emergency Dental Care',
        ];

        foreach ($services as $i => $name) {
            Service::firstOrCreate(
                ['code' => \Illuminate\Support\Str::slug($name)],
                ['name' => $name, 'is_active' => true, 'sort_order' => $i]
            );
        }

        $specialties = [
            'General Dentistry', 'Orthodontics', 'Endodontics', 'Periodontics',
            'Oral Surgery', 'Pediatric Dentistry', 'Prosthodontics', 'Cosmetic Dentistry',
        ];

        foreach ($specialties as $i => $name) {
            Specialty::firstOrCreate(
                ['code' => \Illuminate\Support\Str::slug($name)],
                ['name' => $name, 'is_active' => true, 'sort_order' => $i]
            );
        }
    }
}
