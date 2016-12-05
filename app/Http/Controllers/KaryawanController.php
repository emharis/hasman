<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class KaryawanController extends Controller
{
	public function index(){
		$data = \DB::table('view_karyawan')->orderBy('created_at','desc')->get();
		return view('master.karyawan.index',[
				'data' => $data
			]);
	}

	public function create(){
		$jabatans = \DB::table('jabatan')->get();
		$selectJabatan = [];
		foreach($jabatans as $dt){
			$selectJabatan[$dt->kode] = $dt->nama;
		}

		return view('master.karyawan.create',[
				'selectJabatan' => $selectJabatan
			]);
	}

	public function insert(Request $req){
		return \DB::transaction(function()use($req){

			// generate kode
		//------------------------------------------------------------------
		if($req->jabatan == 'DV'){
			$prefix = \DB::table('appsetting')->whereName('driver_prefix')->first()->value;
			$counter = \DB::table('appsetting')->whereName('driver_counter')->first()->value;
		}else{
			$prefix = \DB::table('appsetting')->whereName('staff_prefix')->first()->value;
			$counter = \DB::table('appsetting')->whereName('staff_counter')->first()->value;
		}		
		
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

		if($req->jabatan == 'DV'){
			\DB::table('appsetting')->whereName('driver_counter')->update(['value'=>$counter]);
		}else{
			\DB::table('appsetting')->whereName('staff_counter')->update(['value'=>$counter]);
		}	
		//------------------------------------------------------------------

			$jabatan_id = \DB::table('jabatan')->whereKode($req->jabatan)->first()->id;

			$fix_tgl_lahir =  $req->tahun . '-' . $req->bulan . '-' . $req->tanggal;

			$karyawan_id = \DB::table('karyawan')
			->insertGetId([
					'nama' => $req->nama,
					'panggilan' => $req->panggilan,
					'kode' => $kode,
					'ktp' => $req->ktp,
					'alamat' => $req->alamat,
					'desa_id' => $req->desa_id,
					'telp' => $req->telp,
					'jabatan_id' => $jabatan_id,
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
		$data = \DB::table('view_karyawan')->find($id);

		$jabatans = \DB::table('jabatan')->get();
		$selectJabatan = [];
		foreach($jabatans as $dt){
			$selectJabatan[$dt->kode] = $dt->nama;
		}


		return view('master.karyawan.edit',[
				'data' => $data,
				'selectJabatan' => $selectJabatan
			]);
	}

	public function update(Request $req){
		return \DB::transaction(function()use($req){
			// cek apakah ajabatan berubah
			$karyawan = \DB::table('karyawan')->find($req->id);

			$jabatan_baru_id = \DB::table('jabatan')->whereKode($req->jabatan)->first()->id;

			if($karyawan->jabatan_id != $jabatan_baru_id){
				// ganti jabatan & generate kode baru
				//------------------------------------------------------------------
				if($req->jabatan == 'DV'){
					$prefix = \DB::table('appsetting')->whereName('driver_prefix')->first()->value;
					$counter = \DB::table('appsetting')->whereName('driver_counter')->first()->value;
				}else{
					$prefix = \DB::table('appsetting')->whereName('staff_prefix')->first()->value;
					$counter = \DB::table('appsetting')->whereName('staff_counter')->first()->value;
				}		
				
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

				if($req->jabatan == 'DV'){
					\DB::table('appsetting')->whereName('driver_counter')->update(['value'=>$counter]);
				}else{
					\DB::table('appsetting')->whereName('staff_counter')->update(['value'=>$counter]);
				}	

				$jabatan_id = $jabatan_baru_id;

			}else{
				$kode = $karyawan->kode;
				$jabatan_id = $karyawan->jabatan_id;
			}

            $tgl_lahir =  $req->tahun . '-' . $req->bulan . '-' . $req->tanggal;

			\DB::table('karyawan')
			->where('id',$req->id)
			->update([
					'nama' => $req->nama,
					'panggilan' => $req->panggilan,
					'kode' => $kode,
					'ktp' => $req->ktp,
					'alamat' => $req->alamat,
					'desa_id' => $req->desa_id,
					'telp' => $req->telp,
					'jabatan_id' => $jabatan_id,
					'tgl_lahir' => $tgl_lahir,
					'tempat_lahir' => $req->tempat_lahir,
					'gaji_pokok' => $req->gaji_pokok,
					'is_active' => $req->is_aktif == 'true' ? 'Y':'N'
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

		// echo $req->is_aktif == 'true' ? 'Y':'N';

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
