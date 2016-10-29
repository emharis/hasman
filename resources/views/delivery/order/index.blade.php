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
        Delivery Orders
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" >
            <div class="row" >
                <div class="col-sm-6 col-md-6 col-lg-6" >
                    <label><h3 style="margin:0;padding:0;font-weight:bold;font-size: 1.3em;" >Delivery Orders</h3></label>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6" >
                    {{-- Filter section --}}
                    <div class="input-group">
                        <span class="input-group-addon bg-gray" >
                            Filter
                        </span>
                        <div class="input-group-btn" style="width: 30%;" >
                            <select name="select_filter_by" class="form-control" >
                                <option value="delivery_order_number" >DO Number</option>
                                <option value="order_date" >Order Date</option>
                                <option value="delivery_date" >Delivery Date</option>
                                <option value="order_number" >Order Number</option>
                                <option value="customer" >Customer</option>
                                <option value="material" >Material</option>
                                <option value="pekerjaan" >Pekerjaan</option>
                                <option value="nopol" >Armada</option>
                                <option disabled>──────────</option>
                                <option value="D" >DRAFT</option>
                                <option value="O" >OPEN</option>
                                <option value="V" >VALIDATED</option>

                            </select>
                        </div><!-- /btn-group -->

                        {{-- Filter by string --}}
                        <input type="text" name="filter_string" class="form-control input-filter ">

                        {{-- Filter by date --}}
                        <div class="input-group-btn input-filter-by-date hide input-filter " style="width: 30%;" >
                            <input type="text" name="input_filter_date_start" class="form-control input-tanggal">
                        </div>
                        <input type="text" name="input_filter_date_end" class="form-control input-filter  input-tanggal input-filter-by-date hide">

                        {{-- Filter submit button --}}
                        <div class="input-group-btn" >
                            <button class="btn btn-success" id="btn-submit-filter" ><i class="fa fa-search" ></i></button>
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
                        <th style="width:25px;">No</th>
                        <th>DO Number</th>
                        <th>Order Date</th>
                        <th>Delivery Date</th>
                        <th>Order Number</th>
                        <th>Customer</th>
                        <th>Material</th>
                        <th>Pekerjaan</th>
                        {{-- <th>Driver</th> --}}
                        {{-- <th>Armada</th> --}}
                        {{-- <th>Tujuan</th> --}}
                        {{-- <th>Lokasi Galian</th> --}}
                        <th>Status</th>
                        <th style="width:25px;"></th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($data as $dt)
                        @for($i=0;$i<$dt->qty;$i++)
                            <tr class="row-to-edit" data-id="{{$dt->id}}" >
                                <td class="text-right">{{$rownum++}}</td>
                                <td>{{$dt->delivery_order_number}}</td>
                                <td>{{$dt->order_date_formatted}}</td>
                                <td>{{$dt->delivery_date_formatted}}</td>
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
                                    {{$dt->nopol}}
                                </td> --}}
                                {{-- <td>
                                    {{  $dt->kecamatan  }}
                                </td>
                                <td>
                                    {{$dt->lokasi_galian}}
                                </td> --}}
                                <td>
                                    @if($dt->status == 'D')
                                        Draft
                                    @elseif($dt->status == 'O')
                                        Open
                                    @elseif($dt->status == 'V')
                                        Validated
                                    @elseif($dt->status == 'DN')
                                        Done
                                    @else
                                        <label class="label label-danger" >ERROR</label>
                                    @endif
                                </td>
                                <td class="text-center" >
                                    <a class="btn btn-primary btn-xs" href="delivery/order/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                                </td>
                            </tr>
                        @endfor
                   @endforeach
                </tbody>
            </table>

            <div class="text-right" >
                {{$data->render()}}
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

    // var TBL_KATEGORI = $('#table-data').DataTable({
    //     "columns": [
    //         {className: "text-right","orderable": false},
    //         {className: "text-left"},
    //         null,
    //         null,
    //         null,
    //         null,
    //         null,
    //         null,
    //         null,
    //         // null,
    //         // null,
    //         // null,
    //         {className: "text-center","orderable": false},
    //         // {className: "text-center"}
    //     ],
    //     sort: false,
    // });

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
        var row = $(this);
        var data_id = row.data('id');
        location.href = 'delivery/order/edit/' + data_id ;
        // alert('delivery/order/edit/' + data_id)
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

            var deleteForm = $('<form>').attr('method','POST').attr('action','delivery/order/delete');
            deleteForm.append($('<input>').attr('type','hidden').attr('name','dataid').attr('value',JSON.stringify(dataid)));
            deleteForm.submit();
        }

        e.preventDefault();
        return false;
    });



})(jQuery);
</script>
@append
