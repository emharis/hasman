<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('sidebar-update', function() {
    $value = \DB::table('appsetting')->whereName('sidebar_collapse')->first()->value;
    \DB::table('appsetting')->whereName('sidebar_collapse')->update(['value' => $value == 1 ? '0' : '1']);
});

// Tampilkan View Login
Route::get('/', function() {
    return redirect('login');
});

Route::get('login', function () {
    return view('login');
});

Route::post('login', function() {
    //auth user
    Auth::attempt(['username' => Request::input('username'), 'password' => Request::input('password')]);

    if (Request::ajax()) {
        if (Auth::check()) {
            return "true";
        } else {
            return "false";
        }
    } else {
        if (Auth::check()) {
            return redirect('home');
        } else {
            return redirect('login');
        }
    }
});

// Logout
Route::get('logout', function() {
    Auth::logout();
    return redirect('login');
});

Route::group(['middleware' => ['web','auth']], function () {
	Route::get('home', ['as' => 'home', 'uses' => 'HomeController@index']);

    Route::group(['prefix' => 'master'], function () {
        // LOKASI GALIAN
        Route::get('lokasi','LokasiGalianController@index');
        Route::get('lokasi/create','LokasiGalianController@create');
        Route::post('lokasi/insert','LokasiGalianController@insert');
        Route::get('lokasi/edit/{id}','LokasiGalianController@edit');
        Route::post('lokasi/update','LokasiGalianController@update');
        Route::post('lokasi/delete','LokasiGalianController@delete');

        // ARMADA
        Route::get('armada','ArmadaController@index');
        Route::get('armada/create','ArmadaController@create');
        Route::post('armada/insert','ArmadaController@insert');
        Route::get('armada/edit/{id}','ArmadaController@edit');
        Route::post('armada/update','ArmadaController@update');
        Route::post('armada/delete','ArmadaController@delete');

        // JABATAN
        Route::get('jabatan','JabatanController@index');
        Route::get('jabatan/create','JabatanController@create');
        Route::post('jabatan/insert','JabatanController@insert');
        Route::get('jabatan/edit/{id}','JabatanController@edit');
        Route::post('jabatan/update','JabatanController@update');
        Route::post('jabatan/delete','JabatanController@delete');

        // KARYAWAN
        Route::get('karyawan','KaryawanController@index');
        Route::get('karyawan/create','KaryawanController@create');
        Route::post('karyawan/insert','KaryawanController@insert');
        Route::get('karyawan/edit/{id}','KaryawanController@edit');
        Route::post('karyawan/update','KaryawanController@update');
        Route::post('karyawan/delete','KaryawanController@delete');

        // SUPPLIER
        Route::get('supplier','SupplierController@index');
        Route::get('supplier/create','SupplierController@create');
        Route::post('supplier/insert','SupplierController@insert');
        Route::get('supplier/edit/{id}','SupplierController@edit');
        Route::post('supplier/update','SupplierController@update');
        Route::post('supplier/delete','SupplierController@delete');

        // CUSTOMER
        Route::get('customer','CustomerController@index');
        Route::get('customer/create','CustomerController@create');
        Route::post('customer/insert','CustomerController@insert');
        Route::get('customer/edit/{id}','CustomerController@edit');
        Route::post('customer/update','CustomerController@update');
        Route::post('customer/delete','CustomerController@delete');

        // MATERIAL
        Route::get('material','MaterialController@index');
        Route::get('material/create','MaterialController@create');
        Route::post('material/insert','MaterialController@insert');
        Route::get('material/edit/{id}','MaterialController@edit');
        Route::post('material/update','MaterialController@update');
        Route::post('material/delete','MaterialController@delete');

        // ALAT
        Route::get('alat','AlatController@index');
        Route::get('alat/create','AlatController@create');
        Route::post('alat/insert','AlatController@insert');
        Route::get('alat/edit/{id}','AlatController@edit');
        Route::post('alat/update','AlatController@update');
        Route::post('alat/delete','AlatController@delete');

        // PRODUCT
        Route::get('product','ProductController@index');
        Route::get('product/create','ProductController@create');
        Route::post('product/insert','ProductController@insert');
        Route::get('product/edit/{id}','ProductController@edit');
        Route::post('product/update','ProductController@update');
        Route::post('product/delete','ProductController@delete');

        // PRODUCT UNITS
        Route::get('unit','ProductUnitController@index');
        Route::get('unit/create','ProductUnitController@create');
        Route::post('unit/insert','ProductUnitController@insert');
        Route::get('unit/edit/{id}','ProductUnitController@edit');
        Route::post('unit/update','ProductUnitController@update');
        Route::post('unit/delete','ProductUnitController@delete');

    });

    Route::group(['prefix' => 'purchase'], function () {
        // ORDERS
        Route::get('order','PurchaseOrderController@index');
        Route::post('order/delete','PurchaseOrderController@delete');
        Route::get('order/create','PurchaseOrderController@create');
        Route::get('order/edit/{id}','PurchaseOrderController@edit');
        Route::get('order/validate/{id}','PurchaseOrderController@validateOrder');
        Route::post('order/insert','PurchaseOrderController@insert');
        Route::post('order/update','PurchaseOrderController@update');
        Route::get('order/delivery/{so_id}','PurchaseOrderController@delivery');
        Route::get('order/delivery/edit/{delivery_id}','PurchaseOrderController@deliveryEdit');
        Route::post('order/delivery/update','PurchaseOrderController@deliveryUpdate');
        Route::post('order/create-pekerjaan','PurchaseOrderController@createPekerjaan');
        Route::get('order/filter','PurchaseOrderController@filter'); 
        Route::get('order/reconcile/{id}','PurchaseOrderController@reconcile');    
        Route::get('order/invoices/{purchase_order_id}','PurchaseOrderController@invoices');    
        Route::get('order/invoices/show/{invoice_id}','PurchaseOrderController@showInvoice');    
    });

    Route::group(['prefix' => 'sales'], function () {
        // ORDERS
        Route::get('order','SalesOrderController@index');
        Route::post('order/delete','SalesOrderController@delete');
        Route::get('order/create','SalesOrderController@create');
        Route::get('order/edit/{id}','SalesOrderController@edit');
        Route::get('order/validate/{id}','SalesOrderController@validateOrder');
        Route::post('order/insert','SalesOrderController@insert');
        Route::post('order/insert-direct-sales','SalesOrderController@insertDirectSales');
        Route::post('order/update','SalesOrderController@update');
        Route::get('order/delivery/{so_id}','SalesOrderController@delivery');
        Route::get('order/delivery/edit/{delivery_id}','SalesOrderController@deliveryEdit');
        Route::post('order/delivery/update','SalesOrderController@deliveryUpdate');
        Route::post('order/create-pekerjaan','SalesOrderController@createPekerjaan');
        Route::get('order/filter','SalesOrderController@filter'); 
        Route::get('order/reconcile/{id}','SalesOrderController@reconcile');    
        Route::get('order/invoices/{sales_order_id}','SalesOrderController@invoices');    
        Route::get('order/invoices/show/{invoice_id}','SalesOrderController@showInvoice');   


        // DIRECT SALES 
    });

    Route::group(['prefix' => 'delivery'], function () {
        // ORDERS
        Route::get('order','DeliveryOrderController@index');
        Route::get('order/edit/{id}','DeliveryOrderController@edit');
        Route::post('order/update','DeliveryOrderController@update');
        Route::post('order/to-validate','DeliveryOrderController@toValidate');
        Route::get('order/reconcile/{id}','DeliveryOrderController@reconcile');
        Route::get('order/filter','DeliveryOrderController@filter');     
    });

    Route::group(['prefix' => 'invoice'], function () {
        // ORDERS
        Route::get('customer','CustomerInvoiceController@index');
        Route::get('customer/edit/{id}','CustomerInvoiceController@edit');
        Route::get('customer/validate/{id}','CustomerInvoiceController@toValidate');
        Route::get('customer/reconcile/{invoice_id}','CustomerInvoiceController@reconcile');
        Route::get('customer/register-payment/{invoice_id}','CustomerInvoiceController@registerPayment');
        Route::get('customer/payments/{invoice_id}','CustomerInvoiceController@payments');
        Route::get('customer/payments/delete/{payment_id}','CustomerInvoiceController@deletePayment');
        Route::post('customer/save-register-payment','CustomerInvoiceController@saveRegisterPayment');
    });

    Route::get('api/get-auto-complete-provinsi','ApiController@getAutoCompleteProvinsi');
    Route::get('api/get-auto-complete-kabupaten','ApiController@getAutoCompleteKabupaten');
    Route::get('api/get-auto-complete-kecamatan','ApiController@getAutoCompleteKecamatan');
    Route::get('api/get-auto-complete-desa','ApiController@getAutoCompleteDesa');
    Route::get('api/get-auto-complete-customer','ApiController@getAutoCompleteCustomer');
    Route::get('api/get-auto-complete-supplier','ApiController@getAutoCompleteSupplier');
    Route::get('api/get-auto-complete-armada','ApiController@getAutoCompleteArmada');
    Route::get('api/get-auto-complete-lokasi-galian','ApiController@getAutoCompleteLokasiGalian');
    Route::get('api/get-auto-complete-material','ApiController@getAutoCompleteMaterial');
    Route::get('api/get-select-customer','ApiController@getSelectCustomer');
    Route::get('api/get-select-pekerjaan/{customer_id}','ApiController@getSelectPekerjaan');
});


