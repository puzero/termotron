<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
    //

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::all();
        
        return response()->json([
            'success' => true,
            'data' => $positions
        ]);
    }
}
