<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
	public function index(){
		$data = \DB::table('view_customer')
				->orderBy('created_at','desc')
				->get();
		return view('master.customer.index',[
				'data' => $data
			]);
	}

	public function create(){
		
		return view('master.customer.create',[

			]);
	}

	public function insert(Request $req){
		// generate kode
		//------------------------------------------------------------------
		$prefix = \DB::table('appsetting')->whereName('customer_prefix')->first()->value;
		$counter = \DB::table('appsetting')->whereName('customer_counter')->first()->value;
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

		\DB::table('appsetting')->whereName('customer_counter')->update(['value'=>$counter]);
		//------------------------------------------------------------------

		\DB::table('customer')
			->insert([
					'nama' => $req->nama,
					'kode' => $kode,
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
		$data = \DB::table('view_customer')->find($id);

		return view('master.customer.edit',[
				'data' => $data,
			]);
	}

	public function update(Request $req){
		\DB::table('customer')
			->where('id',$req->id)
			->update([
					'nama' => $req->nama,
					// 'kode' => $req->kode,
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
