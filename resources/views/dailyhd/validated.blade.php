@extends('layouts.master')

@section('styles')
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<link type="text/css" href="plugins/timepicker/timepicker.less" />
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
        <a href="dailyhd" >Harian Alat Berat</a> 
        <i class="fa fa-angle-double-right" ></i> {{$data->ref}}
    </h1>
</section>

<!-- Main content -->
<section class="content">
  {{-- <form method="POST" action="master/alat/insert" > --}}
    <div class="box box-solid" >
        
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            {{-- <label> <small>Sales Order</small> <h4 style="font-weight: bolder;margin-top:0;padding-top:0;margin-bottom:0;padding-bottom:0;" >New</h4></label> --}}
            <button id="btn-cancel"  class="btn btn-danger" >Cancel Data</button>
            <button id="btn-print"  class="btn btn-success" >Print</button>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-blue" >Validated</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Open</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Draft</a>
        </div>
        <form name="form-create-dailyhd" method="POST" action="dailyhd/update" >
        <input type="hidden" name="dailyhd_id" value="{{$data->id}}">   
            <div class="box-body" >
                <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->ref}}</h3></label>

                <table class="table" >
                    <tbody>
                        <tr>
                            <td class="col-sm-1 col-md-1 col-lg-1" >
                                <label>Tanggal</label>
                            </td>
                            <td class="col-sm-3 col-md-3 col-lg-3" >
                                {{$data->tanggal_formatted}}
                            </td>
                            <td class="col-sm-1 col-md-1 col-lg-1" >
                                <label>Alat</label>
                            </td>
                            <td class="col-sm-3 col-md-3 col-lg-3" >
                                {{$data->alat}}
                            </td>
                            <td class="col-sm-1 col-md-1 col-lg-1" >
                                <label>Lokasi Galian</label>
                            </td>
                            <td class="col-sm-3 col-md-3 col-lg-3" >
                                {{$data->lokasi}}
                            </td>
                        <tr>
                            <td>
                                <label>Jam Kerja</label>
                            </td>
                            <td>
                                <div class='input-group'>
                                    {{$data->mulai}} - {{$data->selesai}}
                                </div>
                                {{-- <input type="text" name="mulai" class="form-control input-time" required> --}}
                            </td>
                            <td>
                                <label>Istirahat</label>
                            </td>
                            <td>
                                {{$data->istirahat_mulai}} - {{$data->istirahat_selesai}}
                            </td>
                            <td>
                                {{-- <label>Total Jam Kerja</label> --}}
                                <label>Total Jam Kerja (jam)</label>
                            </td>
                            <td>
                                {{$data->jam_kerja}}
                            </td>
                        </tr>   
                        <tr>
                            <td>
                                <label>Pengawas</label>
                            </td>
                            <td>
                                {{'['.$data->kode_pengawas . '] ' . $data->nama_pengawas}}
                            </td>
                            <td>
                                <label>Operator</label>
                            </td>
                            <td>
                                {{'['.$data->kode_operator .'] ' . $data->nama_operator}}
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            
                            <td>
                                <label>Solar (liter)</label>
                            </td>
                            <td>
                                {{$data->solar}}
                            </td>
                            <td>
                                <label>Oli (liter)</label>
                            </td>
                            <td>
                                {{$data->oli}}
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <label>Keterangan</label>
                            </td>
                            <td colspan="5" >
                                {{$data->desc}}
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>   {{-- Box Body --}}
            <div class="box-footer" >
                {{-- <button type="submit" class="btn btn-primary" >Save</button> --}}
                <a href="dailyhd" class="btn btn-danger">Close</a>
            </div>
        </form>
    </div> {{-- Box --}}
  {{-- </form> --}}
