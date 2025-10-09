<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Position;
use App\Models\Department;
use App\Models\Vacancy;

class VacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $positions = Position::all();
        $departments = Department::all();

        $vacancies = [
            [
                'name' => 'Инженер-конструктор',
                'description' => 'Разработка конструкторской документации для теплового оборудования. Участие в проектировании новых моделей теплообменников.',
                'requirements' => 'Высшее техническое образование. Опыт работы с CAD-системами (КОМПАС, AutoCAD). Знание ГОСТ и ЕСКД.',
                'responsibilities' => 'Разработка чертежей и технической документации. Проведение расчетов на прочность. Участие в испытаниях опытных образцов.',
                'position_id' => $positions->where('name', 'Инженер-конструктор')->first()->id ?? 1,
                'department_id' => $departments->where('name', 'Конструкторский отдел')->first()->id ?? 1,
                'experience' => '3-5 лет',
                'status' => 'active',
                'candidate_count' => 8,
            ],
            [
                'name' => 'Технолог машиностроительного производства',
                'description' => 'Разработка технологических процессов изготовления деталей теплообменного оборудования. Оптимизация производственных операций.',
                'requirements' => 'Высшее образование по специальности "Технология машиностроения". Опыт работы на машиностроительном предприятии. Знание методов обработки металлов.',
                'responsibilities' => 'Разработка технологических карт. Контроль соблюдения технологической дисциплины. Внедрение новых технологических процессов.',
                'position_id' => $positions->where('name', 'Технолог')->first()->id ?? 2,
                'department_id' => $departments->where('name', 'Технологический отдел')->first()->id ?? 2,
                'experience' => '2-4 года',
                'status' => 'active',
                'candidate_count' => 12,
            ],
            [
                'name' => 'Сварщик аргонодуговой сварки',
                'description' => 'Выполнение сварочных работ при изготовлении теплообменников из нержавеющей стали и цветных металлов.',
                'requirements' => 'Среднее специальное образование. Удостоверение сварщика. Опыт работы с нержавеющей сталью. 4-6 разряд.',
                'responsibilities' => 'Сварка трубных систем теплообменников. Контроль качества сварных швов. Соблюдение техники безопасности.',
                'position_id' => $positions->where('name', 'Сварщик')->first()->id ?? 3,
                'department_id' => $departments->where('name', 'Сварочный цех')->first()->id ?? 3,
                'experience' => '1-3 года',
                'status' => 'active',
                'candidate_count' => 15,
            ],
            [
                'name' => 'Наладчик станков с ЧПУ',
                'description' => 'Обслуживание и наладка станков с числовым программным управлением в механическом цехе.',
                'requirements' => 'Среднее специальное образование. Опыт работы с фрезерными и токарными станками с ЧПУ. Знание систем управления Sinumerik, Fanuc.',
                'responsibilities' => 'Наладка станков по чертежам. Контроль качества обработки. Проведение планового обслуживания оборудования.',
                'position_id' => $positions->where('name', 'Наладчик ЧПУ')->first()->id ?? 4,
                'department_id' => $departments->where('name', 'Механический цех')->first()->id ?? 4,
                'experience' => '2-5 лет',
                'status' => 'active',
                'candidate_count' => 10,
            ],
            [
                'name' => 'Инженер по качеству',
                'description' => 'Контроль качества продукции на всех этапах производства. Ведение документации системы менеджмента качества.',
                'requirements' => 'Высшее техническое образование. Знание стандартов ISO 9001. Опыт работы в ОТК.',
                'responsibilities' => 'Проведение входного контроля материалов. Контроль соблюдения технологических процессов. Участие в сертификации продукции.',
                'position_id' => $positions->where('name', 'Инженер по качеству')->first()->id ?? 5,
                'department_id' => $departments->where('name', 'Отдел технического контроля')->first()->id ?? 5,
                'experience' => '3-6 лет',
                'status' => 'active',
                'candidate_count' => 6,
            ],
            [
                'name' => 'Экономист планового отдела',
                'description' => 'Планирование производственных затрат, калькуляция себестоимости продукции, анализ экономических показателей.',
                'requirements' => 'Высшее экономическое образование. Опыт работы на промышленном предприятии. Знание 1С:Бухгалтерия.',
                'responsibilities' => 'Расчет себестоимости продукции. Планирование производственной программы. Анализ выполнения плановых показателей.',
                'position_id' => $positions->where('name', 'Экономист')->first()->id ?? 6,
                'department_id' => $departments->where('name', 'Планово-экономический отдел')->first()->id ?? 6,
                'experience' => '2-4 года',
                'status' => 'active',
                'candidate_count' => 9,
            ],
            [
                'name' => 'Мастер сборочного цеха',
                'description' => 'Организация работы участка сборки теплообменного оборудования. Руководство рабочими бригады.',
                'requirements' => 'Высшее или среднее техническое образование. Опыт работы мастером на производстве. Знание технологии сборки.',
                'responsibilities' => 'Организация работы участка. Контроль соблюдения технологии сборки. Составление графиков работы.',
                'position_id' => $positions->where('name', 'Мастер')->first()->id ?? 7,
                'department_id' => $departments->where('name', 'Сборочный цех')->first()->id ?? 7,
                'experience' => '5-8 лет',
                'status' => 'closed',
                'candidate_count' => 0,
            ],
            [
                'name' => 'Специалист по закупкам',
                'description' => 'Организация закупок материалов и комплектующих для производства теплообменного оборудования.',
                'requirements' => 'Высшее образование. Опыт работы в закупках на промышленном предприятии. Знание рынка металлопроката.',
                'responsibilities' => 'Поиск поставщиков. Заключение договоров. Контроль сроков поставок. Анализ цен на материалы.',
                'position_id' => $positions->where('name', 'Специалист по закупкам')->first()->id ?? 8,
                'department_id' => $departments->where('name', 'Отдел снабжения')->first()->id ?? 8,
                'experience' => '3-5 лет',
                'status' => 'draft',
                'candidate_count' => 0,
            ],
        ];

        foreach ($vacancies as $vacancy) {
            Vacancy::create($vacancy);
        }
    }
}
