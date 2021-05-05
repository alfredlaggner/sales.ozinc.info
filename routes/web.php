<?php

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

if (App::environment('local')) {
    URL::forceScheme('http');
}
/*Route::get('join', function () {
    $query = \App\Payment::where('payment_type','=','inbound')
        ->where('has_invoices','=', 1)
        ->whereBetween('payment_date', ['2020-05-01','2020-05-31'])
        ->rightJoin('salesorders', 'salesorders.ext_id', '=', 'payments.invoice_ids')
        ->whereNull('salesorders.ext_id')
        ->get();
    dd($query);
});
Route::get('xjoin', function () {
    $query = \App\Invoices::where('state','=','paid')
        ->where('type','=', 1)
        ->whereBetween('payment_date', ['2020-05-01','2020-05-31'])
        ->leftJoin('salesorders', 'salesorders.ext_id', '=', 'payments.invoice_ids')
        ->whereNull('salesorders.ext_id')
        ->get();
    dd($query);
});*/
//WHERE `state` = 'paid' and `payment_date` BETWEEN '2020-05-01' AND '2020-05-31' and `type` = 'out_invoice' and `ext_id` > 0
/*Route::get('/welcome', function () {
    return view('welcome');
});*/

Auth::routes();

Route::get('new', 'ChartsController@unUsedCustomers')->name('new');
Route::get('ar/main/{rep_id?}/{customer_id?}','ArTestController@new_aged_receivables')->name('new_aged_receivables');
Route::get('xxx /{rep_id?}', 'ArTestController@new_aged_receivables')->name('ar_search');

Route::get('/', 'CommissionController@index')->name('home');
Route::get('/home', 'CommissionController@index')->name('home');
Route::post('/commission_calc', 'CommissionController@calcCommissions');
Route::post('/saleorder_calc', 'CommissionController@calcCommissionsPerSalesOrder');
Route::post('/init_calc', 'DevelopController@calcCommissions');
Route::get('go-home', array('as' => 'go-home', 'uses' => 'CommissionController@index'));
Route::post('view-so/{order_id}', array('as' => 'view_so', 'uses' => 'CommissionController@displaySalesOrder'));

Route::get('/init_calc', 'DevelopController@calcCommissions');
Route::get('/comm', 'DevelopController@allCommissions');
Route::get('donutchart/{customer_id}/{customer_name}/{salesperson}/{month}', array('as' => 'donutchart', 'uses' => 'CommissionController@commissionsPerCustomerBrand'));

Route::post('/calc_region', 'DevelopController@calcRegions');

Route::post('/calc_brands', 'DevelopController@calcBrandsPerMonth');
Route::post('/calc_customers', 'DevelopController@calcCustomersPerMonth');
Route::post('/get_customers', 'DevelopController@selectCustomer');
Route::get('/calc_one_customer/{customer_id}', 'DevelopController@calcOneCustomer');

// Display view
Route::get('datatable', 'DataTablesController@datatable');
// Get Data
Route::get('datatable/getdata', 'DataTablesController@getPosts')->name('datatable/getdata');

Route::get('index', 'DisplayDataController@index');
Route::get('create', 'DisplayDataController@create');
Route::get('brand_ajax/{month}', 'DevelopController@brand_ajax');
Route::get('customer_ajax/{month}', 'DevelopController@customer_ajax');
Route::get('ajax_all_months', 'DevelopController@ajax_all_months');
Route::get('ajax_region_months/{region}', 'DevelopController@ajax_region_months');

Route::get('tests', 'TestController@index');
Route::post('calcPerSalesPerson', 'DevelopController@calcPerSalesPerson');
Route::post('calcPerCustomer', 'DevelopController@calcPerCustomer');
Route::post('calcPerBrand', 'DevelopController@calcPerBrand');
Route::post('calcPerProduct', 'DevelopController@calcPerProduct');
Route::post('totalDetails', 'DevelopController@totalDetails');
Route::post('cleanOutSales', 'DevelopController@cleanOutSales')->name('cleanOutSales');
Route::post('totalSalesorders', 'DevelopController@totalSalesorders');

