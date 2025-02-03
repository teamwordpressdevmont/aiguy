<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categories; // Make sure to import the Category model


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Categories::create([
            'category_name' => 'Web Development',
        ]);

        Categories::create([
            'category_name' => 'Data Science',
        ]);

        Categories::create([
            'category_name' => 'Mobile Development',
        ]);

    }
}