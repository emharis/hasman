<?php

if (!function_exists('GeneratePdfHeader')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function GeneratePdfHeader(&$pdf, $header_titel)
    {
    	$logo_cetak = \DB::table('appsetting')->whereName('logo_cetak')->first()->value;
    	$company_name = \DB::table('appsetting')->whereName('company_name')->first()->value;
    	$alamat_1 = \DB::table('appsetting')->whereName('alamat_1')->first()->value;
    	$alamat_2 = \DB::table('appsetting')->whereName('alamat_2')->first()->value;
    	$telp = \DB::table('appsetting')->whereName('telp')->first()->value;
    	$email = \DB::table('appsetting')->whereName('email')->first()->value;

    	// image/logo
    	$pdf->Image('img/' . $logo_cetak,8,8,40);
    	// company name
    	$pdf->SetXY(50,8);
    	$pdf->SetTextColor(0,0,0);
    	$pdf->SetFont('Arial','B',10);
    	$pdf->Cell(0,4,$company_name,0,2,'L',false);
    	$pdf->SetFont('Arial',null,8);
    	$pdf->Cell(0,3,$alamat_1,0,2,'L',false);
    	$pdf->Cell(0,3,$alamat_2,0,2,'L',false);
    	$pdf->Cell(0,3,'T. ' . $telp . ' | ' . 'E. ' . $email ,0,2,'L',false);
        $pdf->Ln(3);
        
        // Line di bawah header
        // $pdf->Line(8,$pdf->GetY(),$pdf->GetPageWidth()-8,$pdf->GetY());
    	$pdf->Cell(0,1,'--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,0,'L',false);
        $last_y = $pdf->GetY();

        // TEXT header titel
        $pdf->SetXY(8,16);
        $pdf->SetFont('Arial','B',20);
        $pdf->Cell($pdf->GetPageWidth()-16,5,$header_titel,0,0,'R',false);
        $pdf->SetXY(0,$last_y);
        $pdf->SetFont('Arial',null,8);
    }
}
