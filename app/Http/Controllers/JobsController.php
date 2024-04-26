<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\Item;
use App\Models\Jobs;
class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    
        
        return view('jobsStock.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parties = Party::orderBy('name')->get();
        $items = Item::orderBy('name')->get();
        $getdeta = Jobs::paginate(10);
        return view('jobsStock.create',compact('parties','items','getdeta'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function isExistStock(Request $request){
        $ex = Jobs::where('party_name',$request->party_id)->where('item_name',$request->item_id)->first(); 
        if(!empty($ex)){
            return response()->json([
                'status'=>true
            ]);
        }else{
            return response()->json([
                'status'=>false
            ]);
        }
    }

    public function deleteExistStock(Request $request){
        $ex = Jobs::where('id',$request->id)->first(); 
        if(!empty($ex)){ 
            Jobs::where('id',$request->id)->delete(); 
            return response()->json([
                'status'=>true
            ]);
        }else{
            return response()->json([
                'status'=>false
            ]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'party_name' => 'required',
            'item_name' => 'required',
            'quantity' => 'required',
            'amount' => 'required',
        ]);
        $ex = Jobs::where('party_name',$request->party_name)->where('item_name',$request->item_name)->first(); 
        if(!empty($ex)){
            $newquantity = (int)$request->quantity;
            $newAmount = $request->amount;
            Jobs::where('party_name',$request->party_name)->where('item_name',$request->item_name)->update([
                'quantity'=>$newquantity,
                'quantity_acc'=>$request->quantity_acc,
                'amount'=>$newAmount,
                'amount_acc'=>$request->amount_acc,
            ]);
        }else{ 
            $user = new Jobs;
                $user->party_name = $request->party_name;
                $user->item_name = $request->item_name;
                $user->quantity = $request->quantity;
                $user->quantity_acc = $request->quantity_acc;
                $user->amount = $request->amount;
                $user->amount_acc = $request->amount_acc;
                $user->save();
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
