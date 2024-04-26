<?php

namespace App\Http\Controllers;

use App\Models\StaffType;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class StaffTypeController  extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $types = StaffType::select();
  
            return DataTables::of($types)
                  ->addColumn('action', function ($type) {
                      return view('staff-types.buttons')->with(['type' => $type]);
                  })->make(true);
        }

        return view('staff-types.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('staff-types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|max:50|unique:staff_types']);
       
        StaffType::create(['name' => $request->name]);

        return  redirect()->route('staff-types.index')->with('success', 'Staff Type Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StaffType  $type
     * @return \Illuminate\Http\Response
     */
    public function show(StaffType $type)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StaffType  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(StaffType $type)
    {
        return view('staff-types.edit', [ 'type' => $type ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StaffType  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StaffType $type)
    {
        $request->validate([
            'name' => 'required|max:50|'.Rule::unique('staff_types')->ignore($type)
        ]);
       
        $type->update([ 
            'name' => $request->name,
         ]);

        return  redirect()->route('staff-types.index')->with('success', 'Staff Type Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StaffType  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaffType $type)
    {
        $type->delete();
        return back()->with('success', 'Staff Type Deleted');
    }
}
