@extends('layouts.master')

@section('styles')
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="plugins/select2/select2.min.css">
<style>
    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
    .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-selected { background: #FFE291; }
    .autocomplete-suggestions strong { font-weight: normal; color: red; }
    .autocomplete-group { padding: 2px 5px; }
    .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }

    .table-row-mid > tbody > tr > td {
        vertical-align:middle;
    }

    input.input-clear {
        display: block;
        padding: 0;
        margin: 0;
        border: 0;
        width: 100%;
        background-color:#EEF0F0;
        float:right;
        padding-right: 5px;
        padding-left: 5px;
    }

    /*#table-payroll tr th{
      text-align: center;
    }*/

    .col-first{
        border-right: solid #F4F4F4 2px;
    }

    .form-horizontal .form-group .control-label{
      text-align: left;
    }

    .table-data thead tr th small{
      font-size:0.5em;
    }


</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="payroll/payroll" >Payroll Option</a>
        <i class="fa fa-angle-double-right" ></i>
        <a href="payroll/payroll/show-payroll-table/{{$data->payment_date_formatted}}/ST" >Payroll Staff</a>
        <i class="fa fa-angle-double-right" ></i>
        {{$data->payroll_number}}
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            {{-- <label> <small>Sales Order</small> <h4 style="font-weight: bolder;margin-top:0;padding-top:0;margin-bottom:0;padding-bottom:0;" >New</h4></label> --}}
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->payroll_number}}</h3></label>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-blue" >Paid</a>
            
            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Open</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Draft</a>
        </div>
        <div class="box-body">
          <div class="row" >
            <div class="col-sm-6" >
              <div class="form-horizontal" >
                <div class="form-group">
                  <label  class="col-sm-4 control-label">Kode Karyawan</label>
                  <div class="col-sm-8">
                    <input type="hidden" name="payroll_id" value="{{$data->id}}">
                    <input type="hidden" name="karyawan_id" value="{{$data->karyawan_id}}">
                    <input type="hidden" name="total_pagi" value="{{$data->total_pagi}}">
                    <input type="hidden" name="total_siang" value="{{$data->total_siang}}">
                    <input type="text" readonly  name="kode" class="form-control" value="{{$data->kode_karyawan}}">
                  </div>
                </div>
                <div class="form-group">
                  <label  class="col-sm-4 control-label">Nama</label>
                  <div class="col-sm-8">
                    <input type="text" readonly  name="nama" class="form-control" value="{{$data->nama_karyawan}}">
                  </div>
                </div>
                <div class="form-group">
                  <label  class="col-sm-4 control-label">Periode Pembayaran</label>
                  <div class="col-sm-8">
                    <input type="text" readonly  name="periode_pembayaran" class="form-control" value="{{$data->payment_date_formatted}}">
                  </div>
                </div>
                <div class="form-group">
                  <label  class="col-sm-4 control-label">Gaji/Hari</label>
                  <div class="col-sm-8">
                    <input type="text" readonly  name="gaji_pokok" class="form-control uang text-right" value="{{$data->gaji_pokok}}">
                  </div>
                </div>
                <div class="form-group">
                  <label  class="col-sm-4 control-label">Jumlah Presensi</label>
                  <div class="col-sm-8">
                    <input type="text" readonly  name="jumlah_presensi" class="form-control text-right" value="{{($data->total_pagi + $data->total_siang)/2}}">
                  </div>
                </div>
                <div class="form-group">
                  <label  class="col-sm-4 control-label">Jumlah Gaji</label>
                  <div class="col-sm-8">
                    <input type="text" readonly  name="jumlah_gaji" class="form-control uang text-right" value="{{($data->total_pagi + $data->total_siang)/2 * $data->gaji_pokok}}">
                  </div>
                </div>
                <div class="form-group">
                  <label  class="col-sm-4 control-label">Potongan</label>
                  <div class="col-sm-8">
                    <input type="text"  readonly name="potongan" class="form-control uang text-right" value="{{$data->potongan}}" >
                  </div>
                </div>
                <hr/>
                <div class="form-group">
                  <label  class="col-sm-4 control-label">Total Gaji</label>
                  <div class="col-sm-8">
                    <input type="text" readonly name="gaji_bersih" style="font-size: 16pt; font-weight: bold;background-color: transparent;border:none;border-bottom: thin solid grey;" class="form-control uang text-right" value="{{$data->netpay}}">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6" >
              <label>Data Presensi</label>
              <table class="table table-condensed table-bordered table-data" >
                <thead>
                  <tr>
                    <th  >Waktu</th>
                    <?php $tgl_awal_idx = $tanggal_awal->format('d'); ?>
                    <th>{{$tanggal_awal->format('d')}}<small>{{$tanggal_awal->format('M')}}</small> </th>
                    <th>{{$tanggal_awal->modify('+1 day')->format('d')}}<small>{{$tanggal_awal->format('M')}}</th>
                    <th>{{$tanggal_awal->modify('+1 day')->format('d')}}<small>{{$tanggal_awal->format('M')}}</th>
                    <th>{{$tanggal_awal->modify('+1 day')->format('d')}}<small>{{$tanggal_awal->format('M')}}</th>
                    <th>{{$tanggal_awal->modify('+1 day')->format('d')}}<small>{{$tanggal_awal->format('M')}}</th>
                    <th>{{$tanggal_awal->modify('+1 day')->format('d')}}<small>{{$tanggal_awal->format('M')}}</th>
                    <th>{{$tanggal_awal->modify('+1 day')->format('d')}}<small>{{$tanggal_awal->format('M')}}</th>
                    <th  >TOTAL</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                      <td>
                        <label>Pagi</label>
                      </td>
                      <?php $total_pagi=0; ?>
                      <?php $tgl_awal_pagi = clone $tanggal_awal_siang_for_table_presensi; ?>
                      @for($i=1;$i<=7;$i++)
                        <td class="text-center" >
                          <?php $tgl_skrng = $tgl_awal_pagi->modify('+'. ($i==1?'0':'1') .' day')->format('Y-m-d'); ?>
                          @foreach($data->presensi_pagi as $dt)
                            @if($dt->tgl === $tgl_skrng)
                              @if($dt->pagi == 'Y')
                                <i class="fa fa-check text-green"  ></i>
                                <?php $total_pagi += 1; ?>
                              @else
                                <i class="fa fa-close text-red" ></i>
                              @endif
                            @else
                            @endif
                          @endforeach 
                        </td>
                      @endfor
                      <td class="text-center">{{$total_pagi}}</td>
                  </tr>
                    <td>
                      <label>Siang </label>
                    </td>
                    <?php $total_siang=0; ?>
                    @for($i=1;$i<=7;$i++)
                      <td class="text-center" >
                        <?php $tgl_skrng = $tanggal_awal_siang_for_table_presensi->modify('+'. ($i==1?'0':'1') .' day')->format('Y-m-d'); ?>
                        @foreach($data->presensi_siang as $dt)
                          @if($dt->tgl === $tgl_skrng)
                            @if($dt->siang == 'Y')
                              <i class="fa fa-check text-green"  ></i>
                              <?php $total_siang += 1; ?>
                            @else
                              <i class="fa fa-close text-red" ></i>
                            @endif
                          @else
                          @endif
                        @endforeach 
                      </td>
                    @endfor

                    <td class="text-center">{{$total_siang}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>


        </div><!-- /.box-body -->
        <div class="box-footer" >
          <a class="btn btn-success" target="_blank" href="payroll/payroll/print-pdf/{{$data->id}}" ><i class="fa fa-file-pdf-o" ></i> Print PDF</a>
          <a class="btn btn-success" target="_blank" href="payroll/payroll/print-copy/{{$data->id}}" ><i class="fa fa-copy" ></i> Print & Copy</a>
          <a class="btn btn-success" target="_blank" href="payroll/payroll/print-direct/{{$data->id}}" ><i class="fa fa-print" ></i> Direct Print</a>
          <a class="btn btn-danger" href="payroll/payroll/show-payroll-table/{{$data->payment_date_formatted}}/ST" >Close</a>

          <a id="btn-reset" class="btn btn-danger pull-right"><i class="fa fa-refresh" ></i> Reset</a>
        </div>
        
    </div><!-- /.box -->


</section><!-- /.content -->

<!-- /.modal -->
</div>

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<!-- Select2 -->
    <script src="plugins/select2/select2.full.min.js"></script>



<script type="text/javascript">
(function ($) {
  $('.uang').autoNumeric('init',{
      vMin:'0',
      vMax:'9999999999'
  });

  $('.uang').each(function(){
    $(this).autoNumeric('set',$(this).autoNumeric('get'));
  });
  // INPUT POTONGAN ON KEYUP
  $('input[name=potongan]').keyup(function(){
    // calculate
    var jumlah_gaji = $('input[name=jumlah_gaji]').autoNumeric('get');
    var potongan = $(this).autoNumeric('get');
    var total_gaji = jumlah_gaji - potongan;
    $('input[name=gaji_bersih]').autoNumeric('set',total_gaji);
  });

  // SAVE PAYROLL
  $('#btn-save-payroll').click(function(){
    var payroll_id = $('input[name=payroll_id]').val();
    var karyawan_id = $('input[name=karyawan_id]').val();
    var total_pagi = $('input[name=total_pagi]').val();
    var total_siang = $('input[name=total_siang]').val();
    var basic_pay = $('input[name=gaji_pokok]').autoNumeric('get');
    var potongan = $('input[name=potongan]').autoNumeric('get');
    var pay_date = $('input[name=periode_pembayaran]').val();

    var newform = $('<form>').attr('method','POST').attr('action','payroll/payroll/update-pay');
    newform.append($('<input>').attr('type','hidden').attr('name','payroll_id').val(payroll_id));
    newform.append($('<input>').attr('type','hidden').attr('name','karyawan_id').val(karyawan_id));
    newform.append($('<input>').attr('type','hidden').attr('name','total_pagi').val(total_pagi));
    newform.append($('<input>').attr('type','hidden').attr('name','total_siang').val(total_siang));
    newform.append($('<input>').attr('type','hidden').attr('name','basic_pay').val(basic_pay));
    newform.append($('<input>').attr('type','hidden').attr('name','potongan').val(potongan));
    newform.append($('<input>').attr('type','hidden').attr('name','pay_date').val(pay_date));
    newform.submit();

  });

  // RESET PAYROLL
  $('#btn-reset').click(function(){
    var payroll_id = $('input[name=payroll_id]').val();
    if(confirm('Anda akan me-reset data ini?')){
      location.href = 'payroll/payroll/reset/'+payroll_id;
    }
  });

})(jQuery);
</script>
@append
