@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

<style>
    #table-data > tbody > tr{
        cursor:pointer;
    }
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
         <a href="sales/order" >Sales Orders</a> 
         <i class="fa fa-angle-double-right" ></i> 
         <a href="sales/order/edit/{{$sales_order->id}}" >{{$sales_order->order_number}}</a> 
         <i class="fa fa-angle-double-right" ></i> 
         Delivery Orders
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >Delivery Orders</h3></label>
        </div>
        <div class="box-body">
            
            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped table-hover" id="table-data" >
                <thead>
                    <tr>
                        <th style="width:25px;">No</th>
                        <th>DO Number</th>
                        <th>Material</th>
                        <th>Driver</th>
                        <th>Armada</th>
                        <th>Lokasi Galian</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($delivery_order as $dt)
                        @for($i=0;$i<$dt->qty;$i++)
                            <tr>
                                <td>{{$rownum++}}</td>
                                <td>{{$dt->delivery_order_number}}</td>
                                <td>
                                    {{'['.$dt->kode_material.'] '.$dt->material}}
                                </td>
                                <td>
                                    @if($dt->karyawan)
                                        {{'['.$dt->kode_karyawan . '] ' . $dt->karyawan}}
                                    @endif
                                </td>
                                <td>
                                    @if($dt->armada)
                                        {{'[' . $dt->kode_armada . '] ' . $dt->armada . ' - ' . $dt->nopol}}
                                    @endif
                                </td>
                                <td>
                                    {{$dt->lokasi_galian}}
                                </td>
                                <td>
                                    {{$dt->alamat}}
                                </td>
                                <td>
                                    @if($dt->status == 'D')
                                        Draft
                                    @elseif($dt->status == 'O')
                                        Open
                                    @else
                                        Delivered
                                    @endif
                                </td>
                                <td class="text-center" >
                                    <a class="btn btn-primary btn-xs" href="sales/order/delivery/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                                </td>
                            </tr>
                        @endfor
                   @endforeach
                </tbody>
            </table>
        </div><!-- /.box-body -->
        <div class="box-footer" >
            <a class="btn btn-danger" href="sales/order/edit/{{$sales_order->id}}" >Close</a>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

    // var TBL_KATEGORI = $('#table-data').DataTable({
    //     "columns": [
    //         {className: "text-center","orderable": false},
    //         {className: "text-right"},
    //         null,
    //         null,
    //         null,
    //         null,
    //         {className: "text-center"},
    //         // {className: "text-center"}
    //     ],
    //     order: [[ 1, 'asc' ]],
    // });

   

})(jQuery);
</script>
@append