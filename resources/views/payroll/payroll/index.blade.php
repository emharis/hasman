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
        Payroll Option
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >Payroll Option</h3></label>
        </div>
        <div class="box-body">
            <form name="form-option" method="POST" action="payroll/payroll/show-payroll-table" >
                
                <table class="table no-border" >
                    <tbody>
                        <tr>
                            <td class="col-sm-2" >
                                <label>Bulan</label>
                            </td>
                            <td class="col-sm-4" >
                                <input type="text" name="bulan" class="form-control" required>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <label>Periode Pembayaran</label>
                            </td>
                            <td>
                                <select name="payday" class="form-control"></select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Jabatan</label>
                            </td>
                            <td>
                                <select name="jabatan" class="form-control" required>
                                    <option value="ST" >Staff</option>
                                    <option value="DV" >Driver</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button type="submit" class="btn btn-primary" >Submit</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </form>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

    // var TBL_KATEGORI = $('#table-data').DataTable({
    //     "columns": [
    //         {className: "text-center","orderable": false},
    //         {className: "text-right"},
    //         null,
    //         null,
    //         {className: "text-center"},
    //         // {className: "text-center"}
    //     ],
    //     order: [[ 1, 'asc' ]],
    // });

    $('select').val([]);


    // FORMAT TANGGAL
    $('input[name=bulan]').datepicker({
        format: "mm-yyyy",
        startView: "months", 
        minViewMode: "months",
        autoclose : true
    }).on('changeDate', function(e) {
        // get data minggu/pay day
        $.post('payroll/payroll/get-pay-day',{
            'bulan' : $('input[name=bulan]').val()
        },function(res){
            var payday = JSON.parse(res);
            // alert(res);
            $('select[name=payday]').empty();
            $.each(payday,function(){
                $('select[name=payday]').append($('<option>', {value:this.tanggal, text:this.tanggal_full}));
            });
            
        });

    });

    // submit
    $('form[name=form-option]').submit(function(){
        var bulan = $('input[name=bulan]').val();
        var select_tanggal = $('select[name=payday]').val();
        var tanggal = select_tanggal + "-" + bulan;
        var kode_jabatan = $('select[name=jabatan]').val();
        location.href = 'payroll/payroll/show-payroll-table/' + tanggal + '/' + kode_jabatan; 

        return false;
    });

})(jQuery);
</script>
@append
