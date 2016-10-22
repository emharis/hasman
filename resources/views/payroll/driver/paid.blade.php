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

    #table-delivery tr th{
      text-align: center;
    }


</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="payroll/driver" >Driver Payroll</a>
        <i class="fa fa-angle-double-right" ></i>
        {{$data->payroll_number}}
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
          <button class="btn btn-danger" id="btn-cancel" data-href="payroll/driver/cancel-payroll/{{$data->id}}" >Cancel Payroll</button>
          <div class="btn-group ">
            <button type="button" class="btn btn-success  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
              Print <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              <li><a href="#">Direct Print</a></li>
              <li><a href="#">PDF</a></li>
            </ul>
          </div>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-blue" >Paid</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Open</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Draft</a>
        </div>
        <div class="box-body">
          <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->payroll_number}}</h3></label>

            <table class="table" >
                <tbody>
                    <tr>
                        <td class="col-lg-2">
                            <label>Driver</label>
                        </td>
                        <td class="col-lg-4" >
                            {{'[' . $data->kode_karyawan . '] ' .$data->nama_karyawan}}" data-driverid="{{$data->karyawan_id}}
                        </td>
                        <td class="col-lg-2" >
                            <label>Payment Date</label>
                        </td>
                        <td class="col-lg-4" >
                            {{$data->payment_date_formatted}}
                        </td>
                    </tr>
                    <tr>
                      <td>
                        <label>Start Date</label>
                      </td>
                      <td>
                        {{$data->start_date_formatted}}
                      </td>
                      <td>
                        <label>End Date</label>
                      </td>
                      <td>
                        {{$data->end_date_formatted}}
                      </td>
                    </tr>
                </tbody>
            </table>

            <h4 class="page-header data-delivery " style="font-size:14px;color:#3C8DBC"><strong>DELIVERY DETAILS</strong></h4>

            <table id="table-delivery" class="table table-bordered table-condensed data-delivery " >
                <thead>
                    <tr>
                        <th rowspan="2" style="width:25px;" >NO</th>
                        <th rowspan="2" >MATERIAL</th>
                        <th rowspan="2" >PEKERJAAN</th>
                        <th rowspan="2" >TUJUAN</th>
                        <th colspan="3" >KALKULASI</th>
                        <th rowspan="2" >HARGA</th>
                        <th rowspan="2" >JUMLAH</th>
                    </tr>
                    <tr>
                      <th>VOLUME</th>
                      <th>NETTO</th>
                      <th>RIT</th>
                    </tr>
                </thead>
                <tbody>
                  <?php $rownum=1; ?>
                  @foreach($data_detail as $dt)
                    <tr class="row-material" data-materialid="{{$dt->material_id}}" data-pekerjaanid="{{$dt->pekerjaan_id}}">
                      <td>{{$rownum++}}</td>
                      <td>{{$dt->material}}</td>
                      <td>{{$dt->pekerjaan}}</td>
                      <td>{{$dt->kecamatan}}</td>
                      <td class="text-right " >
                        {{$dt->kalkulasi == 'K' ? $dt->volume : '-'}}
                      </td>
                      <td class="text-right " >
                        {{$dt->kalkulasi == 'T' ? $dt->netto : '-'}}
                      </td>
                      <td class="text-right " >
                        {{$dt->kalkulasi == 'R' ? $dt->rit : '-'}}
                      </td>
                      <td class="col-sm-2 col-md-2 col-lg-2 uang text-right">
                        {{$dt->harga}}
                      </td>
                      <td class="uang col-total-on-row col-sm-2 col-md-2 col-lg-2 text-right">
                        {{$dt->total}}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
            </table>

            <div class="row data-delivery " >
                <div class="col-lg-8" >
                    {{-- <textarea name="note" class="form-control" rows="3" style="margin-top:5px;" placeholder="Note" ></textarea>
                    <i>* <span>Q.O.H : Quantity on Hand</span></i>
                    <i>&nbsp;|&nbsp;</i>
                    <i><span>S.U.P : Salesperson Unit Price</span></i> --}}
                </div>
                <div class="col-lg-4" >
                    <table class="table table-condensed" >
                        <tbody>
                            <tr>
                                <td class="text-right">
                                    <label>Total :</label>
                                </td>
                                <td class="label-total uang text-right" >

                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" >
                                    <label>Potongan Bahan :</label>
                                </td>
                                <td class="uang text-right" >
                                   {{$data->potongan_bahan}}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" >
                                    <label>Potongan Bon :</label>
                                </td>
                                <td class="uang text-right" >
                                   {{$data->potongan_bon}}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" >
                                    <label>Sisa Bayaran Kemarin :</label>
                                </td>
                                <td class="uang text-right" >
                                   {{$data->sisa_bayaran}}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" >
                                    <label>DP :</label>
                                </td>
                                <td class="uang text-right" >
                                   {{$data->dp}}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" style="border-top:solid darkgray 1px;" >
                                    Saldo :
                                </td>
                                <td class="label-saldo text-right uang" style="font-size:18px;font-weight:bold;border-top:solid darkgray 1px;" >
                                  {{$data->saldo}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                {{-- <div class="col-lg-12" >
                    <button type="submit" class="btn btn-primary" id="btn-save" >Save</button>
                            <a class="btn btn-danger" id="btn-cancel-save" >Cancel</a>
                </div> --}}

            </div>



            {{-- <a id="btn-test" href="#" >TEST</a> --}}


        </div><!-- /.box-body -->
        <div class="box-footer data-delivery " >
            {{-- <button type="submit" class="btn btn-primary" id="btn-save" >Save</button>
            <a class="btn btn-danger" id="btn-cancel-save" >Cancel</a> --}}
            <a class="btn btn-danger" href="payroll/driver" >Close</a>
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

    // format auto numeric uang
    $('.uang').autoNumeric('init',{
        vMin:'0',
        vMax:'9999999999'
    });
    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'))
    });

    // CANCEL PAYROLL
    $('#btn-cancel').click(function(){
      if(confirm('Anda akan membatalkan transaksi ini? \nData yang telah dibatalkan tidak dapat dikembalikan.')){
        location.href = $(this).data('href');
      };
    });

})(jQuery);
</script>
@append
