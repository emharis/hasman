<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReportDeliveryController extends Controller
{
	public function index(){

		$customer = \DB::table('customer')->get();
                $select_customer=[];
		foreach($customer as $dt){
			$select_customer[$dt->id] =  $dt->kode.' - ' .$dt->nama;
		}

                $lokasi_galian = \DB::table('lokasi_galian')->get();
                $select_lokasi_galian = [];
                foreach($lokasi_galian as $dt){
                        $select_lokasi_galian[$dt->id] = $dt->kode.' - ' . $dt->nama;
                }

                $driver = \DB::table('VIEW_KARYAWAN')->select('id','kode','nama')->get();
                $select_driver = [];
                foreach($driver as $dt){
                        $select_driver[$dt->id] = $dt->kode.' - ' . $dt->nama;
                }
		
		return view('report.delivery.index',[
                                'select_customer'=>$select_customer,
                                'select_lokasi_galian'=>$select_lokasi_galian,
				'select_driver'=>$select_driver,
			]);
	}

        public function reportByDate(Request $req){
                $start = $req->start_date;
                $arr_tgl = explode('-',$start);
                $start = new \DateTime();
                $start->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
                $start_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

                $end = $req->end_date;
                $arr_tgl = explode('-',$end);
                $end = new \DateTime();
                $end->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
                $end_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

                $where_kalkulasi = $req->kalkulasi == 'A' ? 'kalkulasi like "%%"' : 'kalkulasi = "' . $req->kalkulasi . '"';

                $data = \DB::table('VIEW_DELIVERY_ORDER')
                        ->whereRaw($where_kalkulasi)
                        ->whereBetween('order_date',[$start_str,$end_str])
                        ->orderBy('order_date','asc')
                        ->get();

                return view('report.delivery.report-by-date',[
                                'data' => $data,
                        ])->with($req->all());
        }

        public function reportByCustomer(Request $req){
                $start = $req->start_date;
                $arr_tgl = explode('-',$start);
                $start = new \DateTime();
                $start->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
                $start_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

                $end = $req->end_date;
                $arr_tgl = explode('-',$end);
                $end = new \DateTime();
                $end->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
                $end_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

                $where_kalkulasi = $req->kalkulasi == 'A' ? 'kalkulasi like "%%"' : 'kalkulasi = "' . $req->kalkulasi . '"';
                $where_pekerjaan = $req->pekerjaan_id == '0' ? 'pekerjaan_id like "%%"' : 'pekerjaan_id = "' . $req->pekerjaan_id . '"';


                $data = \DB::table('VIEW_DELIVERY_ORDER')
                        ->where('customer_id',$req->customer_id)
                        ->whereRaw($where_kalkulasi)
                        ->whereRaw($where_pekerjaan)
                        ->whereBetween('order_date',[$start_str,$end_str])
                        ->orderBy('order_date','asc')
                        ->get();

                $customer = \DB::table('customer')->find($req->customer_id);
                $pekerjaan = \DB::table('VIEW_PEKERJAAN')->find($req->pekerjaan_id);

                return view('report.delivery.report-by-customer',[
                                'data' => $data,
                                'customer' => $customer,
                                'pekerjaan' => $pekerjaan,
                        ])->with($req->all());
        }

//. END OF CODE
}
