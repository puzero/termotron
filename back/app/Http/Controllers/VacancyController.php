<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vacancy;

class VacancyController extends Controller
{
    //
    public function index()
    {
        $vacancies = Vacancy::with(['department', 'position'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $vacancies
        ]);
    }

    public function update(Request $request, string $id)
    {
        //
        if(!empty($id)) {
            $vacancy = Vacancy::find($id);
        }   else{
            $vacancy = new Vacancy();
        }
        $vacancy->name = $request['name'];
        $vacancy->description = $request['description'];
        $vacancy->requirements = $request['requirements'];
        $vacancy->responsibilities = $request['responsibilities'];
        $vacancy->position_id = $request['position_id'];
        $vacancy->department_id = $request['department_id'];
        $vacancy->experience = $request['experience'];
        $vacancy->status = $request['status'];
        $vacancy->candidate_count = $request['candidate_count'];


        $vacancy->save();
        // Возвращаем ответ с кодом 201 (Created)
        return response()->json([
            'success' => true,
            'message' => 'Vacancy created successfully',
        ], 201);
    }

    public function destroy(string $id)
    {
        //
        $vacancy = Vacancy::find($id);

        // Если пользователь не существует
        if (!$vacancy) {
            return response()->json([
                'success' => false,
                'message' => 'Vacancy not found'
            ], 404);
        }

        // Удаляем пользователя
        $vacancy->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vacancy deleted successfully'
        ]);
    }
}