Route::post('create_saved_commissions', 'DevelopController@createSavedCommission');

Route::get('prolist_paid_unpaid_commissions_workducts', 'DevelopController@all_products')->name('products');
//Route::get('aged_receivables', 'ArController@aged_receivables')->name('xaged_receivables');
Route::get('aged_receivables1/{rep_id ?}/{customer_id?}', 'ArTestController@new_aged_receivables')->name('aged_receivables1');
Route::get('aged_receivables_search /{rep_id?}', 'ArController@new_aged_receivables')->name('xxxaged_receivables_search');
Route::get('show_notes /{so}', 'InvoiceNoteController@index')->name('show_notes');
Route::get('list_notes /{customer_id}', 'InvoiceNoteController@list_notes')->name('list_notes');
Route::resource('invoice_notes', 'InvoiceNoteController');


Route::get('importExportView', 'NoteImportController@importExportView');
Route::post('import', 'NoteImportController@import')->name('import');


Route::get('unpaid_paid_work', 'NewCommissionController@list_paid_unpaid_commissions_work')->name('unpaid_paid_work');
Route::get('paid_out/{table_name?}/{rep?}/{description?}', 'NewCommissionController@viewSavedPaidCommissions')->name('paid_out');

Route::get('/test', function () {
    return view('tables.home');
});
Route::get('calc_tmp', 'TmpController@createSavedCommission')->name('calc_tmp');
Route::post('view_saved_commissions', 'NewCommissionController@viewSavedPaidCommissionsbyRep')->name('saved_commission_by_rep');

Route::get('total_so', 'NewCommissionController@total_salesorders')->name('total_sales_orders');

Route::get('view_unpaid_paid', 'CommissionPaidController@view_paid_unpaid_commissions')->name('view_paid_unpaid_commissions');
Route::any('ten_ninety', 'TenNintyCommissionController@index')->name('ten_ninty_main');
Route::post('ten_ninty_new', 'TenNintyCommissionController@enter_month_schema')->name('ten_ninty_new');
Route::post('ten_ninty_update', 'TenNintyCommissionController@update')->name('ten_ninty_update');
Route::any('ten_ninty_create', 'TenNintyCommissionController@create')->name('ten_ninty_create');
Route::get('export_commissions', 'TenNintyCommissionController@export_commissions')->name('export_commissions');

Route::post('unpaid_paid', 'NewCommissionController@list_paid_unpaid_commissions')->name('unpaid_paid');

Route::get('calc_product', 'CommissionPaidController@calc_used_products')->name('calc_product');
Route::any('admin', 'CommissionPaidController@admin')->name('admin');
Route::get('view_saved_commissions/{id}', 'CommissionPaidController@viewSavedCommission')->name('saved_commission');
Route::get('edit_saved_commission/{id}', 'CommissionPaidController@editSavedCommission')->name('edit_saved_commission');
Route::post('save_saved_commission', 'CommissionPaidController@saveSavedCommission')->name('save_saved_commission');
Route::post('create_saved_commissions_paid', 'CommissionPaidController@createSavedCommission');
Route::get('pay_saved_commission/{id}/{table_name}', 'CommissionPaidController@paySavedCommission')->name('pay_saved_commission');

