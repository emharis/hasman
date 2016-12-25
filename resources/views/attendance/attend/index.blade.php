@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<link href="plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>


<style>
    .col-top-item{
        cursor:pointer;
        border: thin solid #CCCCCC;

    }
    .table-top-item > tbody > tr > td{
        border-top-color: #CCCCCC;
    }

    .datepicker {z-index: 1151 !important;}
</style>
@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Presensi
    </h1>
</section>

<!-- Main content -->
<section class="content">
  <div class="box box-solid" >
    <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
        <label><h3 style="margin:0;padding:0;font-weight:bold;" >Presensi</h3></label>
{{-- 
        <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
        <a class="btn  btn-arrow-right pull-right disabled bg-gray" id="label-status-posted" >Posted</a>

        <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
        <a class="btn  btn-arrow-right pull-right disabled bg-blue" id="label-status-draft" >Draft</a> --}}

    </div>
    <div class="box-body" >
      <div class="row" >
        <div class="col-sm-4 col-md-4 col-lg-4" >
          <table class="table" >
            <tr>
              <td class="col-sm-2 col-md-2 col-lg-2" >Tanggal</td>
              <td>
                  <form name="form_presensi" method="POST" action="attendance/get-attendance-table" >
                    <div class="input-group" >
                        <input class="form-control input-tanggal" name="tanggal" required />
                        <div class="input-group-btn" >
                          <button class="btn btn-primary" id="btn-show-data-presensi" type="submit" ><i class="fa fa-search" ></i></button>
                        </div>
                    </div>
                  </form>
                {{-- <input class="form-control input-tanggal" name="tanggal" /> --}}
              </td>
            </tr>
            {{-- <tr>
              <td></td>
              <td>
                <button id="btn-show-data-presensi" class="btn btn-primary btn-sm" >Show</button>
              </td>
            </tr> --}}
          </table>
        </div>
        <div class="col-sm-4 col-md-4 col-lg-4" ></div>
      </div>

      <h4 class="page-header data-presensi hide" style="font-size:14px;color:#3C8DBC"><strong>DATA PRESENSI</strong></h4>

      <div class="row data-presensi hide" >
        <div class="col-sm-12 col-md-12 col-lg-12" >
          <table id="table-presensi" class="table table-bordered table-data">
            <thead>
              <tr>
                <th style="width:25px;" class="text-center">No</th>
                <th class="col-sm-1 col-md-1 col-lg-1">Kode</th>
                <th>Karyawan</th>
                <th class="col-sm-1 col-md-1 col-lg-1 text-center" >
                    Pagi
                </th>
                <th class="col-sm-1 col-md-1 col-lg-1 text-center" >
                    Siang
                </th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>

          <button class="btn btn-primary" id="btn-save-presensi" >Save</button>

        </div>
      </div>

    </div>
    <div class="box-footer" >

    </div>
  </div>
</section><!-- /.content -->

