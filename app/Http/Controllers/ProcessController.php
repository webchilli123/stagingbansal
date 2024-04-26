<?php

namespace App\Http\Controllers;

use App\Models\Process;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class ProcessController extends Controller
{

     public function __construct()
    {
        $this->authorizeResource(Process::class, 'process');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

           $processes = Process::select();
  
            return DataTables::of($processes)
                  ->addColumn('action', function ($process) {
                      return view('processes.buttons')->with(['process' =>$process]);
                  })->make(true);
        }

        return view('processes.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('processes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|max:50|unique:processes']);
       
        Process::create(['name' => $request->name]);

        return  redirect()->route('processes.index')->with('success', 'Process Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Process $process
     * @return \Illuminate\Http\Response
     */
    public function show(Process $process)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Process $process
     * @return \Illuminate\Http\Response
     */
    public function edit(Process $process)
    {
        return view('processes.edit', [ 'process' =>$process ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Process $process
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Process $process)
    {
        $request->validate([
            'name' => 'required|max:50|'.Rule::unique('processes')->ignore($process)
        ]);
       
       $process->update(['name' => $request->name]);

        return  redirect()->route('processes.index')->with('success', 'Process Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Process $process
     * @return \Illuminate\Http\Response
     */
    public function destroy(Process $process)
    {
       $process->delete();
        return back()->with('success', 'Process Deleted');
    }
}
