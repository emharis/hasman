@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

<style>
    #table-data > tbody > tr{
        cursor:pointer;
    }
    .table tbody tr:hover td.balance-on-row {
        /*color: #523959;*/
        background-color:#3C8DBC!important;
    }
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Cashbook
    </h1>
</section>

<!-- Main content -->
<section class="content">
    {{-- <div class="row" >
        <div class="col-sm-3 col-md-3 col-lg-3 pull-right" >
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-dollar"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Balance</span>
                  <span class="info-box-number">1,410</span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->    
        </div>
    </div> --}}
    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border">
            <a class="btn btn-primary btn-sm" id="btn-add" href="cashbook/create" >Create</a>
            <button class="btn btn-danger btn-sm hide" id="btn-delete" href="#" >Delete</button>
            
            <div class="pull-right" >
                <table style="background-color: #ECF0F5;" >
                    <tr>
                        <td class="bg-green text-center" rowspan="2" style="width: 50px;" ><i class="ft-rupiah" ></i></td>
                        <td style="padding-left: 10px;padding-right: 5px;">
                            <label>TOTAL BALANCE</label>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"  style="padding-right: 5px;" >
                            {{number_format($balance,0,'.',',')}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="box-body">
            {{-- <div class="clearfix" ></div>
            <br/> --}}

            <?php $rownum=1; ?>
            <?php $balance=0; ?>
            <?php $debit=0;; ?>
            <?php $credit=0;; ?>
            <table class="table table-bordered table-condensed table-hover" id="table-data" >
                <thead>
                    <tr>
                        <th style="width:25px;">
                            <input type="checkbox" name="ck_all" style="margin-left:15px;padding:0;" >
                        </th>
                        {{-- <th style="width:25px;">No</th> --}}
                        <th class="col-sm-1 col-md-1 col-lg-1" >Ref#</th>
                        <th  >Tanggal</th>
                        <th>Desc</th>
                        <th  >Debit</th>
                        <th  >Credit</th>
                        <th style="background-color: #ECF0F5;" >Balance</th>
                        <th style="width:50px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rownum=1;?>
                    @foreach($data as $dt )
                        @if($dt->in_out == 'I')
                            <?php $balance += $dt->jumlah; ?>
                            <?php $debit += $dt->jumlah; ?>
                        @else 
                            <?php $balance -= $dt->jumlah; ?>
                            <?php $credit += $dt->jumlah; ?>
                        @endif
                    <tr data-id="{{$dt->id}}" >
                        <td class="text-center" >
                            <input type="checkbox" class="ck_row">
                        </td>
                        {{-- <td>
                            {{$rownum++}}
                        </td> --}}
                        <td>
                            {{$dt->cash_number}}
                        </td>
                        <td>
                            {{$dt->tanggal_formatted}}
                        </td>
                        <td>
                            {{$dt->desc}}
                        </td>
                        <td class="uang text-right" >
                            @if($dt->in_out == 'I')
                                {{number_format($dt->jumlah,0,'.',',' )}}
                            @else
                            -
                            @endif
                        </td>
                        <td class="uang text-right" >
                            @if($dt->in_out == 'O')
                                {{number_format($dt->jumlah,0,'.',',' )}}
                            @else 
                            -
                            @endif
                        </td>
                        <td style="background-color: #ECF0F5;" class="uang text-right balance-on-row" >
                            {{number_format($balance,0,'.',',' )}}
                        </td>
                        <td class="text-center" >
                            <a class="btn btn-success btn-xs" href="cashbook/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                            {{-- <a class="btn btn-danger btn-xs btn-delete-cashbook" href="cashbook/delete/{{$dt->id}}" ><i class="fa fa-trash-o" ></i></a> --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot  >
                    <td style="border-top: darkgrey solid 2px;" colspan="4" ></td>
                    <td style="border-top: darkgrey solid 2px;" class="text-right" >
                        <label>{{number_format($debit,0,'.',',')}}</label>
                    </td>
                    <td style="border-top: darkgrey solid 2px;" class="text-right" >
                        <label>{{number_format($credit,0,'.',',')}}</label>
                    </td>
                    <td style="border-top: darkgrey solid 2px; background-color: #ECF0F5;" class="text-right" >
                        <label>{{number_format($balance,0,'.',',')}}</label>
                    </td>
                    <td style="border-top: darkgrey solid 2px;" ></td>
                </tfoot>
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

    var TBL_KATEGORI = $('#table-data').DataTable({
        sort:false,
        "initComplete": function (oSettings) {

            var oTable = this;
            var totalRows = oTable.fnGetData().length;


            oTable.fnPageChange('last');
            page = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength);
        }
    });


    // check all
    $('input[name=ck_all]').change(function(){
        $('.ck_row').prop('checked',$(this).prop('checked'));
        $('.ck_row:first').trigger('change');
    });

    $(document).on('change','.ck_row', function(){
        // alert('ok');
        if($('.ck_row:checked').length > 0){
            $('#btn-delete').removeClass('hide');
            $('#btn-delete').show();
        }else{
            $('#btn-delete').hide();
        }
    });

    // DELETE DATA CASHBOOK
    $('#btn-delete').click(function(){
        if(confirm('Anda akan menghapus data ini?')){
            var timeout = $('.ck_row.checked').length * 100;
            var rowcount = $('.ck_row:checked').length;
            var idx = 1;

            $('.ck_row:checked').each(function(){
                var row = $(this).parent().parent();
                var cash_id = row.data('id');
                var url="cashbook/delete/"+cash_id;
                // alert(url);
                $.get(url,null,function(){
                    row.fadeOut(100,function(){
                        row.remove();

                        // idx++;
                        // idx++;
                        // alert(idx);
                        // alert(rowcount);
                        if( idx++ >= rowcount){
                            pageReload();
                        }
                    });
                });

                
                // window.setTimeout( pageReload(),  timeout * 1000 );
            });
            
        }
    });

    function pageReload(){
        location.reload();
    }

    // -----------------------------------------------------
    // SET AUTO NUMERIC
    // =====================================================
    
    // $('.uang').each(function(){
    //     var jumlah = $(this).text();
    //     alert(jumlah);
    //     $(this).text(0);
    //     $(this).autoNumeric('init',{
    //         vMin:'-99999999999',
    //         vMax:'99999999999'
    //     });
    //     $(this).autoNumeric('set',jumlah);
    // });
    // END OF AUTONUMERIC
})(jQuery);
</script>
@append