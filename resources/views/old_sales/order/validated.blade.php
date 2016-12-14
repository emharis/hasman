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
            
             <a class="btn btn-primary" id="btn-validate" href="sales/order/set-to-done/{{$data_master->id}}" >Set to done</a>

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
            <div class="row" >
                <div class="col-sm-8 col-md-8 col-lg-8">
                    <table class="table" >
                        <tbody>
                            <tr>
                                <td class="col-lg-2">
                                    <label>Customer</label>
                                </td>
                                <td class="col-lg-4" >
                                    {{'['.$data_master->kode_customer .'] ' .$data_master->customer}}
                                </td>
                                <td class="col-lg-2" ></td>
                                <td class="col-lg-2" >
                                    <label>Order Date</label>
                                </td>
                                <td class="col-lg-2" >
                                    {{$data_master->order_date_formatted}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4" >
                     {{-- modul invoices --}}
                     <a class="btn btn-app pull-right" href="sales/order/invoice/{{$data_master->id}}" >
                        <span class="badge bg-green">1</span>
                        <i class="fa fa-newspaper-o"></i> Invoice
                    </a>
                    
                    <a class="btn btn-app pull-right" href="sales/order/delivery/{{$data_master->id}}" >
                        <span class="badge bg-green">{{$delivery_order_count}}</span>
                        <i class="fa fa-truck"></i> Delivery
                    </a>

                    
                </div>
            </div>
            

            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

            <table id="table-product" class="table table-bordered table-condensed" >
                <thead>
                    <tr>
                        <th style="width:25px;" >NO</th>
                        <th  >MATERIAL</th>
                        <th class="col-lg-1" >QUANTITY</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rownum=1; ?>
                    @foreach($data_detail as $dt)
                        <tr class="row-product"  >
                            <td class="text-right" >{{$rownum++}}</td>
                            <td>
                                {{'['.$dt->kode_material.'] ' . $dt->material}}
                            </td>
                            <td>
                                {{$dt->qty}}
                            </td>
                        </tr>
                    @endforeach
                    
                    
                </tbody>
            </table>

            {{-- <div class="row" >
                <div class="col-lg-8" >
                    <textarea name="note" class="form-control" rows="3" style="margin-top:5px;" placeholder="Note" ></textarea>
                    <i>* <span>Q.O.H : Quantity on Hand</span></i>
                    <i>&nbsp;|&nbsp;</i>
                    <i><span>S.U.P : Salesperson Unit Price</span></i>
                </div>
                <div class="col-lg-4" >
                    <table class="table table-condensed" >
                        <tbody>
                            <tr>
                                <td class="text-right">
                                    <label>Subtotal :</label>
                                </td>
                                <td class="label-total-subtotal text-right" >
                                    
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" >
                                    <label>Disc :</label>
                                </td>
                                <td >
                                   <input style="font-size:14px;" type="text" name="disc" class="input-sm form-control text-right input-clear"> 
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" style="border-top:solid darkgray 1px;" >
                                    Total :
                                </td>
                                <td class="label-total text-right" style="font-size:18px;font-weight:bold;border-top:solid darkgray 1px;" >
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-12" >
                    <button type="submit" class="btn btn-primary" id="btn-save" >Save</button>
                            <a class="btn btn-danger" id="btn-cancel-save" >Cancel</a>
                </div>
            </div> --}}



            {{-- <a id="btn-test" href="#" >TEST</a> --}}


        </div><!-- /.box-body -->
        <div class="box-footer" >
            <a class="btn btn-danger" href="sales/order" >Close</a>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')

<script type="text/javascript">
(function ($) {
    

})(jQuery);
</script>
@append