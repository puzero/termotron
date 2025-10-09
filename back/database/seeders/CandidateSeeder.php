<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Candidate;


class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $candidates = [
            [
                'last_name' => 'Иванов',
                'first_name' => 'Иван',
                'middle_name' => 'Иванович',
                'email' => 'ivanov.ii@mail.ru',
                'phone' => '+7 (4832) 12-34-56',
                'birth_date' => '1985-05-15',
                'status' => 'новый',
                'resume_text' => 'Опыт работы на производственных предприятиях 5 лет.',
                'application_date' => '2024-01-15',
                'application_closing_date' => null,
                'interview_date' => null,
            ],
            [
                'last_name' => 'Петрова',
                'first_name' => 'Мария',
                'middle_name' => 'Сергеевна',
                'email' => 'petrova.ms@yandex.ru',
                'phone' => '+7 (4832) 23-45-67',
                'birth_date' => '1990-08-22',
                'status' => 'собеседование',
                'resume_text' => 'Инженер-технолог.',
                'application_date' => '2024-01-10',
                'application_closing_date' => null,
                'interview_date' => '2024-01-25',
            ],
            [
                'last_name' => 'Сидоров',
                'first_name' => 'Алексей',
                'middle_name' => 'Владимирович',
                'email' => 'sidorov.av@gmail.com',
                'phone' => '+7 (4832) 34-56-78',
                'birth_date' => '1988-12-03',
                'status' => 'отклонен',
                'resume_text' => 'Сварщик 6 разряда.',
                'application_date' => '2024-01-05',
                'application_closing_date' => '2024-01-12',
                'interview_date' => null,
            ],
            [
                'last_name' => 'Кузнецова',
                'first_name' => 'Екатерина',
                'middle_name' => 'Александровна',
                'email' => 'kuznetsova.ea@mail.ru',
                'phone' => '+7 (4832) 45-67-89',
                'birth_date' => '1992-03-18',
                'status' => 'принят',
                'resume_text' => 'Экономист.',
                'application_date' => '2024-01-08',
                'application_closing_date' => null,
                'interview_date' => '2024-01-18',
            ],
        ];

        foreach ($candidates as $candidate) {
            Candidate::create($candidate);
        }
    }
}
