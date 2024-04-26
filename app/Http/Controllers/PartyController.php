<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\City;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PartyRequest;

class PartyController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Party::class, 'party');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $parties = Party::with(['category', 'city'])
                        ->select('id', 'name', 'category_id', 'city_id', 'type');
  
            return DataTables::of($parties)
                  ->addColumn('action', function ($party) {
                      return view('parties.buttons')->with(['party' => $party ]);
                  })->make(true);
        }

        $types = Party::types();
        return view('parties.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::OrderBy('name')->pluck('name', 'id');
        $categories = Category::OrderBy('name')->pluck('name', 'id');
        $users = User::OrderBy('username')->pluck('username', 'id');
        $types = Party::types();

        return view('parties.create', compact('cities', 'categories', 'users', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PartyRequest $request)
    {

        if($request->drcr != 'DR' && $request->drcr != 'CR'){
            return back()->withErrors(['message' => 'DR OR CR is required.'])->withInput();
        }

        DB::transaction(function () use($request){
                    
            $party = Party::create($request->validated());
            $party->createOpeningTransaction($request);
        
       });

        return  back()->with('success', 'Party Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function show(Party $party)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function edit(Party $party)
    {
        $cities = City::OrderBy('name')->pluck('name', 'id');
        $categories = Category::OrderBy('name')->pluck('name', 'id');
        $users = User::OrderBy('username')->pluck('username', 'id');
        $types = Party::types();

        return view('parties.edit', compact('party', 'cities', 'categories', 'users', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function update(PartyRequest $request, Party $party)
    {
        
        if($party->cannotRemove()){
            $party->update($request->except('name', 'drcr'));
            return back()->with('success', 'Party Updated.');
        }
                    
        $party->update($request->validated());  

        return  back()->with('success', 'Party Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function destroy(Party $party)
    {

        if($party->cannotRemove()){
            return back()->withErrors([
                'message' => 'Sale, Purchase, Self Store, Stock Tansfer accounts cannot be deleted.'
            ]);
        }
  
        $party->delete();
        return back()->with('success', 'Party Deleted.');
    }
}
