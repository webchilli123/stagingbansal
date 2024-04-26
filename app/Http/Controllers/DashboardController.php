<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function countStatuses($orders) {
        $pendingCount = 0;
        $completeCount = 0;
        $inCompleteCount = 0;
        
        foreach ($orders as $order) {
            switch ($order->status) {
                case 'pending':
                    $pendingCount++;
                    break;
                case 'complete':
                    $completeCount++;
                    break;
                case 'in complete':
                    $inCompleteCount++;
                    break;
            }
        }
        
        return [$pendingCount, $completeCount, $inCompleteCount];
    }

    public function __invoke(Request $request)
    {
        $sale_orders = Order::where('type', 'sale')
                             ->whereDate('due_date', date('Y-m-d'))
                             ->with('party')
                             ->get();

        $purchase_orders = Order::where('type', 'purchase')
                    ->whereDate('due_date', date('Y-m-d'))
                    ->with('party')
                    ->get();
        
        $graphSale = Order::where('type', 'sale')->get();
        $saleOutput = $this->countStatuses($graphSale);
        
        $graphPurchase = Order::where('type', 'purchase')->get();
        $purchaseOutput = $this->countStatuses($graphPurchase);

        return view('dashboard.show', compact('sale_orders', 'purchase_orders','saleOutput','purchaseOutput'));
    }
}
