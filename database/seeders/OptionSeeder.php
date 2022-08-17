<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{


    public function run()
    {
        \App\Models\Option::factory(10)->create();
    }
}
