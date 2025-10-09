<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Position;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $departments = Department::all();
        $positions = Position::all();

        $employees = [
            [
                'last_name' => 'Иванов',
                'first_name' => 'Сергей',
                'middle_name' => 'Петрович',
                'position_id' => $positions->where('name', 'Генеральный директор')->first()->id,
                'department_id' => $departments->where('name', 'Дирекция')->first()->id,
                'hire_date' => '2010-05-15',
                'email' => 's.ivanov@termotron.ru',
                'phone' => '+7 (4832) 12-34-56',
                'birth_date' => '1975-03-20',
                'salary' => 250000.00,
                'status' => 'active',
            ],
            [
                'last_name' => 'Петров',
                'first_name' => 'Александр',
                'middle_name' => 'Викторович',
                'position_id' => $positions->where('name', 'Технический директор')->first()->id,
                'department_id' => $departments->where('name', 'Дирекция')->first()->id,
                'hire_date' => '2012-08-20',
                'email' => 'a.petrov@termotron.ru',
                'phone' => '+7 (4832) 12-34-57',
                'birth_date' => '1978-07-12',
                'salary' => 180000.00,
                'status' => 'active',
            ],
            [
                'last_name' => 'Сидорова',
                'first_name' => 'Елена',
                'middle_name' => 'Михайловна',
                'position_id' => $positions->where('name', 'Коммерческий директор')->first()->id,
                'department_id' => $departments->where('name', 'Дирекция')->first()->id,
                'hire_date' => '2013-03-10',
                'email' => 'e.sidorova@termotron.ru',
                'phone' => '+7 (4832) 12-34-58',
                'birth_date' => '1980-11-05',
                'salary' => 170000.00,
                'status' => 'active',
            ],
            [
                'last_name' => 'Кузнецов',
                'first_name' => 'Дмитрий',
                'middle_name' => 'Игоревич',
                'position_id' => $positions->where('name', 'Начальник производства')->first()->id,
                'department_id' => $departments->where('name', 'Производственный отдел')->first()->id,
                'hire_date' => '2015-06-01',
                'email' => 'd.kuznetsov@termotron.ru',
                'phone' => '+7 (4832) 12-34-59',
                'birth_date' => '1982-09-15',
                'salary' => 120000.00,
                'status' => 'active',
            ],
            [
                'last_name' => 'Васильев',
                'first_name' => 'Андрей',
                'middle_name' => 'Сергеевич',
                'position_id' => $positions->where('name', 'Мастер производственного участка')->first()->id,
                'department_id' => $departments->where('name', 'Производственный отдел')->first()->id,
                'hire_date' => '2018-02-14',
                'email' => 'a.vasilev@termotron.ru',
                'phone' => '+7 (4832) 12-35-00',
                'birth_date' => '1985-04-22',
                'salary' => 80000.00,
                'status' => 'active',
            ],
            [
                'last_name' => 'Федоров',
                'first_name' => 'Михаил',
                'middle_name' => 'Александрович',
                'position_id' => $positions->where('name', 'Сварщик')->first()->id,
                'department_id' => $departments->where('name', 'Производственный отдел')->first()->id,
                'hire_date' => '2019-09-01',
                'email' => 'm.fedorov@termotron.ru',
                'phone' => '+7 (4832) 12-35-01',
                'birth_date' => '1990-12-10',
                'salary' => 65000.00,
                'status' => 'active',
            ],
            [
                'last_name' => 'Николаев',
                'first_name' => 'Владимир',
                'middle_name' => 'Петрович',
                'position_id' => $positions->where('name', 'Слесарь-сборщик')->first()->id,
                'department_id' => $departments->where('name', 'Производственный отдел')->first()->id,
                'hire_date' => '2020-03-15',
                'email' => 'v.nikolaev@termotron.ru',
                'phone' => '+7 (4832) 12-35-02',
                'birth_date' => '1992-08-18',
                'salary' => 55000.00,
                'status' => 'active',
            ],
            [
                'last_name' => 'Орлов',
                'first_name' => 'Павел',
                'middle_name' => 'Владимирович',
                'position_id' => $positions->where('name', 'Главный конструктор')->first()->id,
                'department_id' => $departments->where('name', 'Отдел главного конструктора')->first()->id,
                'hire_date' => '2011-04-12',
                'email' => 'p.orlov@termotron.ru',
                'phone' => '+7 (4832) 12-35-03',
                'birth_date' => '1976-06-30',
                'salary' => 150000.00,
                'status' => 'active',
            ],
            [
                'last_name' => 'Белов',
                'first_name' => 'Алексей',
                'middle_name' => 'Иванович',
                'position_id' => $positions->where('name', 'Инженер-конструктор')->first()->id,
                'department_id' => $departments->where('name', 'Отдел главного конструктора')->first()->id,
                'hire_date' => '2017-11-05',
                'email' => 'a.belov@termotron.ru',
                'phone' => '+7 (4832) 12-35-04',
                'birth_date' => '1988-02-14',
                'salary' => 85000.00,
                'status' => 'active',
            ],
            [
                'last_name' => 'Ковалева',
                'first_name' => 'Ольга',
                'middle_name' => 'Сергеевна',
                'position_id' => $positions->where('name', 'Начальник отдела сбыта')->first()->id,
                'department_id' => $departments->where('name', 'Отдел сбыта')->first()->id,
                'hire_date' => '2014-07-22',
                'email' => 'o.kovaleva@termotron.ru',
                'phone' => '+7 (4832) 12-35-05',
                'birth_date' => '1983-10-08',
                'salary' => 110000.00,
                'status' => 'active',
            ],
            [
                'last_name' => 'Григорьева',
                'first_name' => 'Мария',
                'middle_name' => 'Андреевна',
                'position_id' => $positions->where('name', 'Менеджер по продажам')->first()->id,
                'department_id' => $departments->where('name', 'Отдел сбыта')->first()->id,
                'hire_date' => '2019-01-30',
                'email' => 'm.grigoreva@termotron.ru',
                'phone' => '+7 (4832) 12-35-06',
                'birth_date' => '1991-05-19',
                'salary' => 70000.00,
                'status' => 'active',
            ],
            [
                'last_name' => 'Семенова',
                'first_name' => 'Ирина',
                'middle_name' => 'Владимировна',
                'position_id' => $positions->where('name', 'Главный бухгалтер')->first()->id,
                'department_id' => $departments->where('name', 'Бухгалтерия')->first()->id,
                'hire_date' => '2009-09-01',
                'email' => 'i.semenova@termotron.ru',
                'phone' => '+7 (4832) 12-35-07',
                'birth_date' => '1974-01-25',
                'salary' => 130000.00,
                'status' => 'active',
            ],
            [
                'last_name' => 'Павлова',
                'first_name' => 'Наталья',
                'middle_name' => 'Дмитриевна',
                'position_id' => $positions->where('name', 'Бухгалтер')->first()->id,
                'department_id' => $departments->where('name', 'Бухгалтерия')->first()->id,
                'hire_date' => '2016-04-18',
                'email' => 'n.pavlova@termotron.ru',
                'phone' => '+7 (4832) 12-35-08',
                'birth_date' => '1987-07-03',
                'salary' => 75000.00,
                'status' => 'active',
            ],
            [
                'last_name' => 'Соколов',
                'first_name' => 'Артем',
                'middle_name' => 'Олегович',
                'position_id' => $positions->where('name', 'Инженер-технолог')->first()->id,
                'department_id' => $departments->where('name', 'Отдел главного технолога')->first()->id,
                'hire_date' => '2018-03-10',
                'email' => 'a.sokolov@termotron.ru',
                'phone' => '+7 (4832) 12-35-09',
                'birth_date' => '1989-11-12',
                'salary' => 80000.00,
                'status' => 'inactive',
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
