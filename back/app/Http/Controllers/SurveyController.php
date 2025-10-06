<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\Survey;

class SurveyController extends Controller
{
    public function userAnswers($surveyId, $userId)
    {

        $answers = Answer::whereIn('question_id', function($query) use ($surveyId) {
            $query->select('id')
                  ->from('questions')
                  ->where('survey_id', $surveyId);
        })
        ->where('user_id', $userId)
        ->get();

        $questions = Question::where('survey_id',  '=',$surveyId)->get();

        return response()->json([
        "answers" => $answers,
        "questions" => $questions
        ]);
        // return [
        //     'survey' => $surveyId,
        //     'user' => $userId
        // ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $surveys = Survey::select('id', 'name')->get();
        
        return response()->json($surveys);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

        $survey = Survey::select('id', 'name')->find($id);
        
        if (!$survey) {
            return response()->json(['message' => 'Survey not found'], 404);
        }
        
        return response()->json($survey);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
