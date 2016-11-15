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
        Data Armada
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border">
            <a class="btn btn-primary" id="btn-add" href="master/armada/create" ><i class="fa fa-plus-circle" ></i> Tambah Baru</a>
            <a class="btn btn-danger hide" id="btn-delete" href="#" ><i class="fa fa-trash-o" ></i> Delete</a>

            <div class="pull-right" >
                <table style="background-color: #ECF0F5;" >
                    <tr>
                        <td class="bg-green text-center" rowspan="2" style="width: 50px;" ><i class="fa fa-tags" ></i></td>
                        <td style="padding-left: 10px;padding-right: 5px;">
                            JUMLAH DATA
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"  style="padding-right: 5px;" >
                            <label class="uang">{{count($data)}}</label>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="box-body">
            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped table-hover" id="table-data" >
                <thead>
                    <tr>
                        <th style="width:25px;" class="text-center" >
                            <input type="checkbox" name="ck_all" style="margin-left:15px;padding:0;" >
                        </th>
                        {{-- <th style="width:25px;">No</th> --}}
                        <th class="col-lg-1 col-md-1 col-sm-1">Kode</th>
                        <th>Nama</th>
                        <th>Nopol</th>
                        <th>Driver</th>
                        <th class="col-lg-1 col-md-1 col-sm-1"></th>
                </thead>
                <tbody>
                    @foreach($data as $dt)
                    <tr data-rowid="{{$rownum}}" data-id="{{$dt->id}}">
                        <td class="text-center" >
                            @if($dt->can_delete == 'true')
                                <input type="checkbox" class="ck_row" >
                            @endif
                        </td>
                        {{-- <td class="row-to-edit" >{{$rownum++}}</td> --}}
                        <td class="row-to-edit" >
                            {{$dt->kode}}
                        </td>
                        <td class="row-to-edit" >
                            {{$dt->nama}}
                        </td>
                        <td class="row-to-edit" >
                            {{$dt->nopol}}
                        </td>
                        <td class="row-to-edit" >
                            {{$dt->karyawan}}
                        </td>
                        <td class="text-center" >
                            <a class="btn btn-primary btn-xs" href="master/armada/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
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

<script type="text/javascript">
(function ($) {
    // alert('ter');

    var TBL_KATEGORI = $('#table-data').DataTable({
        sort:false
        
    });
    // TBL_KATEGORI.fnPageChange('last');
    // TBL_KATEGORI.fnPageChange(2,true);
    // alert('complete');

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
    $(document).on('click','.row-to-edit',function(){        
           var btn = $(this).parent().children('td:last').children('a');       
           location.href = btn.attr('href');
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

            var deleteForm = $('<form>').attr('method','POST').attr('action','master/armada/delete');
            deleteForm.append($('<input>').attr('type','hidden').attr('name','dataid').attr('value',JSON.stringify(dataid)));
            deleteForm.submit();
        }

        e.preventDefault();
        return false;
    });

    

})(jQuery);
</script>
@append