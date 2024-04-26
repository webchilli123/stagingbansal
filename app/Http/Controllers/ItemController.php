<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Item::class, 'item');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $items = Item::select();
  
            return DataTables::of($items)
                  ->addColumn('action', function ($item) {
                      return view('items.buttons')->with(['item' => $item]);
                  })->make(true);
        }

        return view('items.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:items|max:150','quantity'=>'required|numeric']);
       
        Item::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'user_id' => auth()->user()->id,
            'company_name' => $request->company_name,
            'master_id' => $request->master_id,
            'part_number' => $request->part_number,
            'group' => $request->group,
            'item_alias' => $request->item_alias,
            'category' => $request->category,
            'hsn_code' => $request->hsn_code,
            'tax_rate' => $request->tax_rate
        ]);

        return  redirect()->route('items.index')->with('success', 'Item Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {

        return view('items.edit', [ 'item' => $item ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|max:150|'.Rule::unique('items')->ignore($item)
        ]);
       
        $item->update(['name' => $request->name,
        'quantity' => $request->quantity,
        'user_id' => auth()->user()->id,
        'company_name' => $request->company_name,
        'master_id' => $request->master_id,
        'part_number' => $request->part_number,
        'group' => $request->group,
        'item_alias' => $request->item_alias,
        'category' => $request->category,
        'hsn_code' => $request->hsn_code,
        'tax_rate' => $request->tax_rate]);

        return  redirect()->route('items.index')->with('success', 'Item Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
       
        if($item->orderItems()->count() > 0){
            return back()->withErrors(['message' => 'Item cannot be deleted as used in Orders.']);
        }

        if($item->transferItems()->count() > 0){
            return back()->withErrors(['message' => 'Item cannot be deleted as used in Transfers.']);
        }

        $item->delete();
        return back()->with('success', 'Item Deleted');
    }
}
