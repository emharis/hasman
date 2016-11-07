<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReportPurchaseController extends Controller
{
	public function index(){
		$suppliers = \DB::table('supplier')->get();
		$select_supplier = [];
		foreach($suppliers as $dt){
			$select_supplier[$dt->id] = '[' .$dt->kode .'] ' . $dt->nama;
		}
		
		return view('report.purchase.index',[
				'select_supplier'=>$select_supplier
			]);
	}

	// FILTER BY DATE
	public function filterByDate(Request $req){
		// generate tanggal
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

        if($req->is_detailed_report == 'true'){
            $data = \DB::table('VIEW_PURCHASE_ORDER_ALL_DETAIL')
                ->orderBy('order_date','asc')
                ->whereBetween('order_date',[$start_str,$end_str])
                ->select('VIEW_PURCHASE_ORDER_ALL_DETAIL.*',\DB::raw('(select amount_due from supplier_bill where purchase_order_id = VIEW_PURCHASE_ORDER_ALL_DETAIL.id) as amount_due' ))
                ->get();

           
        }else{
            $data = \DB::table('VIEW_PURCHASE_ORDER')
                ->orderBy('order_date','asc')
                ->whereBetween('order_date',[$start_str,$end_str])
                ->select('VIEW_PURCHASE_ORDER.*',\DB::raw('(select amount_due from supplier_bill where purchase_order_id = VIEW_PURCHASE_ORDER.id) as amount_due' ))
                ->get();
        }


        $amount_due = \DB::table('VIEW_SUPPLIER_BILL')
                            ->whereBetween('order_date',[$start_str,$end_str])
                            ->sum('amount_due');

        

        return view('report.purchase.report-by-date',[
        		'data' => $data,
                'amount_due' => $amount_due
        	])->with($req->all());

  //       $pdf = \PDF::loadView('report.purchase.report-filter-by-date', ['data'=>$data]);
  //       $pdf->set_base_path('css');
		// // return $pdf->download('invoice.pdf');
		// return $pdf->stream();

        
	}

    public function filterByDateNSupplier(Request $req){
        // generate tanggal
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

        // $data = \DB::table('VIEW_PURCHASE_ORDER')
        //         ->orderBy('order_date','asc')
        //         ->whereBetween('order_date',[$start_str,$end_str])
        //         ->whereSupplierId($req->supplier)
        //         ->get();

        if($req->is_detailed_report == 'true'){
            $data = \DB::table('VIEW_PURCHASE_ORDER_ALL_DETAIL')
                ->orderBy('order_date','asc')
                ->whereBetween('order_date',[$start_str,$end_str])
                ->whereSupplierId($req->supplier)
                ->select('VIEW_PURCHASE_ORDER_ALL_DETAIL.*',\DB::raw('(select amount_due from supplier_bill where purchase_order_id = VIEW_PURCHASE_ORDER_ALL_DETAIL.id) as amount_due' ))
                ->get();
        }else{
            $data = \DB::table('VIEW_PURCHASE_ORDER')
                ->orderBy('order_date','asc')
                ->whereBetween('order_date',[$start_str,$end_str])
                ->whereSupplierId($req->supplier)
                ->select('VIEW_PURCHASE_ORDER.*',\DB::raw('(select amount_due from supplier_bill where purchase_order_id = VIEW_PURCHASE_ORDER.id) as amount_due' ))
                ->get();    
        }
        
        $amount_due = \DB::table('VIEW_SUPPLIER_BILL')
                            ->whereSupplierId($req->supplier)
                            ->whereBetween('order_date',[$start_str,$end_str])
                            ->sum('amount_due');

        $asupplier = \DB::table('supplier')->find($req->supplier);

        return view('report.purchase.report-by-date-n-supplier',[
                'data' => $data,
                'asupplier' => $asupplier,
                'amount_due' => $amount_due,
            ])->with($req->all());
    }

	// GENERATE PDF FILTER BY DATE
	public function filterByDateToPdf($start,$end){
		// $pdf = \App::make('dompdf.wrapper');
		// $pdf->loadHTML('<h1>Test</h1>');
		// return $pdf->stream();

		// generate tanggal
        $start = $start;
        $arr_tgl = explode('-',$start);
        $start = new \DateTime();
        $start->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
        $start_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        $end = $end;
        $arr_tgl = explode('-',$end);
        $end = new \DateTime();
        $end->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
        $end_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        $data = \DB::table('VIEW_PURCHASE_ORDER')
        		->orderBy('order_date','asc')
        		->whereBetween('order_date',[$start_str,$end_str])
        		->get();
        		
		// echo 'pdf';
    	\Fpdf::AddPage();
	    \Fpdf::SetFont('Courier', 'B', 18);
	    \Fpdf::Cell(50, 25, 'Hello World!');
	    

	    \Fpdf::Output('I','test.pdf',false);
	    exit;
	}


//. END OF CODE
}