@stop
@section('scripts')
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script src="plugins/jquery-timepicker/jquery.timepicker.min.js" type="text/javascript"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script type="text/javascript">
(function ($) {

  // SET DATEPICKER
  $('.input-tanggal').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true,
      daysOfWeekDisabled: [{{($libur_sabtu == 'Y' && $libur_minggu == 'Y') ? '0,6':($libur_sabtu == 'Y' ? '6':($libur_minggu == 'Y'?'0':''))}}]
  });

  // $('.input-tanggal').datepicker().on('changeDate', function (ev) {
  //   alert('GANTI TANGGAL');
  // });

  // DATATABLE
  // var tablePresensi = $('#table-presensi').DataTable();
  // var tablePresensi = $('#table-presensi').DataTable({
  //       "columns": [
  //           {className: "text-right"},
  //           null,
  //           {className: "text-center"}
  //       ]
  //   });

  // TAMPILKAN INPUT PRESENSI
  // $('#btn-show-data-presensi').click(function(){
  //   // get data presensi
    


  //   // var tanggal = $('input[name=tanggal]').val();
  //   // if(tanggal != ""){
  //   //   $.post('attendance/get-attendance-table' ,{
  //   //     'tanggal' : tanggal
  //   //   },function(res){
  //   //     // tampilkan data ke table
  //   //     var data_presensi = JSON.parse(res);
  //   //     var tablePresensi = $('#table-presensi tbody');
  //   //     $('.data-presensi').hide();
  //   //     // $('#ck-all').prop('checked',false);
  //   //     tablePresensi.empty();
  //   //     $.each(data_presensi.presensi,function(i,data){
  //   //       // // dengan datatable
  //   //       // tablePresensi.row.add([
  //   //       //     i+1,data.nama,'<input type="checkbox" name="ck_hadir_' + data.id + '" />'
  //   //       // ]).draw();

  //   //       // table biasa
  //   //       tablePresensi.append($('<tr>')
  //   //                               .append($('<td>').addClass('text-right').text(i+1))
  //   //                               .append($('<td>').text(data.kode))
  //   //                               .append($('<td>').text(data.nama))
  //   //                               .append($('<td>').addClass('text-center').append($('<input>')
  //   //                                                                               .attr('data-karyawanid',data.id)
  //   //                                                                               .addClass('ck-row ck-row-pagi')
  //   //                                                                               .attr('type','checkbox')
  //   //                                                                               .attr('name','ck_hadir_pagi_' + data.id)
  //   //                                                                               .prop('checked',data.pagi == 'Y' && true)
  //   //                                                                             ))
  //   //                               .append($('<td>').addClass('text-center').append($('<input>')
  //   //                                                                               .attr('data-karyawanid',data.id)
  //   //                                                                               .addClass('ck-row ck-row-siang')
  //   //                                                                               .attr('type','checkbox')
  //   //                                                                               .attr('name','ck_hadir_siang_' + data.id)
  //   //                                                                               .prop('checked',data.siang == 'Y' && true)
  //   //                                                                             ))
  //   //                           );

          
  //   //       // $(".ck-row-siang").bootstrapSwitch();
  //   //     });

  //   //     // tampilkan data table presensi
  //   //     $('.data-presensi').removeClass('hide');
  //   //     $('.data-presensi').fadeIn(250);

  //   //     // tampilkan status data
  //   //     if(data_presensi.status == 'P'){
  //   //       $('#label-status-posted').removeClass('bg-gray');
  //   //       $('#label-status-posted').removeClass('bg-blue');
  //   //       $('#label-status-posted').addClass('bg-blue');

  //   //       $('#label-status-draft').removeClass('bg-gray');
  //   //       $('#label-status-draft').removeClass('bg-blue');
  //   //       $('#label-status-draft').addClass('bg-gray');
  //   //     }else{
  //   //       $('#label-status-posted').removeClass('bg-gray');
  //   //       $('#label-status-posted').removeClass('bg-blue');
  //   //       $('#label-status-posted').addClass('bg-gray');

  //   //       $('#label-status-draft').removeClass('bg-gray');
  //   //       $('#label-status-draft').removeClass('bg-blue');
  //   //       $('#label-status-draft').addClass('bg-blue');
  //   //     }

  //   //     // format checkbox to switch
  //   //       $(".ck-row").bootstrapSwitch({
  //   //         size:'small',
  //   //         onText:'YES',
  //   //         offText:'NO'
  //   //       });
  //   //       // $(".ck-row-pagi").bootstrapSwitch({
  //   //       //   size:'small',
  //   //       //   onText:'YES',
  //   //       //   offText:'NO'
  //   //       // });

  //   //   });
  //   // }else{
  //   //   alert('Lengkapi data yang kosong.');
  //   //   // focuskan ke input tanggal
  //   //   $('input[name=tanggal]').focus();
  //   // }


  // });

  $('form[name=form_presensi]').ajaxForm(function(res){

        // tampilkan data ke table
        var data = JSON.parse(res);
        var tablePresensi = $('#table-presensi tbody');
        $('.data-presensi').hide();
        // $('#ck-all').prop('checked',false);
        tablePresensi.empty();
        $.each(data.karyawan,function(i,data){
          // // dengan datatable
          // tablePresensi.row.add([
          //     i+1,data.nama,'<input type="checkbox" name="ck_hadir_' + data.id + '" />'
          // ]).draw();

          // table biasa
          // alert('ok');
          tablePresensi.append($('<tr>')
                      .append($('<td>').addClass('text-right').text(i+1))
                      .append($('<td>').text(data.kode))
                      .append($('<td>').text(data.nama))
                      .append($('<td>').addClass('text-center')
                                          .append($('<input>')
                                            .attr('data-karyawanid',data.id)
                                            .addClass('ck-row ck-row-pagi')
                                            .attr('type','checkbox')
                                            .attr('name','ck_hadir_pagi_' + data.id)
                                            .prop('checked',(data.presensi ? data.presensi.pagi : 'N') == 'Y' && true)
                                        ))
                      .append($('<td>').addClass('text-center')
                            .append($('<input>')
                            .attr('data-karyawanid',data.id)
                            .addClass('ck-row ck-row-siang')
                            .attr('type','checkbox')
                            .attr('name','ck_hadir_siang_' + data.id)
                            .prop('checked',(data.presensi ? data.presensi.siang : 'N') == 'Y' && true)
                          ))
                  );

          
          $(".ck-row-siang").bootstrapSwitch();
          $(".ck-row-pagi").bootstrapSwitch();
        });

        // tampilkan data table presensi
        $('.data-presensi').removeClass('hide');
        $('.data-presensi').fadeIn(250);

        // tampilkan status data
        if(data_presensi.status == 'P'){
          $('#label-status-posted').removeClass('bg-gray');
          $('#label-status-posted').removeClass('bg-blue');
          $('#label-status-posted').addClass('bg-blue');

          $('#label-status-draft').removeClass('bg-gray');
          $('#label-status-draft').removeClass('bg-blue');
          $('#label-status-draft').addClass('bg-gray');
        }else{
          $('#label-status-posted').removeClass('bg-gray');
          $('#label-status-posted').removeClass('bg-blue');
          $('#label-status-posted').addClass('bg-gray');

          $('#label-status-draft').removeClass('bg-gray');
          $('#label-status-draft').removeClass('bg-blue');
          $('#label-status-draft').addClass('bg-blue');
        }

        // format checkbox to switch
          $(".ck-row").bootstrapSwitch({
            size:'small',
            onText:'YES',
            offText:'NO'
          });
          // $(".ck-row-pagi").bootstrapSwitch({
          //   size:'small',
          //   onText:'YES',
          //   offText:'NO'
          // });
    });

  //// check all
  // $('#ck-all').change(function(){
  //   $('.ck-row').prop('checked',$(this).is(':checked'));
  // });

  // save data presensi
  $('#btn-save-presensi').click(function(){
    var data_presensi = {"presensi":[]};
    var data_master = {"tanggal":""};
    var tanggal = $('input[name=tanggal]').val();
    data_master.tanggal = tanggal;

    $('.ck-row-pagi').each(function(i,data){
      var hadir_siang = $('input[name=ck_hadir_siang_'+$(this).data('karyawanid')+']').prop('checked');
      var hadir_pagi = $(this).prop('checked');


      // alert($('input[name=ck_hadir_siang_' + karyawan_id + ']').is(':checked'));
      // alert($('input[data-karyawanid=' + karyawan_id + ']').prop(':checked'));
      data_presensi.presensi.push({
          karyawan_id : $(this).data('karyawanid'),
          pagi : hadir_pagi,
          siang : hadir_siang
      });
    });

    // post to database
    // var newform = $('<form>').attr('method','POST').attr('action','attendance/attend/insert');
    // newform.append($('<input>').attr('type','hidden').attr('name','tanggal').val(tanggal));
    // newform.append($('<input>').attr('type','hidden').attr('name','data_presensi').val(JSON.stringify(data_presensi)));
    // newform.submit();

    // post to database using javascript/ajax
    $.post('attendance/attend/insert',{
      'tanggal' : tanggal,
      'data_presensi' : JSON.stringify(data_presensi)
    },function(res){
        alert('Data presensi telah disimpan');
    });

  });

// alert('pret');
})(jQuery);
</script>
@append
