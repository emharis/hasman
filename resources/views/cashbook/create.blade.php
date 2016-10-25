@extends('layouts.master')

@section('styles')
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>

<style>
    .col-top-item{
        cursor:pointer;
        border: thin solid #CCCCCC;
        
    }
    .table-top-item > tbody > tr > td{
        border-top-color: #CCCCCC;
    }
</style>
@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="cashbook" >Cashbook</a> 
        <i class="fa fa-angle-double-right" ></i> Create
    </h1>
</section>

<!-- Main content -->
<section class="content">
  <form method="POST" action="cashbook/insert" >
    <div class="box box-solid" >
        <div class="box-header with-border" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >New</h3></label>
            
            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Posted</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

            <a class="btn btn-arrow-right pull-right disabled bg-blue" >Draft</a>
        </div>
      <div class="box-body" >
        <table class="table table-condensed" >
            <tbody>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Jenis Kas</label>
                    </td>
                    <td class="col-lg-4 col-md-4 col-sm-4" >
                        <select name="jenis_kas" class="form-control" required >
                            <option value="I" >Debit</option>
                            <option value="O" >Credit</option>
                        </select>
                    </td>
                    <td class="col-lg-2 col-md-2 col-sm-2" ><label>Tanggal</label></td>
                    <td class="col-lg-4 col-md-4 col-sm-4" >
                        <input type="text" class="form-control input-date" name="tanggal" value="{{date('d-m-Y')}}" required autocomplete="off">
                    </td>
                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Keterangan</label>
                    </td>
                    <td>
                        <input type="text" name="keterangan" class="form-control" required autocomplete="off" >
                    </td>
                    <td><label>Jumlah</label></td>
                    <td>
                        <input type="text" name="jumlah" class="form-control uang text-right"> 
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" class="btn btn-primary" id="btn-save" >Save</button>
                        <a class="btn btn-danger" href="cashbook" >Cancel</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
  </form>
</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

    // SET DATEPICKER
    $('.input-date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    // END OF SET DATEPICKER

    // -----------------------------------------------------
    // SET AUTO NUMERIC
    // =====================================================
    $('.uang').autoNumeric('init',{
        vMin:'0',
        vMax:'9999999999'
    });
    // END OF AUTONUMERIC
   
    // SAVE LOKASI GALIAN
   
    // $('#btn-save').click(function(){
    //     // cek kelengkapan data
    //     var nama = $('input[name=nama]').val();
    //     var kode = $('input[name=kode]').val();
        

    //     if(nama != "" ){
    //         var formdata = $('<form>').attr('method','POST').attr('action','master/alat/insert');
    //         formdata.append($('<input>').attr('type','hidden').attr('name','kode').val(kode));
    //         formdata.append($('<input>').attr('type','hidden').attr('name','nama').val(nama));
    //         formdata.submit();
    //     }else{
    //         alert('Lengkapi data yang kosong.');
    //     }
    // });

    // END OF LOKASI GALIAN

// alert('pret');
})(jQuery);
</script>
@append