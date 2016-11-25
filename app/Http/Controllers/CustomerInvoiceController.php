<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CustomerInvoiceController extends Controller
{
	// public function index(){
	// 	$paging_item_number = \DB::table('appsetting')->whereName('paging_item_number')->first()->value;

	// 	$data = \DB::table('view_customer_invoice')
	// 		// ->where('status','=','O')
	// 		->orderBy('order_date','desc')
	// 		->paginate($paging_item_number);
			
	// 	return view('invoice.customer.index',[
	// 			'data' => $data,
	// 			'paging_item_number' => $paging_item_number
	// 		]);
	// }

	public function index(){
		
		$data = \DB::table('view_customer_invoice')
			// ->where('status','=','O')
			->orderBy('order_date','desc')
			->get();

		$amount_due = \DB::table('customer_invoices')->sum('amount_due');
		$total = \DB::table('customer_invoices')->sum('total');
		$paid = $total - $amount_due;
			
		return view('invoice.customer.index',[
				'data' => $data,
				'amount_due' => $amount_due,
				'total' => $total,
				'paid' => $paid,
				// 'paging_item_number' => $paging_item_number
			]);
	}

	public function edit($invoice_id){
		$data = \DB::table('view_customer_invoice')
				->find($invoice_id);
		$data_detail = \DB::table('view_customer_invoice_detail')
				->where('customer_invoice_id',$data->id)
				->get();
		$sales_order = \DB::table('sales_order')->find($data->order_id);
		$payments = \DB::table('customer_payment')
					->where('customer_invoice_id',$invoice_id)
					->select('customer_payment.*',\DB::raw('date_format(payment_date,"%d-%m-%Y") as date_formatted'))
					->get();

		return view('invoice.customer.edit',[
				'data' => $data,
				'data_detail' => $data_detail,
				'payments' => $payments,
				'sales_order' => $sales_order,
			]);
	}

	public function showOneInvoice($invoice_id){
		$data = \DB::table('view_customer_invoice')
				->find($invoice_id);
		$data_detail = \DB::table('view_customer_invoice_detail')
				->where('customer_invoice_id',$data->id)
				->get();

		$data_kubikasi = \DB::table('view_customer_invoice_detail')
						->where('customer_invoice_id',$data->id)
						->where('kalkulasi','K')
						->get();
		$data_tonase= \DB::table('view_customer_invoice_detail')
						->where('customer_invoice_id',$data->id)
						->where('kalkulasi','T')
						->get();
		$data_ritase= \DB::table('view_customer_invoice_detail')
						->where('customer_invoice_id',$data->id)
						->where('kalkulasi','R')
						->get();

		$sales_order = \DB::table('sales_order')->find($data->order_id);
		$payments = \DB::table('customer_payment')
					->where('customer_invoice_id',$invoice_id)
					->select('customer_payment.*',\DB::raw('date_format(payment_date,"%d-%m-%Y") as date_formatted'))
					->get();

		return view('invoice.customer.one-invoice',[
				'data' => $data,
				'data_detail' => $data_detail,
				'payments' => $payments,
				'sales_order' => $sales_order,
				'data_kubikasi' => $data_kubikasi,
				'data_tonase' => $data_tonase,
				'data_ritase' => $data_ritase,
			]);
	}

	public function payments($invoice_id){
		$data = \DB::table('view_customer_invoice')
				->find($invoice_id);
		$data_detail = \DB::table('view_customer_invoice_detail')
				->where('customer_invoice_id',$data->id)
				->get();
		$payments = \DB::table('customer_payment')
					->where('customer_invoice_id',$invoice_id)
					->select('customer_payment.*',\DB::raw('date_format(payment_date,"%d-%m-%Y") as date_formatted'),'customer_invoices.inv_number')
					->join('customer_invoices','customer_payment.customer_invoice_id','=','customer_invoices.id')
					->get();
		return view('invoice.customer.payments',[
				'data' => $data,
				'data_detail' => $data_detail,
				'payments' => $payments,
			]);
	}

	public function toValidate($invoice_id){
		$data_invoice = \DB::table('customer_invoices')->find($invoice_id);
		$data_invoice_detail = \DB::table('customer_invoice_detail')
								->where('customer_invoice_id',$invoice_id)
								->get();
		$data_delivery_order = \DB::table('customer_invoice_detail')
								->where('customer_invoice_id',$invoice_id)
								->select('delivery_order_id')
								->get();
		
		return \DB::transaction(function ()use($invoice_id,$data_delivery_order) {
			// update status customer invoice to validated
			\DB::table('customer_invoices')
				->where('id',$invoice_id)
				->update([
					'status' => 'V'
				]);

			// update status delivery order ke "DONE"
			foreach($data_delivery_order as $dt){
				\DB::table('delivery_order')
					->where('id',$dt->delivery_order_id)
					->update([
						'status' => 'DN'
					]);
			}
			
			return redirect()->back();
		});
		
	}

	// REGISTER PAYMENT
	function registerPayment($invoice_id){
		$data = \DB::table('view_customer_invoice')->find($invoice_id);
		return view('invoice.customer.register-payment',[
			'data' => $data
		]);
	}

	// SAVE REGISGTER PAYMENT
	public function saveRegisterPayment(Request $req){
		return \DB::transaction(function () use($req){
			// create payment number
			$payment_number_counter = \DB::table('appsetting')
										->where('name','customer_payment_counter')
										->first()->value;
			$payment_prefix = \DB::table('appsetting')
								->where('name','customer_payment_prefix')
								->first()->value;
			// generate payment number
			$payment_number = $payment_prefix . '/' . date('Y') . '/000' . $payment_number_counter++;

			// update payment counter
			\DB::table('appsetting')
				->where('name','customer_payment_counter')
				->update([
					'value' => $payment_number_counter
				]);

			// generate tanggal
			$payment_date = $req->payment_date;
            $arr_tgl = explode('-',$payment_date);
            $payment_date = new \DateTime();
            $payment_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
			
			// insert into payment table
			\DB::table('customer_payment')
				->insert([
					'payment_number' => $payment_number,
					'customer_invoice_id' => $req->customer_invoice_id,
					'payment_amount' => $req->payment_amount,
					'payment_date' => $payment_date,
				]);
			
			// update amount due customer invoice
			\DB::table('customer_invoices')
				->where('id',$req->customer_invoice_id)
				->update([
					'amount_due' => \DB::raw('amount_due - ' . $req->payment_amount)
				]);
			// update status customer invoice
			\DB::table('customer_invoices')
				->where('id',$req->customer_invoice_id)
				->where('amount_due',0)
				->update([
					'status' => 'P'
				]);

			// CEK SALES ORDER JIKA SEMUA SUDAH PAID MAKA SET SALES ORDER KE DONE
			$invoice = \DB::Table('customer_invoices')->find($req->customer_invoice_id);
			$sales_order = \DB::table('sales_order')->find($invoice->order_id);
			// var is_done = false;
			$not_done_invoice = \DB::table('customer_invoices')->where('order_id',$sales_order->id)->where('status','!=','P')->count();
			if($not_done_invoice == 0){
				// berarti sudah done/sudah paid semua , set sales order to DONE
				\DB::table('sales_order')->whereId($sales_order->id)->update(['status'=>'D']);
			}

			
			// return redirect('invoice/customer/edit/' . $req->customer_invoice_id);
			return redirect('invoice/customer/show-one-invoice/' . $req->customer_invoice_id);

		});
	}

	// DELETE PAYMENT
	public function deletePayment($payment_id){
		return \DB::transaction(function()use($payment_id){
			// get data payment
			$payment = \DB::table('customer_payment')->find($payment_id);

			//get data customer invoice
			$customer_invoice = \DB::table('customer_invoices')->find($payment->customer_invoice_id);

			// delete from table payment
			\DB::table('customer_payment')
				->delete($payment_id);

			//  update amount due dan update status di customer invoice
			\DB::table('customer_invoices')
				->where('id',$customer_invoice->id)
				->update([
						'status' => 'V',
						'amount_due' => \DB::raw('amount_due + ' . $payment->payment_amount)
					]);

			// Update Status Sales Order to Validated
			\DB::table('sales_order')->whereId($customer_invoice->order_id)->update(['status'=>'V']);

			return redirect()->back();

		});
	}

	// RECONCILE CUSTOMER INVOICE
	public function reconcile($invoice_id){
		return \DB::transaction(function()use($invoice_id){
			// get data invoice
			$customer_invoice = \DB::table('customer_invoices')->find($invoice_id);

			// update invoice status ke OPEN
			\DB::table('customer_invoices')
				->where('id',$invoice_id)
				->update([
						'status' => 'O',
						'amount_due' => \DB::raw('total')
					]);

			// update delivery order ke Validated
			$customer_invoice_details = \DB::table('customer_invoice_detail')
											->where('customer_invoice_id',$invoice_id)
											->get();
			foreach($customer_invoice_details as $dt){
				\DB::table('delivery_order')
					->where('id',$dt->delivery_order_id)
					->update([
							'status' => 'V'
						]);
			}

			// delete data payment
			\DB::table('customer_payment')
				->where('customer_invoice_id',$invoice_id)
				->delete();


			return redirect()->back();

		});
	}


}
