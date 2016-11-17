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
        Supplier Bills
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;font-size: 1.3em;" >Supplier Bills</h3></label>

            <div class="pull-right" >
                <table style="background-color: #ECF0F5;" >
                    <tr>
                        <td class="bg-orange text-center" rowspan="2" style="width: 50px;" ><i class="ft-rupiah" ></i></td>
                        <td style="padding-left: 10px;padding-right: 5px;">
                            TOTAL AMOUNT DUE
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"  style="padding-right: 5px;" >
                            <label class="uang" >{{$amount_due}}</label>
                        </td>
                    </tr>
                </table>
            </div>
            
        </div>
        <div class="box-body">
            <table class="table table-bordered table-condensed table-striped table-hover" id="table-data" >
                <thead>
                    <tr>
                        <th>Ref#</th>
                        <th>Customer</th>
                        <th>PO Date</th>
                        <th>PO Ref#</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Amount Due</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dt)
                    <tr  data-id="{{$dt->id}}">
                        
                        <td class="row-to-edit" >
                            {{$dt->bill_number}}
                        </td>
                        <td class="row-to-edit" >
                            {{'[' . $dt->kode_supplier . '] ' . $dt->supplier}}
                        </td>
                        <td class="row-to-edit" >
                            {{$dt->order_date_formatted}}
                        </td>
                        <td class="row-to-edit" >
                            {{$dt->order_number}}
                        </td>
                        <td class="row-to-edit uang text-right" >
                            {{$dt->total}}
                        </td>
                        <td class="row-to-edit text-center" >
                            @if($dt->status == 'O')
                                <label class="label label-warning" >OPEN</label>
                            @elseif($dt->status == 'V')
                                <label class="label label-danger" >VALIDATED</label>
                            @else
                                <label class="label label-success" >PAID</label>
                            @endif    
                        </td>
                        <td class="row-to-edit uang text-right" >
                            {{$dt->amount_due}}
                        </td>
                        <td class="text-center" >
                            <a class="btn btn-primary btn-xs" href="invoice/supplier/bill/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div><!-- /.box-body -->
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

        if(filter_by == 'order_number' || filter_by == 'supplier' || filter_by == 'pekerjaan' ){
            $('input[name=filter_string]').show();
        }else if(filter_by == 'order_date'){
            $('.input-filter-by-date').show();
        }else{
            // order by status open, validated, done
            // otomatis submit tanpa tombol click
            var filter_by = $('select[name=select_filter_by]').val();
            var formFilter = $('<form>').attr('method','GET').attr('action','invoice/supplier/bill/filter');
            formFilter.append($('<input>').attr('type','hidden').attr('name','filter_by').val(filter_by));
            formFilter.submit();
        }

    });

    $('#btn-submit-filter').click(function(){
        var filter_by = $('select[name=select_filter_by]').val();
        var formFilter = $('<form>').attr('method','GET').attr('action','invoice/supplier/bill/filter');

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

    var TBL_DATA = $('#table-data').DataTable({
        sort:false
    });

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
        location.href = 'invoice/supplier/bill/edit/' + data_id ;        
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

            var deleteForm = $('<form>').attr('method','POST').attr('action','invoice/supplier/bill/delete');
            deleteForm.append($('<input>').attr('type','hidden').attr('name','dataid').attr('value',JSON.stringify(dataid)));
            deleteForm.submit();
        }

        e.preventDefault();
        return false;
    });

    

})(jQuery);
</script>
@append