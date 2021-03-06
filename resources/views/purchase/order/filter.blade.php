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
        Sales Orders
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" >
            <div class="row" >
                <div class="col-sm-6 col-md-6 col-lg-6" >
                    <a class="btn btn-primary" id="btn-add" href="sales/order/create" >Create</a>
                    <a class="btn btn-danger hide" id="btn-delete" href="#" >Delete</a>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6" >
                    {{-- Filter section --}}
                    <div class="input-group">
                        <span class="input-group-addon bg-gray" >
                            Filter
                        </span>
                        <div class="input-group-btn" style="width: 30%;" >
                            <select name="select_filter_by" class="form-control" >
                                <option value="order_number" >Nomor Order</option>
                                <option value="order_date" >Tanggal</option>
                                <option value="customer" >Customer</option>
                                <option value="pekerjaan" >Pekerjaan</option>
                                <option disabled>──────────</option>
                                <option value="O" >OPEN</option>
                                <option value="V" >VALIDATED</option>
                                <option value="D" >DONE</option>

                            </select>
                            <input type="hidden" name="filter_by" value="{{Input::get('filter_by')}}"> 
                            {{-- {!! Form::select('select_filter_by',[
                                    'order_number' => 'Order Number',
                                    'order_date' => 'Tanggal',
                                    'customer' => 'Customer',
                                    'pekerjaan' => 'Pekerjaan',
                                    null => '──────────',
                                    'open' => 'OPEN',
                                    'validated' => 'VALIDATED',
                                    'done' => 'DONE',
                                ],Input::get('filter_by'),['class'=>'form-control']) !!} --}}
                        </div><!-- /btn-group -->

                        {{-- Filter by string --}}
                        <input type="text" name="filter_string" class="form-control input-filter " value="{{Input::get('filter_string')}}" >

                        {{-- Filter by date --}}
                        <div class="input-group-btn input-filter-by-date hide input-filter " style="width: 30%;" >
                            <input type="text" name="input_filter_date_start" class="form-control input-tanggal">
                        </div>
                        <input type="text" name="input_filter_date_end" class="form-control input-filter  input-tanggal input-filter-by-date hide">

                        {{-- Filter submit button --}}
                        <div class="input-group-btn" >
                            <button class="btn btn-success btn-flat" id="btn-submit-filter" ><i class="fa fa-search" ></i></button>
                        </div>

                        <div class="input-group-btn" >
                            <a class="btn btn-danger " href="sales/order" ><i class="fa fa-refresh" ></i></a>
                        </div>

                    </div>
                    {{-- End of filter section --}}
                </div>
            </div>
        </div>
        <div class="box-body">
            <?php $rownum = ($data->currentPage() - 1 ) * $paging_item_number + 1 ; ?>
            <table class="table table-bordered table-condensed table-striped table-hover" id="table-data" >
                <thead>
                    <tr>
                        <th style="width:25px;" class="text-center">
                            <input type="checkbox" name="ck_all" >
                        </th>
                        <th style="width:25px;">No</th>
                        <th>Nomor Order</th>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Pekerjaan</th>
                        <th>Status</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dt)
                    <tr data-rowid="{{$rownum}}" data-id="{{$dt->id}}">
                        <td class="text-center" >
                            @if($dt->status == 'O')
                                <input type="checkbox" class="ck_row" >
                            @endif
                        </td>
                        <td class="row-to-edit text-right" >{{$rownum++}}</td>
                        <td class="row-to-edit" >
                            {{$dt->order_number}}
                        </td>
                        <td class="row-to-edit" >
                            {{$dt->order_date_formatted}}
                        </td>
                        <td class="row-to-edit" >
                            {{$dt->customer}}
                        </td>
                        <td class="row-to-edit" >
                            {{$dt->pekerjaan}}
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
                            <a class="btn btn-primary btn-xs" href="sales/order/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-right" >
                {!! $data->appends(['filter_string' => \Input::get('filter_string')])
                        ->appends(['filter_by' => \Input::get('filter_by')])
                        ->appends(['date_start' => \Input::get('date_start')])
                        ->appends(['date_end' => \Input::get('date_end')])
                                    ->render() !!}
            </div>

        </div><!-- /.box-body -->
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

    // set value for select filter
    $('select[name=select_filter_by]').val($('input[name=filter_by]').val());
    $('select[name=select_filter_by]').trigger('change');

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
            var formFilter = $('<form>').attr('method','GET').attr('action','sales/order/filter');
            formFilter.append($('<input>').attr('type','hidden').attr('name','filter_by').val(filter_by));
            formFilter.submit();
        }

    });

    $('#btn-submit-filter').click(function(){
        var filter_by = $('select[name=select_filter_by]').val();
        var formFilter = $('<form>').attr('method','GET').attr('action','sales/order/filter');

        if(filter_by == 'order_date'){
            formFilter.append($('<input>').attr('type','hidden').attr('name','date_start').val($('input[name=input_filter_date_start]').val()));
            formFilter.append($('<input>').attr('type','hidden').attr('name','date_end').val($('input[name=input_filter_date_end]').val()));
        }else{
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

    // var TBL_KATEGORI = $('#table-data').DataTable({
    //     "columns": [
    //         {className: "text-center","orderable": false},
    //         {className: "text-right"},
    //         null,
    //         null,
    //         null,
    //         null,
    //         null,
    //         {className: "text-center"},
    //         // {className: "text-center"}
    //     ],
    //     order: [[ 1, 'asc' ]],
    // });

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
        location.href = 'sales/order/edit/' + data_id ;        
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

            var deleteForm = $('<form>').attr('method','POST').attr('action','sales/order/delete');
            deleteForm.append($('<input>').attr('type','hidden').attr('name','dataid').attr('value',JSON.stringify(dataid)));
            deleteForm.submit();
        }

        e.preventDefault();
        return false;
    });

    

})(jQuery);
</script>
@append