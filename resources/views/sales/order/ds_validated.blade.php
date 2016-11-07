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
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="sales/order" >Sales Orders</a> <i class="fa fa-angle-double-right" ></i> {{$data_master->order_number}}
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            
            <button class="btn btn-danger" data-href="sales/order/reconcile/{{$data_master->id}}"  id="btn-cancel-order" >Cancel Order</button>
            <div class="btn-group ">
                <button type="button" class="btn btn-success  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                  Print <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                  <li><a href="#" onclick="return false;" id="btn-direct-print" >Direct Print</a></li>
                  <li><a href="#" onclick="return false;" id="btn-print-pdf" >PDF</a></li>
                </ul>
            </div>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-gray" >Done</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-blue" >Validated</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Open</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Draft</a>
        </div>
        <div class="box-body">
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data_master->order_number}}</h3></label>
            <input type="hidden" name="sales_order_id" value="{{$data_master->id}}">
            <input type="hidden" name="is_direct_sales" value="Y">
            <table class="table" >
                <tbody>
                    <tr>
                        <td class="col-lg-2">
                            <label>Customer</label>
                        </td>
                        <td class="col-lg-4" >
                            {{'['.$data_master->kode_customer . '] ' . $data_master->customer}}
                        </td>
                        <td class="col-lg-2" >
                            <label>Order Date</label>
                        </td>
                        <td class="col-lg-4" >
                            {{$data_master->order_date_formatted}}
                        </td>
                    </tr>
                    <tr class="direct_sales_input"  >
                        <td>
                            <label>Nopol</label>
                        </td>
                        <td>
                            {{$data_master->nopol}}
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    
                </tbody>
            </table>

            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

            <table id="table-product" class="table table-bordered table-condensed" >
                <thead>
                    <tr>
                        <th style="width:25px;" >NO</th>
                        <th  >MATERIAL</th>
                        {{-- <th class="col-lg-1" >SATUAN</th> --}}
                        <th class="col-lg-1" >QUANTITY</th>
                        <th class="col-lg-2 " >UNIT PRICE</th>
                        {{-- <th class="col-lg-2" >S.U.P</th> --}}
                        <th class="col-lg-2 " >TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- LOAD DATA --}}
                    <?php $rownum=1; ?>
                    <?php $total=0; ?>
                    @foreach($data_detail as $dt)
                        <?php $total+=$dt->total; ?>
                        <tr class="row-product"  >
                            <td class="text-right" >{{$rownum++}}</td>
                            <td>
                                {{'['.$dt->kode_material.'] ' . $dt->material}}
                            </td>
                            <td class="text-right" >
                                {{$dt->qty}}
                            </td>
                            <td class="uang text-right" >
                                {{$dt->harga}}
                            </td>
                            <td class=" text-right uang" >{{$dt->qty * $dt->harga}}</td>
                        </tr>
                    @endforeach
                    {{-- END LOAD DATA --}}
                    
                    <tr class="" >
                        <td colspan="4" class="text-right">
                            <label>TOTAL</label>
                        </td>
                        <td class=" text-right" >
                            <label class="label-total uang" >{{$total}}</label>
                        </td>
                    </tr>
                    
                    
                    
                </tbody>
            </table>

        </div><!-- /.box-body -->
        <div class="box-footer" >
            {{-- <button type="submit" class="btn btn-primary" id="btn-direct-sales-save" >Save</button> --}}
            <a class="btn btn-danger" id="btn-cancel-save" href="sales/order" >Close</a>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {
    

    // -----------------------------------------------------
    // SET AUTO NUMERIC
    // =====================================================
    $('.uang').autoNumeric('init',{
        vMin:'0',
        vMax:'9999999999'
    });
    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
    });

    // CANCEL ORDER
    $('#btn-cancel-order').click(function(){
        if(confirm('Anda akan membatalkan data ini? \nData yang telah tersimpan akan dihapus & tidak dapat dikembalikan.')){
            // alert('reconcile');
            location.href = $(this).data('href');
        }
    });
   
    

})(jQuery);
</script>
@append