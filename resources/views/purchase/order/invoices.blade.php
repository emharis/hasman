@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>

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
         Customer Invoices
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;font-size: 1.3em;" >Customer Invoices</h3></label>
        </div>
        <div class="box-body">
            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped table-hover" id="table-data" >
                <thead>
                    <tr>
                        {{-- <th style="width:25px;" class="text-center">
                            <input type="checkbox" name="ck_all" >
                        </th> --}}
                        <th style="width:25px;">No</th>
                        <th>Customer</th>
                        <th>Number</th>
                        <th>Order Date</th>
                        <th>Order Number</th>
                        <th>Kalkulasi</th>
                        <th>Total</th>
                        <th>Amount Due</th>
                        <th>Status</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dt)
                    <tr data-rowid="{{$rownum}}" data-id="{{$dt->id}}">
                        {{-- <td class="text-center" >
                            @if($dt->status == 'O')
                                <input type="checkbox" class="ck_row" >
                            @endif
                        </td> --}}
                        <td class="row-to-edit text-right" >{{$rownum++}}</td>
                        <td class="row-to-edit" >
                            {{$dt->customer}}
                        </td>
                        <td class="row-to-edit" >
                            {{$dt->inv_number}}
                        </td>
                        <td class="row-to-edit" >
                            {{$dt->order_date_formatted}}
                        </td>
                        <td class="row-to-edit" >
                            {{$dt->order_number}}
                        </td>
                        <td class="row-to-edit" >
                            @if($dt->kalkulasi == 'K')
                                Kubikasi
                            @elseif($dt->kalkulasi == 'T')
                                Tonase
                            @else
                                Ritase
                            @endif
                        </td>
                        <td class="row-to-edit uang text-right" >
                            {{$dt->total}}
                        </td>
                        <td class="row-to-edit uang text-right" >
                            {{$dt->amount_due}}
                        </td>
                        <td class="row-to-edit" >
                            @if($dt->status == 'O')
                                OPEN
                            @elseif($dt->status == 'V')
                                VALIDATED
                            @else
                                DONE
                            @endif    
                        </td>
                        <td class="text-center" >
                            <a class="btn btn-primary btn-xs" href="sales/order/invoices/show/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-right" >
                {{-- {{$data->render()}} --}}
            </div>

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
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

    // ==========================================================================
    // FILTER SECTION
    $('select[name=select_filter_by]').change(function(){
        var filter_by = $(this).val();

        // hide filter input
        $('.input-filter').removeClass('hide');
        $('.input-filter').hide();

        if(filter_by == 'order_number' || filter_by == 'customer' || filter_by == 'pekerjaan' ){
            $('input[name=filter_string]').show();
        }else if(filter_by == 'order_date'){
            $('.input-filter-by-date').show();
        }else{
            // order by status open, validated, done
            // otomatis submit tanpa tombol click
            var filter_by = $('select[name=select_filter_by]').val();
            var formFilter = $('<form>').attr('method','GET').attr('action','invoice/customer/filter');
            formFilter.append($('<input>').attr('type','hidden').attr('name','filter_by').val(filter_by));
            formFilter.submit();
        }

    });

    $('#btn-submit-filter').click(function(){
        var filter_by = $('select[name=select_filter_by]').val();
        var formFilter = $('<form>').attr('method','GET').attr('action','invoice/customer/filter');

        if(filter_by == 'order_date'){
            formFilter.append($('<input>').attr('type','hidden').attr('name','date_start').val($('input[name=input_filter_date_start]').val()));
            formFilter.append($('<input>').attr('type','hidden').attr('name','date_end').val($('input[name=input_filter_date_end]').val()));
        }
        else{
            // FILTER BY STRING
            formFilter.append($('<input>').attr('type','hidden').attr('name','filter_string').val($('input[name=filter_string]').val()));
            // formFilter.append($('<input>').attr('type','hidden').attr('name','total').val($('input[name=input_filter_total]').autoNumeric('get')));
        }

        formFilter.append($('<input>').attr('type','hidden').attr('name','filter_by').val(filter_by));
        formFilter.submit();
    });
    // END OF FILTER SECTION
    // ==========================================================================

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

    // SET DATEPICKER
    $('.input-tanggal').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    // END OF SET DATEPICKER

    // check all checkbox
    $('input[name=ck_all]').change(function(){
        if($(this).prop('checked')){
            $('input.ck_row').prop('checked',true);
        }else{
            $('input.ck_row').prop('checked',false);

        };
        showOptionButton();
    });

    // tampilkan btn delete
    $(document).on('change','.ck_row',function(){
        showOptionButton();
    });

    function showOptionButton(){
        var checkedCk = $('input.ck_row:checked');
        
        if(checkedCk.length > 0){
            // tampilkan option button
            $('#btn-delete').removeClass('hide');
        }else{
            $('#btn-delete').addClass('hide');
        }
    }

    // Row Clicked
    $('.row-to-edit').click(function(){        
        var row = $(this).parent();        
        var data_id = row.data('id');            
        location.href = 'invoice/customer/edit/' + data_id ;        
    });

    // Delete Data Lokasi
    $('#btn-delete').click(function(e){
        if(confirm('Anda akan menhapus data ini?')){
            var dataid = [];
            $('input.ck_row:checked').each(function(i){
                var data_id = $(this).parent().parent().data('id');
                // alert(data_id);
                var newdata = {"id":data_id}
                dataid.push(newdata);
            });

            var deleteForm = $('<form>').attr('method','POST').attr('action','invoice/customer/delete');
            deleteForm.append($('<input>').attr('type','hidden').attr('name','dataid').attr('value',JSON.stringify(dataid)));
            deleteForm.submit();
        }

        e.preventDefault();
        return false;
    });

    

})(jQuery);
</script>
@append