<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $questions = [
            [
                "survey_id" => 1,
                "text" => "Вопрос 1"
            ],
            [
                "survey_id" => 1,
                "text" => "Вопрос 2"
            ],
            [
                "survey_id" => 1,
                "text" => "Вопрос 3"
            ],
            [
                "survey_id" => 2,
                "text" => "Вопрос 1"
            ],
            [
                "survey_id" => 2,
                "text" => "Вопрос 2"
            ],
            [
                "survey_id" => 2,
                "text" => "Вопрос 3"
            ],
            [
                "survey_id" => 3,
                "text" => "Вопрос 1"
            ],
            [
                "survey_id" => 3,
                "text" => "Вопрос 2"
            ],
            [
                "survey_id" => 3,
                "text" => "Вопрос 3"
            ],

        ];

        foreach ($questions as $question) {

            Question::create($question);
        }

    }
}
