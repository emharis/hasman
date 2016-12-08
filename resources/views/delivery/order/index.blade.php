@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="plugins/select2/select2.min.css">
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
        Delivery Orders
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" >
            {{-- <label><h3 style="margin:0;padding:0;font-weight:bold;font-size: 1.3em;" >Delivery Orders</h3></label> &nbsp; --}}
            <button class="btn btn-primary " id="btn-batch-edit" href="#" >Batch Edit</button>
            <button class="btn btn-danger hide" id="btn-delete" href="#" >Delete</button>

            <div class="pull-right" >
                <table style="background-color: #ECF0F5;" >
                    <tr>
                        <td class="bg-green text-center" rowspan="2" style="width: 50px;" ><i class="fa fa-truck" ></i></td>
                        <td style="padding-left: 10px;padding-right: 5px;">
                            DELIVERY TO DO
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"  style="padding-right: 5px;" >
                            <label>{{$delivery_to_do}}</label>
                        </td>
                    </tr>
                </table>
            </div>
            
        </div>
        <div class="box-body">
            <div class="row hide" id="row-form-batch-edit" >
                <div class="col-sm-4 col-md-4 col-lg-4 " >
                    <form name="form-batch-edit" method="POST" action="delivery/order/batch-edit"  class="form-horizontal">
                        <table class="table table-bordered" >
                            <tbody>
                                <tr>
                                    <td colspan="2"  >
                                        <label>Batch Edit</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3" >
                                        SO Ref#
                                    </td>
                                    <td class="col-sm-9">
                                        {!! Form::select('select_so_ref',$select_so_ref,null,['class'=>'form-control','style'=>'width:100%!important']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3" >
                                        
                                    </td>
                                    <td>
                                        <button class="btn btn-primary" type="submit" >Submit</button>
                                        <button class="btn btn-danger" id="btn-cancel-batch-edit" type="reset" >Cancel</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>

            <table class="table table-bordered table-condensed table-striped table-hover" id="table-data" >
                <thead>
                    <tr>
                        {{-- <th style="width:10px;" class="text-center">
                            <input type="checkbox" name="ck_all" style="margin-left:15px;padding:0;" >
                        </th> --}}
                        <th>Ref#</th>
                        <th>SO Date</th>
                        <th>Delivery Date</th>
                        <th>Armada</th>
                        <th>SO Ref#</th>
                        <th>Customer</th>
                        <th>Material</th>
                        <th>Pekerjaan</th>
                        {{-- <th>Driver</th> --}}
                        {{-- <th>Tujuan</th> --}}
                        {{-- <th>Lokasi Galian</th> --}}
                        <th>Status</th>
                        <th ></th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($data as $dt)
                        @for($i=0;$i<$dt->qty;$i++)
                            <tr class="" data-id="{{$dt->id}}" >
                                {{-- <td class="text-center">
                                    @if($dt->status != 'V')
                                        <input type="checkbox" class="ck-row" data-orderid="{{$dt->sales_order_id}}" >
                                    @endif
                                </td> --}}
                                <td>{{$dt->delivery_order_number}}</td>
                                <td>{{$dt->order_date_formatted}}</td>
                                <td>{{$dt->delivery_date_formatted}}</td>
                                <td>
                                    {{$dt->nopol}}
                                </td>
                                <td>{{$dt->order_number}}</td>
                                <td>
                                    {{ $dt->customer}}
                                </td>
                                <td>
                                    {{$dt->material}}
                                </td>
                                <td>
                                    {{$dt->pekerjaan}}
                                </td>
                                {{-- <td>
                                    {{$dt->karyawan}}
                                </td> --}}
                                {{-- <td>
                                    {{  $dt->kecamatan  }}
                                </td>
                                <td>
                                    {{$dt->lokasi_galian}}
                                </td> --}}
                                <td class="text-center" >
                                    @if($dt->status == 'D')
                                        <label class="label label-warning" >DRAFT</label>
                                    @elseif($dt->status == 'O')
                                        <label class="label label-primary" >OPEN</label>
                                    @elseif($dt->status == 'V')
                                        <label class="label bg-maroon" >VALIDATED</label>
                                    @elseif($dt->status == 'DN')
                                        <label class="label label-success" >DONE</label>
                                    @else
                                        <label class="label label-danger" >ERROR</label>
                                    @endif
                                </td>
                                <td class="text-center" >
                                    <a class="btn btn-primary btn-xs" href="delivery/order/edit/{{$dt->id}}" data-toggle="tooltip" title="Edit" ><i class="fa fa-edit" ></i></a>
                                    @if($dt->status == 'O' || $dt->status == 'V' || $dt->status == 'DN')
                                        <a class="btn btn-success btn-xs" data-toggle="tooltip" title="Cetak Surat Jalan" ><i class="fa fa-print" ></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endfor
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
<script src="plugins/select2/select2.full.min.js"></script>

<script type="text/javascript">
(function ($) {

    var TBL_DATA = $('#table-data').DataTable({
        sort: false,
    });

    // select 2
    $('select[name=select_so_ref]').select2();

    // ==========================================================================
    // FILTER SECTION
    $('select[name=select_filter_by]').change(function(){
        var filter_by = $(this).val();

        // hide filter input
        $('.input-filter').removeClass('hide');
        $('.input-filter').hide();

        if(filter_by == 'delivery_order_number'
                    || filter_by == 'order_number'
                    || filter_by == 'customer'
                    || filter_by == 'material'
                    || filter_by == 'nopol'
                    || filter_by == 'pekerjaan' ){
            $('input[name=filter_string]').show();
        }else if(filter_by == 'order_date' || filter_by == 'delivery_date'){
            $('.input-filter-by-date').show();
        }else{
            // order by status open, validated, done
            // otomatis submit tanpa tombol click
            var filter_by = $('select[name=select_filter_by]').val();
            var formFilter = $('<form>').attr('method','GET').attr('action','delivery/order/filter');
            formFilter.append($('<input>').attr('type','hidden').attr('name','filter_by').val(filter_by));
            formFilter.submit();
        }

    });

    $('#btn-submit-filter').click(function(){
        var filter_by = $('select[name=select_filter_by]').val();
        var formFilter = $('<form>').attr('method','GET').attr('action','delivery/order/filter');

        if(filter_by == 'order_date' || filter_by == 'delivery_date'){
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

    // SET DATEPICKER
    $('.input-tanggal').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    // END OF SET DATEPICKER

    // check all checkbox
    $('input[name=ck_all]').change(function(){
        // if($(this).prop('checked')){
        //     $('input.ck-row').prop('checked',true);
        // }else{
        //     $('input.ck-row').prop('checked',false);

        // };
        $('input.ck-row').prop('checked',$(this).prop('checked'));
        $('input.ck-row').trigger('change');
        // showOptionButton();
    });

    // tampilkan btn delete
    $(document).on('change','.ck-row',function(){
        // showOptionButton();
        if($('.ck-row:checked').length > 0){
            $('#btn-delete').removeClass('hide');
        }else{
            $('#btn-delete').addClass('hide');
        }
    });

    // function showOptionButton(){
    //     var checkedCk = $('input.ck-row:checked');

    //     if(checkedCk.length > 0){
    //         // tampilkan option button
    //         $('#btn-delete').removeClass('hide');
    //     }else{
    //         $('#btn-delete').addClass('hide');
    //     }
    // }

    // Row Clicked
    $('.row-to-edit').click(function(){
        var row = $(this);
        var data_id = row.data('id');
        location.href = 'delivery/order/edit/' + data_id ;
        // alert('delivery/order/edit/' + data_id)
    });

    // Delete Data Lokasi
    $('#btn-delete').click(function(e){
        if(confirm('Anda akan menhapus data ini?')){
            var dataid = [];
            $('input.ck-row:checked').each(function(i){
                var data_id = $(this).parent().parent().data('id');
                var sales_order_id = $(this).data('orderid');
                // alert(data_id);
                var newdata = {"id":data_id,"sales_order_id":sales_order_id}
                dataid.push(newdata);
            });

            var deleteForm = $('<form>').attr('method','POST').attr('action','delivery/order/delete');
            deleteForm.append($('<input>').attr('type','hidden').attr('name','dataid').attr('value',JSON.stringify(dataid)));
            deleteForm.submit();
        }

        e.preventDefault();
        return false;
    });

    // TAMPILKAN FORM OPTION BATCH EDIT
    $('#btn-batch-edit').click(function(){
        if($('#row-form-batch-edit').hasClass('hide')){
            $('#row-form-batch-edit').removeClass('hide');
            $('#row-form-batch-edit').hide();
            $('#row-form-batch-edit').slideDown(250);
        }
    });

    // CANCEL BATCH EDIT
    $('#btn-cancel-batch-edit').click(function(){
        $('#row-form-batch-edit').slideUp(250,function(){
            $('#row-form-batch-edit').addClass('hide');
        });
    });

    // SUBMIT BATCH EDIT
    $('form[name=form-batch-edit]').submit(function(){
            var sales_order_id = $('select[name=select_so_ref]').val();
            location.href = 'delivery/order/batch-edit/' + sales_order_id;

        return false;
    });



})(jQuery);
</script>
@append
