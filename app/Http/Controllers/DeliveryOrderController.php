<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DeliveryOrderController extends Controller
{
	public function index(){
		
		return view('delivery.order.index',[
				// 'data' => $data
			]);
	}

	public function create(){
		return view('delivery.order.create',[
			]);
	}


}
