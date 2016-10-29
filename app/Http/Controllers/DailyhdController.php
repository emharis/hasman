<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DailyhdController extends Controller
{
	public function index(){
		$data = \DB::table('VIEW_DAILYHD')
				->orderBy('tanggal','desc')
				->get();
		
		return view('dailyhd.index',[
				'data' => $data
			]);
	}

	public function create(){
		return view('dailyhd.create');
	}

	public function insert(Request $req){
		return \DB::transaction(function()use($req){
			// generate number
			$dailyhd_counter = \DB::table('appsetting')->whereName('dailyhd_counter')->first()->value;
			$dailyhd_number = 'HAB/' . date('Y') . '/000' . $dailyhd_counter++;
			\DB::table('appsetting')
				->whereName('dailyhd_counter')
				->update(['value'=>$dailyhd_counter]);

			// generate tanggal
            $tanggal = $req->tanggal;
            $arr_tgl = explode('-',$tanggal);
            $tanggal = new \DateTime();
            $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

			$dailyhd_id = \DB::table('dailyhd')
				->insertGetId([
						'ref' => $dailyhd_number,
						'tanggal' => $tanggal,
						'alat_id' => $req->alat_id,
						'lokasi_galian_id' => $req->lokasi_id,
						'pengawas_id' => $req->pengawas_id,
						'operator_id' => $req->operator_id,
						'mulai' => $req->mulai,
						'selesai' => $req->selesai,
						'istirahat_mulai' => $req->istirahat_mulai,
						'istirahat_selesai' => $req->istirahat_selesai,
						'jam_kerja' => $req->total_jam_kerja,
						'oli' => $req->oli,
						'solar' => $req->solar,
						'desc' => $req->keterangan,
					]);
				
			// return redirect('dailyhd');
			return redirect('dailyhd/edit/'.$dailyhd_id);
		});

		
	}

	public function edit($id){
		$data = \DB::table('VIEW_DAILYHD')->find($id);
		if($data->status == 'O'){
			return view('dailyhd.edit',['data'=>$data]);
		}else{
			return view('dailyhd.validated',['data'=>$data]);
		}
		
	}

	public function update(Request $req){
		return \DB::transaction(function()use($req){
			// generate tanggal
            $tanggal = $req->tanggal;
            $arr_tgl = explode('-',$tanggal);
            $tanggal = new \DateTime();
            $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

			\DB::table('dailyhd')
				->whereId($req->dailyhd_id)
				->update([
						// 'ref' => $dailyhd_number,
						'tanggal' => $tanggal,
						// 'alat_id' => $req->alat_id,
						// 'lokasi_galian_id' => $req->lokasi_id,
						// 'pengawas_id' => $req->pengawas_id,
						// 'operator_id' => $req->operator_id,
						'mulai' => $req->mulai,
						'selesai' => $req->selesai,
						'istirahat_mulai' => $req->istirahat_mulai,
						'istirahat_selesai' => $req->istirahat_selesai,
						'jam_kerja' => $req->total_jam_kerja,
						'oli' => $req->oli,
						'solar' => $req->solar,
						'desc' => $req->keterangan,
					]);
				
			// return redirect('dailyhd');
			return redirect()->back();
		});
	}

	public function delete(Request $req){
		$dataid = json_decode($req->dataid);
		foreach($dataid as $dt){
			// echo $dt->id . '<br/>';
			\DB::table('dailyhd')->delete($dt->id);	
		}
		


	}

	public function cekDuplikasi($tgl, $alatid){
		// generate tanggal
        $tanggal = $tgl;
        $arr_tgl = explode('-',$tanggal);
        $tanggal = new \DateTime();
        $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
        $tanggal_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        $data = \DB::table('dailyhd')
        		->where('tanggal',$tanggal_str)
        		->where('alat_id',$alatid)
        		->get();

        return count($data) > 0 ? 'true' : 'false';
	}

	public function toValidate(Request $req){
		\DB::table('dailyhd')->whereId($req->dailyhd_id)->update([
				'status' => 'V'
			]);

		return redirect('dailyhd');
	}

}
