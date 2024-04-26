<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransferTransaction;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\Party;
use App\Models\Item;
use App\Models\Category;
use App\Models\City;
use App\Models\Employee;
use App\Models\OrderItem;
use App\Models\Transport;
use DB;
use Auth;

class ApiController extends Controller
{

    public function login(Request $request)
    {
     $rules=array(
          "username"=>"required ",
          "password" => "required",
          "role_id"=>"required ",
        );
         $validator= Validator::make($request->all(),$rules);
         if ($validator->fails()) {
             return $validator->errors();
         }

        if (!auth()->attempt($request->all())) {
            return response(['error_message' => 'Incorrect Details. 
            Please try again']);
        }

        $token = auth()->user()->createToken('API Token')->accessToken;

        return response(['massage' => 'User Login Succsefully','user' => auth()->user(), 'token' => $token]);
    }

    public function orders(Request $request)
    {   

        $ordersdata = DB::select("select orders.id, orders.order_number,orders.order_date,parties.name FROM `orders` LEFT JOIN parties ON orders.party_id = parties.id WHERE status IN('in complete','complete') AND orders.type='sale' ORDER BY order_date DESC");

        return  response([
                'data' => $ordersdata,
                
            ]);
    }


    public function orderDetails(Request $request)
    {   

        $orderdata = Order::
        where('id',$request->id)
        ->select('order_number','id','party_id')
        ->first();
        $party_data = [];
        if (!empty($orderdata)) {
            $getparty = Party::where('id',$orderdata->party_id)->get();
            foreach($getparty as $key =>$value){
                $party_data[] = [
                    'name' =>$value->name
                ];
            }
        }
         $transactions_data = [];
        if (!empty($orderdata)) {
        $transactionsTranscation = TransferTransaction::where('order_id',$orderdata->id)->groupBy('bilty_number')->get();
        // dd($transactionsTranscation);
        foreach($transactionsTranscation as $key =>$value){
            $fatchtransport = Transport::where('id',$value->transport_id)->first();
            $transactions_data[] = [
                'id'=>$value->id,
                'bilty_number'=>$value->bilty_number,
                'image'=>$value->image,
                'transport_date'=>$value->transport_date->format('d/m/Y'),
                'name'=>$fatchtransport->name
            ];
        }
        }
        $orderdata['party_data'] = $party_data;
        $orderdata['transactions_data'] = $transactions_data;
        

        return  response([
                'data' => $orderdata,
                
            ]);
    }

    public function orderPartyName(Request $request)
    {   

        if (!empty($request->name)) {
            $condations = "WHERE parties.name="."'$request->name'";
        }
        $orderdata = DB::select("select orders.id,orders.order_number,orders.order_date,parties.name FROM `orders` LEFT JOIN parties ON orders.party_id = parties.id $condations");

        return  response([
                'data' => $orderdata,
                
            ]);
    }


    public function bilty_image(Request $request)
    {   
        $page = TransferTransaction::where('bilty_number',$request->bilty_number)->first();
        if(!$page){
            return  response([
                'message' => 'No record found',
                'data' => [],
                
            ]);
        }
        $input = $request->all();
            $file= $request->file('image');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('/images'), $filename);
            $input['image']= $filename;

        $page->update($input);
        $page = TransferTransaction::where('bilty_number',$request->bilty_number)->first();
        return  response([
                'data' => $page,
                
            ]);
    }

    public function item(){
        $Item = Item::get();

        return response([
            'data' => $Item,
        ]);

    }    
    public function party(){
        $party = Party::get();

        return response([
            'data' => $party,
        ]);

    }    
    public function category(){
        $category = Category::get();

        return response([
            'data' => $category,
        ]);

    }
    public function cities(){
        $cities = City::get();
        
        return response([
            'data' => $cities,
        ]);

    }
    public function employe(){
        $employe = Employee::get();
        
        return response([
            'data' => $employe,
        ]);

    }

    public function saleOrder(Request $request){
        $data = [];
        $party = Party::where('name',$request->party_id)->first();
        $order = Order::create([
                'order_date' => $request->order_date,
                'order_number' => Order::orderNumber(),
                'type' => 'sale',
                'party_id' => $party->id,
                'status' => Order::PENDING,
            ]);  
            $transactionsTranscation = Order::where('id',$order->id)->get();
            foreach ($request->data as $key => $value) {
            $Itemdata = Item::where('name',$value['item_id'])->first();
            $OrderItem = new OrderItem();
            $OrderItem->order_id = $order->id;
            $OrderItem->item_id = $Itemdata->id;
            $OrderItem->ordered_quantity = $value['ordered_quantity'];
            $OrderItem->save();
            array_push($data,$OrderItem);
        }

        $order['data'] = $data;

           return response([
            'data' => $order,
        ]);

        
    }
}
