<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReportSalesController extends Controller
{
	public function index(){

		$customer = \DB::table('customer')->get();
		foreach($customer as $dt){
			$select_customer[$dt->id] = '[' . $dt->kode.'] ' .$dt->nama;
		}
		
		return view('report.sales.index',[
				'select_customer'=>$select_customer
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

                // $data = \DB::table('VIEW_SALES_ORDER')
                // 		->whereBetween('order_date',[$start_str,$end_str])
                // 		->get();
                $data = \DB::table('VIEW_SALES_ORDER')
                		->whereBetween('order_date',[$start_str,$end_str])
                		->select('VIEW_SALES_ORDER.*',\DB::raw('(select sum(total) from customer_invoices where customer_invoices.order_id = VIEW_SALES_ORDER.id) as total'),
                			\DB::raw('(select sum(amount_due) from customer_invoices where customer_invoices.order_id = VIEW_SALES_ORDER.id) as amount_due'))
                		->get();

                $total = \DB::table('VIEW_CUSTOMER_INVOICE')
                		->whereBetween('order_date',[$start_str,$end_str])
                		->sum('total');

                $total_amount_due = \DB::table('VIEW_CUSTOMER_INVOICE')
                		->whereBetween('order_date',[$start_str,$end_str])
                		->sum('amount_due');

                return view('report.sales.report-by-date',[
                		'data' => $data,
                		'total' => $total,
                		'total_amount_due' => $total_amount_due,
                	])->with($req->all());
	}

	public function reportByDateDetail(Request $req){
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

                // $data = \DB::table('VIEW_SALES_ORDER')
                // 		->whereBetween('order_date',[$start_str,$end_str])
                // 		->select('VIEW_SALES_ORDER.*',\DB::raw('(select sum(total) from customer_invoices where customer_invoices.order_id = VIEW_SALES_ORDER.id) as total'),
                // 			\DB::raw('(select sum(amount_due) from customer_invoices where customer_invoices.order_id = VIEW_SALES_ORDER.id) as amount_due'))
                // 		->get();

                $data = \DB::table('VIEW_SALES_ORDER_ALL_DETAIL')
                		->whereBetween('order_date',[$start_str,$end_str])
                		->get();


                // $total = \DB::table('VIEW_CUSTOMER_INVOICE')
                // 		->whereBetween('order_date',[$start_str,$end_str])
                // 		->sum('total');

                // $total_amount_due = \DB::table('VIEW_CUSTOMER_INVOICE')
                // 		->whereBetween('order_date',[$start_str,$end_str])
                // 		->sum('amount_due');

                return view('report.sales.report-by-date-detail',[
                		'data' => $data,
                		// 'total' => $total,
                		// 'total_amount_due' => $total_amount_due,
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

                $data = \DB::table('VIEW_SALES_ORDER')
                                ->whereBetween('order_date',[$start_str,$end_str])
                                ->select('VIEW_SALES_ORDER.*',\DB::raw('(select sum(total) from customer_invoices where customer_invoices.order_id = VIEW_SALES_ORDER.id) as total'),
                                        \DB::raw('(select sum(amount_due) from customer_invoices where customer_invoices.order_id = VIEW_SALES_ORDER.id) as amount_due'))
                                ->where('customer_id',$req->customer_id)
                                ->get();

                $total = \DB::table('VIEW_CUSTOMER_INVOICE')
                                ->whereBetween('order_date',[$start_str,$end_str])
                                ->where('customer_id',$req->customer_id)
                                ->sum('total');

                $total_amount_due = \DB::table('VIEW_CUSTOMER_INVOICE')
                                ->whereBetween('order_date',[$start_str,$end_str])
                                ->where('customer_id',$req->customer_id)
                                ->sum('amount_due');

                $customer = \DB::table('customer')->find($req->customer_id);

                return view('report.sales.report-by-customer',[
                                'data' => $data,
                                'total' => $total,
                                'total_amount_due' => $total_amount_due,
                                'customer' => $customer,
                        ])->with($req->all());
        }

        public function reportByCustomerDetail(Request $req){
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

                $data = \DB::table('VIEW_SALES_ORDER_ALL_DETAIL')
                                ->whereBetween('order_date',[$start_str,$end_str])
                                ->where('customer_id',$req->customer_id)
                                ->get();

                $customer = \DB::table('customer')->find($req->customer_id);

                return view('report.sales.report-by-customer-detail',[
                                'data' => $data,
                                'customer' => $customer,
                                // 'total_amount_due' => $total_amount_due,
                        ])->with($req->all());
        }





//. END OF CODE
}
