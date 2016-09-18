<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SalesOrderController extends Controller
{
	public function index(){
		
		return view('sales.order.index',[
				// 'data' => $data
			]);
	}


}
