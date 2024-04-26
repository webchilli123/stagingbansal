<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\StaffType;
use App\Models\Designation;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Employee::class, 'employee');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $employees = Employee::with(['designation', 'staffType'])->select();
  
            return DataTables::of($employees)
                  ->addColumn('action', function ($employee) {
                      return view('employees.buttons')->with(['employee' => $employee]);
                  })->make(true);
        }

        return view('employees.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $designations = Designation::orderBy('name')->pluck('name', 'id');
        $staff_types = StaffType::orderBy('name')->pluck('name', 'id');
        return view('employees.create', compact('designations', 'staff_types'));
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
            'name' => 'required|max:100|unique:employees',
            'contact_number' => 'nullable|max:100',
            'address' => 'nullable|max:100',
            'designation_id' => 'required',
            'staff_type_id' => 'required',
            'rate' => 'required|integer'
        ]);
       
        Employee::create($validatedData);

        return  redirect()->route('employees.index')->with('success', 'Employee Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        
        $designations = Designation::orderBy('name')->pluck('name', 'id');
        $staff_types = StaffType::orderBy('name')->pluck('name', 'id');
        return view('employees.edit', compact('designations', 'staff_types', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $validatedData = $request->validate([
            'name' => 'required:max:100|'.Rule::unique('employees')->ignore($employee),
            'contact_number' => 'nullable|max:100',
            'address' => 'nullable|max:100',
            'designation_id' => 'required',
            'staff_type_id' => 'required',
            'rate' => 'required|integer'
        ]);
       
        $employee->update($validatedData);

        return  redirect()->route('employees.index')->with('success', 'Employee Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return back()->with('success', 'Employee Deleted');
    }
}
