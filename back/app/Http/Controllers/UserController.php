<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = User::with('answers')->get();
    
        return response()->json([
            'success' => true,
            'data' => $users
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
        $user = User::all()->find($id);

        if (! $user) {

            return response()->json(["message"=> "User not found"],404);
        }

        return response()->json($user);
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
        if(!empty($id)) {
            $user = User::find($id);
        }   else{
            $user = new User();
        }
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);

        $user->save();
        // Возвращаем ответ с кодом 201 (Created)
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = User::find($id);

        // Если пользователь не существует
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Удаляем пользователя
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
