<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PayrollDriverController extends Controller
{
	

	  public function driver(){
			$data = \DB::table('view_payroll')
						->where('kategori','D')
						->orderBy('payment_date','desc')
						->get();
	    return view('payroll.driver.index',[
				'data' => $data
			]);
	  }

	  public function driverCreate(){
	    return view('payroll.driver.create');
	  }

	// save data payroll
	public function insert(Request $req){
		return \DB::transaction(function()use($req){
			$payroll = json_decode($req->payroll);
			$payroll_detail = json_decode($req->payroll_detail);
			$armada = \DB::table('armada')->where('karyawan_id',$payroll->karyawan_id)->first();

			// generate tanggal
		    $tanggal = $payroll->payment_date;
		    $arr_tgl = explode('-',$tanggal);
		    $tanggal = new \DateTime();
		    $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

		    $start_date = $payroll->start_date;
		    $arr_tgl = explode('-',$start_date);
		    $start_date = new \DateTime();
		    $start_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		    $start_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

			$end_date = $payroll->end_date;
			$arr_tgl = explode('-',$end_date);
			$end_date = new \DateTime();
			$end_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			$end_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

			// generate payroll number
			$payroll_counter = \DB::table('appsetting')->whereName('payroll_counter')->first()->value;
			$payroll_prefix = \DB::table('appsetting')->whereName('payroll_prefix')->first()->value;
			$payroll_number = $payroll_prefix ."/".date('Y/m').'/000'.$payroll_counter++;
			\DB::table('appsetting')->whereName('payroll_counter')->update(['value'=>$payroll_counter]);

			// insert ke table payroll
			$payroll_id = \DB::table('payroll')->insertGetId([
				'kategori' => 'D',
				'karyawan_id' => $payroll->karyawan_id,
				'payroll_number' => $payroll_number,
				'payment_date' => $tanggal,
				'start_date' => $start_date,
				'end_date' => $end_date,
				'total' => $payroll->total,
				'potongan_bahan' => $payroll->potongan_bahan,
				'potongan_bon' => $payroll->potongan_bon,
				'sisa_bayaran' => $payroll->sisa_bayaran,
				'dp' => $payroll->dp,
				'saldo' => $payroll->saldo,
				'status' => 'O'
			]);

			// insert ke table payroll detail
			foreach($payroll_detail->detail as $dt){
				$total = 0;
				if($dt->kalkulasi == 'K'){
					$total = $dt->harga * $dt->volume;
				}else if($dt->kalkulasi == 'T'){
					$total = $dt->harga * $dt->netto;
				}else{
					$total = $dt->harga * $dt->rit;
				}

				\DB::table('payroll_driver')->insert([
					'payroll_id' => $payroll_id,
					'material_id' => $dt->material_id,
					'pekerjaan_id' => $dt->pekerjaan_id,
					'kalkulasi' => $dt->kalkulasi,
					'volume' => $dt->volume,
					'netto' => $dt->netto,
					'rit' => $dt->rit,
					'harga' => $dt->harga,
					'total' => $total
				]);
			}

			// insert data delivery_order yang tercover oleh payrol by date ke table payroll_driver_do
			$delivery_orders = \DB::table('delivery_order')
					->where('armada_id',$armada->id)
					// ->where('delivery_date','<=' ,$arr_tgl[2]."-".$arr_tgl[1]."-".$arr_tgl[0])
					->whereBetween('delivery_date',[$start_date_str,$end_date_str])
					->where('paid_to_driver','N')->get();

			foreach($delivery_orders as $dt){
				\DB::table('payroll_driver_do')->insert([
						'payroll_id' => $payroll_id,
						'delivery_order_id' => $dt->id
					]);
			}


			// UPDATE DELIVERY DRIVER STATUS PAID_TO_DRIVER TO P (PENDING)
			\DB::table('delivery_order')
					->where('armada_id',$armada->id)
					// ->where('delivery_date','<=' ,$arr_tgl[2]."-".$arr_tgl[1]."-".$arr_tgl[0])
					->whereBetween('delivery_date',[$start_date_str,$end_date_str])
					->where('paid_to_driver','N')
					->update(['paid_to_driver'=>'P']);


			return redirect('payroll/driver/edit/' . $payroll_id);

		});
	}

	public function update(Request $req){
		return \DB::transaction(function()use($req){
			$data_payroll = json_decode($req->payroll);
			$data_payroll_detail = json_decode($req->payroll_detail);

			// $payroll = \DB::table('payroll')->find($data_payroll->payroll_id);
			// $payroll_detail = \DB::table('payroll_driver')->where('payroll_id',$data_payroll->payroll_id)->get();

			$armada = \DB::table('armada')->where('karyawan_id',$data_payroll->karyawan_id)->first();

			// generate tanggal
		    $tanggal = $data_payroll->payment_date;
		    $arr_tgl = explode('-',$tanggal);
		    $tanggal = new \DateTime();
		    $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

		    $start_date = $data_payroll->start_date;
		    $arr_tgl = explode('-',$start_date);
		    $start_date = new \DateTime();
		    $start_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		    $start_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

			$end_date = $data_payroll->end_date;
			$arr_tgl = explode('-',$end_date);
			$end_date = new \DateTime();
			$end_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			$end_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

			\DB::table('payroll')->where('id',$data_payroll->payroll_id)->update([
					// 'karyawan_id' => $data_payroll->karyawan_id,
					// 'payroll_number' => $payroll_number,
					'payment_date' => $tanggal,
					// 'start_date' => $start_date,
					// 'end_date' => $end_date,
					'total' => $data_payroll->total,
					'potongan_bahan' => $data_payroll->potongan_bahan,
					'potongan_bon' => $data_payroll->potongan_bon,
					'sisa_bayaran' => $data_payroll->sisa_bayaran,
					'dp' => $data_payroll->dp,
					'saldo' => $data_payroll->saldo,
					// 'status' => 'O'
				]);

			// delete payroll detil di table payroll_driver 
			\DB::table('payroll_driver')->where('payroll_id',$data_payroll->payroll_id)->delete();

			// insert ke table payroll detail
				foreach($data_payroll_detail->detail as $dt){
					$total = 0;
					if($dt->kalkulasi == 'K'){
						$total = $dt->harga * $dt->volume;
					}else if($dt->kalkulasi == 'T'){
						$total = $dt->harga * $dt->netto;
					}else{
						$total = $dt->harga * $dt->rit;
					}

					\DB::table('payroll_driver')->insert([
						'payroll_id' => $data_payroll->payroll_id,
						'material_id' => $dt->material_id,
						'pekerjaan_id' => $dt->pekerjaan_id,
						'kalkulasi' => $dt->kalkulasi,
						'volume' => $dt->volume,
						'netto' => $dt->netto,
						'rit' => $dt->rit,
						'harga' => $dt->harga,
						'total' => $total
					]);
				}

				return redirect()->back();
		});
		

	}

	public function edit($payroll_id){
		$payroll = \DB::table('view_payroll')->find($payroll_id);
		 $payroll_detail = \DB::table('view_payroll_DRIVER')
									 	->where('payroll_id',$payroll_id)
										->get();

		if($payroll->status == 'P'){
			return view('payroll.driver.paid',[
				'data' => $payroll,
				'data_detail' => $payroll_detail,
			]);
		}	else{
			return view('payroll.driver.edit',[
				'data' => $payroll,
				'data_detail' => $payroll_detail,
			]);
		}


	}

	// VALIDATE PAYROLL
	public function validatePayroll($payroll_id){
		return \DB::transaction(function()use($payroll_id){
			$payroll = \DB::table('payroll')->find($payroll_id);
			$armada = \DB::table('armada')->where('karyawan_id',$payroll->karyawan_id)->first();

			// echo json_encode($armada);

			// update status di payroll
			\DB::table('payroll')
					->whereId($payroll_id)
					->update(['status'=>'P']);

			// update status paid_to_driver di data delivery_order

			// \DB::table('delivery_order')
			// 		->where('armada_id',$armada->id)
			// 		->whereBetween('delivery_date',[$payroll->start_date,$payroll->end_date])
			// 		->wherePaidToDriver('P')
			// 		->update([
			// 			'status' => 'Y'
			// 		]);
			\DB::update(\DB::raw('update delivery_order set paid_to_driver = "Y"
where delivery_order.id in (select delivery_order_id from payroll_driver_do where payroll_id = ' . $payroll_id . ')'));

					return redirect()->back();

		});

	}

	// CANCEL DATA PAYROLL
	public function cancelPayroll($payroll_id){
		return \DB::transaction(function()use($payroll_id){
			// $payroll = \DB::table('payroll')->find($payroll_id);
			// $armada = \DB::table('armada')->where('karyawan_id',$payroll->karyawan_id)->first();

			// update delivery order kembalikan ke Not Paid
// 			\DB::update(\DB::raw('update delivery_order set paid_to_driver = "N"
// where delivery_order.id in (select delivery_order_id from payroll_driver_do where payroll_id = ' . $payroll_id . ')'));

			// delete data payroll
			// otomatis data yang terhapus ada di table payroll_detail & payroll_driver_do
			\DB::table('payroll')->delete($payroll_id);

			// UPDATE DELIVERY DRIVER STATUS PAID_TO_DRIVER TO N 
			// $st_date = $payroll->start_date;
			// $arr_tgl = explode('-',$st_date);
			// $st_date = new \DateTime();
			// $st_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			// $st_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

			// $ed_date = $payroll->end_date;
			// $arr_tgl = explode('-',$ed_date);
			// $ed_date = new \DateTime();
			// $ed_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			// $ed_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];



			// echo $ed_date_str;

			// \DB::table('delivery_order')
			// 		->where('armada_id',$armada->id)
			// 		// ->where('delivery_date','<=' ,$arr_tgl[2]."-".$arr_tgl[1]."-".$arr_tgl[0])
			// 		->whereBetween('delivery_date',[$st_date_str,$ed_date_str])
			// 		->update(['paid_to_driver'=>'N']);

			return redirect('payroll/driver');
		});
	}

	public function getDeliveryOrderList($karyawan_id,$start_date, $end_date){
		// generate date
		$st_date = $start_date;
		$arr_tgl = explode('-',$st_date);
		$st_date = new \DateTime();
		$st_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$st_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

		$ed_date = $end_date;
		$arr_tgl = explode('-',$ed_date);
		$ed_date = new \DateTime();
		$ed_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$ed_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

		// $data = \DB::table('view_delivery_order')
		// 			->where('karyawan_id',$karyawan_id)
		// 			->where('delivery_date','<=',$arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0])
		// 			->where('paid_to_driver','N')
		// 			->get();
		$data = \DB::select("SELECT
						view_delivery_order.id,
						view_delivery_order.paid_to_driver,
						view_delivery_order.delivery_order_number,
						view_delivery_order.delivery_date,
						view_delivery_order.material_id,
						view_delivery_order.material,
						view_delivery_order.kalkulasi,
						sum(volume) as sum_volume,
						sum(netto) as sum_netto,
						sum(qty) as sum_qty,
						view_delivery_order.unit_price,
						view_delivery_order.total,
						view_delivery_order.pekerjaan,
						view_delivery_order.pekerjaan_id,
						view_delivery_order.alamat_pekerjaan,
						view_delivery_order.desa,
						view_delivery_order.kecamatan,
						view_delivery_order.kabupaten
					FROM
						view_delivery_order
					WHERE karyawan_id = " . $karyawan_id . "
					and paid_to_driver = 'N'
					and delivery_date between '" . $st_date_str . "' and '" . $ed_date_str . "'
					group by kalkulasi,material_id,pekerjaan_id
					");
			// return json_encode($data);
			echo json_encode($data);

	}

	public function deletePayroll($payroll_id){
		return \DB::transaction(function()use($payroll_id){
			\DB::table('payroll')->delete($payroll_id);
		});
	}

}
