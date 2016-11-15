@extends('layouts.master')

@section('styles')
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
        <a href="master/pekerjaan" >Data Pekerjaan</a> 
        <i class="fa fa-angle-double-right" ></i> 
        New
    </h1>
</section>

<!-- Main content -->   
<section class="content">
  {{-- <form method="POST" action="master/pekerjaan/insert" > --}}
    <div class="box box-solid" >
      <div class="box-body" >
        <table class="table table-bordered table-condensed" >
             <tbody>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Customer</label>
                    </td>
                    <td>
                        <input type="text" name="customer" class="form-control" required autofocus autocomplete="off" value="" >
                        <input type="hidden" name="customer_id" class="form-control" required >
                    </td>
                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Nama</label>
                    </td>
                    <td>
                        <input type="text" name="nama" class="form-control" required  autocomplete="off" value="" >
                    </td>
                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Tahun Pekerjaan</label>
                    </td>
                    <td>
                        <input type="text" name="tahun" class="form-control" required autocomplete="off" value="" >
                    </td>
                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Alamat</label>
                    </td>
                    <td>
                        <input type="text" name="alamat" class="form-control" required autocomplete="off" value="" >
                    </td>
                </tr>
                <tr>
                    <td  >
                        <label>Provinsi</label>
                    </td>
                    <td>
                        <input type="text" name="provinsi" class="form-control" required autocomplete="off" value="" >
                        <input type="hidden" name="provinsi_id" class="form-control" required autocomplete="off" value="" >
                    </td>
                </tr>
                <tr>
                    <td  >
                        <label>Kabupaten</label>
                    </td>
                    <td>
                        <input type="text" name="kabupaten" class="form-control" required autocomplete="off" value="" >
                        <input type="hidden" name="kabupaten_id" class="form-control" required autocomplete="off" value="" >
                    </td>
                </tr>
                <tr>
                    <td  >
                        <label>Kecamatan</label>
                    </td>
                    <td>
                        <input type="text" name="kecamatan" class="form-control" required autocomplete="off" value="" >
                        <input type="hidden" name="kecamatan_id" class="form-control" required autocomplete="off" value="" >
                    </td>
                </tr>
                <tr>
                    <td  >
                        <label>Desa</label>
                    </td>
                    <td>
                        <input type="text" name="desa" class="form-control" required autocomplete="off" value="" >
                        <input type="hidden" name="desa_id" class="form-control" required autocomplete="off" value="" >
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" class="btn btn-primary" id="btn-save" >Save</button>
                        <a class="btn btn-danger" href="master/pekerjaan" >Cancel</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
  {{-- </form> --}}
</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script type="text/javascript">
(function ($) {
     // SAVE LOKASI GALIAN   
    $('#btn-save').click(function(){
        // cek kelengkapan data
        var customer_id = $('input[name=customer_id]').val();
        var nama = $('input[name=nama]').val();
        var alamat = $('input[name=alamat]').val();
        var desa_id = $('input[name=desa_id]').val();
        var tahun = $('input[name=tahun]').val();

        if(nama != ""  ){
            var formdata = $('<form>').attr('method','POST').attr('action','master/pekerjaan/insert');
            formdata.append($('<input>').attr('type','hidden').attr('name','customer_id').val(customer_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','nama').val(nama));
            formdata.append($('<input>').attr('type','hidden').attr('name','alamat').val(alamat));
            formdata.append($('<input>').attr('type','hidden').attr('name','desa_id').val(desa_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','tahun').val(tahun));
            formdata.submit();
        }else{
            alert('Lengkapi data yang kosong.');
        }
    });
    // END OF LOKASI GALIAN

    // SET AUTOCOMPLETE CUSTOMER
    $('input[name=customer]').autocomplete({
        serviceUrl: 'api/get-auto-complete-customer',
        params: {  
                    'nama': function() {
                        return $('input[name=customer]').val();
                    }
                },
        onSelect:function(suggestions){
            // // set data supplier
            $('input[name=customer_id]').val(suggestions.data);
        }

    });
    // END OF SET AUTOCOMPLETE CUSTOMER

    // SET AUTOCOMPLETE PROVINSI
    $('input[name=provinsi]').autocomplete({
        serviceUrl: 'api/get-auto-complete-provinsi',
        params: {  
                    'nama': function() {
                        return $('input[name=provinsi]').val();
                    }
                },
        onSelect:function(suggestions){
            // // set data supplier
            $('input[name=provinsi_id]').val(suggestions.data);
        }

    });
    // END OF SET AUTOCOMPLETE PROVINSI

    // SET AUTOCOMPLETE KABUPATEN
    $('input[name=kabupaten]').autocomplete({
        serviceUrl: 'api/get-auto-complete-kabupaten',
        params: {  
                    'nama': function() {
                        return $('input[name=kabupaten]').val();
                    },
                    'provinsi_id': function() {
                        return $('input[name=provinsi_id]').val();
                    },
                    
                },
        onSelect:function(suggestions){
            // // set data supplier
            $('input[name=kabupaten_id]').val(suggestions.data);
        }

    });
    // END OF SET AUTOCOMPLETE KABUPATEN

    // SET AUTOCOMPLETE KECAMATAN
    $('input[name=kecamatan]').autocomplete({
        serviceUrl: 'api/get-auto-complete-kecamatan',
        params: {  
                    'nama': function() {
                        return $('input[name=kecamatan]').val();
                    },
                    'kabupaten_id': function() {
                        return $('input[name=kabupaten_id]').val();
                    },
                    
                },
        onSelect:function(suggestions){
            // // set data supplier
            $('input[name=kecamatan_id]').val(suggestions.data);
        }

    });
    // END OF SET AUTOCOMPLETE KECAMATAN

    // SET AUTOCOMPLETE DESA
    $('input[name=desa]').autocomplete({
        serviceUrl: 'api/get-auto-complete-desa',
        params: {  
                    'nama': function() {
                        return $('input[name=desa]').val();
                    },
                    'kecamatan_id': function() {
                        return $('input[name=kecamatan_id]').val();
                    },
                    
                },
        onSelect:function(suggestions){
            // // set data supplier
            $('input[name=desa_id]').val(suggestions.data);
            // alert($('input[name=desa]').data('id'));

        }

    });
    // END OF SET AUTOCOMPLETE DESA

// alert('pret');
})(jQuery);
</script>
@append