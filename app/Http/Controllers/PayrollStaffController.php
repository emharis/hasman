<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PayrollStaffController extends Controller
{
	public function index(){
		$data = \DB::table('payroll')
				->where('kategori','S')
				->get();
		return view('payroll.staff.index',[
				'data' => $data
			]);
	}

	public function create(){
		return view('payroll.staff.create');
	}

	public function getAttendance($staff_id, $awal, $akhir){
		// generate tanggal
	    $start_date = $awal;
	    $arr_tgl = explode('-',$start_date);
	    $start_date = new \DateTime();
	    $start_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
	    $start_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

	    $end_date = $akhir;
	    $arr_tgl = explode('-',$end_date);
	    $end_date = new \DateTime();
	    $end_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
	    $end_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

	    $data = \DB::table('attend')
	    			->whereBetween('tgl',[$start_date_str,$end_date_str])
	    			->get();

	    return json_encode($data);
	}

}
