<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;

class SalesOrderController extends Controller
{
	// public function index(){
	// 	$paging_item_number = \DB::table('appsetting')->whereName('paging_item_number')->first()->value;

	// 	$data = \DB::table('view_sales_order')
	// 				->orderBy('order_date','desc')
	// 				->paginate($paging_item_number);
		
	// 	return view('sales.order.index',[
	// 			'data' => $data,
	// 			'paging_item_number' => $paging_item_number
	// 		]);
	// }

	public function index(){
		// $paging_item_number = \DB::table('appsetting')->whereName('paging_item_number')->first()->value;

		$data = \DB::table('view_sales_order')
					->orderBy('order_date','desc')
					->get();
		$delivery_to_do = \DB::table('delivery_order')
							->where('status','!=' ,'V')
							->where('status','!=' ,'DN')
							->count();
		
		return view('sales.order.index',[
				'data' => $data,
				'delivery_to_do' => $delivery_to_do
				// 'paging_item_number' => $paging_item_number
			]);
	}

	public function create(){
		// $pekerjaan = \DB::table('pekerjaan')->get();
		// $selectPekerjaan = [];
		// foreach($pekerjaan as $dt){
		// 	$selectPekerjaan[$dt->id] = $dt->nama;
		// }

		$select_customer = [];
		$customers = \DB::table('customer')
						->select('id','nama')
						->get();
		foreach($customers as $dt){
			$select_customer[$dt->id] = $dt->nama;
		}

		$select_material = [];
		$materials = \DB::table('material')
						->select('id','nama')
						->get();
		foreach($materials as $dt){
			$select_material[$dt->id] = $dt->nama;
		}

		return view('sales.order.create',[
				// 'selectPekerjaan' => $selectPekerjaan
			'selectCustomer' => $select_customer,
			'selectMaterial' => $select_material,
		]);
	}

