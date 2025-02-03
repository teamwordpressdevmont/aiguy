<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tools; // Make sure to import the Tool model


class ToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Tools::create([
            'tool_name' => 'Visual Studio Code',
        ]);

        Tools::create([
            'tool_name' => 'Jupyter Notebook',
        ]);

        Tools::create([
            'tool_name' => 'Xcode',
        ]);

    }
}
