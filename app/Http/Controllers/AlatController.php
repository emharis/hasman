<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AlatController extends Controller
{
	public function index(){
		$data = \DB::table('alat')
				// ->select('alat.*',\DB::raw('(select count(id) from karyawan where karyawan.alat_id = alat.id) as ref'))
				->orderBy('created_at','desc')
				->get();
		return view('master.alat.index',[
				'data' => $data
			]);
	}

	public function create(){
		
		return view('master.alat.create',[

			]);
	}

	public function insert(Request $req){
		// generate kode
		//------------------------------------------------------------------
		$prefix = \DB::table('appsetting')->whereName('alat_prefix')->first()->value;
		$counter = \DB::table('appsetting')->whereName('alat_counter')->first()->value;
		$zero;

		if( strlen($counter) == 1){
				$zero = "000";
			}elseif( strlen($counter) == 2){
					$zero = "00";
			}elseif( strlen($counter) == 3){
					$zero = "0";
			}else{
					$zero =  "";
			}

		$kode = $prefix . $zero . $counter++;

		\DB::table('appsetting')->whereName('alat_counter')->update(['value'=>$counter]);
		//------------------------------------------------------------------

		\DB::table('alat')
			->insert([
					'kode' => $kode,
					'nama' => $req->nama,
				]);

		return redirect('master/alat');
	}

	public function edit($id){
		$data = \DB::table('alat')->find($id);
		return view('master.alat.edit',[
				'data' => $data
			]);
	}

	public function update(Request $req){
		\DB::table('alat')
			->where('id',$req->id)
			->update([
					// 'kode' => $req->kode,
					'nama' => $req->nama,
				]);
		return redirect('master/alat');
	}

	public function delete(Request $req){
		$dataid = json_decode($req->dataid);
		return \db::transaction(function()use($dataid){
			// delete dari database
			foreach($dataid as $dt){
				\DB::table('alat')->delete($dt->id);
			}

			return redirect('master/alat');

		});
	}

}
