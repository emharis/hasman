<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class JabatanController extends Controller
{
	public function index(){
		$data = \DB::table('jabatan')
				->select('jabatan.*',\DB::raw('(select count(id) from karyawan where karyawan.jabatan_id = jabatan.id) as ref'))
				->get();
		return view('master.jabatan.index',[
				'data' => $data
			]);
	}

	public function create(){
		
		return view('master.jabatan.create',[

			]);
	}

	public function insert(Request $req){
		\DB::table('jabatan')
			->insert([
					'nama' => $req->nama,
				]);

		return redirect('master/jabatan');
	}

	public function edit($id){
		$data = \DB::table('jabatan')->find($id);
		return view('master.jabatan.edit',[
				'data' => $data
			]);
	}

	public function update(Request $req){
		\DB::table('jabatan')
			->where('id',$req->id)
			->update([
					'nama' => $req->nama,
				]);
		return redirect('master/jabatan');
	}

	public function delete(Request $req){
		$dataid = json_decode($req->dataid);
		return \db::transaction(function()use($dataid){
			// delete dari database
			foreach($dataid as $dt){
				\DB::table('jabatan')->delete($dt->id);
			}

			return redirect('master/jabatan');

		});
	}

}
