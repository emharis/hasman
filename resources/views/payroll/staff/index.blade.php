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
        Staff Payroll
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-body">
            <a class="btn btn-primary" id="btn-add" href="payroll/staff/create" ><i class="fa fa-plus-circle" ></i> Tambah Baru</a>
            <button class="btn btn-danger hide" id="btn-delete" href="#" ><i class="fa fa-trash-o" ></i> Delete</button>
            <div class="clearfix" ></div>
            <br/>

            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped table-hover" id="table-payroll" >
                <thead>
                    <tr>
                        <th style="width:25px;" class="text-center"  >
                            <input type="checkbox" name="ck_all" style="margin:0;padding:0;margin-left: 15px;"  >
                        </th>
                        {{-- <th style="width:25px;">No</th> --}}
                        <th class="col-sm-2 col-md-2 col-lg-2" >Ref #</th>
                        <th>Nama</th>
                        <th>Payment Date</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dt)
                    <tr data-rowid="{{$rownum}}" data-id="{{$dt->id}}">
                        <td class="text-center" >
                            @if($dt->status != 'P')
                                <input type="checkbox" class="ck_row" >
                            @endif
                        </td>
                        {{-- <td class="row-to-edit" >{{$rownum++}}</td> --}}
                        <td class="row-to-edit" >
                            {{$dt->payroll_number}}
                        </td>
                        <td class="row-to-edit" >
                            {{'['.$dt->kode_karyawan . '] ' . $dt->nama_karyawan}}
                        </td>
                        <td>{{$dt->payment_date_formatted}}</td>
                        <td>{{$dt->start_date_formatted}}</td>
                        <td>{{$dt->end_date_formatted}}</td>
                        <td class="text-center" >
                            @if($dt->status == 'O')
                                <label class="label label-warning" >OPEN</label>
                            @else
                                <label class="label label-success" >PAID</label>
                            @endif
                        </td>
                        <td class="text-right uang" >{{$dt->saldo}}</td>
                        <td class="text-center" >
                            <a class="btn btn-primary btn-xs" href="payroll/staff/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
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
    // format table
    var TBL_KATEGORI = $('#table-payroll').DataTable({
        sort:false
    });

    // format auto numeric uang
  $('.uang').autoNumeric('init',{
      vMin:'0',
      vMax:'9999999999'
  });

  $('.uang').each(function(){
    $(this).autoNumeric('set',$(this).autoNumeric('get'));
  });


  // CHECK ALL
  $('input[name=ck_all]').change(function(){
    $('.ck_row').prop('checked',$(this).prop('checked'));
    $('.ck_row').trigger('change');
  });
  $('.ck_row').change(function(){
    if($('.ck_row:checked').length > 0){
        $('#btn-delete').removeClass('hide');
        $('#btn-delete').show();
    }else{
        $('#btn-delete').hide();
    };

    if($('.ck_row:checked').length < $('.ck_row').length){
        $('input[name=ck_all]').prop('checked',false);
    }

  });

  // delete payroll 
  $('#btn-delete').click(function(){
    // if(confirm('Anda akan menghapus data ini?')){

    // }

    $('.ck_row:checked').each(function(){
        var row = $(this).parent().parent();
        var payroll_id = row.data('id');
        $.get('payroll/staff/cancel-payroll/'+payroll_id,null,function(){
            row.fadeOut(250);
            row.remove();
        });
    });
  });

})(jQuery);
</script>
@append
