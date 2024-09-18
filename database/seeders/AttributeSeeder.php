<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'Color', 'Size', 'Brand', 'Storage Capacity',
        ];

        foreach ($names as $name) {
            Attribute::create([
                'name' => $name,
            ]);
        }
    }
}
