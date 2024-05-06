<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Item;
use App\Models\Party;
use App\Models\Bill;
use App\Models\TransferTransaction;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use App\Http\Requests\OrderRequest;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\Transport;
use App\Models\UpdateLogs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Order::class, 'order');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $orders = Order::orderBy('id', 'desc')->with(['party'])->where('entry_type', 1)->select();

            return DataTables::of($orders)
                ->editColumn('order_number', function ($order) {
                    return $order->type == ORDER::SALE
                        ? "S-{$order->order_number}"
                        : "P-{$order->order_number}";
                })
                ->editColumn('order_date', function ($order) {
                    return $order->order_date->format('d M, Y');
                })
                ->editColumn('due_date', function ($order) {
                    return $order->due_date->format('d M, Y');
                })
                ->editColumn('status', function ($order) {
                    return ucwords($order->status);
                })
                ->addColumn('action', function ($order) {
                    return view('orders.buttons')->with(['order' => $order]);
                })
                ->make(true);
        }

        $parties = Party::orderBy('name')->pluck('name', 'id');
        return view('orders.index', compact('parties'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parties = Party::orderBy('name')->pluck('name', 'id');
        $items = Item::orderBy('name')->pluck('name', 'id');
        $types = Order::types();
        $order_number = Order::orderNumber();

        return view('orders.create', compact('parties', 'items', 'types', 'order_number'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * ALTER TABLE `orders` ADD `wa_narration` TEXT NULL AFTER `is_paid`;
     * ALTER TABLE `transactions` ADD `extra_charges` VARCHAR(255) NULL AFTER `is_paid`;
     * ALTER TABLE `transactions` ADD `gst_amount` VARCHAR(255) NULL AFTER `is_paid`;
     * ALTER TABLE `transactions` ADD `wa_narration` VARCHAR(255) NULL AFTER `is_paid`;
     */
    public function store(OrderRequest $request)
    {
        DB::transaction(function () use ($request) {

            $order = Order::create([
                'order_date' => $request->order_date,
                'due_date' => $request->due_date,
                'order_number' => Order::orderNumber(),
                'type' => $request->type,
                'narration' => $request->narration,
                'wa_narration' => $request->wa_narration ?? '',
                'party_id' => $request->party_id,
                'status' => Order::PENDING,
            ]);

            $order->createOrderItems($request);
        });

        $type = ucfirst($request->type);

        return redirect()->route('orders.index')->with('success', "$type Order Created");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $order->load(['orderItems.item']);

        if ($order->transferTransactions()->count() > 0) {
            $order->load([
                'transferTransactions.item',
                'transferTransactions.transaction',
                'transactions' => function ($query) {
                    $query->where('amt_debt', '>', 0);
                    // ->orderBy('id', 'asc');
                },

                'transferTransactions.transport',
                'party',
            ]);
        }
        // return $order;
        // dd($order->transactions);
        $total_amount = $order->orderItems->sum('total_price');

        return view('orders.show', compact('order', 'total_amount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {

        $order->load(['orderItems', 'orderItems.item']);
        $parties = Party::orderBy('name')->pluck('name', 'id');
        $items = Item::orderBy('name')->pluck('name', 'id');
        $types = Order::types();

        return view('orders.edit', compact('order', 'parties', 'items', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(OrderRequest $request, Order $order)
    {

        $order->load('transferTransactions');
        // dd($request->all());
        if ($order->transferTransactions->count() > 0) {
            $order->update([
                'narration' => $request->narration ?? '',
                'wa_narration' => $request->wa_narration ?? '',
            ]);
            // return back()->with('success', 'Order Narration Updated.');
        }

        DB::transaction(function () use ($request, $order) {

            $order->update([
                'order_date' => $request->order_date,
                'due_date' => $request->due_date,
                'type' => $request->type,
                'narration' => $request->narration,
                'party_id' => $request->party_id,
            ]);

            // $order->orderItems()->delete();
            $order->createOrderItems($request);
        });

        $type = ucfirst($order->type);
        return redirect('orders')->with('success', "$type Order Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $type = ucfirst($order->type);
        $order->orderItems()->delete();
        $order->transactions()->delete();
        $order->transferTransactions()->delete();
        $order->delete();

        return back()->with('success', "$type Order Deleted");
    }

    public function orderSalesEdit($id)
    {
        $items = DB::table('items')->get();
        $trasan =  TransferTransaction::where('transaction_id', $id)->first();
        $order =  Order::where('id', $trasan->order_id)->first();
        $transaction =  TransferTransaction::where('transaction_id', $id)
            ->with([
                'item',
                'transport',
                'order',
                'transaction'
            ])->get();
        $transactiondata = $transaction;
        // dd($transactiondata);
        return view('orders.sales_edit')->with(['transactiondata' => $transactiondata, 'items' => $items]);
    }

    public function orderSalesEditSubmit(Request $request)
    {
        $voucher = [
            'amt_sum' => 0,
            'id' => 0,
        ];
        foreach ($request->ids as $value =>  $id) {
            $order =  TransferTransaction::where('id', $id)->first();
            $order->update([
                'rate' => $request->rates[$value],
                'created_at' => $request->dates[$value],
                'quantity' => '-' . $request->quantities[$value],
                'item_id' => $request->items[$value],
            ]);
            $voucher['amt_sum'] += (float)$order->rate * (float)(abs($order->quantity));
            $voucher['id'] = $order->transaction_id;
        }
        Transaction::where('id', $voucher['id'])->update([
            'amt_debt' => $voucher['amt_sum'],
            'narration' => $request->narration,
        ]);
        UpdateLogs::create([
            'order_id' => $order->order_id,
            'updated_by' => Auth::id(),
            'module' => UpdateLogs::SALE_MODULE
        ]);
        return redirect()->back()->with('success', 'Sales successfully updated');
    }

    public function directSale()
    {
        $parties = Party::orderBy('name')->pluck('name', 'id');
        $items = Item::orderBy('name')->pluck('name', 'id');
        $types = Order::types();
        $ordeCount = Order::orderBy('order_number', 'desc')->value('order_number');
        $transports = Transport::orderBy('name')->pluck('name', 'id');

        return view('direct_order.form', compact('parties', 'items', 'types', 'ordeCount', 'transports'));
    }

    public function directSaleStore(OrderRequest $request, Order $order)
    {
        DB::transaction(function () use ($request) {

            $order = Order::create([
                'order_date' => $request->order_date,
                'due_date' => $request->due_date,
                'order_number' => $request->order_number,
                'type' => $request->type,
                'narration' => $request->narration,
                'wa_narration' => $request->wa_narration ?? '',
                'party_id' => $request->party_id,
                'status' => Order::COMPLETE,
                'entry_type' => 2
            ]);

            // $order->createOrderItems($request);


            

            if ($request->type == 'sale') {
                $tr = Transaction::create([
                    'transaction_date' => $order->due_date,
                    'type' => 'acc',
                    'creditor_id' => $request->party_id,
                    'debitor_id' => Party::SALE,
                    'amt_credit' => $this->arraySum($request->total_prices),
                    'narration' => $order->narration,
                    'wa_narration' => $order->wa_narration,
                    'transaction_number' => Transaction::transactionNumber(),
                    'order_id' => $order->order_number,
                    'transaction_date' => Carbon::now(),
                    'is_paid' => 1
                ]);

                $tr = Transaction::create([
                    'transaction_date' => $order->due_date,
                    'type' => 'acc',
                    'creditor_id' => Party::SALE,
                    'debitor_id' => $request->party_id,
                    'amt_credit' => $this->arraySum($request->total_prices),
                    'narration' => $order->narration,
                    'wa_narration' => $order->wa_narration,
                    'transaction_number' => Transaction::transactionNumber(),
                    'order_id' => $order->order_number,
                    'transaction_date' => Carbon::now(),
                    'is_paid' => 1
                ]);

                foreach ($request->items as $i => $item_id) {

                    $orderItem = Order::find($item_id);

                    // return $orderItem->increment('received_quantity', $request->current_quantities[$i]);


                    TransferTransaction::create([
                        'item_id' => $orderItem->id,
                        'party_id' => $request->party_id ?? Party::SELF_STORE,
                        'order_id' => $order->id,
                        // 'quantity' => $order->quantity,
                        'rate' => '',
                        'type' => Order::SALE,
                        'transport_id' => $request->transport_id ?? null,
                        'bilty_number' => $request->bilty_number ?? null,
                        'vehicle_number' => $request->vehicle_number ?? null,
                        'transport_date' => $request->transport_date ?? null,
                        'transaction_id' => $tr->transaction_number ?? null,
                        'created_at' => Carbon::now()
                    ]);
                }
            }else{
                Transaction::create([
                    'transaction_date' => $order->due_date,
                    'type' => 'acc',
                    'debitor_id' => Party::RETURN,
                    'creditor_id' => $request->party_id,
                    'amt_credit' => $this->arraySum($request->total_prices),
                    'narration' => $order->narration,
                    'transaction_number' => Transaction::transactionNumber(),
                    'order_id' => $order->id,
                    'is_paid' => 1
                ]);
        
                Transaction::create([
                    'transaction_date' => $order->due_date,
                    'type' => 'acc',
                    'debitor_id' => $request->party_id,
                    'creditor_id' => Party::RETURN,
                    'amt_debt' => $this->arraySum($request->total_prices),
                    'narration' => $order->narration,
                    'transaction_number' => Transaction::transactionNumber(),
                    'order_id' => $order->id,
                    'is_paid' => 1
                ]);
            }
        });
        $type = ucfirst($request->type);


        return redirect()->route('orders.index')->with('success', " $type Order Created");
    }

    public function directSaleListing(Request $request)
    {
        if ($request->ajax()) {

            $orders = Order::orderBy('id', 'desc')->with(['party'])->where('entry_type', 2)->select();

            return DataTables::of($orders)
                ->editColumn('order_number', function ($order) {
                    return $order->type == ORDER::SALE
                        ? "SO-{$order->order_number}"
                        : "PO-{$order->order_number}";
                })
                ->editColumn('order_date', function ($order) {
                    return $order->order_date->format('d M, Y');
                })
                ->editColumn('due_date', function ($order) {
                    return $order->due_date->format('d M, Y');
                })
                ->editColumn('status', function ($order) {
                    return ucwords($order->status);
                })
                ->addColumn('action', function ($order) {
                    return view('orders.buttons')->with(['order' => $order]);
                })
                ->make(true);
        }

        $parties = Party::orderBy('name')->pluck('name', 'id');
        return view('orders.directIndex', compact('parties'));
    }

    public function orderLogs($id)
    {
        $logs = UpdateLogs::with('user')->where('order_id', $id)->orderBy('id', 'desc')->get();
        return view('orders.update_logs', compact('logs', 'id'));
    }

    public static function arraySum($total)
    {
        $totalPrice = 0;
        foreach ($total as $i) {
            $totalPrice += $i;
        }

        return $totalPrice;
    }

    public function sale_bills(Request $request)
    {
        if ($request->ajax()) {

            $orders = Order::where('type','sale')->orderBy('id', 'desc')->with(['party'])->get();

            return DataTables::of($orders)
                ->editColumn('order_number', function ($order) {
                    return $order->type == ORDER::SALE
                        ? "S-{$order->order_number}"
                        : "P-{$order->order_number}";
                })
                ->editColumn('entry_type', function ($order) {
                    return $order->entry_type == 1
                        ? "Normal Sale"
                        : "Direct Sale";
                })
                ->editColumn('order_date', function ($order) {
                    return $order->order_date->format('d M, Y');
                })
                ->editColumn('due_date', function ($order) {
                    return $order->due_date->format('d M, Y');
                })
                ->editColumn('status', function ($order) {
                    return ucwords($order->status);
                })
                ->addColumn('action', function ($order) {
                    return view('orders.bill-button')->with(['order' => $order]);
                })
                ->make(true);
        }

        $parties = Party::orderBy('name')->pluck('name', 'id');
        return view('orders.sale-bills', compact('parties'));
    }


    public function purchase_bills(Request $request)
    {
        if ($request->ajax()) {

            $orders = Order::where('type','purchase')->orderBy('id', 'desc')->with(['party'])->get();

            return DataTables::of($orders)
                ->editColumn('order_number', function ($order) {
                    return $order->type == ORDER::SALE
                        ? "S-{$order->order_number}"
                        : "P-{$order->order_number}";
                })
                ->editColumn('entry_type', function ($order) {
                    return $order->entry_type == 1
                        ? "Normal Sale"
                        : "Direct Sale";
                })
                ->editColumn('order_date', function ($order) {
                    return $order->order_date->format('d M, Y');
                })
                ->editColumn('due_date', function ($order) {
                    return $order->due_date->format('d M, Y');
                })
                ->editColumn('status', function ($order) {
                    return ucwords($order->status);
                })
                ->addColumn('action', function ($order) {
                    return view('orders.bill-button')->with(['order' => $order]);
                })
                ->make(true);
        }

        $parties = Party::orderBy('name')->pluck('name', 'id');
        return view('orders.purchase-bills', compact('parties'));
    }

    
    public function bill_purchase_create()
    {
        $parties = Party::orderBy('name')->pluck('name', 'id');
        return view('orders.create_purchase_bills', compact('parties'));

    }

    public function bill_sale_create()
    {
        $parties = Party::orderBy('name')->pluck('name', 'id');
        return view('orders.create_sale_bills', compact('parties'));

    }

    public function getOrdersByParty(Request $request)
    {
        // Retrieve partyId from the request data
        $partyId = $request->input('partyId');

        // Fetch orders associated with the specified partyId
        $orders = Order::where('party_id', $partyId)
                        ->where('type', 'purchase')
                        ->where(function ($query) {
                            $query->where('status', 'pending')
                                ->orWhere('status', 'incomplete');
                        })
                        ->get();
        // Return orders as JSON response
        return response()->json(['orders' => $orders]);
    }

    public function getOrder(Request $request)
    {
        // Retrieve partyId from the request data
        $partyId = $request->input('partyId');

        // Fetch orders associated with the specified partyId
        $orders = Order::where('party_id', $partyId)
                        ->where('type', 'sale')
                        ->where(function ($query) {
                            $query->where('status', 'pending')
                                ->orWhere('status', 'incomplete');
                        })
                        ->get();

        // Return orders as JSON response
        return response()->json(['orders' => $orders]);
    }
    
    public function fetch_item_details(Request $request)
{
    $orderNumber = $request->input('orderNumber');
    // Fetch order based on order number
    $order = Order::find($orderNumber);

    if ($order) {
        // Fetch all item details associated with the order
        $orderItems = OrderItem::where('order_id', $orderNumber)->get();

        // Array to hold item details
        $items = [];

        // Loop through each order item and fetch the item details
        foreach ($orderItems as $orderItem) {
            $item = $orderItem->item; // Access the related item
            if ($item) {
                // Include the order ID in the item details
                $order_id = $orderItem->order_id;
                $item->order_id = $order_id;
                $items[] = $item;
            }
        }

        // Return item details as JSON response
        return response()->json(['items' => $items]);
    } else {
        // Order not found, return empty response or appropriate error message
        return response()->json(['message' => 'Order not found'], 404);
    }
}
public function fetch_purchase_item_details(Request $request)
{
    $orderNumber = $request->input('orderNumber');
   
    // Fetch order based on order number
    $order = Order::find($orderNumber);

    if ($order) {
        // Fetch all item details associated with the order
        $orderItems = OrderItem::where('order_id', $orderNumber)->get();

        // Array to hold item details
        $items = [];

        // Loop through each order item and fetch the item details
        foreach ($orderItems as $orderItem) {
            $item = $orderItem->item; // Access the related item
            if ($item) {
                // Include the order ID in the item details
                $order_id = $orderItem->order_id;
                $item->order_id = $order_id;
                $items[] = $item;
            }
        }

        // Return item details with order_id as JSON response
        return response()->json(['items' => $items]);
    } else {
        // Order not found, return empty response or appropriate error message
        return response()->json(['message' => 'Order not found'], 404);
    }
}



    public function storeBills(Request $request)
    {
        $fields = $request->all();
        // echo "<pre>";
        //     print_r($fields);die;
        foreach($fields['order_id'] as $order){
            $orders = Order::where('id',$order)->get();
        } 
        $items = json_decode($fields['tableData']);
        $bill_id = 'sale-' . rand(1111, 9999);

        foreach($items as $data){

            $bill = new bill();
            $bill->order_number = $data->{0}; // Assuming item_id corresponds to the id field of stdClass object
            $bill->item_number = $data->{1}; // Assuming name corresponds to the third element of stdClass object
            $bill->total_quantity = $data->{4} - $data->{3};
            $bill->sent_quantity = $data->{3};
            $bill->rate = $data->{5}; // Assuming item_id corresponds to the id field of stdClass object
            $bill->total_price = $data->{6}; // Assuming name corresponds to the third element of stdClass object
            $bill->narration = $fields['narration'];
            $bill->whats_app_narration = $fields['wa_narration'];
            $bill->bill_id = $bill_id;
            $bill->bill_type = 'sale';

            $bill->save();

            $orderItem = OrderItem::where('item_id', $data->{1})->first();

            if ($orderItem) {
                $orderItem->decrement('ordered_quantity', $data->{3});
            }
            
    
        }
        $bills = Bill::where('bill_id', $bill_id)
                        ->join('items', 'items.id', '=', 'bills.item_number')
                        ->select('bills.*', 'items.name as item_name')
                        ->get();

        
        // Return the HTML content of the view
        $viewContent = view('orders.bill-print', compact('bills','orders'))->render();

        // Return response
        return response()->json(['html' => $viewContent]);
        
    }


    public function prints(Order $order)
    {
        $order->load(['orderItems.item']);

        if ($order->transferTransactions()->count() > 0) {
            $order->load([
                'transferTransactions.item',
                'transferTransactions.transaction',
                'transactions' => function ($query) {
                    $query->where('amt_debt', '>', 0);
                    // ->orderBy('id', 'asc');
                },

                'transferTransactions.transport',
                'party',
            ]);
        }
        // return $order;
        // dd($order);
        $total_amount = $order->orderItems->sum('total_price');

        return view('orders.print', compact('order', 'total_amount'));
    }
}
