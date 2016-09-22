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
        Delivery Orders
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-body">
            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped table-hover" id="table-data" >
                <thead>
                    <tr>
                        <th style="width:25px;">No</th>
                        <th>DO Number</th>
                        <th>Order Date</th>
                        <th>Delivery Date</th>
                        <th>Customer</th>
                        <th>Material</th>
                        <th>Pekerjaan</th>
                        <th>Driver</th>
                        <th>Nopol</th>
                        <th>Tujuan</th>
                        <th>Lokasi Galian</th>
                        <th>Status</th>
                        <th style="width:25px;"></th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($data as $dt)
                        @for($i=0;$i<$dt->qty;$i++)
                            <tr>
                                <td>{{$rownum++}}</td>
                                <td>{{$dt->delivery_order_number}}</td>
                                <td>{{$dt->order_date_formatted}}</td>
                                <td>{{$dt->delivery_date_formatted}}</td>
                                <td>
                                    {{ $dt->customer}}
                                </td>
                                <td>
                                    {{$dt->material}}
                                </td>
                                <td>
                                    {{$dt->pekerjaan}}
                                </td>
                                <td>
                                    {{$dt->karyawan}}
                                </td>
                                <td>
                                    {{$dt->nopol}}
                                </td>
                                <td>
                                    {{  $dt->kecamatan  }}
                                </td>
                                <td>
                                    {{$dt->lokasi_galian}}
                                </td>
                                <td>
                                    @if($dt->status == 'D')
                                        Draft
                                    @elseif($dt->status == 'O')
                                        Open
                                    @else
                                        Validated
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
        </div><!-- /.box-body -->
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

    var TBL_KATEGORI = $('#table-data').DataTable({
        "columns": [
            {className: "text-right","orderable": false},
            {className: "text-left"},
            null,
            null,
            null,   
            null,   
            null,   
            null,
            null,
            null,
            null,
            null,
            {className: "text-center","orderable": false},
            // {className: "text-center"}
        ],
        sort: false,
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
        location.href = 'delivery/order/edit/' + data_id ;        
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