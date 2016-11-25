<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PekerjaanController extends Controller
{
	public function index(){
		// $data = \DB::table('pekerjaan')
		// 		// ->select('pekerjaan.*',\DB::raw('(select count(id) from karyawan where karyawan.pekerjaan_id = pekerjaan.id) as ref'))
		// 		->join('customer','pekerjaan.customer_id','=','customer.id')
		// 		->select('pekerjaan.*',\DB::raw('customer.kode as kode_customer, customer.nama as customer'),\DB::raw('(select case when count(sales_order.id) > 0 then "false" else "true" end from sales_order where sales_order.pekerjaan_id = pekerjaan.id) as can_delete'))
		// 		->orderBy('customer_id')
		// 		->get();

		$data = \DB::table('view_pekerjaan')
				// ->orderBy('customer_id')
				->orderBy('created_at','desc')
				->get();
		return view('master.pekerjaan.index',[
				'data' => $data
			]);
	}

	public function create(){
		
		return view('master.pekerjaan.create',[

			]);
	}

	public function insert(Request $req){
		\DB::table('pekerjaan')
			->insert([
					'customer_id' => $req->customer_id,
					'nama' => $req->nama,
					'alamat' => $req->alamat,
					'desa_id' => $req->desa_id,
					'tahun' => $req->tahun,
				]);

		return redirect('master/pekerjaan');
	}

	public function edit($id){
		$data = \DB::table('view_pekerjaan')->find($id);
		return view('master.pekerjaan.edit',[
				'data' => $data
			]);
	}

	public function update(Request $req){
		\DB::table('pekerjaan')
			->where('id',$req->id)
			->update([
					'nama' => $req->nama,
					'alamat' => $req->alamat,
					'desa_id' => $req->desa_id,
					'tahun' => $req->tahun,
				]);
		return redirect('master/pekerjaan');
	}

	public function delete(Request $req){
		$dataid = json_decode($req->dataid);
		return \db::transaction(function()use($dataid){
			// delete dari database
			foreach($dataid as $dt){
				\DB::table('pekerjaan')->delete($dt->id);
			}

			return redirect('master/pekerjaan');

		});
	}

}
