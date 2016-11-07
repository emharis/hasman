<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class Helper
{
    public static function GenerateCustomerInvoiceNumber()
    {
        $invoice_counter = \DB::table('appsetting')->where('name','invoice_counter')->first()->value;
		$invoice_number = 'INV/' . date('Y') . '/000' . $invoice_counter++;
		// update invoice counter
		\DB::table('appsetting')->where('name','invoice_counter')->update(['value'=>$invoice_counter]);

		return $invoice_number;
    }
}