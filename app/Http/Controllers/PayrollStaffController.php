<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PayrollStaffController extends Controller
{
	public function index(){
		$data = \DB::table('view_payroll')
				->where('kategori','S')
				->orderBy('payment_date','desc')
				->get();
		return view('payroll.staff.index',[
				'data' => $data
			]);
	}

	public function create(){
		$staff = \DB::table('view_karyawan')
						->whereKodeJabatan('ST')
						->get();
		$selectStaff = [];
		foreach ($staff as $dt) {
			$selectStaff[$dt->id] = $dt->nama;
		}

		return view('payroll.staff.create',[
				'selectStaff' => $selectStaff
			]);
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
	    			->whereKaryawanId($staff_id)
	    			->whereBetween('tgl',[$start_date_str,$end_date_str])
	    			->get();

	    return json_encode($data);
	}

	public function getWorkday($staff_id,$awal,$akhir){
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

	    $attendance = \DB::select('select count(case when pagi = "Y" then 1 end) as pagi,count(case when siang = "Y" then 1 end) as siang from `attend` where `karyawan_id` = ' . $staff_id . ' and `tgl` between "' . $start_date_str . '" and "' . $end_date_str . '"');

	    $salary = \DB::table('karyawan')->find($staff_id)->gaji_pokok;
	    $daywork = ($attendance[0]->pagi + $attendance[0]->siang) / 2;

	    $data = (object)[	'gaji_pokok'=>$salary, 
	    					'daywork'=>$daywork
	    		];
	    // $data->gaji_pokok = $salary->gaji_pokok;
	    // $data->workday= ($data[0]->pagi + $data[0]->siang) / 2;

	    // echo $data->gaji_pokok;

	    return json_encode($data);

	    // return ($data[0]->pagi + $data[0]->siang) / 2;
	    // return $data[0]->pagi ;
	    // return $data[0]->siang ;
	}

	public function insert(Request $req){
		return \DB::transaction(function()use($req){
			$payroll = json_decode($req->payroll);

			// generate payroll number
			$payroll_counter = \DB::table('appsetting')->whereName('payroll_counter')->first()->value;
			$payroll_prefix = \DB::table('appsetting')->whereName('payroll_prefix')->first()->value;
			$payroll_number = $payroll_prefix ."/".date('Y/m').'/000'.$payroll_counter++;
			\DB::table('appsetting')->whereName('payroll_counter')->update(['value'=>$payroll_counter]);

			// generate tanggal
		    $payment_date = $payroll->payment_date;
		    $arr_tgl = explode('-',$payment_date);
		    $payment_date = new \DateTime();
		    $payment_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

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

			$payroll_id = \DB::table('payroll')->insertGetId([
					'kategori' => 'S',
					'payroll_number' => $payroll_number,
					'karyawan_id' => $payroll->karyawan_id,
					'start_date' => $start_date,
					'end_date' => $end_date,
					'payment_date' => $payment_date,
					'potongan_bon' => $payroll->potongan_bon,
					'basicpay' => $payroll->basic_pay,
					'daywork' => $payroll->daywork,
					'saldo' => $payroll->net_pay,
					'status' => 'O',
				]);

			return redirect('payroll/staff/edit/' . $payroll_id);
		});
	}

	public function edit($payroll_id){
		$data = \DB::table('view_payroll')->find($payroll_id);

		if($data->status =='O'){
			return view('payroll.staff.edit',[
					'data' => $data
				]);

		}else{
			return view('payroll.staff.paid',[
					'data' => $data
				]);						
		}
	}


	public function update(Request $req){
		return \DB::transaction(function()use($req){
			$payroll = json_decode($req->payroll);

			// generate tanggal
		    $payment_date = $payroll->payment_date;
		    $arr_tgl = explode('-',$payment_date);
		    $payment_date = new \DateTime();
		    $payment_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

			\DB::table('payroll')
				->whereId($payroll->payroll_id)
				->update([
					// 'payroll_number' => $payroll_number,
					// 'karyawan_id' => $payroll->karyawan_id,
					// 'start_date' => $start_date,
					// 'end_date' => $end_date,
					'payment_date' => $payment_date,
					'potongan_bon' => $payroll->potongan_bon,
					'basicpay' => $payroll->basic_pay,
					'daywork' => $payroll->daywork,
					'saldo' => $payroll->net_pay,
				]);

			return redirect('payroll/staff/edit/' . $payroll->payroll_id);
		});
	}

	public function validatePayroll($payroll_id){
		\DB::table('payroll')->whereId($payroll_id)->update([
				'status' => 'P'
			]);
		return redirect('payroll/staff/edit/' . $payroll_id);
	}

	public function cancelPayroll($payroll_id){
		return \DB::transaction(function()use($payroll_id){
			\DB::table('payroll')->delete($payroll_id);

			return redirect('payroll/staff');
		});
	}


}
