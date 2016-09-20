<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SalesOrderController extends Controller
{
	public function index(){
		$data = \DB::table('VIEW_SALES_ORDER')->orderBy('order_date','desc')->get();
		
		return view('sales.order.index',[
				'data' => $data
			]);
	}

	public function create(){
		// $pekerjaan = \DB::table('pekerjaan')->get();
		// $selectPekerjaan = [];
		// foreach($pekerjaan as $dt){
		// 	$selectPekerjaan[$dt->id] = $dt->nama;
		// }

		return view('sales.order.create',[
				// 'selectPekerjaan' => $selectPekerjaan
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


			// isnert master sales
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
		$data_master = \DB::table('VIEW_SALES_ORDER')->find($id);
		$data_detail = \DB::table('VIEW_SALES_ORDER_DETAIL')->where('sales_order_id',$id)->get();

		$pekerjaan = \DB::table('VIEW_PEKERJAAN')->where('customer_id',$data_master->customer_id)->get();
		$select_pekerjaan = [];
		foreach($pekerjaan as $dt){
			$select_pekerjaan[$dt->id] = $dt->nama;
		}

		if($data_master->status == 'O'){
			return view('sales.order.edit',[
				'data_master' => $data_master,
				'data_detail' => $data_detail,
				'select_pekerjaan' => $select_pekerjaan
			]);
		}elseif($data_master->status = 'V'){
			$delivery_order_count = \DB::table('delivery_order')->where('sales_order_id',$id)->count();
			return view('sales.order.validated',[
					'data_master' => $data_master,
					'data_detail' => $data_detail,
					'select_pekerjaan' => $select_pekerjaan,
					'delivery_order_count' => $delivery_order_count,
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

		            // insert delivery order
					\DB::table('delivery_order')
					->insert([
							'sales_order_id' => $id,
							'delivery_order_number' => $do_number,
							'material_id' => $dt->material_id,
							'qty' => 1,
							'status' => 'D',
						]);
				}
			}
		return redirect()->back();

		});
		
	}

	public function delivery($so_id){
		$sales_order = \DB::table('VIEW_SALES_ORDER')->find($so_id);
		$sales_order_detail = \DB::table('VIEW_SALES_ORDER_DETAIL')
								->where('sales_order_id',$so_id)->get();
		$delivery_order = \DB::table('VIEW_DELIVERY_ORDER')->where('sales_order_id',$so_id)->get();
		return view('sales.order.delivery',[
				'sales_order' => $sales_order,
				'sales_order_detail' => $sales_order_detail,
				'delivery_order' => $delivery_order,
			]);
	}

	public function deliveryEdit($delivery_id){
		$data = \DB::table('VIEW_DELIVERY_ORDER')->find($delivery_id);
		return view('sales.order.delivery_edit',[
				'data' => $data
			]);
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
			]);
		$data = \DB::table('VIEW_PEKERJAAN')

				->find($data_id);

		echo json_encode($data);
	}


}
