<?php

if (!function_exists('CetakDeliveryOrder')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function CetakDeliveryOrder($id)
    {
    	$data = \DB::table('view_delivery_order')->find($id);
        // $data_detail = \DB::table('view_sales_order_detail')->where('sales_order_id',$id)->get();

        $sopdf = new \Codedge\Fpdf\Fpdf\FPDF();
        $sopdf->AddPage();
        $sopdf->setMargins(8,8,8);
        $sopdf->SetAutoPageBreak(false,8);
        $sopdf->SetXY(8,8);

        // HEADER
        // generate header
        GeneratePdfHeader($sopdf,'DELIVERY ORDER');

        // ALAMAT PENGIRIMAN
        $sopdf->Ln(5);
        $sopdf->SetX(8);
        $sopdf->SetFont('Arial','B',8);
        $ship_to_width = $sopdf->GetPageWidth()/2 - 8;
        $sopdf->Cell($ship_to_width/3-2,4,'Kepada',0,0,'L',false);
        $sopdf->Cell(2,4,':',0,0,'L',false);
        $sopdf->SetFont('Arial',null,8);
        $sopdf->Cell(($ship_to_width/3)*2,4,$data->customer,0,0,'L',false);

        $sopdf->SetFont('Arial','B',8);
        $ship_to_width = $sopdf->GetPageWidth()/2 - 8;
        $sopdf->Cell($ship_to_width/3-2,4,'DO Ref#',0,0,'L',false);
        $sopdf->Cell(2,4,':',0,0,'L',false);
        $sopdf->SetFont('Arial',null,8);
        $sopdf->Cell(($ship_to_width/3)*2,4,$data->delivery_order_number,0,2,'L',false);

        $sopdf->SetX(8);
        $sopdf->SetFont('Arial','B',8);
        $ship_to_width = $sopdf->GetPageWidth()/2 - 8;
        $sopdf->Cell($ship_to_width/3-2,4,'Pekerjaan',0,0,'L',false);
        $sopdf->Cell(2,4,':',0,0,'L',false);
        $sopdf->SetFont('Arial',null,8);
        $sopdf->Cell(($ship_to_width/3)*2,4,$data->pekerjaan,0,0,'L',false);

        $sopdf->SetFont('Arial','B',8);
        $ship_to_width = $sopdf->GetPageWidth()/2 - 8;
        $sopdf->Cell($ship_to_width/3-2,4,'Tanggal',0,0,'L',false);
        $sopdf->Cell(2,4,':',0,0,'L',false);
        $sopdf->SetFont('Arial',null,8);
        $sopdf->Cell(($ship_to_width/3)*2,4,$data->delivery_date_formatted,0,2,'L',false);

        $sopdf->SetFont('Arial','B',8);
        $sopdf->SetX(8);
        $ship_to_width = $sopdf->GetPageWidth()/2 - 8;
        $sopdf->Cell($ship_to_width/3-2,4,'Alamat Pengiriman',0,0,'L',false);
        $sopdf->Cell(2,4,':',0,0,'L',false);
        $sopdf->SetFont('Arial',null,8);
        $sopdf->Cell(($ship_to_width/3)*2,4,($data->alamat_pekerjaan != "" ? $data->alamat_pekerjaan .', ' : '') . $data->desa,0,0,'L',false);

        $sopdf->SetFont('Arial','B',8);
        $ship_to_width = $sopdf->GetPageWidth()/2 - 8;
        $sopdf->Cell($ship_to_width/3-2,4,'Driver',0,0,'L',false);
        $sopdf->Cell(2,4,':',0,0,'L',false);
        $sopdf->SetFont('Arial',null,8);
        $sopdf->Cell(($ship_to_width/3)*2,4,$data->karyawan,0,2,'L',false);

        // alamat 2
        $sopdf->SetX(8 + $ship_to_width/3-2 + 2);
        $sopdf->Cell(($ship_to_width/3)*2,4,$data->kecamatan .', ' . $data->kabupaten,0,0,'L',false);

        $sopdf->SetFont('Arial','B',8);
        $ship_to_width = $sopdf->GetPageWidth()/2 - 8;
        $sopdf->Cell($ship_to_width/3-2,4,'Nopol',0,0,'L',false);
        $sopdf->Cell(2,4,':',0,0,'L',false);
        $sopdf->SetFont('Arial',null,8);
        $sopdf->Cell(($ship_to_width/3)*2,4,$data->nopol,0,2,'L',false);
        // END OF ALAMAT PENGIRIMAN

        // TABLE HEADER
        $sopdf->Ln(10);
        $sopdf->SetX(8);
        $page_content_width = $sopdf->GetPageWidth()-16;
        $col_barang = $page_content_width/4 * 2 - 5;
        $col_kode_barang = $page_content_width/4 - 5;
        $col_qty = $page_content_width/4 - 5;
        $col_header_height = 8;
        $col_row_height = 8;

        $sopdf->Ln(5);
        $sopdf->Cell(0,1,'--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,2,'L',false);
        $sopdf->SetFont('Arial','B',8);
        $sopdf->Cell(5,$col_header_height,null,0,0,'L',false);
        $sopdf->Cell($col_kode_barang,$col_header_height,'KODE MATERIAL',0,0,'L',false);
        $sopdf->Cell($col_barang,$col_header_height,'MATERIAL',0,0,'L',false);
        $sopdf->Cell($col_qty,$col_header_height,'JUMLAH',0,2,'R',false);
        $sopdf->Cell(5,$col_header_height,null,0,0,'L',false);
        $sopdf->SetX(8);
        $sopdf->Cell(0,1,'--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,2,'L',false);

        $sopdf->SetFont('Arial',null,8);
        $sopdf->Cell(5,$col_row_height,null,0,0,'L',false);
        $sopdf->Cell($col_kode_barang,$col_row_height,$data->kode_material,0,0,'L',false);
        $sopdf->Cell($col_barang,$col_row_height,$data->material,0,0,'L',false);
        $sopdf->Cell($col_qty,$col_row_height,1,0,2,'R',false);
        $sopdf->Cell(5,$col_row_height,null,0,0,'L',false);
        $sopdf->SetX(8);

        $sopdf->Cell(0,1,'--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,2,'L',false);


        $sopdf->Ln(20);
        $col_ttd = $page_content_width/3;

        $sopdf->Cell($col_ttd,5,'ADMIN',0,0,'C',false);
        $sopdf->Cell($col_ttd,5,'DRIVER',0,0,'C',false);
        $sopdf->Cell($col_ttd,5,'PENERIMA',0,0,'C',false);

        $sopdf->Ln(20);

        $sopdf->SetFont('Arial','B',8);
        $sopdf->Cell($col_ttd,5,'(  ' . 'ADMIN' .'  )',0,0,'C',false);
        $sopdf->Cell($col_ttd,5,'(  ' . $data->karyawan .'  )',0,0,'C',false);
        $sopdf->Cell($col_ttd,5,'(_____________________)',0,0,'C',false);

        // END TABLE HEADER

        $sopdf->Output();
        exit;

    }
}
