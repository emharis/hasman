<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
	public function setting(){
		$in_time = \DB::table('appsetting')->where('name','attendance_in_time')->first()->value;
		$out_time = \DB::table('appsetting')->where('name','attendance_out_time')->first()->value;
		$libur_sabtu = \DB::table('appsetting')->where('name','attendance_libur_sabtu')->first()->value;
		$libur_minggu = \DB::table('appsetting')->where('name','attendance_libur_minggu')->first()->value;
		$holiday = \DB::table('attend_holiday')->orderBy('tgl','desc')->get();

		return view('attendance.setting',[
					'in_time' => $in_time,
					'out_time' => $out_time,
					'libur_sabtu' => $libur_sabtu,
					'libur_minggu' => $libur_minggu,
					'holiday' => $holiday,
			]);
	}

	public function updateTimeSetting(Request $req){

		return \DB::transaction(function()use($req){
			// update in_time on table appsetting
			\DB::table('appsetting')
					->where('name','attendance_in_time')
					->update([
						'value' => $req->in_time
					]);
			// update out_time on table appsetting
			\DB::table('appsetting')
					->where('name','attendance_out_time')
					->update([
						'value' => $req->out_time
					]);

			// update libur_sabtu on table appsetting
			\DB::table('appsetting')
					->where('name','attendance_libur_sabtu')
					->update([
						'value' => $req->libur_sabtu ? 'Y' : 'N'
					]);

					// update libur_minggu on table appsetting
					\DB::table('appsetting')
							->where('name','attendance_libur_minggu')
							->update([
								'value' => $req->libur_minggu ? 'Y' : 'N'
							]);

			// return
			return redirect()->back();
		});
	}

	// INSERT DATA HOLIDAY
	public function insertHoliday(Request $req){
		return \DB::transaction(function()use($req){
			// generate date
			$tanggal = $req->tanggal;
			$arr_tgl = explode('-',$tanggal);
			$tanggal = new \DateTime();
			$tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			// insert into table holiday
			\DB::table('attend_holiday')->insert([
				'tgl' => $tanggal,
				'keterangan' => $req->keterangan,
			]);

			return redirect('attendance/setting#tab_2');
		});
	}

	// DELETE DATA HOLIDAY
	public function deleteHoliday($holiday_id){
		\DB::table('attend_holiday')->delete($holiday_id);
		return redirect('attendance/setting#tab_2');
	}

	// TAMPILKAN FORM ABSENSI
	public function attend(){
		return redirect('attendance/attend/index');
	}

}
