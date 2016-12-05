<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
	public function index(){
		$data = \DB::table('view_supplier')->get();
		return view('master.supplier.index',[
				'data' => $data
			]);
	}

	public function create(){
		
		return view('master.supplier.create',[

			]);
	}

	public function insert(Request $req){
		// generate kode
		//------------------------------------------------------------------
		$prefix = \DB::table('appsetting')->whereName('supplier_prefix')->first()->value;
		$counter = \DB::table('appsetting')->whereName('supplier_counter')->first()->value;
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

		\DB::table('appsetting')->whereName('supplier_counter')->update(['value'=>$counter]);
		//------------------------------------------------------------------

		\DB::table('supplier')
			->insert([
					'nama' => $req->nama,
					'kode' => $kode,
					'alamat' => $req->alamat,
					'desa_id' => $req->desa_id,
					'telp' => $req->telp,
					'telp2' => $req->telp2,
					'telp3' => $req->telp3,
				]);

		return redirect('master/supplier');
	}

	public function edit($id){
		$data = \DB::table('view_supplier')->find($id);

		return view('master.supplier.edit',[
				'data' => $data,
			]);
	}

	public function update(Request $req){
		\DB::table('supplier')
			->where('id',$req->id)
			->update([
					'nama' => $req->nama,
					// 'kode' => $req->kode,
					'alamat' => $req->alamat,
					'desa_id' => $req->desa_id,
					'telp' => $req->telp,
					'telp2' => $req->telp2,
					'telp3' => $req->telp3,
				]);
		return redirect('master/supplier');
	}

	public function delete(Request $req){
		$dataid = json_decode($req->dataid);
		return \db::transaction(function()use($dataid){
			// delete dari database
			foreach($dataid as $dt){
				\DB::table('supplier')->delete($dt->id);
			}

			return redirect('master/supplier');

		});
	}

}
