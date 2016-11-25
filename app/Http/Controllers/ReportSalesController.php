<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReportSalesController extends Controller
{
	public function index(){

		$customer = \DB::table('customer')->get();
                $select_customer=[];
		foreach($customer as $dt){
			$select_customer[$dt->id] = '[' . $dt->kode.'] ' .$dt->nama;
		}

                $lokasi_galian = \DB::table('lokasi_galian')->get();
                $select_lokasi_galian = [];
                foreach($lokasi_galian as $dt){
                        $select_lokasi_galian[$dt->id] = '['.$dt->kode.'] ' . $dt->nama;
                }
		
		return view('report.sales.index',[
                                'select_customer'=>$select_customer,
				'select_lokasi_galian'=>$select_lokasi_galian,
			]);
	}

        public function getPekerjaanByCustomer($customer_id){
                $pekerjaan = \DB::table('pekerjaan')
                                ->where('customer_id',$customer_id)
                                ->select('id','nama')
                                ->get();

                return json_encode($pekerjaan);
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

                $direct_sales = $req->sales_type == 0 ? '%%' : ($req->sales_type == 1 ? 'Y':'N'); 

                $data = \DB::table('view_sales_order')
                		->whereBetween('order_date',[$start_str,$end_str])
                                ->where('is_direct_sales','like',$direct_sales)
                		->select('view_sales_order.*',\DB::raw('(select sum(total) from customer_invoices where customer_invoices.order_id = view_sales_order.id) as total'),
                			\DB::raw('(select sum(amount_due) from customer_invoices where customer_invoices.order_id = view_sales_order.id) as amount_due'))
                                ->orderBy('order_date','asc')
                		->get();

                $total = \DB::table('view_customer_invoice')
                		// ->whereBetween('order_date',[$start_str,$end_str])
                                ->whereIn('order_id',function($query)use($start_str,$end_str,$direct_sales){
                                                $query->from('view_sales_order')
                                                ->whereBetween('order_date',[$start_str,$end_str])
                                                ->where('is_direct_sales','like',$direct_sales)
                                                ->select('id');

                                        })
                		->sum('total');

                $total_amount_due = \DB::table('view_customer_invoice')
                		->whereIn('order_id',function($query)use($start_str,$end_str,$direct_sales){
                                                $query->from('view_sales_order')
                                                ->whereBetween('order_date',[$start_str,$end_str])
                                                ->where('is_direct_sales','like',$direct_sales)
                                                ->select('id');

                                        })
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

                // $data = \DB::table('view_sales_order')
                // 		->whereBetween('order_date',[$start_str,$end_str])
                // 		->select('view_sales_order.*',\DB::raw('(select sum(total) from customer_invoices where customer_invoices.order_id = view_sales_order.id) as total'),
                // 			\DB::raw('(select sum(amount_due) from customer_invoices where customer_invoices.order_id = view_sales_order.id) as amount_due'))
                // 		->get();

                $data = \DB::table('view_sales_order_ALL_DETAIL')
                		->whereBetween('order_date',[$start_str,$end_str])
                		->get();


                // $total = \DB::table('view_customer_invoice')
                // 		->whereBetween('order_date',[$start_str,$end_str])
                // 		->sum('total');

                // $total_amount_due = \DB::table('view_customer_invoice')
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

                $direct_sales = $req->sales_type == 0 ? '%%' : ($req->sales_type == 1 ? 'Y':'N'); 

                $where_pekerjaan = $req->pekerjaan_id == 0 ? 'pekerjaan_id like "%%"' : 'pekerjaan_id = '. $req->pekerjaan_id;

                $customer = \DB::table('customer')->find($req->customer_id);
                $pekerjaan = \DB::table('view_pekerjaan')->find($req->pekerjaan_id);
                
                $data = \DB::table('view_sales_order')
                                ->whereBetween('order_date',[$start_str,$end_str])
                                ->where('is_direct_sales','like',$direct_sales)                                
                                ->whereRaw($where_pekerjaan)                                
                                ->select('view_sales_order.*',\DB::raw('(select sum(total) from customer_invoices where customer_invoices.order_id = view_sales_order.id) as total'),
                                        \DB::raw('(select sum(amount_due) from customer_invoices where customer_invoices.order_id = view_sales_order.id) as amount_due'))
                                ->where('customer_id',$req->customer_id)
                                ->orderBy('order_date','asc')
                                ->get();

                $total = \DB::table('view_customer_invoice')
                                ->whereIn('order_id',function($query)use($start_str,$end_str,$direct_sales,$where_pekerjaan,$customer){
                                        $query->from('view_sales_order')
                                                ->whereBetween('order_date',[$start_str,$end_str])
                                                ->where('is_direct_sales','like',$direct_sales)                                
                                                ->whereRaw($where_pekerjaan)                                
                                                ->select('id')
                                                ->where('customer_id',$customer->id);
                                })
                                ->sum('total');

                $total_amount_due = \DB::table('view_customer_invoice')
                                ->whereBetween('order_date',[$start_str,$end_str])
                                ->where('customer_id',$req->customer_id)
                                ->sum('amount_due');

                

                return view('report.sales.report-by-customer',[
                                'data' => $data,
                                'total' => $total,
                                'total_amount_due' => $total_amount_due,
                                'customer' => $customer,
                                'pekerjaan' => $pekerjaan,
                        ])->with($req->all());
        }

        public function reportByCustomerPekerjaan(Request $req){
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

                $data = \DB::table('view_sales_order')
                                ->whereBetween('order_date',[$start_str,$end_str])
                                ->select('view_sales_order.*',\DB::raw('(select sum(total) from customer_invoices where customer_invoices.order_id = view_sales_order.id) as total'),
                                        \DB::raw('(select sum(amount_due) from customer_invoices where customer_invoices.order_id = view_sales_order.id) as amount_due'))
                                ->where('customer_id',$req->customer_id)
                                ->where('pekerjaan_id',$req->pekerjaan_id)
                                ->get();

                $total = \DB::table('view_customer_invoice')
                                ->whereBetween('order_date',[$start_str,$end_str])
                                ->where('pekerjaan_id',$req->pekerjaan_id)
                                ->where('customer_id',$req->customer_id)
                                ->sum('total');

                $total_amount_due = \DB::table('view_customer_invoice')
                                ->whereBetween('order_date',[$start_str,$end_str])
                                ->where('pekerjaan_id',$req->pekerjaan_id)
                                ->where('customer_id',$req->customer_id)
                                ->sum('amount_due');

                $customer = \DB::table('customer')->find($req->customer_id);
                $pekerjaan = \DB::table('view_pekerjaan')->find($req->pekerjaan_id);

                return view('report.sales.report-by-customer-pekerjaan',[
                                'data' => $data,
                                'total' => $total,
                                'total_amount_due' => $total_amount_due,
                                'customer' => $customer,
                                'pekerjaan' => $pekerjaan,
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

                $data = \DB::table('view_sales_order_ALL_DETAIL')
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

        public function reportBySalesTypeAll(Request $req){
                // return redirect('report/sales/')
        }

        public function reportBySalesType(Request $req){
                $direct_sales = $req->sales_type == 1 ? 'Y' : 'N';

                // direct sales
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

                // if($sales_type == 1){
                        
                        // $data = \DB::table('view_sales_order')
                        //              ->whereBetween('order_date',[$start_str,$end_str])
                        //              ->get();
                        $data = \DB::table('view_sales_order')
                                        ->whereBetween('order_date',[$start_str,$end_str])
                                        ->where('is_direct_sales',$direct_sales)
                                        ->select('view_sales_order.*',\DB::raw('(select sum(total) from customer_invoices where customer_invoices.order_id = view_sales_order.id) as total'),
                                                \DB::raw('(select sum(amount_due) from customer_invoices where customer_invoices.order_id = view_sales_order.id) as amount_due'))
                                        ->orderBy('order_date','asc')
                                        ->get();

                        $total = \DB::table('view_customer_invoice')
                                        ->whereIn('order_id',function($query)use($start_str,$end_str,$direct_sales){
                                                $query->select('id')
                                                ->from('view_sales_order')
                                                ->whereBetween('order_date',[$start_str,$end_str])
                                                ->where('is_direct_sales',$direct_sales);
                                                // ->get();

                                        })
                                        ->sum('total');

                        $total_amount_due = \DB::table('view_customer_invoice')
                                        ->whereIn('order_id',function($query)use($start_str,$end_str,$direct_sales){
                                                $query->from('view_sales_order')
                                                ->whereBetween('order_date',[$start_str,$end_str])
                                                ->where('is_direct_sales',$direct_sales)
                                                ->select('id')
                                                ->orderBy('order_date','asc');

                                        })
                                        ->sum('amount_due');

                        // echo $total;

                        return view('report.sales.report-by-sales-type',[
                                        'data' => $data,
                                        'total' => $total,
                                        'total_amount_due' => $total_amount_due,
                                ])->with($req->all());
                // }

        }

        public function reportByLokasiGalian(Request $req){
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

                $data = \DB::table('view_sales_order')
                                ->whereBetween('order_date',[$start_str,$end_str])
                                ->select('view_sales_order.*',\DB::raw('(select sum(total) from customer_invoices where customer_invoices.order_id = view_sales_order.id) as total'),
                                        \DB::raw('(select sum(amount_due) from customer_invoices where customer_invoices.order_id = view_sales_order.id) as amount_due'))
                                ->where('lokasi_galian_id',$req->lokasi_galian_id)
                                ->get();

                $total = \DB::table('view_customer_invoice')
                                ->whereBetween('order_date',[$start_str,$end_str])
                                ->where('lokasi_galian_id',$req->lokasi_galian_id)
                                ->sum('total');

                $total_amount_due = \DB::table('view_customer_invoice')
                                ->whereBetween('order_date',[$start_str,$end_str])
                                ->where('lokasi_galian_id',$req->lokasi_galian_id)
                                ->sum('amount_due');

                $lokasi_galian = \DB::table('view_lokasi_galian')->find($req->lokasi_galian_id);

                return view('report.sales.report-by-lokasi-galian',[
                                'data' => $data,
                                'total' => $total,
                                'total_amount_due' => $total_amount_due,
                                'lokasi_galian' => $lokasi_galian,
                        ])->with($req->all());
        }





//. END OF CODE
}
