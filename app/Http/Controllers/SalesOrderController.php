<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SalesOrderController extends Controller
{
	public function index(){
		
		return view('sales.order.index',[
				// 'data' => $data
			]);
	}

	public function create(){
		return view('sales.order.create',[
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
										'customer_id' => $so_master->customer_id
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

		if($data_master->status == 'O'){
			return view('sales.order.edit',[
				'data_master' => $data_master,
				'data_detail' => $data_detail,
			]);
		}elseif($data_master->status = 'V'){
			return view('sales.order.validated',[
					'data_master' => $data_master,
					'data_detail' => $data_detail,
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
						'customer_id' => $so_master->customer_id
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
		\DB::table('sales_order')->where('id',$id)->update([
				'status' => 'V'
			]);
		return redirect()->back();
	}


}
