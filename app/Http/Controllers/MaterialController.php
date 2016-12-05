<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MaterialController extends Controller
{
	public function index(){
		$data = \DB::table('material')
				// ->select('material.*',\DB::raw('(select count(id) from karyawan where karyawan.material_id = material.id) as ref'))
				->orderBy('created_at','desc')
				->get();
		return view('master.material.index',[
				'data' => $data
			]);
	}

	public function create(){
		
		return view('master.material.create',[

			]);
	}

	public function insert(Request $req){
		// generate kode
		//------------------------------------------------------------------
		$prefix = \DB::table('appsetting')->whereName('material_prefix')->first()->value;
		$counter = \DB::table('appsetting')->whereName('material_counter')->first()->value;
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

		\DB::table('appsetting')->whereName('material_counter')->update(['value'=>$counter]);
		//------------------------------------------------------------------

		\DB::table('material')
			->insert([
					'kode' => $kode,
					'nama' => $req->nama,
				]);

		return redirect('master/material');
	}

	public function edit($id){
		$data = \DB::table('material')->find($id);
		return view('master.material.edit',[
				'data' => $data
			]);
	}

	public function update(Request $req){
		\DB::table('material')
			->where('id',$req->id)
			->update([
					// 'kode' => $req->kode,
					'nama' => $req->nama,
				]);
		return redirect('master/material');
	}

	public function delete(Request $req){
		$dataid = json_decode($req->dataid);
		return \db::transaction(function()use($dataid){
			// delete dari database
			foreach($dataid as $dt){
				\DB::table('material')->delete($dt->id);
			}

			return redirect('master/material');

		});
	}

}
