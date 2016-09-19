<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
	public function index(){
		$data = \DB::table('VIEW_CUSTOMER')->get();
		return view('master.customer.index',[
				'data' => $data
			]);
	}

	public function create(){
		
		return view('master.customer.create',[

			]);
	}

	public function insert(Request $req){
		\DB::table('customer')
			->insert([
					'nama' => $req->nama,
					'kode' => $req->kode,
					'npwp' => $req->npwp,
					'owner' => $req->owner,
					'alamat' => $req->alamat,
					'desa_id' => $req->desa_id,
					'telp' => $req->telp,
					'telp2' => $req->telp2,
					'telp3' => $req->telp3,
				]);

		return redirect('master/customer');
	}

	public function edit($id){
		$data = \DB::table('VIEW_CUSTOMER')->find($id);

		return view('master.customer.edit',[
				'data' => $data,
			]);
	}

	public function update(Request $req){
		\DB::table('customer')
			->where('id',$req->id)
			->update([
					'nama' => $req->nama,
					'kode' => $req->kode,
					'npwp' => $req->npwp,
					'owner' => $req->owner,
					'alamat' => $req->alamat,
					'desa_id' => $req->desa_id,
					'telp' => $req->telp,
					'telp2' => $req->telp2,
					'telp3' => $req->telp3,
				]);
		return redirect('master/customer');
	}

	public function delete(Request $req){
		$dataid = json_decode($req->dataid);
		return \db::transaction(function()use($dataid){
			// delete dari database
			foreach($dataid as $dt){
				\DB::table('customer')->delete($dt->id);
			}

			return redirect('master/customer');

		});
	}

}
