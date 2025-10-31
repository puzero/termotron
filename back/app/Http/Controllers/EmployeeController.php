<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Employee::with(['position', 'department']);
        
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            
            $query->where(function($q) use ($searchTerm) {
                $q->where('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('middle_name', 'like', "%{$searchTerm}%")
                  ->orWhere(DB::raw("CONCAT(last_name, ' ', first_name, ' ', middle_name)"), 'like', "%{$searchTerm}%");
            });
        }
        
        $employees = $query->get();
        
        return response()->json([
            'data' => $employees,
            'success' => true
        ]);
    }

    public function update(Request $request, string $id)
    {
        //
        if(!empty($id)) {
            $employee = Employee::find($id);
        }   else{
            $employee = new Employee();
        }
        $employee->last_name = $request['last_name'];
        $employee->middle_name = $request['middle_name'];
        $employee->first_name = $request['first_name'];
        $employee->phone = $request['phone'];
        $employee->email = $request['email'];
        $employee->salary = $request['salary'];
        $employee->position_id = $request['position_id'];
        $employee->department_id = $request['department_id'];
        $employee->hire_date = $request['hire_date'];
        $employee->birth_date = $request['birth_date'];
        $employee->status = $request['status'];


        $employee->save();
        // Возвращаем ответ с кодом 201 (Created)
        return response()->json([
            'success' => true,
            'message' => 'Employee created successfully',
        ], 201);
    }

    public function destroy(string $id)
    {
        //
        $employee = Employee::find($id);

        // Если пользователь не существует
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Удаляем пользователя
        $employee->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
