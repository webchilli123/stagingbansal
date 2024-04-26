<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class CityController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(City::class, 'city');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $cities = City::select();
  
            return Datatables::of($cities)
                  ->addColumn('action', function ($city) {
                      return view('cities.buttons')->with(['city' => $city ]);
                  })->make(true);
        }

        return view('cities.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|max:40|unique:cities']);
       
        City::create(['name' => $request->name]);

        return redirect()->route('cities.index')->with('success', 'City Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Modes\City $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Modes\City $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        return view('cities.edit', ['city' => $city]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modes\City $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        $request->validate([
            'name' => 'required|max:40|'.Rule::unique('cities')->ignore($city)
        ]);
       
        $city->update(['name' => $request->name]);

        return  redirect()->route('cities.index')->with('success', 'City Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Modes\City $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        $city->delete();
        return back()->with('success', 'City Deleted');
    }
}
