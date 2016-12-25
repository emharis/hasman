<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PayrollController extends Controller
{
	  public function index(){
	  	return view('payroll.payroll.index');
	  }

	  public function getPayDay(Request $req){
	  	$payday = \DB::table('appsetting')->where('name','payroll_day')->first()->value;

	  	$hari = "";
	  	if($payday == 0){
	  		$hari  = "Minggu";
	  	}else if($payday == 1){
	  		$hari  = "Senin";
	  	}else if($payday == 2){
	  		$hari  = "Selasa";
	  	}else if($payday == 3){
	  		$hari  = "Rabu";
	  	}else if($payday == 4){
	  		$hari  = "Kamis";
	  	}else if($payday == 5){
	  		$hari  = "Jumat";
	  	}else if($payday == 6){
	  		$hari  = "Sabtu";
	  	}


	  	$firstDateOfMonth = '01-' . $req->bulan;

	  	// generate tanggal
        $arr_tgl = explode('-',$firstDateOfMonth);
        $firstDateOfMonth = new \DateTime();
        $firstDateOfMonth->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

        $select_pay_day = [];
        // 0 = sunday

		for($i=1;$i<=$firstDateOfMonth->format('t');$i++){
			$aDate = new \DateTime();
			// echo $i . '  -   ' . $aDate->setDate($arr_tgl[2],$arr_tgl[1],$i)->format('w') .  '<br/>';
			if($aDate->setDate($arr_tgl[2],$arr_tgl[1],$i)->format('w') == $payday){
				array_push($select_pay_day, ['tanggal' => $i, 'tanggal_full' => $hari .', ' . $i .'-'.$arr_tgl[1].'-'.$arr_tgl[2]] );
			}
		}

		echo json_encode($select_pay_day);

	  }

	  // public function showPayrollTable(Request $req){
	  // 	return $req->jabatan == 'ST' ? $this->showStaffTable($req) : $this->showDriverTable($req);
	  // } 

	  public function showPayrollTable($tanggal, $kode_jabatan){
	  	return $kode_jabatan == 'ST' ? $this->showStaffTable($tanggal) : $this->showDriverTable($tanggal);

	  } 

	  public function showStaffTable($tanggal){
	  	// get data staff
	  	$data = \DB::table('view_karyawan')
	  				->where('kode_jabatan','ST')
	  				->where('is_active','Y')
	  				->orderBy('created_at','desc')
	  				->get();

	 //  	// generate date
		$tanggal = $tanggal;
		$arr_tgl = explode('-',$tanggal);
		$tanggal_gaji = new \DateTime();
		$tanggal_awal = new \DateTime();
		$tanggal_akhir = new \DateTime();
		$tanggal_gaji->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_awal->modify('-7 day');
		$tanggal_akhir->modify('-1 day');

		// echo $tanggal_awal->format('d-m-Y') . '<br/>';
		// echo $tanggal_akhir->format('d-m-Y') . '<br/>';

	  	foreach($data as $dt){
	  		$dt->payroll = \DB::table('payroll_staff')
								->whereKaryawanId($dt->id)
								->wherePaymentDate($tanggal_gaji->format('Y-m-d'))
								->first();
			$dt->total_pagi = \DB::table('attend')
								->select('pagi')
								->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
								->whereKaryawanId($dt->id)
								->where('pagi','Y')
								->count();
			$dt->total_siang = \DB::table('attend')
								->select('siang')
								->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
								->whereKaryawanId($dt->id)
								->where('siang','Y')
								->count();
	  	}

	  	

	  	return view('payroll.staff.payroll-table',[
	  			'data' => $data,
	  			'tanggal_penggajian' =>$tanggal_gaji->format('d-m-Y')
	  		]);
	  }

	  public function showDriverTable(Request $req){}

	  // insert / register payroll payment
	  public function addPay($karyawan_id,$tanggal){
  	 	// generate date
		$arr_tgl = explode('-',$tanggal);
		$tanggal_gaji = new \DateTime();
		$tanggal_awal = new \DateTime();
		$tanggal_akhir = new \DateTime();
		$tanggal_awal_siang_for_table_presensi = new \DateTime();
		$tanggal_akhir_siang_for_table_presensi = new \DateTime();
		$tanggal_gaji->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_awal->modify('-7 day');
		$tanggal_akhir->modify('-1 day');

		$tanggal_awal_siang_for_table_presensi->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_awal_siang_for_table_presensi->modify('-7 day');
		$tanggal_akhir_siang_for_table_presensi->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_akhir_siang_for_table_presensi->modify('-1 day');

	  	$data = \DB::table('view_karyawan')->find($karyawan_id);
	  	$data->total_hadir_pagi  = \DB::table('attend')
								->select('pagi')
								->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
								->whereKaryawanId($data->id)
								->where('pagi','Y')
								->count();
		$data->total_hadir_siang = \DB::table('attend')
								->select('siang')
								->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
								->whereKaryawanId($data->id)
								->where('siang','Y')
								->count();
		$data->presensi_pagi = \DB::table('attend')
								->select('pagi','tgl')
								->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
								->whereKaryawanId($data->id)
								->orderBy('tgl')
								->get();
		$data->presensi_siang = \DB::table('attend')
								->select('siang','tgl')
								->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
								->whereKaryawanId($data->id)
								->orderBy('tgl')
								->get();

	  	return view('payroll.staff.add-pay',[
	  			'data' => $data,
	  			'tanggal_gaji' => $tanggal,
	  			'tanggal_awal' => $tanggal_awal,
	  			'tanggal_akhir' => $tanggal_gaji,
	  			'tanggal_awal_siang_for_table_presensi' => $tanggal_awal_siang_for_table_presensi,
	  			'tanggal_akhir_siang_for_table_presensi' => $tanggal_akhir_siang_for_table_presensi,
	  		]);
	  }

	  	// INSERT PAYROLL PAYMENT
		public function insertPay(Request $req){
			return \DB::transaction(function()use($req){
				// generate tanggal
		        $arr_tgl = explode('-',$req->pay_date);
		        $tanggal = new \DateTime();
		        $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

		        // generate payroll
		        $counter = \DB::table('appsetting')->whereName('payroll_counter')->first()->value;
		        $prefix = \DB::table('appsetting')->whereName('payroll_prefix')->first()->value;
		        $payroll_number  = $prefix . '/'.date('Y').'/'.date('m').'/'.$counter++;


				$payroll_id = \DB::table('payroll_staff')->insertGetId([
						'status' => 'O',
						'payroll_number' => $payroll_number,
						'payment_date' => $tanggal,
						'karyawan_id' => $req->karyawan_id,
						'basic_pay' => $req->basic_pay,
						'total_pagi' => $req->total_pagi,
						'total_siang' => $req->total_siang,
						'potongan' => $req->potongan,
						'netpay' => (($req->total_pagi + $req->total_siang ) /2) * $req->basic_pay - $req->potongan,
						'user_id' => \Auth::user()->id
					]);


		        // update counter
		        \DB::table('appsetting')
		        	->whereName('payroll_counter')
		        	->update(['value'=>$counter]);

		        return redirect('payroll/payroll/edit-pay/' . $payroll_id);
			});
	  	}

	  	// EDIT PAYROLL
	  	public function editPay($payroll_id){
	  		$data = \DB::table('view_payroll_staff')
	  						->whereId($payroll_id)
	  						->select('view_payroll_staff.*',\DB::raw('date_format(payment_date,"%d-%m-%Y") as payment_date_formatted'))
	  						->first();

	  		// generate date
				$arr_tgl = explode('-',$data->payment_date_formatted);
				$tanggal_gaji = new \DateTime();
				$tanggal_awal = new \DateTime();
				$tanggal_akhir = new \DateTime();
				$tanggal_awal_siang_for_table_presensi = new \DateTime();
				$tanggal_akhir_siang_for_table_presensi = new \DateTime();
				$tanggal_gaji->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
				$tanggal_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
				$tanggal_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
				$tanggal_awal->modify('-7 day');
				$tanggal_akhir->modify('-1 day');

				$tanggal_awal_siang_for_table_presensi->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
				$tanggal_awal_siang_for_table_presensi->modify('-7 day');
				$tanggal_akhir_siang_for_table_presensi->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
				$tanggal_akhir_siang_for_table_presensi->modify('-1 day');


		  		$data->total_hadir_pagi  = \DB::table('attend')
									->select('pagi')
									->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
									->whereKaryawanId($data->karyawan_id)
									->where('pagi','Y')
									->count();
				$data->total_hadir_siang = \DB::table('attend')
										->select('siang')
										->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
										->whereKaryawanId($data->karyawan_id)
										->where('siang','Y')
										->count();
				$data->presensi_pagi = \DB::table('attend')
										->select('pagi','tgl')
										->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
										->whereKaryawanId($data->karyawan_id)
										->orderBy('tgl')
										->get();
				$data->presensi_siang = \DB::table('attend')
										->select('siang','tgl')
										->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
										->whereKaryawanId($data->karyawan_id)
										->orderBy('tgl')
										->get();

	  		// cek apakah sudah ter-validate
	  		if($data->status == 'P'){
	  			return view('payroll/staff/validated-pay',[
		  				'data' => $data,
		  				'tanggal_awal' => $tanggal_awal,
			  			'tanggal_akhir' => $tanggal_gaji,
			  			'tanggal_awal_siang_for_table_presensi' => $tanggal_awal_siang_for_table_presensi,
			  			'tanggal_akhir_siang_for_table_presensi' => $tanggal_akhir_siang_for_table_presensi,
		  			]);
	  		}else{
		  		
		  		return view('payroll/staff/edit-pay',[
		  				'data' => $data,
		  				'tanggal_awal' => $tanggal_awal,
			  			'tanggal_akhir' => $tanggal_gaji,
			  			'tanggal_awal_siang_for_table_presensi' => $tanggal_awal_siang_for_table_presensi,
			  			'tanggal_akhir_siang_for_table_presensi' => $tanggal_akhir_siang_for_table_presensi,
		  			]);
	  			
	  		}

	  	}

	  	// UPDATE PAYROLL
	  	public function updatePay(Request $req){
			return \DB::transaction(function()use($req){
				\DB::table('payroll_staff')
						->whereId($req->payroll_id)
						->update([
						'total_pagi' => $req->total_pagi,
						'total_siang' => $req->total_siang,
						'potongan' => $req->potongan,
						'netpay' => (($req->total_pagi + $req->total_siang ) /2) * $req->basic_pay - $req->potongan,
					]);

		        return redirect()->back();
			});
	  	}

	  	// VALIDATE PAYROLL
	  	public function validatePay($payroll_id){
	  		return \DB::transaction(function()use($payroll_id){
				\DB::table('payroll_staff')
						->whereId($payroll_id)
						->update([
						'status' => 'P'
					]);

		        return redirect()->back();
			});
	  	}

	  	// RESET PAYROLL
	  	public function resetPay($payroll_id){
	  		return \DB::transaction(function()use($payroll_id){
	  			$data_payroll = \DB::table('payroll_staff')->find($payroll_id);

	  			$arr_tgl = explode('-',$data_payroll->payment_date);
	        	$tanggal = new \DateTime();
	        	$tanggal->setDate($arr_tgl[0],$arr_tgl[1],$arr_tgl[2]);

				\DB::table('payroll_staff')
						->delete($payroll_id);

		        return redirect('payroll/payroll/pay/' . $data_payroll->karyawan_id . '/' . $tanggal->format('d-m-Y'));
			});
	  	}

	  	// PRINT PDF
	  	public function printPdf($payroll_id){
	  		echo 'print pdf';
	  	}
	  	// PRINT PDF & COPY
	  	public function printCopy($payroll_id){
	  		echo 'print copy';
	  	}
	  	// PRINT DIRECT
	  	public function printDirect($payroll_id){
	  		echo 'print direct';
	  	}


}
