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
    $login_background = \DB::table('appsetting')->whereName('login_background')->first()->value;
    return view('login',['login_background'=>$login_background]);
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

    Route::group(['prefix' => 'dailyhd'], function () {
        Route::get('/','DailyhdController@index');
        Route::get('create','DailyhdController@create');
        Route::get('edit/{id}','DailyhdController@edit');
        Route::get('cek-duplikasi/{tanggal}/{id}','DailyhdController@cekDuplikasi');
        Route::post('insert','DailyhdController@insert');
        Route::post('update','DailyhdController@update');
        Route::post('delete','DailyhdController@delete');
        Route::post('validate','DailyhdController@toValidate');
    });
    
    Route::group(['prefix' => 'cashbook'], function () {
        Route::get('/', 'CashbookController@index');
        Route::get('create', 'CashbookController@create');
        Route::post('insert', 'CashbookController@insert');
        Route::post('update', 'CashbookController@update');
        Route::get('edit/{cashbook_id}', 'CashbookController@edit');
        Route::get('delete/{cashbook_id}', 'CashbookController@delete');
    });

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

        // PEKERJAAN
        Route::get('pekerjaan','PekerjaanController@index');
        Route::get('pekerjaan/create','PekerjaanController@create');
        Route::post('pekerjaan/insert','PekerjaanController@insert');
        Route::get('pekerjaan/edit/{id}','PekerjaanController@edit');
        Route::post('pekerjaan/update','PekerjaanController@update');
        Route::post('pekerjaan/delete','PekerjaanController@delete');

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
        Route::get('order/can-delete/{order_id}','PurchaseOrderController@canDelete');
        Route::get('order/cancel-order/{purchase_order_id}','PurchaseOrderController@cancelOrder');
    });

    Route::group(['prefix' => 'attendance'], function () {
        // SETTING
        Route::get('setting','AttendanceController@setting');
        Route::post('update-time-setting','AttendanceController@updateTimeSetting');
        Route::post('setting/insert-holiday','AttendanceController@insertHoliday');
        Route::get('setting/delete-holiday/{holiday_id}','AttendanceController@deleteHoliday');
        // ATTENDANCE
        Route::get('attend','AttendanceController@attend');
        Route::post('attend/insert','AttendanceController@insertAttend');
        Route::get('get-attendance-table/{tanggal}','AttendanceController@getAttendanceTable');
    });

    Route::group(['prefix' => 'payroll'], function () {
        // PAYROLL STAFF
        // Route::get('staff','PayrollDriverController@staff');

        // PAYROLL DRIVER
        Route::get('driver','PayrollDriverController@driver');
        Route::get('driver/create','PayrollDriverController@driverCreate');
        Route::post('driver/insert','PayrollDriverController@insert');
        Route::post('driver/update','PayrollDriverController@update');
        Route::get('driver/delete/{payroll_id}','PayrollDriverController@deletePayroll');
        Route::get('driver/edit/{payroll_id}','PayrollDriverController@edit');
        Route::get('driver/validate/{payroll_id}','PayrollDriverController@validatePayroll');
        Route::get('driver/cancel-payroll/{payroll_id}','PayrollDriverController@cancelPayroll');
        Route::get('driver/get-delivery-order/{driver_id}/{start_date}/{end_date}','PayrollDriverController@getDeliveryOrderList');

        // PAYROLL STAFF
        Route::get('staff','PayrollStaffController@index');
        Route::get('staff/create','PayrollStaffController@create');
        Route::get('staff/edit/{payroll_id}','PayrollStaffController@edit');
        Route::post('staff/insert','PayrollStaffController@insert');
        Route::post('staff/update','PayrollStaffController@update');
        Route::get('staff/get-attendance/{staff_id}/{awal}/{akhir}','PayrollStaffController@getAttendance');
        Route::get('staff/get-workday/{staff_id}/{awal}/{akhir}','PayrollStaffController@getWorkday');
        Route::get('staff/validate/{payroll_id}','PayrollStaffController@validatePayroll');
        Route::get('staff/cancel-payroll/{payroll_id}','PayrollStaffController@cancelPayroll');

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
        Route::post('order/update-direct-sales','SalesOrderController@updateDirectSales');
        Route::get('order/validate-direct-sales/{sales_order_id}','SalesOrderController@validateDirectSalesOrder');


        // DIRECT SALES
        Route::post('order/insert-direct-sales','SalesOrderController@insertDirectSales');

    });

    Route::group(['prefix' => 'delivery'], function () {
        // ORDERS
        Route::get('order','DeliveryOrderController@index');
        Route::get('order/edit/{id}','DeliveryOrderController@edit');
        Route::post('order/delete','DeliveryOrderController@delete');
        Route::post('order/update','DeliveryOrderController@update');
        Route::post('order/to-validate','DeliveryOrderController@toValidate');
        Route::get('order/reconcile/{id}','DeliveryOrderController@reconcile');
        Route::get('order/filter','DeliveryOrderController@filter');
    });

    Route::group(['prefix' => 'invoice'], function () {
        // CUSTOMER INVOICE
        Route::get('customer','CustomerInvoiceController@index');
        Route::get('customer/edit/{id}','CustomerInvoiceController@edit');
        Route::get('customer/show-one-invoice/{invoice_id}','CustomerInvoiceController@showOneInvoice');
        Route::get('customer/validate/{id}','CustomerInvoiceController@toValidate');
        Route::get('customer/reconcile/{invoice_id}','CustomerInvoiceController@reconcile');
        Route::get('customer/register-payment/{invoice_id}','CustomerInvoiceController@registerPayment');
        Route::get('customer/payments/{invoice_id}','CustomerInvoiceController@payments');
        Route::get('customer/payments/delete/{payment_id}','CustomerInvoiceController@deletePayment');
        Route::post('customer/save-register-payment','CustomerInvoiceController@saveRegisterPayment');

        // SUPPLIER BILL
        Route::get('supplier/bill','SupplierBillController@index');
        Route::get('supplier/bill/edit/{bill_id}','SupplierBillController@edit');
        Route::get('supplier/bill/reg-payment/{bill_id}','SupplierBillController@regPayment');
        Route::post('supplier/bill/save-register-payment','SupplierBillController@saveRegPayment');
        Route::get('supplier/bill/payments/{bill_id}','SupplierBillController@payments');
        Route::get('supplier/bill/payment/show/{payment_id}','SupplierBillController@showPayment');
        Route::get('supplier/bill/payment/delete/{payment_id}','SupplierBillController@deletePayment');
        Route::get('supplier/bill/cancel-order/{bill_id}','SupplierBillController@cancelOrder');

    });

    Route::group(['prefix' => 'report'], function () {
        // REPORT PURCHASE
        Route::get('purchase','ReportPurchaseController@index');
        Route::post('purchase/filter-by-date','ReportPurchaseController@filterByDate');
        Route::post('purchase/filter-by-date-n-supplier','ReportPurchaseController@filterByDateNSupplier');
        Route::get('purchase/filter-by-date/pdf/{start}/{end}','ReportPurchaseController@filterByDateToPdf');

        // REPORT SALES
        Route::get('sales','ReportSalesController@index');
        Route::get('sales/get-pekerjaan-by-customer/{customer_id}','ReportSalesController@getPekerjaanByCustomer');
        Route::post('sales/report-by-date','ReportSalesController@reportByDate');
        Route::post('sales/report-by-date-detail','ReportSalesController@reportByDateDetail');
        Route::post('sales/report-by-customer','ReportSalesController@reportByCustomer');
        Route::post('sales/report-by-customer-pekerjaan','ReportSalesController@reportByCustomerPekerjaan');
        Route::post('sales/report-by-customer-detail','ReportSalesController@reportByCustomerDetail');
        Route::post('sales/report-by-lokasi-galian','ReportSalesController@reportByLokasiGalian');
        Route::post('sales/report-by-sales-type','ReportSalesController@reportBySalesType');
        Route::post('sales/report-by-sales-type-all','ReportSalesController@reportBySalesTypeAll');

        //  REPORT DELIVERY
        Route::get('delivery','ReportDeliveryController@index');
        Route::post('delivery/report-by-date','ReportDeliveryController@reportByDate');
        Route::post('delivery/report-by-customer','ReportDeliveryController@reportByCustomer');
    });

    Route::get('api/get-auto-complete-provinsi','ApiController@getAutoCompleteProvinsi');
    Route::get('api/get-auto-complete-kabupaten','ApiController@getAutoCompleteKabupaten');
    Route::get('api/get-auto-complete-kecamatan','ApiController@getAutoCompleteKecamatan');
    Route::get('api/get-auto-complete-desa','ApiController@getAutoCompleteDesa');
    Route::get('api/get-auto-complete-customer','ApiController@getAutoCompleteCustomer');
    Route::get('api/get-auto-complete-supplier','ApiController@getAutoCompleteSupplier');
    Route::get('api/get-auto-complete-armada','ApiController@getAutoCompleteArmada');
    Route::get('api/get-auto-complete-alat','ApiController@getAutoCompleteAlat');
    Route::get('api/get-auto-complete-driver','ApiController@getAutoCompleteDriver');
    Route::get('api/get-auto-complete-lokasi-galian','ApiController@getAutoCompleteLokasiGalian');
    Route::get('api/get-auto-complete-material','ApiController@getAutoCompleteMaterial');
    Route::get('api/get-auto-complete-product','ApiController@getAutoCompleteProduct');
    Route::get('api/get-auto-complete-staff','ApiController@getAutoCompleteStaff');
    Route::get('api/get-select-customer','ApiController@getSelectCustomer');
    Route::get('api/get-select-pekerjaan/{customer_id}','ApiController@getSelectPekerjaan');
    Route::get('api/get-pekerjaan-by-customer/{customer_id}','ApiController@getPekerjaanByCustomer');
});
