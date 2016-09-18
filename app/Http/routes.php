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

    });

    Route::get('api/get-auto-complete-provinsi','ApiController@getAutoCompleteProvinsi');
    Route::get('api/get-auto-complete-kabupaten','ApiController@getAutoCompleteKabupaten');
    Route::get('api/get-auto-complete-kecamatan','ApiController@getAutoCompleteKecamatan');
    Route::get('api/get-auto-complete-desa','ApiController@getAutoCompleteDesa');
});


