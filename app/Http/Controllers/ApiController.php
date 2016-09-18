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

}