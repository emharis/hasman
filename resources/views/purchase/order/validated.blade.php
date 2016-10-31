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
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="purchase/order" >Purchase Orders</a>
        <i class="fa fa-angle-double-right" ></i>
        {{$data_master->order_number}}
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <button class="btn btn-danger btn-sm " id="btn-cancel-order" data-href="purchase/order/cancel-order/{{$data_master->id}}" >Cancel Order</button>
            <button class="btn btn-success btn-sm" >Print</button>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled {{$data_master->status == 'DN' ? 'bg-blue' : 'bg-gray'}}" >Done</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled {{$data_master->status == 'V' ? 'bg-blue' : 'bg-gray'}}" >Validated</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Open</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Draft</a>
        </div>
        <div class="box-body">
          
            <div class="row" >
                <div class="col-sm-10 col-md-10 col-lg-10">
                    <label>
                        <h3 style="margin:0;padding:0;font-weight:bold;" >{{$data_master->order_number}}</h3>
                    </label>

                    <input type="hidden" name="purchase_order_id" value="{{$data_master->id}}">
                    <table class="table" id="table-master-so" >
                        <tbody>
                            <tr>
                                <td class="col-lg-2">
                                    <label>Supplier</label>
                                </td>
                                <td class="col-lg-6" >
                                    {{'['.$data_master->kode_supplier .'] ' .$data_master->supplier}}
                                </td>
                                <td class="col-lg-2" >
                                    <label>Order Date</label>
                                </td>
                                <td class="col-lg-2" >
                                    {{$data_master->order_date_formatted}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Supplier Ref#</label>
                                </td>
                                <td>
                                    {{$data_master->supplier_ref}}
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="col-sm-2 col-md-2 col-lg-2" >
                    {{-- INVOICES SHORTCUT --}}
                    <a class="btn btn-app pull-right" href="purchase/order/invoices/show/{{$data_master->id}}" >
                            <span class="badge bg-green">1</span>
                            <i class="fa fa-newspaper-o"></i> Invoices
                        </a>
                </div>
            </div>

            

            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

            <table id="table-product" class="table table-bordered table-condensed" >
                <thead>
                    <tr>
                        {{-- <th style="width:25px;" >NO</th> --}}
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
                        {{-- <td class="text-right" >{{$rownum++}}</td> --}}
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
                                    {{$data_master->subtotal}}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" >
                                    <label>Disc :</label>
                                </td>
                                <td class="text-right uang" >
                                   {{$data_master->disc}}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" style="border-top:solid darkgray 1px;" >
                                    Total :
                                </td>
                                <td class="label-total text-right uang" style="font-size:18px;font-weight:bold;border-top:solid darkgray 1px;" >
                                    {{$data_master->total}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                {{-- <div class="col-lg-12" >
                    <button type="submit" class="btn btn-primary" id="btn-save" >Save</button>
                            <a class="btn btn-danger" id="btn-cancel-save" >Cancel</a>
                </div> --}}
            </div>


        </div><!-- /.box-body -->
        <div class="box-footer" >
            <a class="btn btn-danger" href="purchase/order" >Close</a>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {
    // Reconcile
    $('#btn-reconcile').click(function(){
        if(confirm('Anda akan membatalkan data ini? \nData yang telah tersimpan akan dihapus & tidak dapat dikembalikan.')){
            // alert('reconcile');
            location.href = $(this).data('href');
        }
    });

    // AUTO NUMERIC
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
        // check can delete
        var purchase_order_id = $('input[name=purchase_order_id]').val();
        // location.href = "purchase/order/can-delete/" + purchase_order_id;

        $.get("purchase/order/can-delete/" + purchase_order_id,null,function(res){
            // if(res == 'false'){
            //   alert('Data purchase order ini tidak dapat dihapus. \nAnda harus menghapus data invoice yang berhubungan dengan purchase order ini terlebih dahulu.');
            // }else{
              location.href = "purchase/order/cancel-order/" + purchase_order_id;
            // }
        });


      }
    });

})(jQuery);
</script>
@append
