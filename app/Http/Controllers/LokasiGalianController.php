<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LokasiGalianController extends Controller
{
	public function index(){
		$data = \DB::table('VIEW_LOKASI_GALIAN')->get();
		return view('master.lokasi_galian.index',[
				'data' => $data
			]);
	}

	public function create(){
		
		return view('master.lokasi_galian.create',[

			]);
	}

	public function insert(Request $req){
		\DB::table('lokasi_galian')
			->insert([
					'nama' => $req->nama,
					'kode' => $req->kode,
					'desa_id' => $req->desa_id,
					'keterangan' => $req->keterangan,
				]);

		return redirect('master/lokasi');
	}

	public function edit($id){
		$data = \DB::table('VIEW_LOKASI_GALIAN')->find($id);
		return view('master.lokasi_galian.edit',[
				'data' => $data
			]);
	}

	public function update(Request $req){
		\DB::table('lokasi_galian')
			->where('id',$req->id)
			->update([
					'nama' => $req->nama,
					'kode' => $req->kode,
					'desa_id' => $req->desa_id,
					'keterangan' => $req->keterangan,
				]);
		return redirect('master/lokasi');
	}

	public function delete(Request $req){
		$dataid = json_decode($req->dataid);
		return \db::transaction(function()use($dataid){
			// delete dari database
			foreach($dataid as $dt){
				\DB::table('lokasi_galian')->delete($dt->id);
			}

			return redirect('master/lokasi');

		});
	}

}
