<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\OfferOption;
use App\Models\Property;
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
        User::factory()->create([
            'first_name' => 'Marcelo',
            'last_name' => 'José',
            'email' => 'marcelomj1978@gmail.com',
        ]);
        
       /*  User::factory()->create([
            'first_name' => 'Manuel',
            'last_name' => 'Cardoso',
            'email' => 'manuelcardosomasc@gmail.com',
        ]); */

        OfferOption::factory()->create([
            'icon' => 'https://example.com/icon.png',
            'title' => 'Example Offer Option',
        ]);
        Property::factory(10)->create();
        OfferOption::factory(10)->create();
        Category::factory(10)->create();
    }
}
