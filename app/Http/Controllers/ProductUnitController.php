<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductUnitController extends Controller
{
	public function index(){
		$data = \DB::table('product_unit')
				// ->select('product.*',\DB::raw('(select count(id) from karyawan where karyawan.product_id = product.id) as ref'))
				->get();
		return view('master.product_unit.index',[
				'data' => $data
			]);
	}

	public function create(){
		
		return view('master.product_unit.create',[

			]);
	}

	public function insert(Request $req){
		\DB::table('product_unit')
			->insert([
					
					'nama' => $req->nama,
				]);

		return redirect('master/unit');
	}

	public function edit($id){
		$data = \DB::table('product_unit')->find($id);
		return view('master.product_unit.edit',[
				'data' => $data
			]);
	}

	public function update(Request $req){
		\DB::table('product_unit')
			->where('id',$req->id)
			->update([
					
					'nama' => $req->nama,
				]);
		return redirect('master/unit');
	}

	public function delete(Request $req){
		$dataid = json_decode($req->dataid);
		return \db::transaction(function()use($dataid){
			// delete dari database
			foreach($dataid as $dt){
				\DB::table('product_unit')->delete($dt->id);
			}

			return redirect('master/unit');

		});
	}

}
