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
        Harian Alat Berat
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-body">
            <a class="btn btn-primary btn-sm" id="btn-add" href="dailyhd/create" >Create</a>
            <button class="btn btn-danger btn-sm hide" id="btn-delete" >Delete</button>
            <div class="clearfix" ></div>
            <br/>

            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped table-hover" id="table-data" >
                <thead>
                    <tr>
                        <th style="width:25px;">
                            <input type="checkbox" name="ck_all" style="margin-left:15px;padding:0;" >
                        </th>
                        {{-- <th style="width:25px;">No</th> --}}
                        <th  >Ref#</th>
                        <th  >Tanggal</th>
                        <th>Alat</th>
                        <th>Lokasi</th>
                       {{--  <th>Jam Kerja</th>
                        <th>Solar</th>
                        <th>Oli</th> --}}
                        <th>Pengawas</th>
                        <th>Operator</th>
                        <th>Status</th>
                        <th style="width:25px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dt)
                    <tr data-rowid="{{$rownum}}" data-id="{{$dt->id}}">
                        <td class="text-center" >
                            {{-- @if($dt->ref == 0) --}}
                                <input type="checkbox" class="ck_row" >
                            {{-- @endif --}}
                        </td>
                        {{-- <td class="row-to-edit" >{{$rownum++}}</td> --}}
                        <td class="row-to-edit" >
                            {{$dt->ref}}
                        </td>
                        <td class="row-to-edit" >
                            {{$dt->tanggal_formatted}}
                        </td>
                        <td>
                            {{'['.$dt->kode_alat . '] ' .$dt->alat}}
                        </td>
                        <td>
                            {{'['.$dt->kode_lokasi . '] ' .$dt->lokasi}}
                        </td>
                        {{-- <td class="text-right" >
                            {{$dt->jam_kerja}}
                        </td>
                        <td class="text-right" >
                            {{$dt->solar}}
                        </td>
                        <td class="text-right" >
                            {{$dt->oli}}
                        </td> --}}
                        <td>
                            {{'['.$dt->kode_pengawas . '] ' .$dt->nama_pengawas}}
                        </td>
                        <td>
                            {{'['.$dt->kode_operator . '] ' .$dt->nama_operator}}
                        </td>
                        <td>
                            @if($dt->status == 'O')
                                Open
                            @else
                                Validated
                            @endif
                        </td>
                        <td class="text-center">
                            <a class="btn btn-primary btn-xs" href="dailyhd/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
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
    $(document).on('click','.row-to-edit',function(){        
        var row = $(this).parent();        
        var data_id = row.data('id');            
        location.href = 'dailyhd/edit/' + data_id ;        
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
                var row = $(this).parent().parent();
                row.fadeOut(150,function(){
                    TBL_DATA.row( row ).remove().draw();      
                });
                
            });

            // remove check all & btn delete
            $('input[name=ck_all]').prop('checked',false);
            $('input[name=ck_all]').trigger('change');

            // var deleteForm = $('<form>').attr('method','POST').attr('action','dailyhd/delete');
            // deleteForm.append($('<input>').attr('type','hidden').attr('name','dataid').attr('value',JSON.stringify(dataid)));
            // deleteForm.submit();
            
            $.post('dailyhd/delete',{
                'dataid' : JSON.stringify(dataid)
            },function(){
                // reorder row number
                // var rownum=1;
                // TBL_DATA.rows().iterator( 'row', function ( context, index ) {
                //     this.cell(index,1).data(rownum++);
                //     // this.invalidate();
                // } );                
                // TBL_DATA.draw();
            });
        }

        e.preventDefault();
        return false;
    });

    

})(jQuery);
</script>
@append