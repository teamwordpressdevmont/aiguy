<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AffiliateLinks; // <-- Import the model

class AffiliateLinkSeeder extends Seeder
{
    public function run()
    {
        AffiliateLinks::insert([
            [
                'title' => "AI Guy's Favorite Tools",
                'url' => "https://aiguy.tools/affiliate/123",
                'description' => "A curated list of must-have tools for AI developers and researchers.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => "Learn AI with AI Guy",
                'url' => "https://aiguy.learn/affiliate/456",
                'description' => "Exclusive AI courses by AI Guy, covering deep learning, ML, and more.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => "AI Guy's Blog",
                'url' => "https://aiguy.blog",
                'description' => "Stay updated with AI Guy's latest blogs and tutorials on AI trends.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => "AI Guy's Hardware Recommendations",
                'url' => "https://aiguy.hardware/affiliate/789",
                'description' => "The ultimate hardware recommendations for AI enthusiasts and developers.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => "AI Guy's Software Picks",
                'url' => "https://aiguy.software/affiliate/012",
                'description' => "Top-notch software tools recommended by AI Guy for AI and ML projects.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}