</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script type="text/javascript" src="plugins/timepicker/bootstrap-timepicker.js"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script type="text/javascript">
(function ($) {
   $('.input-time').timepicker({
                minuteStep: 1,
                template: 'modal',
                appendWidgetTo: 'body',
                showSeconds: true,
                showMeridian: false,
                defaultTime: false
            });

   // AUTONUMERIC
   $('.input-liter').autoNumeric('init',{
        vMin:'0.00',
        vMax:'9999999999.00'
    });

   // SET DATEPICKER
    $('.input-date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    // END OF SET DATEPICKER

    // SET AUTOCOMPLETE SUPPLIER
    $('input[name=pengawas]').autocomplete({
        serviceUrl: 'api/get-auto-complete-staff',
        params: {  'nama': function() {
                        return $('input[name=pengawas]').val();
                    }
                },
        onSelect:function(suggestions){
            $('input[name=pengawas_id]').val(suggestions.data);
        }

    });

    $('input[name=operator]').autocomplete({
        serviceUrl: 'api/get-auto-complete-staff',
        params: {  'nama': function() {
                        return $('input[name=operator]').val();
                    }
                },
        onSelect:function(suggestions){
            $('input[name=operator_id]').val(suggestions.data);
        }

    });

    $('input[name=alat]').autocomplete({
        serviceUrl: 'api/get-auto-complete-alat',
        params: {  'nama': function() {
                        return $('input[name=alat]').val();
                    }
                },
        onSelect:function(suggestions){
            $('input[name=alat_id]').val(suggestions.data);
        }

    });

    $('input[name=lokasi]').autocomplete({
        serviceUrl: 'api/get-auto-complete-lokasi-galian',
        params: {  'nama': function() {
                        return $('input[name=lokasi]').val();
                    }
                },
        onSelect:function(suggestions){
            $('input[name=lokasi_id]').val(suggestions.data);
        }

    });

    // hitung jam kerja
    var mulai;
    var selesai;
    var istirahat_mulai;
    var istirahat_selesai;
    $('input[name=mulai]').timepicker().on('changeTime.timepicker', function(e) {
        // mulai = e.time;
        hitungJamKerja();
      });
    $('input[name=selesai]').timepicker().on('changeTime.timepicker', function(e) {
        // selesai = e.time;
        hitungJamKerja();
      });

    $('input[name=istirahat_mulai]').timepicker().on('changeTime.timepicker', function(e) {
        // istirahat_mulai = e.time;
        hitungJamKerja();
      });
    $('input[name=istirahat_selesai]').timepicker().on('changeTime.timepicker', function(e) {
        // istirahat_selesai = e.time;
        hitungJamKerja();
      });

    function hitungJamKerja(){
        mulai = $('input[name=mulai]').data('timepicker');
        selesai = $('input[name=selesai]').data('timepicker');
        istirahat_mulai = $('input[name=istirahat_mulai]').data('timepicker');
        istirahat_selesai = $('input[name=istirahat_selesai]').data('timepicker');
        // alert('Mulai ' + mulai.hours +' '+ mulai.minute + ' ' + mulai.second);
        // alert('Selesai ' + selesai.hours +' '+ selesai.minute + ' ' + selesai.second);
        // alert('Istirahat Mulai ' + istirahat_mulai.hours +' '+ istirahat_mulai.minute + ' ' + istirahat_mulai.second);
        // alert('Istirahat Selesai ' + istirahat_selesai.hours +' '+ istirahat_selesai.minute + ' ' + istirahat_selesai.second);

        if(mulai!="" && selesai!= "" && mulai != null && selesai != null && mulai != undefined && selesai != undefined &&
            istirahat_mulai!="" && istirahat_selesai!= "" && istirahat_mulai != null && istirahat_selesai != null && istirahat_mulai != undefined && istirahat_selesai != undefined ){
            var tgl_mulai = new Date(10,10,2000,mulai.hour,mulai.minute,mulai.second);
            var tgl_selesai = new Date(10,10,2000,selesai.hour,selesai.minute,selesai.second);
            var tgl_istirahat_mulai = new Date(10,10,2000,istirahat_mulai.hour,istirahat_mulai.minute,istirahat_mulai.second);
            var tgl_istirahat_selesai = new Date(10,10,2000,istirahat_selesai.hour,istirahat_selesai.minute,istirahat_selesai.second);
            var selisih = (tgl_selesai - tgl_mulai);  
            var selisih_istirahat = (tgl_istirahat_selesai - tgl_istirahat_mulai);
            var jam_kerja = selisih - selisih_istirahat ;
            // console.log();
            console.log(jam_kerja / (1000 * 60 * 60));

            $('input[name=total_jam_kerja]').val(jam_kerja / (1000 * 60 * 60));
        }else{
            $('input[name=total_jam_kerja]').val('');
        }
    }

    // VALIDATE
    $('#btn-validate').click(function(){
        var newform = $('<form>').attr('method','POST').attr('action','dailyhd/validate');
        newform.append($('<input>').attr('type','hidden').attr('name','dailyhd_id').val($('input[name=dailyhd_id]').val()));
        newform.submit(); 
    });


// alert('pret');
})(jQuery);
</script>
@append