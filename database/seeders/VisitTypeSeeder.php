<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VisitTypeSeeder extends Seeder
{


    public function run()
    {
        // \App\Models\VisitType::factory(10)->create();
        \App\Models\VisitType::insert([
            [
                'name'  => "Family",
            ],
            [
                'name'  => "Solo",
            ],
            [
                'name'  => "Business",
            ],
            [
                'name'  => "Friends",
            ],
        ]);
    }
}
