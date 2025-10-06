<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Answer;
class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //


        $answers = [
            [
                "question_id" => 1,
                "user_id" => 2,
                "text" => "Ответ 1"
            ],
            [
                "question_id" => 2,
                "user_id" => 2,
                "text" => "Ответ 2"
            ],
            [
                "question_id" => 3,
                "user_id" => 2,
                "text" => "Ответ 3"
            ],
            [
                "question_id" => 4,
                "user_id" => 2,
                "text" => "Ответ 1 Опросника 2"
            ]


            ];

            foreach ($answers as $answer) {
                Answer::create($answer);
            }
    }
}
