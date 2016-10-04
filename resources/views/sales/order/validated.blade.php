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
        <a href="sales/order" >Sales Orders</a> <i class="fa fa-angle-double-right" ></i> {{$data_master->order_number}}
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            @if($data_master->status != 'D')
                <button class="btn btn-danger btn-sm" id="btn-reconcile" data-href="sales/order/reconcile/{{$data_master->id}}" >Reconcile</button> 

                <a class="btn btn-primary btn-sm" id="btn-validate" href="sales/order/set-to-done/{{$data_master->id}}" >Set to done</a>

            @endif

            <button class="btn btn-success btn-sm" >Print</button>
             
            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled {{$data_master->status == 'D' ? 'bg-blue' : 'bg-gray'}}" >Done</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled {{$data_master->status == 'V' ? 'bg-blue' : 'bg-gray'}}" >Validated</a>

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
                    <table class="table" id="table-master-so" >
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
                            <tr>
                                <td>
                                    <label>Pekerjaan</label>
                                </td>
                                <td>
                                    @if($data_master->pekerjaan)    
                                        {{$data_master->pekerjaan}}<br/>
                                        {{$data_master->alamat_pekerjaan .', ' . $data_master->desa . ', ' . $data_master->kecamatan}} <br/>
                                        {{$data_master->kabupaten . ', ' . $data_master->provinsi }}
                                    @else
                                        -
                                    @endif
                                    
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4" >
                    {{-- INVOICES SHORTCUT --}}
                    @if($data_master->status == 'D')
                        <a class="btn btn-app pull-right" href="sales/order/invoices/{{$data_master->id}}" >
                            <span class="badge bg-green">{{$invoices_count}}</span>
                            <i class="fa fa-newspaper-o"></i> Invoices
                        </a>
                    @endif

                    {{-- DELIVERY SHORTCUT --}}
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
    // Reconcile
    $('#btn-reconcile').click(function(){
        if(confirm('Anda akan membatalkan data ini? \nData yang telah tersimpan akan dihapus & tidak dapat dikembalikan.')){
            // alert('reconcile');
            location.href = $(this).data('href');
        }
    });

})(jQuery);
</script>
@append