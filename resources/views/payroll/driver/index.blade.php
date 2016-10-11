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
            <a class="btn btn-danger btn-sm hide" id="btn-delete" href="#" >Delete</a>
            <div class="clearfix" ></div>
            <br/>

            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped table-hover " id="table-data" >
                <thead>
                    <tr>
                        <th style="width:25px;">
                            <input type="checkbox" name="ck_all" style="margin-left:15px;padding:0;" >
                        </th>
                        <th style="width:25px;">No</th>
                        <th class="col-sm-2 col-md-2 col-lg-2" >Kode</th>
                        <th>Nama</th>
                        <th>Tanggal Awal</th>
                        <th>Tanggal Akhir</th>
                        <th>Total</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach($data as $dt) --}}
                    {{-- <tr data-rowid="{{$rownum}}" data-id="{{$dt->id}}">
                        <td>
                            <input type="checkbox" class="ck_row" >
                        </td>
                        <td class="row-to-edit" >{{$rownum++}}</td>
                        <td class="row-to-edit" >
                            {{$dt->kode}}
                        </td>
                        <td class="row-to-edit" >
                            {{$dt->nama}}
                        </td>
                        <td >
                            <a class="btn btn-primary btn-xs" href="master/alat/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                        </td>
                    </tr> --}}
                    {{-- @endforeach --}}
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
})(jQuery);
</script>
@append
