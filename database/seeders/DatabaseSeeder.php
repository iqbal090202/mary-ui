<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        User::factory(20)->create()->each(function ($user) {
            $user->assignRole('admin');
        });
        Product::factory(25)->create();
        ProductVariant::factory(20)->create();
        $this->call(TransactionSeeder::class);
    }
}
