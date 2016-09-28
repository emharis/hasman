<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CustomerInvoiceController extends Controller
{
	public function index(){
		$paging_item_number = \DB::table('appsetting')->whereName('paging_item_number')->first()->value;

		$data = \DB::table('VIEW_CUSTOMER_INVOICE')
			->where('status','=','O')
			->orderBy('order_date','desc')
			->paginate($paging_item_number);
			
		return view('invoice.customer.index',[
				'data' => $data,
				'paging_item_number' => $paging_item_number
			]);
	}

	public function edit($id){
		$data = \DB::table('VIEW_CUSTOMER_INVOICE')
				->find($id);
		$data_detail = \DB::table('VIEW_CUSTOMER_INVOICE_DETAIL')
				->where('customer_invoice_id',$data->id)
				->get();

		return view('invoice.customer.edit',[
				'data' => $data,
				'data_detail' => $data_detail,
			]);
	}


}
