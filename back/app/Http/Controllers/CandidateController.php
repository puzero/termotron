<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;

class CandidateController extends Controller
{
    //
    public function index()
    {
        $candidates = Candidate::all();
        
        return response()->json([
            'success' => true,
            'data' => $candidates
        ]);
    }

    public function update(Request $request, string $id)
    {
        //
        if(!empty($id)) {
            $candidate = Candidate::find($id);
        }   else{
            $candidate = new Candidate();
        }
        $candidate->last_name = $request['last_name'];
        $candidate->middle_name = $request['middle_name'];
        $candidate->first_name = $request['first_name'];
        $candidate->phone = $request['phone'];
        $candidate->email = $request['email'];
        $candidate->application_date = $request['application_date'];
        $candidate->birth_date = $request['birth_date'];
        $candidate->interview_date = $request['iterview_date'];
        $candidate->status = $request['status'];
        $candidate->resume_text = $request['resume_text'];


        $candidate->save();
        // Возвращаем ответ с кодом 201 (Created)
        return response()->json([
            'success' => true,
            'message' => 'Candidate created successfully',
        ], 201);
    }

    public function destroy(string $id)
    {
        //
        $candidate = Candidate::find($id);

        // Если пользователь не существует
        if (!$candidate) {
            return response()->json([
                'success' => false,
                'message' => 'Candidate not found'
            ], 404);
        }

        // Удаляем пользователя
        $candidate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vacancy deleted successfully'
        ]);
    }
}
