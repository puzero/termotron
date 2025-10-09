<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CandidateVacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $relations = [
            ['candidate_id' => 1, 'vacancy_id' => 1],
            ['candidate_id' => 1, 'vacancy_id' => 2],
            ['candidate_id' => 2, 'vacancy_id' => 1],
            ['candidate_id' => 3, 'vacancy_id' => 3],
            ['candidate_id' => 4, 'vacancy_id' => 2],
        ];

        foreach ($relations as $relation) {
            DB::table('candidate_vacancies')->insert([
                'candidate_id' => $relation['candidate_id'],
                'vacancy_id' => $relation['vacancy_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Successfully seeded candidate-vacancy relationships.');
    }
}
