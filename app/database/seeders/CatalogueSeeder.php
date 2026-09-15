<?php

namespace Database\Seeders;

use App\Modules\Clinic\Models\Service;
use App\Modules\Clinic\Models\Specialty;
use App\Modules\Marketplace\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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

        $categories = [
            'Consumables' => ['Gloves & PPE', 'Impression Materials', 'Anesthetics', 'Restorative Materials'],
            'Equipment' => ['Dental Chairs', 'Sterilizers', 'Handpieces', 'X-Ray Equipment'],
            'Instruments' => ['Hand Instruments', 'Endodontic Instruments', 'Surgical Instruments'],
            'Orthodontics' => ['Brackets & Wires', 'Aligners'],
        ];

        $i = 0;
        foreach ($categories as $parentName => $children) {
            $parent = ProductCategory::firstOrCreate(
                ['slug' => Str::slug($parentName)],
                ['name' => $parentName, 'is_active' => true, 'sort_order' => $i++]
            );

            foreach ($children as $j => $childName) {
                ProductCategory::firstOrCreate(
                    ['slug' => Str::slug($parentName.'-'.$childName)],
                    ['parent_id' => $parent->id, 'name' => $childName, 'is_active' => true, 'sort_order' => $j]
                );
            }
        }
    }
}
