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
        <a href="master/karyawan" >Data Karyawan</a>
        <i class="fa fa-angle-double-right" ></i>
        Edit
    </h1>
</section>

<!-- Main content -->
<section class="content">
  {{-- <form method="POST" action="master/karyawan/insert" > --}}
    <div class="box box-solid" >
      <div class="box-body" >
        <table class="table table-bordered table-condensed" >
             <tbody>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Nama</label>
                    </td>
                    <td>
                        <input type="text" name="nama" class="form-control"  autofocus autocomplete="off" required value="{{$data->nama}}" >
                        <input type="hidden" name="id" class="form-control" value="{{$data->id}}">
                    </td>
                    <td rowspan="11" class="col-lg-2 col-md-2 col-sm-2" >
                        <img id="foto-karyawan" class="col-lg-12 col-sm-12 col-md-12" src="foto_karyawan/{{$data->foto}}" >
                    </td>
                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Panggilan</label>
                    </td>
                    <td>
                        <input type="text" name="panggilan" class="form-control"  autocomplete="off" required value="{{$data->panggilan}}" >
                    </td>

                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Kode</label>
                    </td>
                    <td>
                        <input type="text" name="kode" class="form-control"  autocomplete="off" required value="{{$data->kode}}" >
                    </td>

                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Jabatan</label>
                    </td>
                    <td>
                        {!! Form::select('jabatan',$selectJabatan,$data->jabatan_id,['class'=>'form-control']) !!}
                    </td>

                </tr>
                <tr>
                  <td>
                    <label>Gaji/Harian</label>
                  </td>
                  <td>
                    <input name="gaji_pokok" class="form-control input-uang" value="{{$data->gaji_pokok}}" />
                  </td>
                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>KTP</label>
                    </td>
                    <td>
                        <input type="text" name="ktp" class="form-control"  autocomplete="off" value="{{$data->ktp}}" >
                    </td>

                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Tempat/Tanggal Lahir</label>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" name="tempat_lahir" class="form-control" value="{{$data->tempat_lahir}}">

                             <div class="input-group-btn" style="width:30%;">
                              <input type="text" name="tgl_lahir" class="input-date form-control" value="{{$data->tgl_lahir_formatted != '00-00-0000' ? $data->tgl_lahir_formatted : ''}}">
                            </div>
                        </div>
                    </td>

                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Alamat</label>
                    </td>
                    <td>
                        <input type="text" name="alamat" class="form-control " value="{{$data->alamat}}" >
                    </td>
                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Provinsi</label>
                    </td>
                    <td>
                        <input type="text" name="provinsi" class="form-control " data-id="{{$data->provinsi_id}}" value="{{$data->provinsi}}" >
                    </td>
                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Kota/Kabupaten</label>
                    </td>
                    <td>
                        <input type="text" name="kabupaten" class="form-control " data-id="{{$data->kabupaten_id}}" value="{{$data->kabupaten}}" >
                    </td>

                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Kecamatan</label>
                    </td>
                    <td>
                        <input type="text" name="kecamatan" class="form-control " data-id="{{$data->kecamatan_id}}" value="{{$data->kecamatan}}" >
                    </td>
                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Desa</label>
                    </td>
                    <td>
                        <input type="text" name="desa" class="form-control " data-id="{{$data->desa_id}}" value="{{$data->desa}}" >
                    </td>
                </tr>
                <tr>
                    <td class="col-lg-2 col-md-2 col-sm-2" >
                        <label>Telp</label>
                    </td>
                    <td>
                        <input type="text" name="telp" class="form-control " data-id="" value="{{$data->telp}}" >
                    </td>
                    <td>
                        <input type="file" name="foto" accept="image/*">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2" >
                        <button type="submit" class="btn btn-primary" id="btn-save" >Save</button>
                        <a class="btn btn-danger" href="master/karyawan" >Cancel</a>
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
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<script type="text/javascript">
(function ($) {

    // SET DATEPICKER
    $('.input-date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    // END OF SET DATEPICKER

    // SET AUTONUMERIC GAJI POKOK
    $('.input-uang').autoNumeric('init',{
        vMin:'0',
        vMax:'9999999999'
    });

     // SAVE

    $('#btn-save').click(function(){
       // cek kelengkapan data
        var id = $('input[name=id]').val();
        var nama = $('input[name=nama]').val();
        var panggilan = $('input[name=panggilan]').val();
        var kode = $('input[name=kode]').val();
        var ktp = $('input[name=ktp]').val();
        var alamat = $('input[name=alamat]').val();
        var provinsi = $('input[name=provinsi]').val();
        var provinsi_id = $('input[name=provinsi]').data('id');
        var kabupaten = $('input[name=kabupaten]').val();
        var kabupaten_id = $('input[name=kabupaten]').data('id');
        var kecamatan = $('input[name=kecamatan]').val();
        var kecamatan_id = $('input[name=kecamatan]').data('id');
        var desa = $('input[name=desa]').val();
        var desa_id = $('input[name=desa]').data('id');
        var jabatan = $('select[name=jabatan]').val();
        var telp = $('input[name=telp]').val();
        var tgl_lahir = $('input[name=tgl_lahir]').val();
        var tempat_lahir = $('input[name=tempat_lahir]').val();
        var gaji_pokok = $('input[name=gaji_pokok]').autoNumeric('get');

        if(nama != "" && kode != ""){

        // alert('masuk kondisi');
            var formdata = $('<form>').attr('method','POST').attr('action','master/karyawan/update').attr('enctype','multipart/form-data');
            formdata.append($('<input>').attr('type','hidden').attr('name','id').val(id));
            formdata.append($('<input>').attr('type','hidden').attr('name','nama').val(nama));
            formdata.append($('<input>').attr('type','hidden').attr('name','panggilan').val(panggilan));
            formdata.append($('<input>').attr('type','hidden').attr('name','kode').val(kode));
            formdata.append($('<input>').attr('type','hidden').attr('name','ktp').val(ktp));
            formdata.append($('<input>').attr('type','hidden').attr('name','alamat').val(alamat));
            formdata.append($('<input>').attr('type','hidden').attr('name','provinsi').val(provinsi));
            formdata.append($('<input>').attr('type','hidden').attr('name','provinsi_id').val(provinsi_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','kabupaten').val(kabupaten));
            formdata.append($('<input>').attr('type','hidden').attr('name','kabupaten_id').val(kabupaten_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','kecamatan').val(kecamatan));
            formdata.append($('<input>').attr('type','hidden').attr('name','kecamatan_id').val(kecamatan_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','desa').val(desa));
            formdata.append($('<input>').attr('type','hidden').attr('name','desa_id').val(desa_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','jabatan').val(jabatan));
            formdata.append($('<input>').attr('type','hidden').attr('name','telp').val(telp));
            formdata.append($('<input>').attr('type','hidden').attr('name','tgl_lahir').val(tgl_lahir));
            formdata.append($('<input>').attr('type','hidden').attr('name','tempat_lahir').val(tempat_lahir));
            formdata.append($('<input>').attr('type','hidden').attr('name','gaji_pokok').val(gaji_pokok));
            formdata.append($('input[name=foto]'));
            formdata.submit();
        }else{
            alert('Lengkapi data yang kosong.');
        }
    });

    // END OF SAVE

    // alert('ok');
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

    // TAMPILKAN PREVIEW FOTO
     $(document).on('change','input[name=foto]',function(){
        var imgPrev = $('#foto-karyawan');

        var reader = new FileReader();
        reader.onload = function (e) {
            imgPrev.attr('src',e.target.result);
         }

        reader.readAsDataURL($(this)[0].files[0]);
    });
    // END OF TAMPIKLKAN PREVIEW FOTO

// alert('pret');
})(jQuery);
</script>
@append
