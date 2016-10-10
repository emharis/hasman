@extends('layouts.master')

@section('styles')
<link href="plugins/jquery-timepicker/jquery.timepicker.min.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>

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
        Attendance Setting
    </h1>
</section>

<!-- Main content -->
<section class="content">
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#tab_1" data-toggle="tab">Time Setting</a></li>
      <li><a href="#tab_2" data-toggle="tab">Holiday</a></li>
      {{-- <li><a href="#tab_3" data-toggle="tab">Tab 3</a></li> --}}
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="tab_1">
        {{-- TIME SETTING --}}
        <form name="form_time_setting" method="POST" action="attendance/update-time-setting" >
          <table class="table table-bordered" >
            <tbody>
              {{-- <tr>
                <td class="col-sm-2 col-md-2 col-lg-2" >
                  <label>In Time</label>
                </td>
                <td>
                  <div class="input-group">
                    <input type="text" name="in_time" class="form-control timepicker" value="{{$in_time}}" autofocus required />
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div><!-- /.input group -->
                </td>
              </tr>
              <tr>
                <td>
                  <label>Out Time</label>
                </td>
                <td>
                  <div class="input-group">
                    <input type="text" name="out_time" class="form-control timepicker" value="{{$out_time}}" required/>
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div><!-- /.input group -->
                </td>
              </tr> --}}
              <tr>
                <td class="col-sm-2 col-md-2 col-lg-2" >
                  <label>Libur Akhir Pekan</label>
                </td>
                <td>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="libur_sabtu" {{$libur_sabtu == 'Y' ? 'checked':''}}>
                      Sabtu
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="libur_minggu" {{$libur_minggu == 'Y' ? 'checked':''}} >
                      Minggu
                    </label>
                  </div>
                </td>
              </tr>
              <tr>
                <td></td>
                <td>
                  <button type="submit" class="btn btn-primary">Save</button>
                </td>
              </tr>
            </tbody>
          </table>
        </form>
      </div><!-- /.tab-pane -->
      <div class="tab-pane" id="tab_2">
        <button class="btn btn-primary btn-sm" id="btn-add-holiday" >Add Holiday</button>

        <div class="clearfix" ></div>
        <br />
        {{-- HOLIDAY SETTING --}}
        <table class="table table-bordered" id="table-holiday" >
          <thead>
            <tr>
              <th class="col-sm-1 col-md-1 col-lg-1" >No</th>
              <th>Tanggal</th>
              <th>Keterangan</th>
              <th class="col-sm-1 col-md-1 col-lg-1" ></th>
            </tr>
          </thead>
          <tbody>
            <?php $rownum=1; ?>
            @foreach($holiday as $dt)
              <tr>
                <td>{{$rownum++}}</td>
                <td>
                  {{$dt->tgl}}
                </td>
                <td>
                  {{$dt->keterangan}}
                </td>
                <td class="text-center" >
                  <a class="btn btn-danger btn-xs btn-delete-holiday" href="attendance/setting/delete-holiday/{{$dt->id}}" ><i class="fa fa-trash-o" ></i></a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div><!-- /.tab-pane -->
      <div class="tab-pane" id="tab_3">

      </div><!-- /.tab-pane -->
    </div><!-- /.tab-content -->
  </div>

  <div class="modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <form name="form_holiday" action="attendance/setting/insert-holiday" method="POST" >
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Add Holiday</h4>
          </div>
          <div class="modal-body">
            <table class="table table-bordered" >
              <tbody>
                <tr>
                  <td><label>Tanggal</label></td>
                  <td><input type="text" class="form-control input-tanggal" name="tanggal" required  /></td>
                </tr>
                <tr>
                  <td><label>Keterangan</label></td>
                  <td><input type="text" class="form-control" name="keterangan" autocomplete="off" required /></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
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
<script type="text/javascript">
(function ($) {

  // SET DATEPICKER
  $('.input-tanggal').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true,
      daysOfWeekDisabled: [{{($libur_sabtu == 'Y' && $libur_minggu == 'Y') ? '0,6':($libur_sabtu == 'Y' ? '6':($libur_minggu == 'Y'?'0':''))}}]
  });
  // END OF SET DATEPICKER

    // //Timepicker
    $('input.timepicker').timepicker({
        timeFormat: 'HH:mm:ss',
        dropdown:false
    });

    // Add holiday
    $('#btn-add-holiday').click(function(){
      // clear input
      $('.modal input[name=tanggal]').val('');
      $('.modal input[name=keterangan]').val('');
      $('.modal').modal('show');
    });

    // format DataTable
    $('#table-holiday').DataTable({
      sort:false
    });

    // Javascript to enable link to tab
    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

    // delete holiday
    $('.btn-delete-holiday').click(function(){
      if(confirm('Anda akan menghapus data ini?')){

      }else{
        return false;
      }
    });


// alert('pret');
})(jQuery);
</script>
@append