	public function insert(Request $req){
		return \DB::transaction(function()use($req){
			$so_master = json_decode($req->so_master);
            $so_material = json_decode($req->so_material)->material;

            $so_counter = \DB::table('appsetting')->where('name','so_counter')->first()->value;
            $so_prefix = \DB::table('appsetting')->where('name','so_prefix')->first()->value;
            $so_number = $so_prefix . '/' . date('Y') . '/000' . $so_counter++;

            // update so_counter
            \DB::table('appsetting')->where('name','so_counter')->update(['value'=>$so_counter]);

			// generate tanggal
            $order_date = $so_master->order_date;
            $arr_tgl = explode('-',$order_date);
            $fix_order_date = new \DateTime();
            $fix_order_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);     


			// isnert master sales order
			$sales_order_id = \DB::table('sales_order')
								->insertGetId([
										'order_number' => $so_number,
										'order_date' => $fix_order_date,
										'customer_id' => $so_master->customer_id,
										'pekerjaan_id' => $so_master->pekerjaan_id,
									]);
			// insert detail sales order
			foreach($so_material as $dt){
				\DB::table('sales_order_detail')
					->insert([
							'sales_order_id' => $sales_order_id,
							'material_id' => $dt->id,
							'qty' => $dt->qty,
						]);
			}

			return redirect('sales/order/edit/' . $sales_order_id);

		});
	}


	public function edit($id){
		$data_master = \DB::table('view_sales_order')->find($id);
		$data_detail = \DB::table('view_sales_order_detail')->where('sales_order_id',$id)->get();

		$pekerjaan = \DB::table('view_pekerjaan')->where('customer_id',$data_master->customer_id)->get();
		$select_pekerjaan = [];
		foreach($pekerjaan as $dt){
			$select_pekerjaan[$dt->id] = $dt->nama;
		}

		$select_material = [];
		$materials = \DB::table('material')
						->select('id','nama')
						->get();
		foreach($materials as $dt){
			$select_material[$dt->id] = $dt->nama;
		}

		// jika direct selling
		if($data_master->is_direct_sales == 'Y'){
			if($data_master->status == 'O'){
				return view('sales.order.ds_edit',[
					'data_master' => $data_master,
					'data_detail' => $data_detail,
					'selectMaterial' => $select_material,
				]);
			}elseif($data_master->status == 'V'){
				return view('sales.order.ds_validated',[
					'data_master' => $data_master,
					'data_detail' => $data_detail
				]);
			}
			
		}

		if($data_master->status == 'O'){
			return view('sales.order.edit',[
				'data_master' => $data_master,
				'data_detail' => $data_detail,
				'select_pekerjaan' => $select_pekerjaan,
				'selectMaterial' => $select_material,
			]);
		}elseif($data_master->status == 'V' ){
		// }elseif($data_master->status == 'V'){
			// get jumlah DO
			$delivery_order_count = \DB::table('delivery_order')->where('sales_order_id',$id)->count();
			return view('sales.order.validated',[
					'data_master' => $data_master,
					'data_detail' => $data_detail,
					'select_pekerjaan' => $select_pekerjaan,
					'delivery_order_count' => $delivery_order_count,
				]);
		}elseif($data_master->status == 'D'){
			$delivery_order_count = \DB::table('delivery_order')->where('sales_order_id',$id)->count();
			$invoices_count = \DB::table('customer_invoices')->where('order_id',$id)->count();
			return view('sales.order.validated',[
					'data_master' => $data_master,
					'data_detail' => $data_detail,
					'select_pekerjaan' => $select_pekerjaan,
					'delivery_order_count' => $delivery_order_count,
					'invoices_count' => $invoices_count,
					'selectMaterial' => $select_material,
				]);
		}

		
	}

	public function update(Request $req){
		return \DB::transaction(function()use($req){
			$so_master = json_decode($req->so_master);
            $so_material = json_decode($req->so_material)->material;

			// generate tanggal
            $order_date = $so_master->order_date;
            $arr_tgl = explode('-',$order_date);
            $fix_order_date = new \DateTime();
            $fix_order_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);     


			// update master sales
			\DB::table('sales_order')
				->where('id',$so_master->sales_order_id)
				->update([
						'order_date' => $fix_order_date,
						'customer_id' => $so_master->customer_id,
						'pekerjaan_id' => $so_master->pekerjaan_id,
					]);
			// delete data material yang lama
			\DB::table('sales_order_detail')
			->where('sales_order_id',$so_master->sales_order_id)
			->delete();

			// insert detail sales order yang baru
			foreach($so_material as $dt){
				\DB::table('sales_order_detail')
					->insert([
							'sales_order_id' => $so_master->sales_order_id,
							'material_id' => $dt->id,
							'qty' => $dt->qty,
						]);
			}

			return redirect('sales/order/edit/' . $so_master->sales_order_id);

		});
	}

	public function validateOrder($id){
		return \DB::transaction(function()use($id){
			\DB::table('sales_order')->where('id',$id)->update([
				'status' => 'V'
			]);

			// GENERATE 1 INVOICE
			// =======================================================================
			// generate customer invoice
			// $invoice_counter = \DB::table('appsetting')->where('name','invoice_counter')->first()->value;
			// $invoice_number = 'INV/' . date('Y') . '/000' . $invoice_counter++;
			// \DB::table('appsetting')->where('name','invoice_counter')->update(['value'=>$invoice_counter]);

			// $customer_invoice_id = \DB::table('customer_invoices')->insertGetId([
			// 		'inv_number' => $invoice_number,
			// 		'order_id' => $id,
			// 		'status' => 'D'
			// 	]);

			// generate & insert delivery order for this sales order
			$sales_order_detail = \DB::table('sales_order_detail')->where('sales_order_id',$id)->get();
			foreach($sales_order_detail as $dt){
				// insert delivery order 
				for($i=0;$i<$dt->qty;$i++){
					// generate delivery order number
					$do_counter = \DB::table('appsetting')->where('name','do_counter')->first()->value;
            		$do_prefix = \DB::table('appsetting')->where('name','do_prefix')->first()->value;
            		$do_number = $do_prefix . '/' . date('Y') . '/000' . $do_counter++;

		            // update so_counter
		            \DB::table('appsetting')->where('name','do_counter')->update(['value'=>$do_counter]);

		            // Create & insert delivery order
					$do_id = \DB::table('delivery_order')
					->insertGetId([
							'sales_order_id' => $id,
							'delivery_order_number' => $do_number,
							'material_id' => $dt->material_id,
							'qty' => 1,
							'status' => 'D',
						]);

					// // generate Invoice detail
					// \DB::table('customer_invoice_detail')->insert([
					// 		'customer_invoice_id' => $customer_invoice_id,
					// 		'delivery_order_id' => $do_id,
					// 		'qty' => 1
					// 	]);
				}
			}
			// END GENERATE 1 INVOICE
			// =======================================================================

		return redirect()->back();

		});
		
	}

	public function delivery($so_id){
		$sales_order = \DB::table('view_sales_order')->find($so_id);
		$sales_order_detail = \DB::table('view_sales_order_detail')
								->where('sales_order_id',$so_id)->get();
		$delivery_order = \DB::table('view_delivery_order')->where('sales_order_id',$so_id)->get();
		return view('sales.order.delivery',[
				'sales_order' => $sales_order,
				'sales_order_detail' => $sales_order_detail,
				'delivery_order' => $delivery_order,
			]);
	}

	public function deliveryEdit($delivery_id){
		$data = \DB::table('view_delivery_order')->find($delivery_id);

		if($data->status == 'V' || $data->status == 'DN'){
			return view('sales.order.delivery_validated',[
				'data' => $data
			]);
		}else{
			return view('sales.order.delivery_edit',[
				'data' => $data
			]);	
		}
		
	}

	public function deliveryUpdate(Request $req){
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
						// 'desa_id' => $req->desa_id,
						// 'alamat' => $req->alamat,
						'keterangan' => $req->keterangan,
						'status' => 'O',
						'delivery_date' => $fix_delivery_date,
					]);

			return redirect()->back();
		});
	}

	public function createPekerjaan(Request $req){
		// \DB::table('pekerjaan')
		$data_id = \DB::table('pekerjaan')->insertGetId([
				'nama' => $req->nama,
				'alamat' => $req->alamat,
				'customer_id' => $req->customer_id,
				'desa_id' => $req->desa_id,
				'tahun' => $req->tahun,
			]);
		$data = \DB::table('view_pekerjaan')

				->find($data_id);

		echo json_encode($data);
	}

	// Delete Data Sales Order yang ber status open
	public function delete(Request $req){
		return \DB::transaction(function()use($req){
			$data_for_delete = json_decode($req->dataid);
			foreach($data_for_delete as $dt){
				\DB::table('sales_order')->delete($dt->id);
			}

			return redirect()->back();	
		});
		
	}

	// Filter  with Pagination
	public function filter(Request $req){
		// echo $req->filter_by;
		// echo '<br/>';
		// echo $req->filter_string;
		// echo '<br/>';
		// echo $req->date_start;
		// echo '<br/>';
		// echo $req->date_end;

		 $paging_item_number = \DB::table('appsetting')->where('name','paging_item_number')->first()->value; 

         if($req->filter_by == 'order_date'){
         	// generate tanggal
            $date_start = $req->date_start;
            $arr_tgl = explode('-',$date_start);  
            $date_start = $arr_tgl[2]. '-' . $arr_tgl[1] . '-' . $arr_tgl[0];

            $date_end = $req->date_end;
            $arr_tgl = explode('-',$date_end);
            $date_end = $arr_tgl[2]. '-' . $arr_tgl[1] . '-' . $arr_tgl[0];

         	$data = \DB::table('view_sales_order')
					->orderBy('order_date','desc')
					->whereBetween('order_date',[$date_start,$date_end])
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
         	$data = \DB::table('view_sales_order')
					->orderBy('order_date','desc')
					->where('status','=',$req->filter_by)
					->paginate($paging_item_number)
	                ->appends([
	                    'filter_string' => $req->filter_string
	                    ])
	                ->appends([
	                    'filter_by' => $req->filter_by
	                    ]);

         }else{
         	$data = \DB::table('view_sales_order')
					->orderBy('order_date','desc')
					->where($req->filter_by,'like','%' . $req->filter_string . '%')
					->paginate($paging_item_number)
	                ->appends([
	                    'filter_string' => $req->filter_string
	                    ])
	                ->appends([
	                    'filter_by' => $req->filter_by
	                    ]);
         }

         return view('sales.order.filter',[
                'data' => $data,
                'paging_item_number' => $paging_item_number
            ]);

	}

	public function reconcile($id){
		return \DB::transaction(function()use($id){
			// // delete customer invoice
			// \DB::table('customer_invoices')->where('order_id',$id)->delete();
			// // delete delivery order
			// \DB::table('delivery_order')->where('sales_order_id',$id)->delete();
			// // update status sales order
			// \DB::table('sales_order')->where('id',$id)->update([
			// 		'status' => 'O'
			// 	]);

			// delete sales_order
			\DB::table('sales_order')->delete($id);

			return redirect('sales/order');
		});
	}


	public function invoices($sales_order_id){
		$sales_order = \DB::table('sales_order')->find($sales_order_id);
		$data = \DB::table('view_customer_invoice')
				->where('order_id',$sales_order_id)
				->get();
		return view('sales.order.invoices',[
				'data' => $data,
				'sales_order' => $sales_order,
			]);
	}

	public function showInvoice($invoice_id){
		$data = \DB::table('view_customer_invoice')
				->find($invoice_id);
		$data_detail = \DB::table('view_customer_invoice_detail')
				->where('customer_invoice_id',$invoice_id)
				->get();
		$sales_order = \DB::table('sales_order')->find($data->order_id);

		return view('sales.order.invoice-show',[
				'data' => $data,
				'data_detail' => $data_detail,
				'sales_order' => $sales_order,
			]);
	}

	// INSERT DIRECT SALES
	public function insertDirectSales(Request $req){
		return \DB::transaction(function()use($req){
			$so_master = json_decode($req->so_master);
            $so_material = json_decode($req->so_material)->material;

            $so_counter = \DB::table('appsetting')->where('name','so_counter')->first()->value;
            $so_prefix = \DB::table('appsetting')->where('name','so_prefix')->first()->value;
            $so_number = $so_prefix . '/' . date('Y') . '/000' . $so_counter++;

            // update so_counter
            \DB::table('appsetting')->where('name','so_counter')->update(['value'=>$so_counter]);

			// generate tanggal
            $order_date = $so_master->order_date;
            $arr_tgl = explode('-',$order_date);
            $fix_order_date = new \DateTime();
            $fix_order_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);     


			// isnert master sales order
			$sales_order_id = \DB::table('sales_order')
								->insertGetId([
										'order_number' => $so_number,
										'order_date' => $fix_order_date,
										'is_direct_sales' => 'Y',
										'customer_id' => $so_master->customer_id,
										// 'nama_customer' => $so_master->customer_name,
										'nopol' => $so_master->nopol,
									]);
			// insert detail sales order
			foreach($so_material as $dt){
				\DB::table('sales_order_detail')
					->insert([
							'sales_order_id' => $sales_order_id,
							'material_id' => $dt->id,
							'qty' => $dt->qty,
							'harga' => $dt->unit_price,
							'total' => $dt->unit_price * $dt->qty,
						]);
			}

			// langsung generate invoice

			return redirect('sales/order/edit/' . $sales_order_id);
			

		});
	}

	public function updateDirectSales(Request $req){
		return \DB::transaction(function()use($req){
			$so_master = json_decode($req->so_master);
            $so_material = json_decode($req->so_material)->material;

			// generate tanggal
            $order_date = $so_master->order_date;
            $arr_tgl = explode('-',$order_date);
            $fix_order_date = new \DateTime();
            $fix_order_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);     


			// update master sales
			\DB::table('sales_order')
				->where('id',$so_master->sales_order_id)
				->update([
						'order_date' => $fix_order_date,
						// 'customer_id' => $so_master->customer,
						'nopol' => $so_master->nopol,
					]);
			// delete data material yang lama
			\DB::table('sales_order_detail')
			->where('sales_order_id',$so_master->sales_order_id)
			->delete();

			// insert detail sales order yang baru
			foreach($so_material as $dt){
				\DB::table('sales_order_detail')
					->insert([
							'sales_order_id' => $so_master->sales_order_id,
							'material_id' => $dt->id,
							'qty' => $dt->qty,
							'harga' => $dt->unit_price,
							'total' => $dt->unit_price * $dt->qty,
						]);
			}

			return redirect('sales/order/edit/' . $so_master->sales_order_id);

		});
	}

	public function validateDirectSalesOrder($sales_order_id){
		return \DB::transaction(function()use($sales_order_id){
			\DB::table('sales_order')->where('id',$sales_order_id)->update([
				'status' => 'V'
			]);

			// generate & insert delivery order for this sales order
			$sales_order_detail = \DB::table('sales_order_detail')->where('sales_order_id',$sales_order_id)->get();

			$total = \DB::table('sales_order_detail')->where('sales_order_id',$sales_order_id)->sum('total');
			
			// GENERATE INVOICE
			// =======================================================================
			$invoice_id = \DB::table('customer_invoices')->insertGetId([
					'inv_number' => Helper::GenerateCustomerInvoiceNumber(),
					'order_id' => $sales_order_id,
					'total' => 	$total,
					'amount_due' => $total,
					'status' => 'O',
					'kalkulasi' => 'R'
				]);

			// insert invoice detail
			foreach($sales_order_detail as $dt){
				\DB::table('customer_invoice_detail')->insert([
						'customer_invoice_id' => $invoice_id,
						'material_id' => $dt->material_id,
						'kalkulasi' => 'R',
						'unit_price' => $dt->harga,
						'qty' => $dt->qty,
						'total' => $dt->total
					]);
			}
			

		return redirect()->back();

		});
		// echo Helper::GenerateCustomerInvoiceNumber();
	}


//. END OF CODE
}
