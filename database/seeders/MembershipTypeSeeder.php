<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    $types = [
        ['name' => 'Daily Pass', 'price' => 150, 'duration_days' => 1], // 1 Day
        ['name' => 'Monthly Basic', 'price' => 1000, 'duration_days' => 30], // 30 Days
        ['name' => 'VIP Quarterly', 'price' => 4000, 'duration_days' => 90], // 90 Days
    ];

    foreach ($types as $type) {
        \App\Models\MembershipType::create($type);
    }
}
}
