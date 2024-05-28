<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    protected $dates = ['order_date', 'payment_date', 'due_date'];

    protected $appends = [
        'entry_type_name',
        'order_date_formatted'
    ];

    // order status
    const PENDING = 'pending';
    const IN_COMPLETE = 'in complete';
    const COMPLETE = 'complete';

    // types
    const SALE = 'sale';
    const PURCHASE = 'purchase';


    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id')->select('id', 'name', 'address', 'phone');
    }


    // order types
    public static function types()
    {
        return [ self::SALE , self::PURCHASE ];
    }

    public static function orderNumber()
    {
        return self::doesntExist()
         ? 1001
         : self::max('order_number') + 1;
    }


    public function updateOrderStatus()
    {
        $orderedQuantityTotal =  $this->orderItems->sum('ordered_quantity');
        $receivedQuantityTotal =  $this->orderItems->sum('received_quantity');

        ($orderedQuantityTotal > $receivedQuantityTotal)
          ? $this->update(['status' => self::IN_COMPLETE])
          : $this->update(['status' => self::COMPLETE]);
    }


    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }


    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'order_id');
    }


    public function GetNarson()
    {
        return $this->hasOne(Transaction::class, 'order_id','id');
    }

    public function transferTransactions()
    {
        return $this->hasMany(TransferTransaction::class, 'order_id')->orderBy('id', 'asc');
    }

    public function transferTransactionsGet()
    {
        return $this->hasMany(TransferTransaction::class, 'order_id','id');
    }
    public function createOrderItems($request)
    {
        if($request->deleted_items){
            foreach ($request->deleted_items as $k => $deleted_items) {
                $this->orderItems()->where('id',$deleted_items)->delete();
            }
        }
        foreach ($request->items as $i => $item) {
            if(!empty($request->old_id[$i])){
                  $this->orderItems()->where('id',$request->old_id[$i])->update([
                      'item_id' => $item,
                      'ordered_quantity' => $request->quantities[$i],
                      'rate' => $request->rates[$i],
                      'total_price' => $request->total_prices[$i],
                    ]);
            }else{
                $this->orderItems()->create([
                    'item_id' => $item,
                    'ordered_quantity' => $request->quantities[$i],
                    'rate' => $request->rates[$i],
                    'total_price' => $request->total_prices[$i],
                ]);
            }
        }
    }
    public function createOrderItemsDirect($request)
    {
        if($request->deleted_items){
            foreach ($request->deleted_items as $k => $deleted_items) {
                $this->orderItems()->where('id',$deleted_items)->delete();
            }
        }
        foreach ($request->items as $i => $item) {
            if(!empty($request->old_id[$i])){
                  $this->orderItems()->where('id',$request->old_id[$i])->update([
                      'item_id' => $item,
                      'ordered_quantity' => $request->quantities[$i],
                      'received_quantity' => $request->quantities[$i],
                      'rate' => $request->rates[$i],
                      'total_price' => $request->total_prices[$i],
                    ]);
            }else{
                $this->orderItems()->create([
                    'item_id' => $item,
                    'ordered_quantity' => $request->quantities[$i],
                    'received_quantity' => $request->quantities[$i],
                    'rate' => $request->rates[$i],
                    'total_price' => $request->total_prices[$i],
                ]);
            }
        }
    }
    

    public function makeVoucherDescription($request){

        $gst_amount = "GST Amount : $request->gst_amount";
        $extra_charges = "Extra Charges : $request->extra_charges";

      return  $request->filled('voucher_narration')
            ? "Order No. {$this->order_number} - {$gst_amount} - {$extra_charges} - {$request->voucher_narration}"
            : "Order No. {$this->order_number}";
    }


    public function createSaleAccountVoucher($request)
    {
        $description = $this->makeVoucherDescription($request);
        $transactionNumber = Transaction::transactionNumber();

       $tr = Transaction::create([
            'transaction_date' => $request->payment_date,
            'type' => 'acc',
            'creditor_id' => $this->party_id,
            'debitor_id' => Party::SALE,
            'amt_credit' => $request->payment_amount,
            'extra_charges' => $request->extra_charges,
            'gst_amount' => $request->gst_amount,
            'narration' => $description,
            'wa_narration' => $request->wa_narration,
            'transaction_number' => $transactionNumber,
            'order_id' => $this->id
        ]);

        Transaction::create([
            'transaction_date' => $request->payment_date,
            'type' => 'acc',
            'creditor_id' => Party::SALE,
            'debitor_id' => $this->party_id,
            'amt_debt' => $request->payment_amount,
            'extra_charges' => $request->extra_charges,
            'gst_amount' => $request->gst_amount,
            'narration' => $description,
            'wa_narration' => $request->wa_narration,
            'transaction_number' => $transactionNumber,
            'order_id' => $this->id
        ]);
        return $tr;
    }

    public function createReturnSaleAccountVoucher($request)
    {
        // dd(Party::RETURN);
        $description = $this->makeVoucherDescription($request);
        $transactionNumber = Transaction::transactionNumber();

       $tr = Transaction::create([
            'transaction_date' => $request->payment_date,
            'type' => 'acc',
            'creditor_id' => $this->party_id,
            'debitor_id' => Party::RETURN,
            'amt_debt' => $request->payment_amount,
            'extra_charges' => $request->extra_charges,
            'gst_amount' => $request->gst_amount,
            'narration' => $description,
            'wa_narration' => $request->wa_narration,
            'transaction_number' => $transactionNumber,
            'order_id' => $this->id
        ]);

        Transaction::create([
            'transaction_date' => $request->payment_date,
            'type' => 'acc',
            'creditor_id' => Party::RETURN,
            'debitor_id' => $this->party_id,
            'amt_credit' => $request->payment_amount,
            'extra_charges' => $request->extra_charges,
            'gst_amount' => $request->gst_amount,
            'narration' => $description,
            'wa_narration' => $request->wa_narration,
            'transaction_number' => $transactionNumber,
            'order_id' => $this->id
        ]);
        return $tr;
    }


    public function createPurchaseAccountVoucher($request)
    {
        $description = $this->makeVoucherDescription($request);
        $transactionNumber = Transaction::transactionNumber();

        Transaction::create([
            'transaction_date' => $request->payment_date,
            'type' => 'acc',
            'debitor_id' => Party::PURCHASE,
            'creditor_id' => $this->party_id,
            'amt_debt' => $request->payment_amount,
            'narration' => $description,
            'transaction_number' => $transactionNumber,
            'order_id' => $this->id
        ]);

        Transaction::create([
            'transaction_date' => $request->payment_date,
            'type' => 'acc',
            'debitor_id' => $this->party_id,
            'creditor_id' => Party::PURCHASE,
            'amt_credit' => $request->payment_amount,
            'narration' => $description,
            'transaction_number' => $transactionNumber,
            'order_id' => $this->id
        ]);
    }

    public function createReturnPurchaseAccountVoucher($request)
    {
        $description = $this->makeVoucherDescription($request);
        $transactionNumber = Transaction::transactionNumber();

        Transaction::create([
            'transaction_date' => $request->payment_date,
            'type' => 'acc',
            'debitor_id' => Party::RETURN,
            'creditor_id' => $this->party_id,
            'amt_credit' => $request->payment_amount,
            'narration' => $description,
            'transaction_number' => $transactionNumber,
            'order_id' => $this->id
        ]);

        Transaction::create([
            'transaction_date' => $request->payment_date,
            'type' => 'acc',
            'debitor_id' => $this->party_id,
            'creditor_id' => Party::RETURN,
            'amt_debt' => $request->payment_amount,
            'narration' => $description,
            'transaction_number' => $transactionNumber,
            'order_id' => $this->id
        ]);
    }

    // update sale or purchase order stock
    public function updateStock($request,$transaction){

        foreach ($request->items_id as $i => $item_id) {

            $orderItem = $this->orderItems->find($item_id);

            // update received quantity of order
            $orderItem->increment('received_quantity', $request->current_quantities[$i]);

            // -ve transaction on sale or +ve on purchase
            $this->type == Order::SALE
            ? $quantity = "-{$request->current_quantities[$i]}"
            : $quantity = $request->current_quantities[$i];

            // stock transfer transaction
            TransferTransaction::create([
                'item_id' => $orderItem->item_id,
//                'party_id' => Party::SELF_STORE,
                'party_id' => $request->transfer_to ?? Party::SELF_STORE,
                'order_id' => $this->id,
                'quantity' => $quantity,
                'rate' => $orderItem->rate,
                'type'=> $this->type,
                'transport_id'=> $request->transport_id ?? null,
                'bilty_number'=> $request->bilty_number ?? null,
                'vehicle_number'=> $request->vehicle_number ?? null,
                'transport_date'=> $request->transport_date ?? null,
                'transaction_id'=> $transaction->id ?? null,
                'created_at' => $request->payment_date
            ]);
        }
    }
    // update sale or purchase order stock
    public function updateReturnStock($request,$transaction){
        foreach ($request->items_id as $i => $item_id) {
            $orderItem = $this->orderItems->find($item_id);
            $orderItem->decrement('received_quantity', $request->current_quantities[$i]);
            $this->type == Order::SALE
            ? $quantity = "{$request->current_quantities[$i]}"
            : $quantity = "-{$request->current_quantities[$i]}";
            // stock transfer transaction
            TransferTransaction::create([
                'item_id' => $orderItem->item_id,
                'party_id' => Party::RETURN,
                'order_id' => $this->id,
                'quantity' => $quantity,
                'rate' => $orderItem->rate,
                'type'=> $this->type,
                'transport_id'=> $request->transport_id ?? null,
                'bilty_number'=> $request->bilty_number ?? null,
                'vehicle_number'=> $request->vehicle_number ?? null,
                'transport_date'=> $request->transport_date ?? null,
                'transaction_id'=> $transaction->id ?? null,
                'created_at' => $request->payment_date,
                'return' => 1
            ]);
        }
    }

    public function getEntryTypeNameAttribute() {
        return ($this->entry_type == 1) ? 'Regular' : 'Direct';
    }

    public function getOrderDateFormattedAttribute(){
        return Carbon::parse($this->order_date)->format('d M, Y');
    }

    

}
