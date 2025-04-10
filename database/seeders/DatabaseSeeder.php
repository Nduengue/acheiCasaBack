<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\OfferOption;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'first_name' => 'Marcelo',
            'last_name' => 'José',
            'email' => 'marcelomj1978@gmail.com',
        ]);
        OfferOption::factory()->create([
            'icon' => 'https://example.com/icon.png',
            'title' => 'Example Offer Option',
        ]);
        OfferOption::factory(10)->create();
        Category::factory(10)->create();
    }
}
