<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/linkstorage', function () {
    Artisan::call('storage:link', [] );
});
Route::redirect('/', '/login');

// login
Route::get('/login', [Controllers\LoginController::class, 'create'])->name('login');
Route::post('/login', [Controllers\LoginController::class, 'store']);

// logout
Route::match(['get', 'post'], '/logout', Controllers\LogoutController::class)
    ->name('logout')
    ->middleware('auth');

// user check
Route::match(['get', 'post'], 'user-check', Controllers\UserCheckController::class)->name('user.check');


Route::middleware('auth')->group(function () {

    // dashboard
    Route::get('/dashboard', Controllers\DashboardController::class)->name('dashboard');

    // users
    Route::resource('users', Controllers\UserController::class);

    // roles
    Route::resource('roles', Controllers\RoleController::class);

    // permissions
    Route::resource('permissions', Controllers\PermissionController::class);

    // items
    Route::resource('items', Controllers\ItemController::class);

    // categories
    Route::resource('categories', Controllers\CategoryController::class);

    // cities
    Route::resource('cities', Controllers\CityController::class);

    // parties
    Route::resource('parties', Controllers\PartyController::class);

    // processes
    Route::resource('processes', Controllers\ProcessController::class);

    // designations
    Route::resource('designations', Controllers\DesignationController::class);

    // transports
    Route::resource('transports', Controllers\TransportController::class);

    // staff-types
    Route::resource('staff-types', Controllers\StaffTypeController::class)
    ->parameters(['staff-types' => 'type']);

    // employees
    Route::resource('employees', Controllers\EmployeeController::class);

    // employees attendance
    Route::resource('employee-attendances', Controllers\EmployeeAttendanceController::class)
    ->parameters(['employee-attendances' => 'attendance']);

    // salary vouchers
    Route::resource('salary-vouchers', Controllers\SalaryVoucherController::class)
    ->parameters(['salary-vouchers' => 'voucher']);


    // order transfer (receive / send) order material
    Route::get('orders/{order}/transfer', [Controllers\OrderTransferController::class, 'create'])
        ->name('orders.transfer.create');

    Route::post('orders/{order}/transfer', [Controllers\OrderTransferController::class, 'store'])
    ->name('orders.transfer.store');
    Route::get('order-sales-edit/{orderId}',[Controllers\OrderController::class, 'orderSalesEdit'])->name('orders.sales.edit');
    Route::post('order-sales-edit',[Controllers\OrderController::class, 'orderSalesEditSubmit'])->name('orders.sales.submit');
    Route::get('direct-sale', [Controllers\OrderController::class, 'directSale'])->name('direct.sale.create');
    Route::post('direct-sale', [Controllers\OrderController::class, 'directSaleStore'])->name('direct.sale.store');
    Route::get('direct-sale-lisitng', [Controllers\OrderController::class, 'directSaleListing'])->name('direct.sale.listing');
    Route::get('order-logs/{id}', [Controllers\OrderController::class, 'orderLogs'])->name('orders.logs');
    // orders - sale & purchase
    Route::resource('orders', Controllers\OrderController::class);


    Route::resource('jobs', Controllers\JobsController::class);
    Route::get('isExistStock', [Controllers\JobsController::class,'isExistStock']);
    Route::get('deleteExistStock', [Controllers\JobsController::class,'deleteExistStock']);

    // orders bills
    Route::get('order-sale-bills', [Controllers\OrderController::class, 'sale_bills'])->name('order.sale.bills');
    Route::get('order-purchase-bills', [Controllers\OrderController::class, 'purchase_bills'])->name('order.purchase.bills');
    Route::get('order-prints', [Controllers\OrderController::class, 'prints'])->name('order.prints');
    Route::get('bill-purchase-create', [Controllers\OrderController::class, 'bill_purchase_create'])->name('bill.purchase.create');
    Route::get('bill-sale-create', [Controllers\OrderController::class, 'bill_sale_create'])->name('bill.sale.create');
    Route::get('add-bill', [Controllers\OrderController::class, 'getOrdersByParty'])->name('get.order.by.party');
    Route::get('add-sale-bill', [Controllers\OrderController::class, 'getOrder'])->name('get.order');
    Route::get('fetch-item-details', [Controllers\OrderController::class, 'fetch_item_details'])->name('fetch.item.details');

    


    // account vouchers
    Route::resource('account-vouchers', Controllers\AccountVoucherController::class)
    ->parameters(['account-vouchers' => 'transaction']);

     // multiple account voucher
    Route::prefix('multiple')->name('multiple.')->group(function(){

        Route::resource('account-vouchers', Controllers\MultipleAccountVoucherController::class)
        ->only(['create', 'store', 'index'])
        ->parameters(['account-vouchers' => 'transaction']);
    });


    // stock vouchers
    Route::resource('stock-vouchers', Controllers\StockVoucherController::class)
    ->parameters(['stock-vouchers' => 'transaction']);


    // ledger 1 - pagination
    Route::get('ledger', Controllers\LedgerController::class)->name('ledger.index');

    // ledger 2 - lazy load
    Route::get('ledger2', Controllers\Ledger2Controller::class)->name('ledger2.index');

    // ledger 3 - show last 30 days data
    Route::get('ledger3', Controllers\Ledger3Controller::class)->name('ledger3.index');


    // transfer material receive
    Route::get('transfers/receive', [Controllers\TransferReceiveController::class, 'create'])
            ->name('transfers.receive.create');

    // transfer material receive
    Route::post('transfers/receive', [Controllers\TransferReceiveController::class, 'store'])
    ->name('transfers.receive.store');

    // transfer material (items)
    Route::resource('transfers', Controllers\TransferController::class);

    // Send WahtsApp sale bill
    Route::match(['get', 'post'], '/sendmessage/{order}', Controllers\OrderWhatsappController::class)->name('sendmessage');


    // party stock report
    Route::get('pdfpreview/{id?}', [Controllers\PdfPreview::class,'pdfpreview'])->name('pdfpreview');


    Route::get('location-stock', Controllers\LocationStockController::class)->name('location-stock.index');

     // party stock
    Route::get('party-stock', Controllers\PartyStockController::class)->name('party-stock.index');


    Route::get('itemwise-stock', Controllers\ItemwisestockCntroller::class)->name('itemwise-stock.index');

    Route::get('narration', Controllers\NarrationController::class)->name('narration.index');

    // item stock
    Route::get('item-stock', Controllers\ItemStockController::class)->name('item-stock.index');
  // item location stock
     Route::get('itemlocationstock', Controllers\ItemLocationStockController::class)->name('itemLocationStock.index');

    // transfer transactions
    Route::get('transfer-transactions', Controllers\TransferTransactionController::class)
        ->name('transfer-transactions.index');

    Route::get('item-LocationStock', Controllers\ItemLocationConrtroller::class)
->name('item-LocationStock.index');

    // check stock
    Route::get('stock-check', Controllers\StockCheckController::class)->name('stock.check');
    Route::get('checkClient', [Controllers\StockCheckController::class,'checkClient'])->name('stock.checkClient');

    // mark vouchers as paid
    Route::post('mark-transactions', Controllers\TransactionMarkController::class)->name('transactions.mark');

    // order transports
    Route::get('order-transports', Controllers\OrderTransportController::class)->name('order-transports.index');

    // storage link
    Route::get('storage-link', Controllers\StorageLinkController::class)->name('storage.link.create');

});



