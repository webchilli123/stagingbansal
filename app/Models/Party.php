<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];
    
    
    // parties ids
    const RETURN = 1001;
    const SALE = 15;
    const PURCHASE = 116;
    const SELF_STORE = 4029;
    const STOCK_TRANSFER = 3978;

   //INSERT INTO `parties` (`id`, `name`, `opening_balance`, `address`, `city_id`, `phone`, `mobile`, `email`, `fax`, `url`, `tin_number`, `category_id`, `note`, `type`, `user_id`) VALUES ('1001', 'Return', '0', '', '1', '', '', '', '', '', '', '0', '', '', '1');
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->withDefault(['name' => '-']);
    }
   
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id')->withDefault(['name' => '-']);
    }
    
    // parties types
    
    public static function types(){
        return [
            'ACC' => 'Account', 
            'INV' => 'Stock', 
            'EMP' => 'Employee', 
            'DEB' => 'Debitor',
            'CRE' => 'Creditor'
        ];
    }

    // create opening transaction for party
    public function createOpeningTransaction($request)
    {
        $data = [
            'transaction_date' => date('Y-m-d'),
            'debitor_id' => $this->id,
            'narration' => 'Opening',
            'transaction_number' => Transaction::transactionNumber(),
        ];
        
        if ($request->type == 'ACC' || $request->type == 'EMP'
        || $request->type == 'DEB' || $request->type == 'CRE') {

            $data['type'] = 'acc';
            $request->drcr == 'CR'
                ? $data['amt_credit'] = $request->opening_balance
                : $data['amt_debt'] = $request->opening_balance;

        } else {

            $data['type'] = 'inv';
            $request->drcr == 'CR'
                ? $data['stock_credit'] = $request->opening_balance
                : $data['stock_debt'] = $request->opening_balance;
        }

        Transaction::create($data);

    }

    // remove opening transaction of party
    public function deleteOpeningTransaction(){

        Transaction::where([
            ['debitor_id', $this->id],
            ['narration', 'Opening'],
        ])->delete();

    }
    
    public function cannotRemove(){

        if($this->id == Party::SALE 
        || $this->id == Party::PURCHASE 
        || $this->id == party::SELF_STORE
        || $this->id == party::STOCK_TRANSFER){
            return true;
        }
         
    }

}
