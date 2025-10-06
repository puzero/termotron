<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $answers = Answer::select('id','question_id','user_id','text')->get();

        return response()->json($answers);
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

        $user = $request->user();
        $input = $request->all();
        
        // print_r($request->user());die();

        $answersData = $input['answers'] ?? [];
        
        if (empty($answersData)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No answers provided'
            ], 400);
        }

        $preparedAnswers = [];
        foreach ($answersData as $answer) {
            $preparedAnswers[] = [
                'user_id' => $user->id,
                'question_id' => $answer['question_id'],
                'text' => $answer['text'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 5. Выполняем вставку в транзакции

            Answer::insert($preparedAnswers);

        return response()->json([
            'status' => 'success',
            'message' => 'Answers saved successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $answer = Answer::select('id','question_id','user_id','text')->find($id);

        if (! $answer) {
            return response()->json(['message'=> 'Answer not found'],404);
        }

        return response()->json($answer);
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
