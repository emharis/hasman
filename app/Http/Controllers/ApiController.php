<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
	public function getAutoCompleteProvinsi(Request $req){
		$provinsi = \DB::select('select id as data,name as value from provinsi where name like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $provinsi];

		return json_encode($data_res);
	}

	public function getAutoCompleteKabupaten(Request $req){
		$kabupaten = \DB::select('select id as data,name as value from kabupaten where provinsi_id = ' . $req->provinsi_id . ' and name like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $kabupaten];

		return json_encode($data_res);
	}

	public function getAutoCompleteKecamatan(Request $req){
		$data = \DB::select('select id as data,name as value from kecamatan where kabupaten_id = ' . $req->kabupaten_id . ' and name like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteDesa(Request $req){
		$data = \DB::select('select id as data,name as value from desa where kecamatan_id = ' . $req->kecamatan_id . ' and name like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteCustomer(Request $req){
		// $data = \DB::select('select id as data,concat("[",kode,"] ",nama) as value, nama from customer where nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%"');
		$data = \DB::select('select id as data,nama as value, nama from customer where nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteSupplier(Request $req){
		$data = \DB::select('select id as data,concat("[",kode,"] ",nama) as value, nama from supplier where nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteLokasiGalian(Request $req){
		// $data = \DB::select('select id as data,concat("[",kode,"] ",nama) as value, nama from lokasi_galian where nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%"');
		$data = \DB::select('select id as data,nama as value, nama from lokasi_galian where nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteAlat(Request $req){
		$data = \DB::select('select id as data,concat("[",kode,"] ",nama) as value, nama from alat where nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteArmada(Request $req){

		// $data = \DB::select('select id as data,concat("[",kode,"] ",nopol ," - ","[",kode_karyawan,"] ",karyawan) as value, nama
		$data = \DB::select('select id as data,concat(nopol ," - ",karyawan) as value, nama
				from view_armada
				where karyawan_id is not NULL and (
				nama like "%'.$req->get('nama').'%"
				or kode like "%'.$req->get('nama').'%"
				or kode_karyawan like "%'.$req->get('nama').'%"
				or karyawan like "%'.$req->get('nama').'%"
				or nopol like "%'.$req->get('nama').'%"
				)');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteDriver(Request $req){

		$data = \DB::select('select id as data,concat("[",kode,"] ", nopol ," - ","[",kode_karyawan,"] ",karyawan) as value, nama , nopol
				from view_armada
				where karyawan_id is not NULL and (
				nama like "%'.$req->get('nama').'%"
				or kode like "%'.$req->get('nama').'%"
				or kode_karyawan like "%'.$req->get('nama').'%"
				or karyawan like "%'.$req->get('nama').'%"
				or nopol like "%'.$req->get('nama').'%"
				)');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteStaff(Request $req){
		// $data = \DB::select('select id as data,concat("[",kode,"] ",nama) as value, nama from view_karyawan where kode_jabatan = "ST" and (nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%")');
		$data = \DB::select('select id as data,nama as value, nama from view_karyawan where kode_jabatan = "ST" and (nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%")');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteMaterial(Request $req){
		// $data = \DB::select('select id as data,concat("[",kode,"] ",nama) as value, nama from material where nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%"');
		$data = \DB::select('select id as data,nama as value, nama from material where nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteProduct(Request $req){
		// $data = \DB::select('select id as data,concat("[",kode,"] ",nama) as value, nama, from material where nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%"');
		$data = \DB::select('select product.id as data,concat("[",kode,"] ",product.nama) as value, product.nama,
							product_unit.nama AS unit,
							product.product_unit_id
								FROM
							product_unit
							INNER JOIN product
	 						ON product_unit.id = product.product_unit_id where product.nama like "%'.$req->get('nama').'%" or product.kode like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getSelectCustomer(){
		$data = \DB::table('view_customer')->get();
		$selectCustomer = [];
		foreach($data as $dt){
			$selectCustomer[$dt->id] = '[' . $dt->kode . '] ' . $dt->nama;
		}

		return json_encode($selectCustomer);
	}

	public function getSelectPekerjaan($customer_id){
		$data = \DB::table('view_pekerjaan')->where('customer_id',$customer_id)->get();
		$selectPekerjaan = [];
		foreach($data as $dt){
			$selectPekerjaan[$dt->id] =  $dt->nama;
		}

		return json_encode($selectPekerjaan);
	}

	public function getPekerjaanByCustomer($customer_id){
		$pekerjaan = \DB::table('pekerjaan')
                                ->where('customer_id',$customer_id)
                                ->select('id','nama')
                                ->get();

                return json_encode($pekerjaan);
	}


}
