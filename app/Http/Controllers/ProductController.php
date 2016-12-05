<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
	public function index(){
		$data = \DB::select('SELECT product.*, product_unit.nama AS product_unit FROM product_unit INNER JOIN product ON product_unit.id = product.product_unit_id order by created_at desc');
		return view('master.product.index',[
				'data' => $data
			]);
	}

	public function create(){

		$unit = \DB::table('product_unit')->get();
		$select_unit = [];
		foreach($unit as $dt){
			$select_unit[$dt->id] = $dt->nama;
		}
		
		return view('master.product.create',[
				'select_unit' => $select_unit
			]);
	}

	public function insert(Request $req){
		// generate kode
		//------------------------------------------------------------------
		$prefix = \DB::table('appsetting')->whereName('product_prefix')->first()->value;
		$counter = \DB::table('appsetting')->whereName('product_counter')->first()->value;
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

		\DB::table('appsetting')->whereName('product_counter')->update(['value'=>$counter]);
		//------------------------------------------------------------------

		\DB::table('product')
			->insert([
					'kode' => $kode,
					'nama' => $req->nama,
					'product_unit_id' => $req->product_unit,
				]);

		return redirect('master/product');
	}

	public function edit($id){
		$unit = \DB::table('product_unit')->get();
		$select_unit = [];
		foreach($unit as $dt){
			$select_unit[$dt->id] = $dt->nama;
		}

		$data = \DB::table('product')->find($id);

		return view('master.product.edit',[
				'data' => $data,
				'select_unit' => $select_unit,
			]);
	}

	public function update(Request $req){
		\DB::table('product')
			->where('id',$req->id)
			->update([
					// 'kode' => $req->kode,
					'nama' => $req->nama,
					'product_unit_id' => $req->product_unit,
				]);
		return redirect('master/product');
	}

	public function delete(Request $req){
		$dataid = json_decode($req->dataid);
		return \db::transaction(function()use($dataid){
			// delete dari database
			foreach($dataid as $dt){
				\DB::table('product')->delete($dt->id);
			}

			return redirect('master/product');

		});
	}

}
