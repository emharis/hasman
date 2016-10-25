<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CashbookController extends Controller
{
	public function index(){
		$data = \DB::table('cashbook')
				->select('cashbook.*',\DB::raw('date_format(tanggal,"%d-%m-%Y") as tanggal_formatted'))
				->orderBy('tanggal','asc')
				// ->orderBy('created_at','asc')
				->get();
		// $balance = \DB::table('cashbook')->orderBy('created_at','desc')->first()->saldo;
		$debit_credit = \DB::select("select sum( case when in_out = 'I' then jumlah end) as debit, sum( case when in_out = 'O' then jumlah end) as credit
from cashbook");

		$balance = $debit_credit[0]->debit - $debit_credit[0]->credit;

		return view('cashbook.index',[
				'data' => $data,
				'balance' => $balance
			]);
	}

	public function create(){
		return view('cashbook.create');
	}

	// INSERT DATA TO TABLE
	public function insert(Request $req){
		return \DB::transaction(function()use($req){
			// generate cahsbook number
			$cashbook_counter = \DB::table('appsetting')->whereName('cashbook_counter')->first()->value;
			if($req->jenis_kas == 'I'){
				$cashbook_num = 'CASH.IN/'.date('Y').'/000'.$cashbook_counter++;	
			}else{
				$cashbook_num = 'CASH.OUT/'.date('Y').'/000'.$cashbook_counter++;	
			}
			// update counter
			\DB::table('appsetting')->whereName('cashbook_counter')->update([
					'value' => $cashbook_counter
				]);

			// generate tanggal
            $cash_date = $req->tanggal;
            $arr_tgl = explode('-',$cash_date);
            $cash_date = new \DateTime();
            $cash_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

            // jumlah
            $jumlah = str_replace(',', '', $req->jumlah);
            $jumlah = str_replace('.00', '', $jumlah);

			// insert to table cashbook
			$cashbook_id = \DB::table('cashbook')->insertGetId([
					'tanggal' => $cash_date,
					'cash_number' => $cashbook_num,
					'jumlah' => $jumlah,
					'in_out' => $req->jenis_kas,
					'desc' => $req->keterangan,
				]);

			// // update saldo
			// $cashdata = \DB::table('cashbook')->orderBy('created_at','desc')->limit(2)->get();
			// echo count($cashdata);
			// if(count($cashdata) == 1){
			// 	$saldo_baru = 0;
			// 	if($req->jenis_kas == 'I'){
			// 		$saldo_baru = $jumlah;
			// 	}else{
			// 		$saldo_baru = 0-$jumlah;
			// 	}

			// 	\DB::table('cashbook')->whereId($cashbook_id)->update([
			// 		'saldo' => $saldo_baru ]);
			// }else{
			// 	$saldo_terakhir = 0;
			// 	foreach($cashdata as $dt)
			// 	{					
			// 		if($dt->id != $cashbook_id){
			// 			$saldo_terakhir = $dt->saldo;
			// 		}
			// 	}

			// 	$saldo_baru = 0;

			// 	if($req->jenis_kas == 'I'){
			// 		$saldo_baru = $saldo_terakhir + $jumlah;
			// 	}else{
			// 		$saldo_baru = $saldo_terakhir - $jumlah;
			// 	}

			// 	// echo $saldo_baru;


			// 	\DB::table('cashbook')->whereId($cashbook_id)->update([
			// 		'saldo' => $saldo_baru ]);
			// }

			return redirect('cashbook');
		});
	}

	public function edit($cashbook_id){
		$data = \DB::table('cashbook')
				->select('cashbook.*',\DB::raw('date_format(tanggal,"%d-%m-%Y") as tanggal_formatted'))
				->whereId($cashbook_id)
				->first();

		return view('cashbook.edit',[
				'data' => $data
			]);
	}

	public function update(Request $req){
		return \DB::transaction(function()use($req){
			

			// generate tanggal
            $cash_date = $req->tanggal;
            $arr_tgl = explode('-',$cash_date);
            $cash_date = new \DateTime();
            $cash_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

            // jumlah
            $jumlah = str_replace(',', '', $req->jumlah);
            $jumlah = str_replace('.00', '', $jumlah);

			// insert to table cashbook
			 \DB::table('cashbook')
			 	->whereId($req->cashbook_id)
			 	->update([
					'tanggal' => $cash_date,
					'jumlah' => $jumlah,
					'desc' => $req->keterangan,
				]);

			return redirect('cashbook');
		});
	}

	public function delete($cashbook_id){
		$data = \DB::table('cashbook')
				->delete($cashbook_id);

		return redirect('cashbook');
	}



}
