<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DeliveryOrderController extends Controller
{
	public function index(){
		$paging_item_number = \DB::table('appsetting')->whereName('paging_item_number')->first()->value;

		$data = \DB::table('VIEW_DELIVERY_ORDER')
			//->where('status','!=','D')
			->orderBy('order_date','desc')
			->paginate($paging_item_number);
			
		return view('delivery.order.index',[
				'data' => $data,
				'paging_item_number' => $paging_item_number
			]);
	}

	public function edit($id){
		$data = \DB::table('VIEW_DELIVERY_ORDER')->find($id);
		
		
		if($data->status == 'D' || $data->status == 'O'){
			return view('delivery.order.edit',[
				'data' => $data
				]);
		}else if($data->status == 'V'){
			return view('delivery.order.validated',[
				'data' => $data
				]);
		}else if($data->status == 'DN'){
			// status DONE
			return view('delivery.order.done',[
				'data' => $data
			]);
		}
	}

	public function update(Request $req){
		return \DB::transaction(function()use($req){
			// generate tanggal
            $delivery_date = $req->delivery_date;
            $arr_tgl = explode('-',$delivery_date);
            $fix_delivery_date = new \DateTime();
            $fix_delivery_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 

			\DB::table('delivery_order')
				->where('id',$req->delivery_order_id)
				->update([
						'armada_id' => $req->armada_id,
						'lokasi_galian_id' => $req->lokasi_galian_id,
						'keterangan' => $req->keterangan,
						'status' => 'O',
						'delivery_date' => $fix_delivery_date,
					]);

			return redirect()->back();
		});
	}

	public function toValidate(Request $req){
		// echo $req->no_nota_timbang . '<br/>';
		// echo $req->kalkulasi . '<br/>';
		// echo $req->panjang . '<br/>';
		// echo $req->lebar . '<br/>';
		// echo $req->tinggi . '<br/>';
		// echo $req->delivery_id;

		return \DB::transaction(function()use($req){
			$unit_price = str_replace('.','',str_replace(',','',$req->unit_price));

			// update table delivery order
			\DB::table('delivery_order')
				->where('id',$req->delivery_id)
				->update([
						'no_nota_timbang' => $req->no_nota_timbang,
						'kalkulasi' => $req->kalkulasi,
						'panjang' => $req->panjang,
						'lebar' => $req->lebar,
						'tinggi' => $req->tinggi,
						'volume' => $req->panjang * $req->lebar * $req->tinggi,
						'gross' => $req->gross,
						'tarre' => $req->tarre,
						'netto' => $req->gross - $req->tarre,
						'unit_price' => $unit_price,
						'status' => 'V'
					]);

			if($req->kalkulasi == "K"){
				// hitung total kubikasi
				\DB::table('delivery_order')
				->where('id',$req->delivery_id)
				->update([
						'total' => $unit_price * $req->panjang * $req->lebar * $req->tinggi,
					]);
			}elseif($req->kalkulasi == "T"){
				// hitung price berdasar tonase
				\DB::table('delivery_order')
				->where('id',$req->delivery_id)
				->update([
						'total' => $unit_price * ($req->gross - $req->tarre),
					]);
			}else{
				// hitung price berdasar ritase
				\DB::table('delivery_order')
				->where('id',$req->delivery_id)
				->update([
						'total' => $unit_price ,
					]);
			}

			$data_do = \DB::table('delivery_order')->find($req->delivery_id);
			$sales_order = \DB::table('sales_order')->find($data_do->sales_order_id);
			

			//===================================================================
			// UNTUK 1 INVOICE
			// // update customer invoice detail
			// \DB::table('customer_invoice_detail')
			// 	->where('delivery_order_id',$data_do->id)
			// 	->update([
			// 			'kalkulasi' => $data_do->kalkulasi,
			// 			'panjang' => $data_do->panjang,
			// 			'lebar' => $data_do->lebar,
			// 			'tinggi' => $data_do->tinggi,
			// 			'volume' => $data_do->volume,
			// 			'gross' => $data_do->gross,
			// 			'tarre' => $data_do->tarre,
			// 			'netto' => $data_do->netto,
			// 			'unit_price' => $data_do->unit_price,
			// 			'total' => $data_do->total,
			// 			'qty' => $data_do->qty,
			// 		]);
			
			// // Update Status Customer Invoice			
			// \DB::table('customer_invoices')
			// 	->whereRaW("(select count(id) 
			// 			from delivery_order where status = 'V' and sales_order_id = " . $data_do->sales_order_id . ") = (select sum(qty) from sales_order_detail where sales_order_id = " . $data_do->sales_order_id . ")
			// 		and order_id = " . $data_do->sales_order_id)
			// 	->update([
			// 			'status' => 'O',
			// 			'total' =>\DB::raw('(select sum(total) from delivery_order where sales_order_id = '. $data_do->sales_order_id . ')'),
			// 			'amount_due' => \DB::raw('(select sum(total) from delivery_order where sales_order_id = '. $data_do->sales_order_id . ')')
			// 		]);

			// $customer_invoice = \DB::table('customer_invoices')->where('order_id',$sales_order->id)->first();

			// // Jika invoice sudah open maka ototmatis SO ter validasi
			// // update status invoice to done

			// if($customer_invoice->status == 'O'){
			// 	\DB::table('sales_order')
			// 		->where('id',$sales_order->id)
			// 		->update([
			// 				'status' => 'D'
			// 			]);
			// }
			// UNTUK 1 INVOICE
			//===================================================================

			// cek jika delivery order untuk satu sales order telah di kirim &  validated
			// maka customer invoice akan di generate
			$open_do = \DB::table('delivery_order')
				->whereRaw('sales_order_id = ' . $data_do->sales_order_id . ' and status != "V"')
				->count();
			if($open_do == 0){
				// genereate multi invoice
				$by_kubikasi = \DB::table('delivery_order')
								->where('sales_order_id',$data_do->sales_order_id)
								->where('kalkulasi','K')->get();
				$by_tonase = \DB::table('delivery_order')
								->where('sales_order_id',$data_do->sales_order_id)
								->where('kalkulasi','T')->get();
				$by_ritase = \DB::table('delivery_order')
								->where('sales_order_id',$data_do->sales_order_id)
								->where('kalkulasi','R')->get();

				if(count($by_kubikasi) > 0){
					// create invoice kubikasi
					$customer_invoice_id = \DB::table('customer_invoices')->insertGetId([
							'inv_number' => $this->getNewCustomerInvoice(),
							'order_id' => $data_do->sales_order_id,
							'status' => 'O',
							'kalkulasi' => 'K'
						]);
					// insert detail invoice
					$total = 0;
					foreach($by_kubikasi as $dt){
						\DB::table('customer_invoice_detail')
							->insert([
									'customer_invoice_id' => $customer_invoice_id,
									'delivery_order_id' => $dt->id,
									'kalkulasi' => 'K',
									'panjang' => $dt->panjang,
									'lebar' => $dt->lebar,
									'tinggi' => $dt->tinggi,
									'volume' => $dt->volume,
									'unit_price' => $dt->unit_price,
									'total' => $dt->total,
									'qty' => 1,
								]);
						$total += $dt->total;
					}

					// update total & amount_due di sales order
					\DB::table('customer_invoices')
						->where('id',$customer_invoice_id)
						->update([
								'total' => $total,
								'amount_due' => $total,
							]);
					
				}

				if(count($by_tonase) > 0){
					// create invoice TONASE
					$customer_invoice_id = \DB::table('customer_invoices')->insertGetId([
							'inv_number' => $this->getNewCustomerInvoice(),
							'order_id' => $data_do->sales_order_id,
							'status' => 'O',
							'kalkulasi' => 'T'
						]);
					// insert detail invoice
					$total = 0;
					foreach($by_tonase as $dt){
						\DB::table('customer_invoice_detail')
							->insert([
									'customer_invoice_id' => $customer_invoice_id,
									'delivery_order_id' => $dt->id,
									'kalkulasi' => 'T',
									'gross' => $dt->gross,
									'tarre' => $dt->tarre,
									'netto' => $dt->netto,
									'unit_price' => $dt->unit_price,
									'total' => $dt->total,
									'qty' => 1,
								]);
						$total += $dt->total;
					}

					// update total & amount_due di sales order
					\DB::table('customer_invoices')
						->where('id',$customer_invoice_id)
						->update([
								'total' => $total,
								'amount_due' => $total,
							]);
					
				}

				if(count($by_ritase) > 0){
					// create invoice RITASE
					$customer_invoice_id = \DB::table('customer_invoices')->insertGetId([
							'inv_number' => $this->getNewCustomerInvoice(),
							'order_id' => $data_do->sales_order_id,
							'status' => 'O',
							'kalkulasi' => 'R'
						]);
					// insert detail invoice
					$total = 0;
					foreach($by_ritase as $dt){
						\DB::table('customer_invoice_detail')
							->insert([
									'customer_invoice_id' => $customer_invoice_id,
									'delivery_order_id' => $dt->id,
									'kalkulasi' => 'R',
									'unit_price' => $dt->unit_price,
									'total' => $dt->total,
									'qty' => 1,
								]);
						$total += $dt->total;
					}

					// update total & amount_due di sales order
					\DB::table('customer_invoices')
						->where('id',$customer_invoice_id)
						->update([
								'total' => $total,
								'amount_due' => $total,
							]);
					
				}

				// UPDATE SALES ORDER TO DONE
				\DB::table('sales_order')
					->where('id',$data_do->sales_order_id)
					->update([
							'status' => 'D'
						]);


			}



			return redirect()->back();
		});
	}

	public function getNewCustomerInvoice(){
		// generate customer invoice
		$invoice_counter = \DB::table('appsetting')->where('name','invoice_counter')->first()->value;
		$invoice_number = 'INV/' . date('Y') . '/000' . $invoice_counter++;
		// update invoice counter
		\DB::table('appsetting')->where('name','invoice_counter')->update(['value'=>$invoice_counter]);

		return $invoice_number;
	}

	public function reconcile($id){
		return \DB::transaction(function()use($id){
			$do = \DB::table('delivery_order')->find($id);
			// update delivery order
			\DB::table('delivery_order')->where('id',$id)->update([
					'status' => 'O',
					'no_nota_timbang' => '',
					'kalkulasi' => '',
					'panjang' => '',
					'lebar' => '',
					'tinggi' => '',
					'volume' => '',
					'gross' => '',
					'tarre' => '',
					'netto' => '',
					'unit_price' => '',
					'total' => '',
				]);

			// reset customer invoice detail
			\DB::table('customer_invoice_detail')
				->where('delivery_order_id',$id)
				->update([
					'kalkulasi' => '',
					'panjang' => '',
					'lebar' => '',
					'tinggi' => '',
					'volume' => '',
					'gross' => '',
					'tarre' => '',
					'netto' => '',
					'unit_price' => '',
					'total' => '',
				]);

			// reset status customer invoice to 'Draft'
			\DB::table('customer_invoices')
			->where('order_id',$do->sales_order_id)
			->update([
				'status' => 'D',
				'total' => 0,
				'amount_due' => 0,
			]);

			// reset status sales_order to Validated
			\DB::table('sales_order')
				->where('id',$do->sales_order_id)
				->update([
						'status' => 'V'
					]);

			return redirect()->back();
		});
	}

	// Filter  with Pagination
	public function filter(Request $req){

		 $paging_item_number = \DB::table('appsetting')->where('name','paging_item_number')->first()->value; 

         if($req->filter_by == 'order_date' || $req->filter_by == 'delivery_date'){
         	// generate tanggal
            $date_start = $req->date_start;
            $arr_tgl = explode('-',$date_start);  
            $date_start = $arr_tgl[2]. '-' . $arr_tgl[1] . '-' . $arr_tgl[0];

            $date_end = $req->date_end;
            $arr_tgl = explode('-',$date_end);
            $date_end = $arr_tgl[2]. '-' . $arr_tgl[1] . '-' . $arr_tgl[0];

         	$data = \DB::table('VIEW_DELIVERY_ORDER')
					->orderBy('order_date','desc')
					//->where('status','!=','D')
					->whereBetween($req->filter_by,[$date_start,$date_end])
					->paginate($paging_item_number)
	                ->appends([
	                    'date_start' => $date_start
	                    ])
	                ->appends([
	                    'date_end' => $date_end
	                    ])
	                ->appends([
	                    'filter_by' => $req->filter_by
	                    ]);

         }else if($req->filter_by == 'O' || $req->filter_by == 'V' || $req->filter_by == 'D' ){
         	$data = \DB::table('VIEW_DELIVERY_ORDER')
					->orderBy('order_date','desc')
					//->where('status','!=','D')
					->where('status','=',$req->filter_by)
					->paginate($paging_item_number)
	                ->appends([
	                    'filter_string' => $req->filter_string
	                    ])
	                ->appends([
	                    'filter_by' => $req->filter_by
	                    ]);

         }else{
         	$data = \DB::table('VIEW_DELIVERY_ORDER')
					->orderBy('order_date','desc')
					//->where('status','!=','D')
					->where($req->filter_by,'like','%' . $req->filter_string . '%')
					->paginate($paging_item_number)
	                ->appends([
	                    'filter_string' => $req->filter_string
	                    ])
	                ->appends([
	                    'filter_by' => $req->filter_by
	                    ]);
         }

         return view('delivery.order.filter',[
                'data' => $data,
                'paging_item_number' => $paging_item_number
            ]);

	}


}