Route::any('admin_1099', 'TenNinetyPaidController@admin')->name('admin_1099');
Route::get('calc_product_1099', 'TenNinetyPaidController@calc_used_products')->name('calc_product_1099');
Route::get('view_saved_commissions_1099/{id}', 'TenNinetyPaidController@viewSavedCommission')->name('saved_commission_1099');
Route::get('edit_saved_commission_1099/{id}', 'TenNinetyPaidController@editSavedCommission')->name('edit_saved_commission_1099');
Route::post('save_saved_commission_1099', 'TenNinetyPaidController@saveSavedCommission')->name('save_saved_commission_1099');
Route::any('create_saved_commissions_paid_1099', 'TenNinetyPaidController@createSavedCommission')->name('create_saved_commissions_paid_1099');
Route::get('pay_saved_commission_1099/{id}/{table_name}', 'TenNinetyPaidController@paySavedCommission')->name('pay_saved_commission_1099');
Route::get('remove_1099/{id}/{table_name}', 'TenNinetyPaidController@removeSavedCommission')->name('remove_commission_1099');

Route::any('1099_init/{year?}/{month?}/{half?}', 'TenNinetyController@bonus_init')->name('1099_init');
Route::get('1099_update', 'TenNinetyController@update')->name('1099_update');

Route::any('notify', 'NotificationsController@notify_customer')->name('notify_customer');
Route::get('cst/{customer_id}/{customer_name}', 'ArController@customer_statement')->name('customer_statement');

Route::get('export_aged_ar_detail', 'ArController@export_aged_ar_detail')->name('export_aged_ar_detail');
Route::post('export', 'ArController@export')->name('do_export');
Route::get('export_ar_notes', 'ArController@export_ar_notes')->name('export_ar_notes');
Route::get('export_aged_ar', 'ArController@export_aged_ar')->name('export_aged_ar');
Route::get('toggle_felon/{customer_id}', 'ArController@toggle_felon')->name('toggle_felon');

Route::get('/ajaxRequest', 'ArController@ajaxRequest');

Route::post('/ajaxRequestPost', 'ArController@ajaxRequestPost');

Route::get('old_commissions', 'CommissionController@index_old');


Route::get('notifications', 'NotificationsController@view_notifications');
Route::any('bonus', 'EmployeeBonusController@index')->name('bonus_commissions');
Route::any('bonus/{year?}/{month?}', 'EmployeeBonusController@index')->name('bonus_commissions_2');
Route::any('bonus_init', 'EmployeeBonusController@bonus_init')->name('bonus_init');
Route::get('bonus_update', 'EmployeeBonusController@update')->name('bonus_update');
Route::get('bonus_calc', 'EmployeeBonusController@update')->name('bonus_calc');
Route::get('export_bonus', 'EmployeeBonusController@export_bonus')->name('export_bonus');
Route::get('import_customers', 'EmployeeBonusController@importToCustomerImport')->name('import_customers');
//Route::get('testbcc', 'BccController@bcc')->name('bcc.datatables');
Route::post('bcc.companies','BccController@index')->name('bcc.customers');
Route::any('bcctest','BccController@index');

Route::get('test', function () {
    Mail::to('alfred@ozinc.info')->send(new App\Mail\TestAmazonSes('It works!'));
});

Route::any('arajax.customers', 'CustomersPerRepController@ajax_totalindex')->name('customers_per_rep');

Route::any('customer_totals', 'CustomersPerRepController@index')->name('customer.invoices');
Route::any('customers_ajax', 'CustomersPerRepController@ajax_index');
Route::any('invoices_ajax/{customer_id}', 'CustomersPerRepController@ajax_invoices');


Route::get('ar/ajax/{customer_id?}','ArTestController@ajax_new_aged_receivables')->name('ajax.new_aged_receivables');
Route::get('ar/ajax_invoice/{sales_order?}','ArTestController@ajax_sales_order')->name('ajax.new_aged_receivables');

//Route::any('invoices', 'CustomersPerRepController@ajax_invoices')->name('customer.invoices');
//Route::any('ar.customers', 'CustomersPerRepController@totalindex')->name('customers_per_rep');
//Route::any('ar.customer.details', 'CustomersPerRepController@totalindex')->name('customer_details');

Route::get('ttt', function () {
   return view('sales.test');
    });
/*Route::resource('datatables', 'DataTablesController', [
    'anyData'  => 'datatables.data',
    'index' => 'datatables',
]);*/
