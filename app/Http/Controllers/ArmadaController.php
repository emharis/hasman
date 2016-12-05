<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ArmadaController extends Controller
{
	public function index(){
		$data = \DB::table('view_armada')->orderBy('created_at','desc')->get();
		return view('master.armada.index',[
				'data' => $data
			]);
	}

	public function create(){
		$drivers = \DB::select('select id,nama,kode 
					from view_karyawan  
					where view_karyawan.kode_jabatan = "DV" and  view_karyawan.id not in (select karyawan_id from armada where karyawan_id is not null )
					');
		$selectDriver = [
				'0' => 'NONE'
			];

		foreach ($drivers as $dt){
			$selectDriver[$dt->id] = $dt->nama .' - ' . $dt->kode;
		}

		return view('master.armada.create',[
				'selectDriver' => $selectDriver,
			]);
	}

	public function insert(Request $req){
		// generate kode
		//------------------------------------------------------------------
		$prefix = \DB::table('appsetting')->whereName('armada_prefix')->first()->value;
		$counter = \DB::table('appsetting')->whereName('armada_counter')->first()->value;
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

		\DB::table('appsetting')->whereName('armada_counter')->update(['value'=>$counter]);
		//------------------------------------------------------------------

		\DB::table('armada')
			->insert([
					'nama' => $req->nama,
					'kode' => $kode,
					'nopol' => $req->nopol,
					'karyawan_id' => $req->driver,
				]);

		return redirect('master/armada');
	}

	public function edit($id){
		$data = \DB::table('armada')->find($id);
		$drivers = \DB::select('select * 
					from view_karyawan 
					where view_karyawan.kode_jabatan = "DV" and  view_karyawan.id not in (select karyawan_id from armada where karyawan_id is not null and armada.id != ' . $id . ')
					');
		$selectDriver = [
				'0' => 'NONE'
			];
		foreach($drivers as $dt){
			$selectDriver[$dt->id] = $dt->nama . ' [' . $dt->jabatan . ']';
		}
		return view('master.armada.edit',[
				'data' => $data,
				'selectDriver' => $selectDriver,
			]);
	}

	public function update(Request $req){
		\DB::table('armada')
			->where('id',$req->id)
			->update([
					'nama' => $req->nama,
					// 'kode' => $req->kode,
					'nopol' => $req->nopol,
					'karyawan_id' => $req->driver,
				]);
		return redirect('master/armada');
	}

	public function delete(Request $req){
		$dataid = json_decode($req->dataid);
		return \db::transaction(function()use($dataid){
			// delete dari database
			foreach($dataid as $dt){
				\DB::table('armada')->delete($dt->id);
			}

			return redirect('master/armada');

		});
	}

}
