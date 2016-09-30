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
        <a href="master/supplier" >Data Supplier</a> 
        <i class="fa fa-angle-double-right" ></i> Create
    </h1>
</section>

<!-- Main content -->
<section class="content">
  {{-- <form method="POST" action="master/supplier/insert" > --}}
    <div class="box box-solid" >
      <div class="box-body" >
        <table class="table table-bordered table-condensed" >
            <tbody>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Nama</label>
                    </td>
                    <td>
                        <input type="text" name="nama" class="form-control" required autofocus autocomplete="off" >
                    </td>
                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Kode</label>
                    </td>
                    <td>
                        <input type="text" name="kode" class="form-control " required autocomplete="off" >
                    </td>
                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Alamat</label>
                    </td>
                    <td>
                        <input type="text" name="alamat" class="form-control " >
                    </td>
                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Provinsi</label>
                    </td>
                    <td>
                        <input type="text" name="provinsi" class="form-control " data-id="" >
                    </td>
                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Kota/Kabupaten</label>
                    </td>
                    <td>
                        <input type="text" name="kabupaten" class="form-control " data-id="" >
                    </td>
                    
                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Kecamatan</label>
                    </td>
                    <td>
                        <input type="text" name="kecamatan" class="form-control " data-id="" >
                    </td>
                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Desa</label>
                    </td>
                    <td>
                        <input type="text" name="desa" class="form-control " data-id="" >
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Telp</label>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="telp">
                    </td>
                </tr><tr>
                    <td>
                       
                    </td>
                    <td>
                        <input type="text" class="form-control" name="telp2">
                    </td>
                </tr><tr>
                    <td>
                        
                    </td>
                    <td>
                        <input type="text" class="form-control" name="telp3">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" class="btn btn-primary" id="btn-save" >Save</button>
                        <a class="btn btn-danger" href="master/supplier" >Cancel</a>
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
   
    // SAVE 
    $('#btn-save').click(function(){
        // cek kelengkapan data
        var nama = $('input[name=nama]').val();
        var kode = $('input[name=kode]').val();
        var nopol = $('input[name=nopol]').val();
        var alamat = $('input[name=alamat]').val();
        var provinsi = $('input[name=provinsi]').val();
        var provinsi_id = $('input[name=provinsi]').data('id');
        var kabupaten = $('input[name=kabupaten]').val();
        var kabupaten_id = $('input[name=kabupaten]').data('id');
        var kecamatan = $('input[name=kecamatan]').val();
        var kecamatan_id = $('input[name=kecamatan]').data('id');
        var desa = $('input[name=desa]').val();
        var desa_id = $('input[name=desa]').data('id');
        var telp = $('input[name=telp]').val();
        var telp2 = $('input[name=telp2]').val();
        var telp3 = $('input[name=telp3]').val();

        if(nama != "" && kode != "" && nopol != "" ){
            var formdata = $('<form>').attr('method','POST').attr('action','master/supplier/insert');
            formdata.append($('<input>').attr('type','hidden').attr('name','nama').val(nama));
            formdata.append($('<input>').attr('type','hidden').attr('name','kode').val(kode));
            formdata.append($('<input>').attr('type','hidden').attr('name','alamat').val(alamat));
            formdata.append($('<input>').attr('type','hidden').attr('name','provinsi').val(provinsi));
            formdata.append($('<input>').attr('type','hidden').attr('name','provinsi_id').val(provinsi_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','kabupaten').val(kabupaten));
            formdata.append($('<input>').attr('type','hidden').attr('name','kabupaten_id').val(kabupaten_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','kecamatan').val(kecamatan));
            formdata.append($('<input>').attr('type','hidden').attr('name','kecamatan_id').val(kecamatan_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','desa').val(desa));
            formdata.append($('<input>').attr('type','hidden').attr('name','desa_id').val(desa_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','telp').val(telp));
            formdata.append($('<input>').attr('type','hidden').attr('name','telp2').val(telp2));
            formdata.append($('<input>').attr('type','hidden').attr('name','telp3').val(telp3));
            formdata.submit();
        }else{
            alert('Lengkapi data yang kosong.');
        }
    });
    // END OF SAVE

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
            $('input[name=provinsi]').data('id',suggestions.data);
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
                        return $('input[name=provinsi]').data('id');
                    },
                    
                },
        onSelect:function(suggestions){
            // // set data supplier
            $('input[name=kabupaten]').data('id',suggestions.data);
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
                        return $('input[name=kabupaten]').data('id');
                    },
                    
                },
        onSelect:function(suggestions){
            // // set data supplier
            $('input[name=kecamatan]').data('id',suggestions.data);
        }

    });
    // END OF SET AUTOCOMPLETE KECAMATAN

    // SET AUTOCOMPLETE KECAMATAN
    $('input[name=desa]').autocomplete({
        serviceUrl: 'api/get-auto-complete-desa',
        params: {  
                    'nama': function() {
                        return $('input[name=desa]').val();
                    },
                    'kecamatan_id': function() {
                        return $('input[name=kecamatan]').data('id');
                    },
                    
                },
        onSelect:function(suggestions){
            // // set data supplier
            $('input[name=desa]').data('id',suggestions.data);
            // alert($('input[name=desa]').data('id'));

        }

    });
    // END OF SET AUTOCOMPLETE KECAMATAN

// alert('pret');
})(jQuery);
</script>
@append