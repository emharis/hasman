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
		$libur_sabtu = \DB::table('appsetting')->where('name','attendance_libur_sabtu')->first()->value;
		$libur_minggu = \DB::table('appsetting')->where('name','attendance_libur_minggu')->first()->value;
		return view('attendance/attend/index',[
			'libur_sabtu' => $libur_sabtu,
			'libur_minggu' => $libur_minggu,
		]);
	}

	public function insertAttend(Request $req){
		return \DB::transaction(function()use($req){
			// generate date
			$tanggal = $req->tanggal;
			$arr_tgl = explode('-',$tanggal);
			$tanggal = new \DateTime();
			$tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

			// delete data sebelumnya
			\DB::table('attend')
				->where('tgl',$arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0])
				->delete();

			// insert data baru
			$data_presensi = json_decode($req->data_presensi);
			// echo var_dump($data_presensi->presensi);
			foreach($data_presensi->presensi as $dt){
					\DB::table('attend')
						->insert([
							'tgl' => $tanggal,
							'karyawan_id' => $dt->karyawan_id,
							'pagi' => $dt->pagi == 'true' ? 'Y':'N',
							'siang' => $dt->siang == 'true' ? 'Y':'N',
						]);
			}

			return redirect()->back();
		});
	}

	public function getAttendanceTable(Request $req){
		// generate date
		$tanggal = $req->tanggal;
		$arr_tgl = explode('-',$tanggal);
		$tanggal = new \DateTime();
		$tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

		// $data = json_decode('{"presensi":"","status":""}');
		// $data->presensi = \DB::table('view_attend')
		// 				->where('tgl',$arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0])
		// 				->where('jabatan_id',4)
		// 				->get();
		// $data->status = 'P';

		// if(count($data->presensi) == 0){
		// 	$data->presensi = \DB::select('SELECT
		// 						karyawan.id,
		// 						karyawan.kode,
		// 						karyawan.nama,
		// 						karyawan.jabatan_id,
		// 						jabatan.nama AS jabatan
		// 					FROM
		// 						karyawan
		// 						INNER JOIN jabatan
		// 						 ON karyawan.jabatan_id = jabatan.id
		// 					where jabatan_id = 4 and is_active ="Y"
		// 						 ');
		// 	$data->status = 'D';
		// }

		$data = json_decode('{"karyawan":""}');
		$data->karyawan = \DB::table('view_karyawan')
							->where('kode_jabatan','ST')
							->where('is_active','Y')
							->get();

		foreach($data->karyawan as $dk){
			$dk->presensi = \DB::table('attend')
								->select('tgl','pagi','siang','karyawan_id')
								->whereKaryawanId($dk->id)
								->whereTgl($tanggal_str)
								->first();

			// echo json_encode($dk->presensi .'<br/><br/><br/>';
		}

		return json_encode($data);
	}

}
