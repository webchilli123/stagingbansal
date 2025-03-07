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
use App\Models\BillItem;
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
                    return view('orders.buttons')->with(['order' => $order, 'direct' => '0']);
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
        return view('orders.sales_edit')->with(['transactiondata' => $transactiondata, 'items' => $items, 'order' => $order]);
    }

    public function orderSalesEditSubmit(Request $request)
    {
        $orderTable = Order::find($request->order_id);

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

            $orderItems = OrderItem::where('order_id', $order->order_id)->first();
            $orderItems->delete();
            OrderItem::create([
                'order_id' => $order->order_id,
                'item_id' => $request->items[$value],
                'ordered_quantity' => $request->quantities[$value],
                'received_quantity' => $request->quantities[$value],
                'rate' => $request->rates[$value],
                'total_price' => $request->rates[$value] * $request->quantities[$value],
            ]);
            $voucher['amt_sum'] += (float)$order->rate * (float)(abs($order->quantity));
            $voucher['id'] = $order->transaction_id;
            $voucher['type'] = $order->type;
            $voucher['stock_debt'] = (float)(abs($order->quantity));

            //
            $transaction = Transaction::find($voucher['id']);
            
            if ($voucher['type'] == 'sale') {
                $transaction->update([
                    'amt_credit' => $voucher['amt_sum'],
                    'narration' => $request->narration,
                    'stock_debt' => $voucher['stock_debt'],
                ]);
            } else {
                // $transaction->update([
                //     'amt_debt' => $voucher['amt_sum'],
                //     'narration' => $request->narration,
                // ]);
            }
        }
        $orderTable->update(['narration' => $request->narration]);


        UpdateLogs::create([
            'order_id' => $order->order_id,
            'updated_by' => Auth::id(),
            'module' => UpdateLogs::SALE_MODULE
        ]);
        return redirect(url('orders/' . $orderTable->id))->with('success', 'Sales successfully updated');
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
                // 'order_date' => $request->order_date,
                'due_date' => $request->due_date,
                'order_number' => $request->order_number,
                'type' => $request->type,
                'narration' => $request->narration,
                // 'wa_narration' => $request->wa_narration ?? '',
                'party_id' => $request->party_id,
                'status' => Order::COMPLETE,
                'entry_type' => 2
            ]);

            $order->createOrderItemsDirect($request);

            $stocks = Order::with('orderItems')->find($order->id);
            $stkCredit = 0;
            $amtCredit = 0;


            foreach ($stocks->orderItems as $stock) {
                $stkCredit += $stock->received_quantity;
                $amtCredit += $stock->total_price;
            }

            if ($request->type == 'sale') {
                $tr = Transaction::create([
                    'transaction_date' => $order->due_date,
                    'type' => 'acc',
                    'creditor_id' => $request->party_id,
                    'debitor_id' => Party::SALE,
                    'amt_debt' => $amtCredit,
                    'stock_credit' => $stkCredit,
                    'narration' => $order->narration,
                    // 'wa_narration' => $order->wa_narration,
                    'transaction_number' => Transaction::transactionNumber(),
                    'order_id' => $order->id,
                    'transaction_date' => Carbon::now(),
                    'is_paid' => 1
                ]);
            } else {
                $tr = Transaction::create([
                    'transaction_date' => $order->due_date,
                    'type' => 'acc',
                    'creditor_id' => Party::SALE,
                    'debitor_id' => $request->party_id,
                    'amt_credit' => $amtCredit,
                    'stock_debt' => $stkCredit,
                    'narration' => $order->narration,
                    // 'wa_narration' => $order->wa_narration,
                    'transaction_number' => Transaction::transactionNumber(),
                    'order_id' => $order->id,
                    'transaction_date' => Carbon::now(),
                    'is_paid' => 1
                ]);
            }
            

            foreach ($request->items as $i => $item_id) {

                $orderItem = Order::find($item_id);
                // return $orderItem->increment('received_quantity', $request->current_quantities[$i]);


                TransferTransaction::create([
                    'item_id' => $orderItem->id,
                    'party_id' => $request->party_id ?? Party::SELF_STORE,
                    'order_id' => $order->id,
                    'quantity' => $request->quantities[$i],
                    'rate' => $request->rates[$i],
                    'type' => Order::SALE,
                    'transport_id' => $request->transport_id ?? null,
                    'bilty_number' => $request->bilty_number ?? null,
                    'vehicle_number' => $request->vehicle_number ?? null,
                    'transport_date' => $request->transport_date ?? null,
                    'transaction_id' => $tr->id ?? null,
                    'created_at' => Carbon::now()
                ]);
            }
        });
        $type = ucfirst($request->type);


        return redirect()->route('direct.sale.listing')->with('success', " $type Order Created");
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
                    return view('orders.buttons')->with(['order' => $order, 'direct' => '1']);
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

            $bills = Bill::with(['party'])
                ->where('bill_type', 'sale')
                ->orderBy('id', 'DESC')
                ->get();


            return DataTables::of($bills)
                ->editColumn('bill_id', function ($bill) {
                    return ucfirst($bill->bill_id);
                })
                ->editColumn('party_name', function ($bill) {
                    return $bill->party_name;
                })
                ->editColumn('created_at', function ($bill) {
                    return $bill->created_at->format('d M, Y');
                })
                ->addColumn('action', function ($bill) {
                    return view('orders.bill-button')->with(['order' => $bill]);
                })
                ->make(true);
        }
        $parties = Party::orderBy('name')->pluck('name', 'id');

        return view('orders.sale-bills', compact('parties'));
    }


    public function purchase_bills(Request $request)
    {
        if ($request->ajax()) {

            $bills = Bill::with(['party'])
                ->where('bill_type', 'purchase')
                ->orderBy('id', 'desc')
                ->groupBy('bill_id')
                ->get();


            return DataTables::of($bills)
                ->editColumn('bill_id', function ($bill) {
                    return ucfirst($bill->bill_id);
                })
                ->editColumn('party_name', function ($bill) {
                    return $bill->party_name;
                })
                ->editColumn('created_at', function ($bill) {
                    return $bill->created_at->format('d M, Y');
                })
                ->addColumn('action', function ($bill) {
                    return view('orders.bill-button')->with(['order' => $bill]);
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
            $orderItems = OrderItem::with('item:id,name')->where('order_id', $orderNumber)->get();

            // Array to hold item details
            $items = [];

            // Loop through each order item and fetch the item details
            // foreach ($orderItems as $orderItem) {
            //     $item = $orderItem->item; // Access the related item
            //     if ($item) {
            //         // Include the order ID in the item details
            //         $order_id = $orderItem->order_id;
            //         $item->order_id = $order_id;
            //         $items[] = $item;
            //     }
            // }
            // Return item details as JSON response
            return response()->json(['items' => $orderItems]);
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

        $orders = [];
        foreach ($fields['order_id'] as $order) {
            $order = Order::where('id', $order)->first();
            if ($order) {
                $orders[] = $order;
            }
        }
        $items = json_decode($fields['tableData']);

        $bill_id = 'sale_bill#' . rand(1111, 9999);

        $bill = Bill::create([
            'bill_id' => $bill_id,
            'bill_type' => 'sale',
            'party_id' => $request->party_id,
            'narration' => $request->narration,
            'whats_app_narration' => $request->whats_app_narration,
        ]);

        if ($bill) {
            foreach ($items as $item) {
                BillItem::create([
                    'bill_id' => $bill->id,
                    'item_id' => $item->{0},
                    'order_id' => $item->{1},
                    'item' => $item->{2},
                    'sent_quantity' => $item->{3},
                    'rate' => $item->{4},
                    'price' => $item->{5},
                    'order_quantity' => $item->{6},
                    'order_price' => $item->{7},
                ]);

                $orderItem = OrderItem::find($item->{0});

                if ($item->{3} > $orderItem->ordered_quantity || $item->{3} == $orderItem->ordered_quantity) {
                    $amount = 0;
                    $status = 'complete';
                } else {
                    $amount = $orderItem->ordered_quantity - $item->{3};
                    $status = 'pending';
                }
                $orderItem->update([
                    'ordered_quantity' => $amount
                ]);
                Order::where('id', $item->{1})->update([
                    'status' => $status
                ]);
            }

            $stocks = Order::with('orderItems')->find($item->{1});
            $stkCredit = 0;
            $amtCredit = 0;


            foreach ($stocks->orderItems as $stock) {
                $stkCredit += $stock->received_quantity;
                $amtCredit += $stock->total_price;
            }

            if ($request->type == 'sale') {
                $tr = Transaction::create([
                    'transaction_date' => $order->due_date,
                    'type' => 'acc',
                    'creditor_id' => $request->party_id,
                    'debitor_id' => Party::SALE,
                    'amt_debt' => $amtCredit,
                    'stock_credit' => $stkCredit,
                    'narration' => $order->narration,
                    // 'wa_narration' => $order->wa_narration,
                    'transaction_number' => Transaction::transactionNumber(),
                    'order_id' => $order->id,
                    'transaction_date' => Carbon::now(),
                    'is_paid' => 1
                ]);
            } else {
                $tr = Transaction::create([
                    'transaction_date' => $order->due_date,
                    'type' => 'acc',
                    'creditor_id' => Party::SALE,
                    'debitor_id' => $request->party_id,
                    'amt_credit' => $amtCredit,
                    'stock_debt' => $stkCredit,
                    'narration' => $order->narration,
                    // 'wa_narration' => $order->wa_narration,
                    'transaction_number' => Transaction::transactionNumber(),
                    'order_id' => $order->id,
                    'transaction_date' => Carbon::now(),
                    'is_paid' => 1
                ]);
            }


            
        }

        $totals = BillItem::where('bill_id', $bill->id)->sum('price');
        $bills = Bill::with('billItems', 'party')->find($bill->id);

        $viewContent = view('orders.bill-print', compact('bills', 'orders', 'totals'))->render();

        return response()->json(['html' => $viewContent]);
    }

    public function storePurchaseBills(Request $request)
    {
        $fields = $request->all();
        $orders = [];
        foreach ($fields['order_id'] as $order) {
            $order = Order::where('id', $order)->first();
            if ($order) {
                $orders[] = $order;
            }
        }

        $items = json_decode($fields['tableData']);

        $bill_id = 'purchase_bill#' . rand(1111, 9999);

        $bill = Bill::create([
            'bill_id' => $bill_id,
            'bill_type' => 'purchase',
            'party_id' => $request->party_id,
            'narration' => $request->narration,
            'whats_app_narration' => $request->whats_app_narration,
        ]);

        if ($bill) {
            foreach ($items as $item) {
                BillItem::create([
                    'bill_id' => $bill->id,
                    'item_id' => $item->{0},
                    'order_id' => $item->{1},
                    'item' => $item->{2},
                    'sent_quantity' => $item->{3},
                    'rate' => $item->{4},
                    'price' => $item->{5},
                    'order_quantity' => $item->{6},
                    'order_price' => $item->{7},
                ]);
            }

            Transaction::create([
                'transaction_date' => Carbon::now(),
                'type' => 'acc',
                'debitor_id' => Party::RETURN,
                'creditor_id' => $request->party_id,
                // 'amt_credit' => $this->arraySum($request->total_prices),
                'narration' => $request->narration,
                'transaction_number' => Transaction::transactionNumber(),
                'order_id' => implode(',', $request->order_id),
                'is_paid' => 1
            ]);

            Transaction::create([
                'transaction_date' => Carbon::now(),
                'type' => 'acc',
                'debitor_id' => $request->party_id,
                'creditor_id' => Party::RETURN,
                // 'amt_debt' => $this->arraySum($request->total_prices),
                'narration' => $request->narration,
                'transaction_number' => Transaction::transactionNumber(),
                'order_id' => implode(',', $request->order_id),
                'is_paid' => 1
            ]);
        }

        // foreach($items as $data){

        //     $bill = new Bill();
        //     $bill->order_number = $data->{1}; 
        //     $bill->item_number = $data->{0}; 
        //     $bill->total_quantity = $data->{6};
        //     $bill->sent_quantity = $data->{3};
        //     $bill->rate = $data->{4}; 
        //     $bill->total_price = $data->{3} * $data->{4}; 
        //     $bill->narration = $fields['narration'];
        //     $bill->whats_app_narration = $fields['wa_narration'];
        //     $bill->bill_id = $bill_id;
        //     $bill->bill_type = 'purchase';
        //     $bill->party_id = $fields['party_id'];

        //     $bill->save();


        // }

        $totals = BillItem::where('bill_id', $bill->id)->sum('price');

        $bills = Bill::with('billItems', 'party')->find($bill->id);


        // Return the HTML content of the view
        $viewContent = view('orders.bill-print', compact('bills', 'orders', 'totals'))->render();

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

    public function bill_prints(Request $request)
    {
        $bill_id = $request->all();

        $bills = Bill::with('billItems', 'party')->where('bill_id', $bill_id['order'])
            ->orderBy('id', 'DESC')
            ->groupBy('order_number')
            ->first();

        $totals = BillItem::where('bill_id', $bills->id)->sum('price');

        return view('orders.bill-print-outside', compact('bills', 'totals'));
    }
}
