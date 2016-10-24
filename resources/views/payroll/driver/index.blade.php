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
        Driver Payroll
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid ">
        <div class="box-body">
            <a class="btn btn-primary btn-sm" id="btn-add" href="payroll/driver/create" >Create</a>
            <button class="btn btn-danger btn-sm hide" id="btn-delete" href="#" >Delete</button>
            <div class="clearfix" ></div>
            <br/>

            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped table-hover " id="table-data" >
                <thead>
                    <tr>
                        <th style="width:25px;" class="text-center" >
                            <input type="checkbox" name="ck_all"  >
                        </th>
                        <th style="width:25px;">No</th>
                        <th class="col-sm-2 col-md-2 col-lg-2" >Ref #</th>
                        <th>Nama</th>
                        <th>Payment Date</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dt)
                    <tr data-rowid="{{$rownum}}" data-id="{{$dt->id}}">
                        <td class="text-center" >
                          @if($dt->status != 'P')
                            <input type="checkbox" class="ck-row"  />
                          @endif
                        </td>
                        <td>{{$rownum++}}</td>
                        <td>{{$dt->payroll_number}}</td>
                        <td>
                          {{'['.$dt->kode_karyawan . '] ' . $dt->nama_karyawan}}
                        </td>
                        <td>
                          {{$dt->payment_date_formatted}}
                        </td>
                        <td>{{$dt->start_date_formatted}}</td>
                        <td>{{$dt->end_date_formatted}}</td>
                        <td class="uang text-right" >{{$dt->saldo}}</td>
                        <td class="text-center" >
                          @if($dt->status == 'O')
                            <label class="label label-warning" >Open</label>
                          @else
                            <label class="label label-success" >Paid</label>
                          @endif
                        </td>
                        <td class="text-center" >
                          <a class="btn btn-primary btn-xs" href="payroll/driver/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
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
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>

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
    //         null,
    //         {className: "text-center"},
    //         // {className: "text-center"}
    //     ],
    //     order: [[ 1, 'asc' ]],
    // });


    // format auto numeric uang
    $('.uang').autoNumeric('init',{
        vMin:'0',
        vMax:'9999999999'
    });
    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'))
    });

    // delete data 
    $('input[name=ck_all]').change(function(){
        // check all ck-row
        $('.ck-row').prop('checked',$(this).prop('checked'));
        $('.ck-row').trigger('change');
    });

    // tampilkan tombol delete ketika adad row yang di centang
    $('.ck-row').change(function(){
        if($('.ck-row:checked').length > 0){
            $('#btn-delete').removeClass('hide');
            $('#btn-delete').show();
        }else{
            $('#btn-delete').hide();
        }
    });

    // delete data payroll
    $('#btn-delete').click(function(){
        if(confirm('Anda akan menghapus data ini?')){
            $('.ck-row:checked').each(function(){
                // alert('oke');
                var data_row = $(this).parent().parent();
                var data_id = data_row.data('id');
                // alert(data_id);
                var url = "payroll/driver/delete/" + data_id;
                // location.href = url;
                $.get(url,null,function(){
                    data_row.fadeOut(250);
                });
            });

            $('input[name=ck_all]').prop('checked',false);
        }

        
    });


})(jQuery);
</script>
@append
