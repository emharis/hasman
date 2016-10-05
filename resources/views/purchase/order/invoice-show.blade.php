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
        <a href="purchase/order" >Purchase Order</a> 
         <i class="fa fa-angle-double-right" ></i> 
         <a href="purchase/order/edit/{{$purchase_order->id}}" >{{$purchase_order->order_number}}</a> 
         <i class="fa fa-angle-double-right" ></i> 
         <a href="purchase/order/invoices/{{$purchase_order->id}}" >Supplier Bills</a> 
         <i class="fa fa-angle-double-right" ></i> 
         {{$data->bill_number}}
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->bill_number}}</h3></label>
             
            {{-- <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label> --}}
            {{-- <a class="btn  btn-arrow-right pull-right disabled {{$data->status == 'D' ? 'bg-blue' : 'bg-gray'}}" >Done</a> --}}

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled {{$data->status == 'V' ? 'bg-blue' : 'bg-gray'}}" >Paid</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

            <a class="btn btn-arrow-right pull-right disabled {{$data->status == 'O' ? 'bg-blue' : 'bg-gray'}}"" >Open</a>

        </div>
        <div class="box-body">
            

            <input type="hidden" name="invoice_order_id" value="{{$data->id}}">
            
            <table class="table" id="table-master-so" >
                        <tbody>
                            <tr>
                                <td class="col-lg-2">
                                    <label>Supplier</label>
                                </td>
                                <td class="col-lg-4" >
                                    {{'['.$data->kode_supplier .'] ' .$data->supplier}}
                                </td>
                                <td class="col-lg-2" ></td>
                                <td class="col-lg-2" >
                                    <label>Order Number</label>
                                </td>
                                <td class="col-lg-2" >
                                    {{$data->order_number}}
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
            

            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

            <table id="table-product" class="table table-bordered table-condensed" >
                <thead>
                    <tr>
                        <th style="width:25px;" >NO</th>
                        <th  >PRODUCT</th>
                        <th class="col-lg-1" >SATUAN</th>
                        <th class="col-lg-1" >QUANTITY</th>
                        <th class="col-lg-2" >UNIT PRICE</th>
                        {{-- <th class="col-lg-2" >S.U.P</th> --}}
                        <th class="col-lg-2" >SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rownum=1; ?>
                    @foreach($data_detail as $dt)
                    <tr class="row-product">
                        <td class="text-right" >{{$rownum++}}</td>
                        <td>
                            {{'[' . $dt->kode_product .'] ' . $dt->product}}
                        </td>
                        <td class="label-satuan" >{{$dt->product_unit}}</td>
                        <td class="text-right" >
                            {{$dt->qty}}
                        </td>
                        <td class="text-right uang" >  
                            {{$dt->unit_price}}
                        </td>
                        <td class="uang text-right" >
                            {{$dt->qty * $dt->unit_price}}
                        </td>
                    </tr>
                    @endforeach
                    
                    
                    
                </tbody>
            </table>

            <div class="row" >
                <div class="col-lg-8" >
                    {{-- <textarea name="note" class="form-control" rows="3" style="margin-top:5px;" placeholder="Note" ></textarea>
                    <i>* <span>Q.O.H : Quantity on Hand</span></i>
                    <i>&nbsp;|&nbsp;</i>
                    <i><span>S.U.P : Salesperson Unit Price</span></i> --}}
                </div>
                <div class="col-lg-4" >
                    <table class="table table-condensed" >
                        <tbody>
                            <tr>
                                <td class="text-right">
                                    <label>Subtotal :</label>
                                </td>
                                <td class="label-total-subtotal text-right uang" >
                                    {{-- {{$data->subtotal}} --}}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" >
                                    <label>Disc :</label>
                                </td>
                                <td class="text-right uang" >
                                   {{$data->disc}}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" style="border-top:solid darkgray 1px;" >
                                    Total :
                                </td>
                                <td class="label-total text-right uang" style="font-size:18px;font-weight:bold;border-top:solid darkgray 1px;" >
                                    {{$data->total}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>            

        </div><!-- /.box-body -->
        <div class="box-footer" >
            <a class="btn btn-danger" href="purchase/order/invoices/{{$purchase_order->id}}" >Close</a>
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