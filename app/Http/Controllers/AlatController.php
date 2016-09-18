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
		\DB::table('alat')
			->insert([
					'kode' => $req->kode,
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
					'kode' => $req->kode,
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
