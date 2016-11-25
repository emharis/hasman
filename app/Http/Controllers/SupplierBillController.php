<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SupplierBillController extends Controller
{
	// public function index(){
	// 	$paging_item_number = \DB::table('appsetting')->whereName('paging_item_number')->first()->value;

	// 	$data = \DB::table('view_supplier_bill')
	// 		// ->where('status','=','O')
	// 		->orderBy('order_date','desc')
	// 		->paginate($paging_item_number);
	// 	$amount_due = \DB::table('supplier_bill')->sum('amount_due');

	// 	return view('invoice.supplier.index',[
	// 			'data' => $data,
	// 			'paging_item_number' => $paging_item_number,
	// 			'amount_due' => $amount_due
	// 		]);
	// }

	public function index(){
		$data = \DB::table('view_supplier_bill')
			// ->where('status','=','O')
			->orderBy('order_date','desc')
			->get();
		$amount_due = \DB::table('supplier_bill')->sum('amount_due');

		return view('invoice.supplier.index',[
				'data' => $data,
				// 'paging_item_number' => $paging_item_number,
				'amount_due' => $amount_due
			]);
	}

	public function edit($bill_id){
		$data = \DB::table('view_supplier_bill')
					->find($bill_id);
		$purchase_order = \DB::table('purchase_order')->find($data->purchase_order_id);
		$data_detail = \DB::table('view_purchase_order_DETAIL')
						->where('purchase_order_id',$data->purchase_order_id)
						->get();
		$payments = \DB::table('supplier_payment')
						->select('supplier_payment.*',\DB::raw('date_format(payment_date,"%d-%m-%Y") as date_formatted'))
						->where('supplier_bill_id',$bill_id)
						->get();


		return view('invoice.supplier.edit',[
				'data' => $data,
				'data_detail' => $data_detail,
				'purchase_order' => $purchase_order,
				'payments' => $payments,
			]);
	}

	public function regPayment($bill_id){
		$data_bill = \DB::table('view_supplier_bill')->find($bill_id);
		return view('invoice/supplier/regpayment',[
				'data' => $data_bill
			]);
	}

	public function saveRegPayment(Request $req){
		return \DB::transaction(function()use($req){
			// create payment number
			$payment_counter = \DB::table('appsetting')->where('name','supplier_payment_counter')->first()->value;
			$payment_prefix = \DB::table('appsetting')->where('name','supplier_payment_prefix')->first()->value;
			$payment_number = $payment_prefix . '/000' . $payment_counter++;
			// update payment counter
			\DB::table('appsetting')->where('name','supplier_payment_counter')->update([
					'value' => $payment_counter
				]);

			// generate tanggal
            $payment_date = $req->payment_date;
            $arr_tgl = explode('-',$payment_date);
            $payment_date = new \DateTime();
            $payment_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

			// insert payment
			\DB::table('supplier_payment')
				->insert([
						'payment_number' => $payment_number,
						'supplier_bill_id' => $req->supplier_bill_id,
						'payment_amount' => $req->payment_amount,
						'payment_date' => $payment_date,
					]);
			// update amount due
			\DB::table('supplier_bill')
					->where('id',$req->supplier_bill_id)
					->update([
							'amount_due' => \DB::raw('amount_due - ' . $req->payment_amount)
						]);
			// update status
			\DB::table('supplier_bill')
					->where('id',$req->supplier_bill_id)
					->where('amount_due',0)
					->update([
							'status' => 'P'
						]);

			// update status purchase order ke Done jiak pembayaran telah lunas
			$bill = \DB::table('supplier_bill')
					->find($req->supplier_bill_id);
			if($bill->status == 'P'){
				\DB::table('purchase_order')
					->whereId($bill->purchase_order_id)
					->update([
							'status' => 'D'
						]);
			}

			return redirect('invoice/supplier/bill/edit/'.$req->supplier_bill_id);
		});
	}

	// tampilkan data list payments
	public function payments($bill_id){
		$bill = \DB::table('supplier_bill')->find($bill_id);
		$payments = \DB::table('supplier_payment')
								->where('supplier_bill_id',$bill_id)
								->orderBy('created_at','desc')
								->select('supplier_payment.*',\DB::raw('date_format(payment_date,"%d-%m-%Y") as payment_date_formatted'))
								->get();

		return view('invoice/supplier/payments',[
			'bill' => $bill,
			'payments'=>$payments
		]);
	}

	// tampilkan payments
	public function showPayment($payment_id){
		$payment = \DB::table('view_supplier_payment')->find($payment_id);
		return view('invoice/supplier/show-payment',[
			'data' => $payment
		]);

	}

	// DELETE PAYMENT
	public function deletePayment($payment_id){
		return \DB::transaction(function()use($payment_id){
			// get data payment
			$payment = \DB::table('supplier_payment')->find($payment_id);

			//get data supplier bill
			$supplier_bill = \DB::table('supplier_bill')->find($payment->supplier_bill_id);

			// delete from table payment
			\DB::table('supplier_payment')
				->delete($payment_id);

			//  update amount due dan update status di supplier bill
			\DB::table('supplier_bill')
				->where('id',$supplier_bill->id)
				->update([
						'status' => 'O',
						'amount_due' => \DB::raw('amount_due + ' . $payment->payment_amount)
					]);

			return redirect()->back();

		});
	}

	// CANCEL ORDER
	public function cancelOrder($bill_id){
			return \DB::transaction(function()use($bill_id){
				// delete payments
				\DB::table('supplier_payment')
						->where('supplier_bill_id',$bill_id)
						->delete();

				// update status purchase order
				$bill = \DB::table('supplier_bill')->find($bill_id);
				\DB::table('purchase_order')
					->where('id',$bill->purchase_order_id)
					->update([
						'status' => 'O'
					]);

				// delete bill
				\DB::table('supplier_bill')
						->delete($bill_id);

				return redirect('invoice/supplier/bill');
			});
	}

	// public function edit($bill_id){
	// 	$data = \DB::table('view_supplier_bill')
	// 			->find($bill_id);
	// 	$data_detail = \DB::table('view_supplier_bill_DETAIL')
	// 			->where('supplier_bill_id',$data->id)
	// 			->get();
	// 	$payments = \DB::table('supplier_payment')
	// 				->where('supplier_bill_id',$bill_id)
	// 				->select('supplier_payment.*',\DB::raw('date_format(payment_date,"%d-%m-%Y") as date_formatted'))
	// 				->get();

	// 	return view('bill.supplier.edit',[
	// 			'data' => $data,
	// 			'data_detail' => $data_detail,
	// 			'payments' => $payments,
	// 		]);
	// }

	// public function payments($bill_id){
	// 	$data = \DB::table('view_supplier_bill')
	// 			->find($bill_id);
	// 	$data_detail = \DB::table('view_supplier_bill_DETAIL')
	// 			->where('supplier_bill_id',$data->id)
	// 			->get();
	// 	$payments = \DB::table('supplier_payment')
	// 				->where('supplier_bill_id',$bill_id)
	// 				->select('supplier_payment.*',\DB::raw('date_format(payment_date,"%d-%m-%Y") as date_formatted'),'supplier_bills.inv_number')
	// 				->join('supplier_bills','supplier_payment.supplier_bill_id','=','supplier_bills.id')
	// 				->get();
	// 	return view('bill.supplier.payments',[
	// 			'data' => $data,
	// 			'data_detail' => $data_detail,
	// 			'payments' => $payments,
	// 		]);
	// }

	// public function toValidate($bill_id){
	// 	$data_bill = \DB::table('supplier_bills')->find($bill_id);
	// 	$data_bill_detail = \DB::table('supplier_bill_detail')
	// 							->where('supplier_bill_id',$bill_id)
	// 							->get();
	// 	$data_delivery_order = \DB::table('supplier_bill_detail')
	// 							->where('supplier_bill_id',$bill_id)
	// 							->select('delivery_order_id')
	// 							->get();

	// 	return \DB::transaction(function ()use($bill_id,$data_delivery_order) {
	// 		// update status supplier bill to validated
	// 		\DB::table('supplier_bills')
	// 			->where('id',$bill_id)
	// 			->update([
	// 				'status' => 'V'
	// 			]);

	// 		// update status delivery order ke "DONE"
	// 		foreach($data_delivery_order as $dt){
	// 			\DB::table('delivery_order')
	// 				->where('id',$dt->delivery_order_id)
	// 				->update([
	// 					'status' => 'DN'
	// 				]);
	// 		}

	// 		return redirect()->back();
	// 	});

	// }

	// // REGISTER PAYMENT
	// function registerPayment($bill_id){
	// 	$data = \DB::table('view_supplier_bill')->find($bill_id);
	// 	return view('bill.supplier.register-payment',[
	// 		'data' => $data
	// 	]);
	// }

	// // SAVE REGISGTER PAYMENT
	// public function saveRegisterPayment(Request $req){
	// 	return \DB::transaction(function () use($req){
	// 		// create payment number
	// 		$payment_number_counter = \DB::table('appsetting')
	// 									->where('name','supplier_payment_counter')
	// 									->first()->value;
	// 		$payment_prefix = \DB::table('appsetting')
	// 							->where('name','supplier_payment_prefix')
	// 							->first()->value;
	// 		// generate payment number
	// 		$payment_number = $payment_prefix . '/' . date('Y') . '/000' . $payment_number_counter++;

	// 		// update payment counter
	// 		\DB::table('appsetting')
	// 			->where('name','supplier_payment_counter')
	// 			->update([
	// 				'value' => $payment_number_counter
	// 			]);

	// 		// generate tanggal
	// 		$payment_date = $req->payment_date;
 //            $arr_tgl = explode('-',$payment_date);
 //            $payment_date = new \DateTime();
 //            $payment_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

	// 		// insert into payment table
	// 		\DB::table('supplier_payment')
	// 			->insert([
	// 				'payment_number' => $payment_number,
	// 				'supplier_bill_id' => $req->supplier_bill_id,
	// 				'payment_amount' => $req->payment_amount,
	// 				'payment_date' => $payment_date,
	// 			]);

	// 		// update amount due supplier bill
	// 		\DB::table('supplier_bills')
	// 			->where('id',$req->supplier_bill_id)
	// 			->update([
	// 				'amount_due' => \DB::raw('amount_due - ' . $req->payment_amount)
	// 			]);
	// 		// update status supplier bill
	// 		\DB::table('supplier_bills')
	// 			->where('id',$req->supplier_bill_id)
	// 			->where('amount_due',0)
	// 			->update([
	// 				'status' => 'P'
	// 			]);


	// 		return redirect('bill/supplier/edit/' . $req->supplier_bill_id);

	// 	});
	// }



	// // RECONCILE SUPPLIER BILL
	// public function reconcile($bill_id){
	// 	return \DB::transaction(function()use($bill_id){
	// 		// get data bill
	// 		$supplier_bill = \DB::table('supplier_bills')->find($bill_id);

	// 		// update bill status ke OPEN
	// 		\DB::table('supplier_bills')
	// 			->where('id',$bill_id)
	// 			->update([
	// 					'status' => 'O',
	// 					'amount_due' => \DB::raw('total')
	// 				]);

	// 		// update delivery order ke Validated
	// 		$supplier_bill_details = \DB::table('supplier_bill_detail')
	// 										->where('supplier_bill_id',$bill_id)
	// 										->get();
	// 		foreach($supplier_bill_details as $dt){
	// 			\DB::table('delivery_order')
	// 				->where('id',$dt->delivery_order_id)
	// 				->update([
	// 						'status' => 'V'
	// 					]);
	// 		}

	// 		// delete data payment
	// 		\DB::table('supplier_payment')
	// 			->where('supplier_bill_id',$bill_id)
	// 			->delete();


	// 		return redirect()->back();

	// 	});
	// }


}
