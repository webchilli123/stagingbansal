<?php

namespace App\Http\Controllers;

use App\Models\EmployeeAttendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class EmployeeAttendanceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $attendances = EmployeeAttendance::with('employee')->select();
  
            return DataTables::of($attendances)
                    ->editColumn('attendance_date', function ($attendance) {
                        return $attendance->attendance_date->format('d M, Y') ;
                    })
                    ->editColumn('start_time', function ($attendance) {
                        return $attendance->start_time->format('H:i A') ;
                    })
                    ->editColumn('end_time', function ($attendance) {
                        return $attendance->end_time->format('H:i A') ;
                    })
                  ->addColumn('action', function ($attendance) {
                      return view('attendances.buttons')->with(['attendance' => $attendance]);
                  })->make(true);
        }

        return view('attendances.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::orderBy('name')->pluck('name', 'id');
        return view('attendances.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'employee_id' => 'required',
            'attendance_date' => 'required|date|'.Rule::unique('employee_attendances')
                                ->where(function($query) use($request) {
                                    return $query->where('emplyee_id', $request->employee_id)
                                        ->where('attendance_date', $request->attendance_date);
                                }),
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i'
        ]);
       
        EmployeeAttendance::create($validatedData);

        return  redirect()->route('employee-attendances.index')->with('success', 'Employee Attendance Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeAttendance   $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeAttendance $attendance)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeAttendance   $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeAttendance $attendance)
    {
        $employees = Employee::orderBy('name')->pluck('name', 'id');
        return view('attendances.edit', compact('attendance', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeAttendance   $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeAttendance $attendance)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required',
            'attendance_date' => 'required|date|',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i'
        ]);
       
        $attendance->update($validatedData);

        return  redirect()->route('employee-attendances.index')->with('success', 'Employee Attendance  Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeAttendance   $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeAttendance  $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Employee Attendance  Deleted');
    }
}
