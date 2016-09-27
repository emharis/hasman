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
			->orderBy('order_date','desc')
			->paginate($paging_item_number);
			
		return view('delivery.order.index',[
				'data' => $data,
				'paging_item_number' => $paging_item_number
			]);
	}

	public function edit($id){
		$data = \DB::table('VIEW_DELIVERY_ORDER')->find($id);
		if($data->status != 'V'){
			return view('delivery.order.edit',[
				'data' => $data
				]);
		}else{
			return view('delivery.order.validated',[
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

			return redirect()->back();
		});
	}

	public function reconcile($id){
		return \DB::transaction(function()use($id){
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
