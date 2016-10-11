<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class KaryawanController extends Controller
{
	public function index(){
		$data = \DB::table('VIEW_KARYAWAN')->orderBy('created_at','desc')->get();
		return view('master.karyawan.index',[
				'data' => $data
			]);
	}

	public function create(){
		$jabatans = \DB::table('jabatan')->get();
		$selectJabatan = [];
		foreach($jabatans as $dt){
			$selectJabatan[$dt->id] = $dt->nama;
		}

		return view('master.karyawan.create',[
				'selectJabatan' => $selectJabatan
			]);
	}

	public function insert(Request $req){
		return \DB::transaction(function()use($req){

			// generate tanggal
			if($req->tgl_lahir != "" ){
	      $tgl_lahir = $req->tgl_lahir;
	      $arr_tgl = explode('-',$tgl_lahir);
	      $fix_tgl_lahir = new \DateTime();
	      $fix_tgl_lahir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			}else{
				$fix_tgl_lahir = 'NULL';
			}

			$karyawan_id = \DB::table('karyawan')
			->insertGetId([
					'nama' => $req->nama,
					'panggilan' => $req->panggilan,
					'kode' => $req->kode,
					'ktp' => $req->ktp,
					'alamat' => $req->alamat,
					'desa_id' => $req->desa_id,
					'telp' => $req->telp,
					'jabatan_id' => $req->jabatan,
					'tgl_lahir' => $fix_tgl_lahir,
					'tempat_lahir' => $req->tempat_lahir,
					'gaji_pokok' => $req->gaji_pokok,
				]);

			//insert foto
			if($req->foto){
				$foto = $req->foto;
				$foto_name = 'foto_karyawan_' . str_random(10) . $karyawan_id . '.'.$foto->getClientOriginalExtension();

				$foto->move(
					base_path() . '/public/foto_karyawan/', $foto_name
				);

				// update ke table karyawan
				\DB::table('karyawan')
					->where('id',$karyawan_id)->update([
						'foto' => $foto_name
					]);
			}


			return redirect('master/karyawan');

		});

	}

	public function edit($id){
		$data = \DB::table('VIEW_KARYAWAN')->find($id);

		$jabatans = \DB::table('jabatan')->get();
		$selectJabatan = [];
		foreach($jabatans as $dt){
			$selectJabatan[$dt->id] = $dt->nama;
		}


		return view('master.karyawan.edit',[
				'data' => $data,
				'selectJabatan' => $selectJabatan
			]);
	}

	public function update(Request $req){
		return \DB::transaction(function()use($req){
			// generate tanggal
            $tgl_lahir = $req->tgl_lahir;
			if($req->tgl_lahir != ""){

	            $arr_tgl = explode('-',$tgl_lahir);
	            $tgl_lahir = new \DateTime();
	            $tgl_lahir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
        	}

			\DB::table('karyawan')
			->where('id',$req->id)
			->update([
					'nama' => $req->nama,
					'panggilan' => $req->panggilan,
					'kode' => $req->kode,
					'ktp' => $req->ktp,
					'alamat' => $req->alamat,
					'desa_id' => $req->desa_id,
					'telp' => $req->telp,
					'jabatan_id' => $req->jabatan,
					'tgl_lahir' => $tgl_lahir,
					'tempat_lahir' => $req->tempat_lahir,
					'gaji_pokok' => $req->gaji_pokok,
				]);

			$foto_lama = \DB::table('karyawan')->find($req->id)->foto;

			//insert foto
			if($req->foto){
				// hapus foto yang lama
				 if(file_exists(base_path() . '/public/foto_karyawan/'. $foto_lama)){
			        @unlink(base_path() . '/public/foto_karyawan/'. $foto_lama);
			     }

				$foto = $req->foto;
				$foto_name = 'foto_karyawan_' . str_random(10) . $req->id . '.'.$foto->getClientOriginalExtension();

				$foto->move(
					base_path() . '/public/foto_karyawan/', $foto_name
				);

				// update ke table karyawan
				\DB::table('karyawan')
					->where('id',$req->id)->update([
						'foto' => $foto_name
					]);
			}

			return redirect('master/karyawan');
		});

	}

	public function delete(Request $req){
		$dataid = json_decode($req->dataid);
		return \db::transaction(function()use($dataid){
			// delete dari database
			foreach($dataid as $dt){
				// DELETE FOTO
				$foto_lama = \DB::table('karyawan')->find($dt->id)->foto;
				 if(file_exists(base_path() . '/public/foto_karyawan/'. $foto_lama)){
			        @unlink(base_path() . '/public/foto_karyawan/'. $foto_lama);
			     }
				// DEL;ETE DATA FROM DATABASE
				\DB::table('karyawan')->delete($dt->id);
			}

			return redirect('master/karyawan');

		});
	}

}
