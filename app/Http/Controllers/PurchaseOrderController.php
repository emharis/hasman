<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PurchaseOrderController extends Controller
{
	public function index(){
		$paging_item_number = \DB::table('appsetting')->whereName('paging_item_number')->first()->value;

		$data = \DB::table('VIEW_PURCHASE_ORDER')
					->orderBy('order_date','desc')
					->paginate($paging_item_number);
		
		return view('purchase.order.index',[
				'data' => $data,
				'paging_item_number' => $paging_item_number
			]);
	}

	public function create(){
		return view('purchase.order.create',[
				
			]);
	}

	public function insert(Request $req){
		 return \DB::transaction(function()use($req){
			$po_master = json_decode($req->po_master);
            $po_product = json_decode($req->po_product)->product;
            // echo $req->po_product;

            $po_counter = \DB::table('appsetting')->where('name','po_counter')->first()->value;
            $po_prefix = \DB::table('appsetting')->where('name','po_prefix')->first()->value;
            $po_number = $po_prefix . '/' . date('Y') . '/000' . $po_counter++;

            // update po_counter
            \DB::table('appsetting')->where('name','po_counter')->update(['value'=>$po_counter]);

			// generate tanggal
            $order_date = $po_master->order_date;
            $arr_tgl = explode('-',$order_date);
            $fix_order_date = new \DateTime();
            $fix_order_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);     


			// isnert master purchase
			$purchase_order_id = \DB::table('purchase_order')
								->insertGetId([
										'order_number' => $po_number,
										'order_date' => $fix_order_date,
										'supplier_id' => $po_master->supplier_id,
										'subtotal' => $po_master->subtotal,
										'disc' => $po_master->disc,
										'total' => $po_master->total,
										'user_id' => \Auth::user()->id,
										// 'pekerjaan_id' => $po_master->pekerjaan_id,
									]);
			// insert detail purchase order
			foreach($po_product as $dt){
				\DB::table('purchase_order_detail')
					->insert([
							'purchase_order_id' => $purchase_order_id,
							'product_id' => $dt->id,
							'qty' => $dt->qty,
							'unit_price' => $dt->unit_price,
						]);
			}

			return redirect('purchase/order/edit/' . $purchase_order_id);

		});
	}

	public function edit($id){
		$data_master = \DB::table('VIEW_PURCHASE_ORDER')->find($id);
		$data_detail = \DB::table('VIEW_PURCHASE_ORDER_DETAIL')->where('purchase_order_id',$id)->get();

		// $pekerjaan = \DB::table('VIEW_PEKERJAAN')->where('supplier_id',$data_master->supplier_id)->get();
		// $select_pekerjaan = [];
		// foreach($pekerjaan as $dt){
		// 	$select_pekerjaan[$dt->id] = $dt->nama;
		// }

		if($data_master->status == 'O'){
			return view('purchase.order.edit',[
				'data_master' => $data_master,
				'data_detail' => $data_detail,
				// 'select_pekerjaan' => $select_pekerjaan
			]);
		}elseif($data_master->status == 'V' ){
		// }elseif($data_master->status == 'V'){
			// get jumlah DO
			$delivery_order_count = \DB::table('delivery_order')->where('purchase_order_id',$id)->count();
			return view('purchase.order.validated',[
					'data_master' => $data_master,
					'data_detail' => $data_detail,
					'select_pekerjaan' => $select_pekerjaan,
					'delivery_order_count' => $delivery_order_count,
				]);
		}elseif($data_master->status == 'D'){
			$delivery_order_count = \DB::table('delivery_order')->where('purchase_order_id',$id)->count();
			$invoices_count = \DB::table('supplier_invoices')->where('order_id',$id)->count();
			return view('purchase.order.validated',[
					'data_master' => $data_master,
					'data_detail' => $data_detail,
					'select_pekerjaan' => $select_pekerjaan,
					'delivery_order_count' => $delivery_order_count,
					'invoices_count' => $invoices_count,
				]);
		}

		
	}

	public function update(Request $req){
		return \DB::transaction(function()use($req){
			$po_master = json_decode($req->po_master);
            $po_product = json_decode($req->po_product)->material;

			// generate tanggal
            $order_date = $po_master->order_date;
            $arr_tgl = explode('-',$order_date);
            $fix_order_date = new \DateTime();
            $fix_order_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);     


			// update master purchase
			\DB::table('purchase_order')
				->where('id',$po_master->purchase_order_id)
				->update([
						'order_date' => $fix_order_date,
						'supplier_id' => $po_master->supplier_id,
						'pekerjaan_id' => $po_master->pekerjaan_id,
					]);
			// delete data material yang lama
			\DB::table('purchase_order_detail')
			->where('purchase_order_id',$po_master->purchase_order_id)
			->delete();

			// insert detail purchase order yang baru
			foreach($po_product as $dt){
				\DB::table('purchase_order_detail')
					->insert([
							'purchase_order_id' => $po_master->purchase_order_id,
							'material_id' => $dt->id,
							'qty' => $dt->qty,
						]);
			}

			return redirect('purchase/order/edit/' . $po_master->purchase_order_id);

		});
	}

	public function validateOrder($id){
		return \DB::transaction(function()use($id){
			\DB::table('purchase_order')->where('id',$id)->update([
				'status' => 'V'
			]);

			// GENERATE 1 INVOICE
			// =======================================================================
			// generate supplier invoice
			// $invoice_counter = \DB::table('appsetting')->where('name','invoice_counter')->first()->value;
			// $invoice_number = 'INV/' . date('Y') . '/000' . $invoice_counter++;
			// \DB::table('appsetting')->where('name','invoice_counter')->update(['value'=>$invoice_counter]);

			// $supplier_invoice_id = \DB::table('supplier_invoices')->insertGetId([
			// 		'inv_number' => $invoice_number,
			// 		'order_id' => $id,
			// 		'status' => 'D'
			// 	]);

			// generate & insert delivery order for this purchase order
			$purchase_order_detail = \DB::table('purchase_order_detail')->where('purchase_order_id',$id)->get();
			foreach($purchase_order_detail as $dt){
				// insert delivery order 
				for($i=0;$i<$dt->qty;$i++){
					// generate delivery order number
					$do_counter = \DB::table('appsetting')->where('name','do_counter')->first()->value;
            		$do_prefix = \DB::table('appsetting')->where('name','do_prefix')->first()->value;
            		$do_number = $do_prefix . '/' . date('Y') . '/000' . $do_counter++;

		            // update po_counter
		            \DB::table('appsetting')->where('name','do_counter')->update(['value'=>$do_counter]);

		            // Create & insert delivery order
					$do_id = \DB::table('delivery_order')
					->insertGetId([
							'purchase_order_id' => $id,
							'delivery_order_number' => $do_number,
							'material_id' => $dt->material_id,
							'qty' => 1,
							'status' => 'D',
						]);

					// // generate Invoice detail
					// \DB::table('supplier_invoice_detail')->insert([
					// 		'supplier_invoice_id' => $supplier_invoice_id,
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

	public function delivery($po_id){
		$purchase_order = \DB::table('VIEW_PURCHASE_ORDER')->find($po_id);
		$purchase_order_detail = \DB::table('VIEW_PURCHASE_ORDER_DETAIL')
								->where('purchase_order_id',$po_id)->get();
		$delivery_order = \DB::table('VIEW_DELIVERY_ORDER')->where('purchase_order_id',$po_id)->get();
		return view('purchase.order.delivery',[
				'purchase_order' => $purchase_order,
				'purchase_order_detail' => $purchase_order_detail,
				'delivery_order' => $delivery_order,
			]);
	}

	public function deliveryEdit($delivery_id){
		$data = \DB::table('VIEW_DELIVERY_ORDER')->find($delivery_id);

		if($data->status == 'V'){
			return view('purchase.order.delivery_validated',[
				'data' => $data
			]);
		}else{
			return view('purchase.order.delivery_edit',[
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
				'supplier_id' => $req->supplier_id,
				'desa_id' => $req->desa_id,
				'tahun' => $req->tahun,
			]);
		$data = \DB::table('VIEW_PEKERJAAN')

				->find($data_id);

		echo json_encode($data);
	}

	// Delete Data Purchase Order yang ber status open
	public function delete(Request $req){
		return \DB::transaction(function()use($req){
			$data_for_delete = json_decode($req->dataid);
			foreach($data_for_delete as $dt){
				\DB::table('purchase_order')->delete($dt->id);
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

         	$data = \DB::table('VIEW_PURCHASE_ORDER')
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
         	$data = \DB::table('VIEW_PURCHASE_ORDER')
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
         	$data = \DB::table('VIEW_PURCHASE_ORDER')
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

         return view('purchase.order.filter',[
                'data' => $data,
                'paging_item_number' => $paging_item_number
            ]);

	}

	public function reconcile($id){
		return \DB::transaction(function()use($id){
			// delete supplier invoice
			\DB::table('supplier_invoices')->where('order_id',$id)->delete();
			// delete delivery order
			\DB::table('delivery_order')->where('purchase_order_id',$id)->delete();
			// update status purchase order
			\DB::table('purchase_order')->where('id',$id)->update([
					'status' => 'O'
				]);
			return redirect()->back();
		});
	}


	public function invoices($purchase_order_id){
		$purchase_order = \DB::table('purchase_order')->find($purchase_order_id);
		$data = \DB::table('VIEW_CUSTOMER_INVOICE')
				->where('order_id',$purchase_order_id)
				->get();
		return view('purchase.order.invoices',[
				'data' => $data,
				'purchase_order' => $purchase_order,
			]);
	}

	public function showInvoice($invoice_id){
		$data = \DB::table('VIEW_CUSTOMER_INVOICE')
				->find($invoice_id);
		$data_detail = \DB::table('VIEW_CUSTOMER_INVOICE_DETAIL')
				->where('supplier_invoice_id',$invoice_id)
				->get();
		$purchase_order = \DB::table('purchase_order')->find($data->order_id);

		return view('purchase.order.invoice-show',[
				'data' => $data,
				'data_detail' => $data_detail,
				'purchase_order' => $purchase_order,
			]);
	}


//. END OF CODE
}
