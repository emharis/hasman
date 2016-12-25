<?php

if (!function_exists('CetakSalesOrder')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function CetakSalesOrder($id)
    {
    	$data_master = \DB::table('view_sales_order')->find($id);
		$data_detail = \DB::table('view_sales_order_detail')->where('sales_order_id',$id)->get();

		$pekerjaan = \DB::table('view_pekerjaan')->where('customer_id',$data_master->customer_id)->get();
		$select_pekerjaan = [];
		foreach($pekerjaan as $dt){
			$select_pekerjaan[$dt->id] = $dt->nama;
		}

		$select_material = [];
		$materials = \DB::table('material')
						->select('id','nama')
						->get();
		foreach($materials as $dt){
			$select_material[$dt->id] = $dt->nama;
		}

		// jika direct selling
		if($data_master->is_direct_sales == 'Y'){
			if($data_master->status == 'O'){
				return view('sales.order.ds_edit',[
					'data_master' => $data_master,
					'data_detail' => $data_detail,
					'selectMaterial' => $select_material,
				]);
			}elseif($data_master->status == 'V'){
				return view('sales.order.ds_validated',[
					'data_master' => $data_master,
					'data_detail' => $data_detail
				]);
			}
			
		}

		// cetak sales order PDF
		// $sopdf = new \fpdf\FPDF();
		$sopdf = new \fpdf\FPDF('L','mm',array('217','140'));
		$sopdf->AddPage();
		$sopdf->setMargins(8,8,8);
		$sopdf->SetAutoPageBreak(false,8);
		// $sopdf->SetFont(false,8);
	    $sopdf->SetXY(8,8);

	    $logo_cetak = \DB::table('appsetting')->whereName('logo_cetak')->first()->value;

	    // cetak image
	    $sopdf->Image('img/' . $logo_cetak,8,8,40);
	    // $sopdf->Cell(10,5,'OKDEH');

	    $sopdf->Output();
	    exit;



    }
}
