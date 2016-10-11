<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PayrollController extends Controller
{
	public function staff(){
		// $data = \DB::table('alat')
		// 		->get();
		return view('payroll.staff.index',[
				// 'data' => $data
			]);
	}

  public function driver(){
    return view('payroll.driver.index');
  }

  public function driverCreate(){
    return view('payroll.driver.create');
  }

}
