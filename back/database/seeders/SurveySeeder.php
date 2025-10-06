<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Survey;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $surceys = [
            [
                "name"=> "Опрос 1",
            ],
            [
                "name"=> "Опрос 2",

            ],
            [
                "name"=> "Опрос 3",
            ]
            ];

            foreach ($surceys as $surcey) {
                Survey::create($surcey);
            }
    }
}
