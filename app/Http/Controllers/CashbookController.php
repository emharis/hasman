<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CashbookController extends Controller
{
	public function index(){
		// $data = \DB::table('VIEW_ARMADA')->get();
		return view('cashbook.index',[
				// 'data' => $data
			]);
	}

}
