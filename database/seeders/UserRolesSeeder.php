<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \App\Models\Admin::create([
        'username' => 'superadmin',
        'password' => \Hash::make('CrownAdmin2026'), // Always hash passwords
        'contactnum' => '09123456789',
        'email' => 'admin@crownfitness.com',
        'familyname' => 'Equibal',
        'role' => 'super_admin',
        'status' => 'active',
    ]);
}
}
