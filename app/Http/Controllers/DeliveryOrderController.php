<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DeliveryOrderController extends Controller
{
	public function index(){
		$data = \DB::table('VIEW_DELIVERY_ORDER')
			->orderBy('order_date','desc')
			->get();
			
		return view('delivery.order.index',[
				'data' => $data
			]);
	}

	public function create(){
		return view('delivery.order.create',[
			]);
	}


}
