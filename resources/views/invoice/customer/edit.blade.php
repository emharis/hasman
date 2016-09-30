@extends('layouts.master')

@section('styles')
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<style>
    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
    .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-selected { background: #FFE291; }
    .autocomplete-suggestions strong { font-weight: normal; color: red; }
    .autocomplete-group { padding: 2px 5px; }
    .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }

    .table-row-mid > tbody > tr > td {
        vertical-align:middle;
    }

    input.input-clear {
        display: block; 
        padding: 0; 
        margin: 0; 
        border: 0; 
        width: 100%;
        background-color:#EEF0F0;
        float:right;
        padding-right: 5px;
        padding-left: 5px;
    }

    #table-master-so tr td{
        vertical-align: top;
    }

    #table-invoice-detail thead tr th{
        text-align: center;
    }
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="invoice/customer" >Customer Invoices</a> <i class="fa fa-angle-double-right" ></i> {{$data->inv_number}}
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            {{-- @if($data->status != 'D')
                <button class="btn btn-danger btn-sm" id="btn-reconcile" data-href="invoice/order/reconcile/{{$data->id}}" >Reconcile</button> 

                <a class="btn btn-primary btn-sm" id="btn-validate" href="invoice/order/set-to-done/{{$data->id}}" >Set to done</a>

            @endif --}}
            <button class="btn btn-primary btn-sm" id="btn-register-payment" >Register Payment</button>
            <div class="btn-group">
              <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Print <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">
                <li><a href="#">Direct Print</a></li>
                <li><a href="#">PDF</a></li>
              </ul>
            </div>
            
             
            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled {{$data->status == 'D' ? 'bg-blue' : 'bg-gray'}}" >Done</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled {{$data->status == 'V' ? 'bg-blue' : 'bg-gray'}}" >Validated</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

            <a class="btn btn-arrow-right pull-right disabled {{$data->status == 'O' ? 'bg-blue' : 'bg-gray'}}"" >Open</a>

        </div>
        <div class="box-body">
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->inv_number}}</h3></label>

            <input type="hidden" name="invoice_order_id" value="{{$data->id}}">
            
            <table class="table" id="table-master-so" >
                        <tbody>
                            <tr>
                                <td class="col-lg-2">
                                    <label>Customer</label>
                                </td>
                                <td class="col-lg-4" >
                                    {{'['.$data->kode_customer .'] ' .$data->customer}}
                                </td>
                                <td class="col-lg-2" ></td>
                                <td class="col-lg-2" >
                                    <label>Order Number</label>
                                </td>
                                <td class="col-lg-2" >
                                    {{$data->order_number}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Pekerjaan</label>
                                </td>
                                <td>
                                    {{$data->pekerjaan}}<br/>
                                    {{$data->alamat_pekerjaan .', ' . $data->desa . ', ' . $data->kecamatan}} <br/>
                                    {{$data->kabupaten . ', ' . $data->provinsi }}
                                </td>
                                <td></td>
                                <td  >
                                    <label>Order Date</label>
                                </td>
                                <td  >
                                    {{$data->order_date_formatted}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
            

            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

            @if($data->kalkulasi == 'K')
                {{-- TABLE KUBIKASI --}}
                <table  class="table table-bordered table-condensed" id="table-invoice-detail" >
                    <thead>
                        <tr>
                            <th rowspan="2" style="width:40px;" >NO</th>
                            <th rowspan="2" >DELIVERY DATE</th>
                            <th rowspan="2" >NOPOL</th>
                            <th rowspan="2" >MATERIAL</th>
                            <th colspan="3" >KUBIKASI</th>
                            <th rowspan="2" >VOLUME</th>
                            <th rowspan="2" >HARGA</th>
                            <th rowspan="2" >TOTAL</th>
                        </tr>
                        <tr>
                            <th>P</th>
                            <th>L</th>
                            <th>T</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rownum=1; ?>
                        <?php $vol=0; ?>
                       @foreach($data_detail as $dt)
                        <tr>
                            <td class="text-right">
                                {{$rownum++}}
                            </td>
                            <td>
                                {{$dt->delivery_date_formatted}}
                            </td>
                            <td>
                                {{$dt->nopol}}
                            </td>
                            <td>
                                {{$dt->material}}
                            </td>
                            <td class="text-right" >                                
                                    {{$dt->panjang}}                                
                            </td>
                            <td class="text-right" >                               
                                    {{$dt->lebar}}                                
                            </td>
                            <td class="text-right" >                                
                                    {{$dt->tinggi}}                            
                            </td>
                            <td class="text-right" style="background-color:whitesmoke;" >                                
                                    {{$dt->volume}}                                
                            </td>
                            
                            <td class="text-right uang" >{{$dt->unit_price}}</td>
                            <td class="text-right uang" style="background-color:whitesmoke;" >{{$dt->total}}</td>
                        </tr>
                        <?php $vol+= $dt->volume; ?>
                       @endforeach                   
                        <tr style="border-top: solid 2px gray;" >
                            <td colspan="7" class="text-right" >
                                <label>TOTAL</label>
                            </td>
                            <td class="text-right" style="background-color:whitesmoke;" >
                                <label>{{$vol}}</label>
                            </td>
                            <td colspan="2" class="text-right " style="background-color:whitesmoke;" >
                                <label class="uang" >{{$data->total}}</label>
                            </td>
                        </tr>
                    </tbody>
                </table>
                {{-- END OF TABLE KUBIKASI --}}
            @elseif($data->kalkulasi == 'T')
                {{-- TABLE TONASE --}}
                <table  class="table table-bordered table-condensed" id="table-invoice-detail" >
                    <thead>
                        <tr>
                            <th rowspan="2" style="width:40px;" >NO</th>
                            <th rowspan="2" >DELIVERY DATE</th>
                            <th rowspan="2" >NOPOL</th>
                            <th rowspan="2" >MATERIAL</th>
                            <th colspan="2" >TONASE</th>
                            <th rowspan="2" >NETTO</th>
                            <th rowspan="2" >HARGA</th>
                            <th rowspan="2" >TOTAL</th>
                        </tr>
                        <tr>
                            <th>GROSS</th>
                            <th>TARE</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rownum=1; ?>
                        <?php $netto=0; ?>
                       @foreach($data_detail as $dt)
                        <tr>
                            <td class="text-right">
                                {{$rownum++}}
                            </td>
                            <td>
                                {{$dt->delivery_date_formatted}}
                            </td>
                            <td>
                                {{$dt->nopol}}
                            </td>
                            <td>
                                {{$dt->material}}
                            </td>
                            <td class="text-right" >                                
                                    {{$dt->gross}}                                
                            </td>
                            <td class="text-right" >                               
                                    {{$dt->tarre}}                                
                            </td>
                            <td class="text-right" style="background-color:whitesmoke;" >
                                    {{$dt->netto}}                                
                            </td>                            
                            <td class="text-right uang" >{{$dt->unit_price}}</td>
                            <td class="text-right uang" style="background-color:whitesmoke;" >{{$dt->total}}</td>
                        </tr>
                        <?php $netto+= $dt->netto; ?>
                       @endforeach                   
                        <tr style="border-top: solid 2px gray;" >
                            <td colspan="6" class="text-right" >
                                <label>TOTAL</label>
                            </td>
                            <td class="text-right" style="background-color:whitesmoke;" >
                                <label>{{$netto}}</label>
                            </td>
                            <td colspan="2" class="text-right " style="background-color:whitesmoke;" >
                                <label class="uang" >{{$data->total}}</label>
                            </td>
                        </tr>
                    </tbody>
                </table>
                {{-- END OF TABLE TONASE --}}
            @else
            @endif

            

        </div><!-- /.box-body -->
        <div class="box-footer" >
            <a class="btn btn-danger" href="invoice/customer" >Close</a>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<script type="text/javascript">
(function ($) {
    // // Reconcile
    // $('#btn-reconcile').click(function(){
    //     if(confirm('Anda akan membatalkan data ini? \nData yang telah tersimpan akan dihapus & tidak dapat dikembalikan.')){
    //         // alert('reconcile');
    //         location.href = $(this).data('href');
    //     }
    // });

    // -----------------------------------------------------
    // SET AUTO NUMERIC
    // =====================================================
    $('.uang').autoNumeric('init',{
        vMin:'0',
        vMax:'9999999999'
    });
    // normalize
    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
    });

})(jQuery);
</script>
@append