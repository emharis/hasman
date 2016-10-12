<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PayrollController extends Controller
{
	public function staff(){
		// $data = \DB::table('alat')
		// 		->get();
		return view('payroll.staff.index',[
				// 'data' => $data
			]);
	}

  public function driver(){
    return view('payroll.driver.index');
  }

  public function driverCreate(){
    return view('payroll.driver.create');
  }

	public function getDeliveryOrderList($karyawan_id,$payment_date){
		// generate date
		$tanggal = $payment_date;
		$arr_tgl = explode('-',$tanggal);
		$tanggal = new \DateTime();
		$tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

		// $data = \DB::table('VIEW_DELIVERY_ORDER')
		// 			->where('karyawan_id',$karyawan_id)
		// 			->where('delivery_date','<=',$arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0])
		// 			->where('paid_to_driver','N')
		// 			->get();
		$data = \DB::select("SELECT
						VIEW_DELIVERY_ORDER.id,
						VIEW_DELIVERY_ORDER.paid_to_driver,
						VIEW_DELIVERY_ORDER.delivery_order_number,
						VIEW_DELIVERY_ORDER.delivery_date,
						VIEW_DELIVERY_ORDER.material_id,
						VIEW_DELIVERY_ORDER.material,
						VIEW_DELIVERY_ORDER.kalkulasi,
						sum(volume) as sum_volume,
						sum(netto) as sum_netto,
						sum(qty) as sum_qty,
						VIEW_DELIVERY_ORDER.unit_price,
						VIEW_DELIVERY_ORDER.total,
						VIEW_DELIVERY_ORDER.pekerjaan,
						VIEW_DELIVERY_ORDER.pekerjaan_id,
						VIEW_DELIVERY_ORDER.alamat_pekerjaan,
						VIEW_DELIVERY_ORDER.desa,
						VIEW_DELIVERY_ORDER.kecamatan,
						VIEW_DELIVERY_ORDER.kabupaten
					FROM
						VIEW_DELIVERY_ORDER
					WHERE karyawan_id = " . $karyawan_id . "
					and delivery_date <= '" . $arr_tgl[2]."-".$arr_tgl[1]."-".$arr_tgl[0] . "'
					group by kalkulasi,material_id,pekerjaan_id
					");
			// return json_encode($data);
			echo json_encode($data);

	}

}
