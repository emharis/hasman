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
         <a href="invoice/supplier/bill" >Supplier Bills</a>
         <i class="fa fa-angle-double-right" ></i>
         <a href="invoice/supplier/bill/edit/{{$bill->id}}" >{{$bill->bill_number}}</a>
         <i class="fa fa-angle-double-right" ></i>
         Payments
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label>
                <small>Payments of</small>
                <h3 style="margin:0;padding:0;font-weight:bold;font-size: 1.3em" >{{$bill->bill_number}}</h3>
            </label>
        </div>
        <div class="box-body">

            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-hover" id="table-data" >
                <thead>
                    <tr>
                        <th style="width:25px;">No</th>
                        <th>Payment Date</th>
                        <th>Number</th>
                        <th>Payment Amount</th>
                        <th>Status</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                <?php $total = 0; ?>
                   @foreach($payments as $dt)
                        <tr>
                            <td>{{$rownum++}}</td>
                            <td>
                                {{$dt->payment_date_formatted}}
                            </td>
                            <td>
                                {{$dt->payment_number}}
                            </td>
                            <td class="uang text-right" >
                                {{$dt->payment_amount}}
                            </td>
                            <td>
                                @if($dt->status == 'P')
                                    Posted
                                @else
                                    Draft
                                @endif
                            </td>
                            <td class="text-center" >
                                <a class="btn btn-primary btn-xs btn-show-payment" href="invoice/supplier/bill/payment/show/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                                <a class="btn btn-danger btn-xs btn-delete-payment" href="invoice/supplier/bill/payment/delete/{{$dt->id}}" ><i class="fa fa-trash-o" ></i></a>
                            </td>
                        </tr>
                        <?php $total+=$dt->payment_amount; ?>
                   @endforeach
                    <tr style="background-color: #EFEFF7;border-top: 2px solid #CACACA;" >
                        <td colspan="3" ></td>
                        <td class=" text-right" >
                            <label class="uang" >{{$total}}</label>
                        </td>
                        <td colspan="2" ></td>
                    </tr>
                </tbody>
            </table>
        </div><!-- /.box-body -->
        <div class="box-footer" >
            <a class="btn btn-danger" href="invoice/supplier/bill/edit/{{$bill->id}}" >Close</a>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
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

    // normalize
    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
    });
    // END OF AUTONUMERIC

    // DELETE PAYMENT
    $('.btn-delete-payment').click(function(){
        if(confirm('Anda akan menghapus data ini?')){

        }else{
            return false;
        }
    });

})(jQuery);
</script>
@append
