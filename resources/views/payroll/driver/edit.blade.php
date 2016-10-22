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
          <button class="btn btn-primary" id="btn-validate" data-href="payroll/driver/validate/{{$data->id}}" >Validate</button>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Paid</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-blue" >Open</a>

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
                            <input type="text" name="driver" autofocus class="form-control " value="{{'[' . $data->kode_karyawan . '] ' .$data->nama_karyawan}}" data-driverid="{{$data->karyawan_id}}" required readonly>
                        </td>
                        <td class="col-lg-2" >
                            <label>Payment Date</label>
                        </td>
                        <td class="col-lg-4" >
                            <input type="text" name="payment_date" class="input-tanggal form-control" value="{{$data->payment_date_formatted}}" required>
                        </td>
                    </tr>
                    <tr>
                      <td>
                        <label>Start Date</label>
                      </td>
                      <td>
                        <input type="text" name="start_date" class="input-tanggal form-control" value="{{$data->start_date_formatted}}" readonly />
                      </td>
                      <td>
                        <label>End Date</label>
                      </td>
                      <td>
                        <input type="text" name="end_date" class="form-control input-tanggal" value="{{$data->end_date_formatted}}" readonly />
                      </td>
                    </tr>

                    {{-- <tr>
                      <td></td>
                      <td>
                        <button class="btn btn-primary" id="btn-show-delivery" >Show</button>
                      </td>
                      <td></td>
                      <td></td>
                    </tr> --}}
                </tbody>
            </table>

            <h4 class="page-header data-delivery " style="font-size:14px;color:#3C8DBC"><strong>DELIVERY DETAILS</strong></h4>
            {!! Form::hidden('payroll_id',$data->id) !!}
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
                    <tr class="row-material" data-materialid="{{$dt->material_id}}" data-pekerjaanid="{{$dt->pekerjaan_id}}" data-kalkulasi="{{$dt->kalkulasi}}">
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
                      <td class="col-sm-2 col-md-2 col-lg-2">
                        <input data-kalkulasi="{{$dt->kalkulasi}}" class="form-control text-right uang input-harga-on-row" value="{{$dt->harga}}"  >
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
                                {{$data->total}}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" >
                                    <label>Potongan Bahan :</label>
                                </td>
                                <td >
                                   <input style="font-size:14px;" type="text" name="potongan_bahan" class="input-potongan input-sm form-control text-right input-clear uang" value="{{$data->potongan_bahan}}" >
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" >
                                    <label>Potongan Bon :</label>
                                </td>
                                <td >
                                   <input style="font-size:14px;" type="text" name="potongan_bon" class="input-potongan  input-sm form-control text-right input-clear uang" value="{{$data->potongan_bon}}" >
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" >
                                    <label>Sisa Bayaran Kemarin :</label>
                                </td>
                                <td >
                                   <input style="font-size:14px;" type="text" name="sisa_bayaran" class="input-potongan  input-sm form-control text-right input-clear uang" value="{{$data->sisa_bayaran}}" >
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" >
                                    <label>DP :</label>
                                </td>
                                <td >
                                   <input style="font-size:14px;" type="text" name="input_dp" class="input-potongan input-sm form-control text-right input-clear uang" value="{{$data->dp}}" >
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
            </div>

        </div><!-- /.box-body -->
        <div class="box-footer data-delivery" >
            <button type="submit" class="btn btn-primary" id="btn-save" >Save</button>
            <a class="btn btn-danger" id="btn-cancel-save" href="payroll/driver" >Close</a>
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

    //Initialize Select2 Elements
    // $(".select2").select2();


    // SET DATEPICKER
    $('.input-tanggal').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        endDate : 'now'
    });
    // END OF SET DATEPICKER

    // SET AUTOCOMPLETE SUPPLIER
    $('input[name=driver]').autocomplete({
        serviceUrl: 'api/get-auto-complete-driver',
        params: {  'nama': function() {
                        return $('input[name=driver]').val();
                    }
                },
        onSelect:function(suggestions){
            // set data driver
            $('input[name=driver]').data('driverid',suggestions.data);

            // get data pekerjaan
            // fillSelectPekerjaan(suggestions.data);

            // // enablekan select pekerjaan
            // $('select[name=pekerjaan]').removeAttr('disabled');
            // $('#btn-add-pekerjaan').removeAttr('disabled');

            //set data pekerjaan id
            $('form[name=form_create_pekerjaan] input[name=driver_id]').val(suggestions.data);
        }

    });

    // VALIDATE PAYROLL DATA
    $('#btn-validate').click(function(){
      if(confirm('Anda akan memvalidasi data ini?')){
        location.href = $(this).data('href');
      }
    });

    // GET DATA DELIVERY DRIVER
    // $('#btn-show-delivery').click(function(){
    //   var driver_id = $('input[name=driver]').data('driverid');
    //   var driver = $('input[name=driver]').val();
    //   var tanggal = $('input[name=payment_date]').val();
    //   var start_date = $('input[name=start_date]').val();
    //   var end_date = $('input[name=end_date]').val();
    //
    //   if(driver_id != "" && driver != "" && tanggal != "" && start_date != "" && end_date != ""){
    //
    //     var url = 'payroll/driver/get-delivery-order/' + driver_id + "/" + start_date + "/" + end_date;
    //
    //     $.get(url,null,function(res){
    //       $('.data-delivery').hide();
    //       $('#table-delivery tbody').empty();
    //
    //       // alert('ok');
    //       var data_do = JSON.parse(res);
    //       var table_delivery = $('#table-delivery tbody');
    //       var rownum=1;
    //       $.each(data_do,function(){
    //         table_delivery.append($('<tr>').addClass('row-material').attr('data-materialid',$(this)[0].material_id).attr('data-pekerjaanid',$(this)[0].pekerjaan_id)
    //                               .append($('<td>').text(rownum++))
    //                               .append($('<td>').html($(this)[0].material))
    //                               .append($('<td>').html($(this)[0].pekerjaan))
    //                               .append($('<td>').html($(this)[0].kecamatan))
    //                               // .append($('<td>').html($(this)[0].kalkulasi == 'K' && 'KUBIKASI' || $(this)[0].kalkulasi == 'T' && 'TONASE' || "RITASE" ))
    //                               .append($('<td>').html($(this)[0].kalkulasi == 'K' && $(this)[0].sum_volume || '-'))
    //                               .append($('<td>').html($(this)[0].kalkulasi == 'T' && $(this)[0].sum_netto || '-'))
    //                               .append($('<td>').html($(this)[0].kalkulasi == 'R'&& $(this)[0].sum_qty || '-'))
    //                               .append($('<td>').addClass('col-sm-2 col-md-2 col-lg-2').append($('<input data-kalkulasi="' + $(this)[0].kalkulasi + '" class="form-control text-right uang input-harga-on-row"  />')))
    //                               .append($('<td>').addClass('uang col-total-on-row col-sm-2 col-md-2 col-lg-2 text-right'))
    //                               );
    //       });
    //
    //       // BERSIHKAN DATA TOTAL & potongan
    //       $('.label-total').text('');
    //       $('.label-saldo').text('');
    //       $('input[name=potongan_bahan]').val('');
    //       $('input[name=potongan_bon]').val('');
    //       $('input[name=sisa_bayaran]').val('');
    //       $('input[name=input_dp]').val('');
    //
    //       // tampilkan table data delivery
    //       $('.data-delivery').removeClass('hide');
    //       $('.data-delivery').fadeIn(250);
    //
    //       // format auto numeric uang
    //       $('.uang').autoNumeric('init',{
    //           vMin:'0',
    //           vMax:'9999999999'
    //       });
    //
    //       // disable input
    //       $('input[name=driver]').attr('readonly','readonly');
    //       $('input[name=payment_date]').attr('readonly','readonly');
    //       $('input[name=start_date]').attr('readonly','readonly');
    //       $('input[name=end_date]').attr('readonly','readonly');
    //
    //     });
    //     // location.href = url;
    //   }else{
    //     alert('Lengkapi data yang kosong.');
    //   }
    // });

    // KALKULASI JUMLAH
    $(document).on('keyup','.input-harga-on-row',function(){

      var harga = $(this).autoNumeric('get');
      var kalkulasi = $(this).data('kalkulasi');
      var jumlah = 0;
      if(kalkulasi == 'K'){
        var vol = $(this).parent().prev().prev().prev().text();
        jumlah = Number(vol) * Number(harga);
      }else if(kalkulasi == 'T'){
        var netto = $(this).parent().prev().prev().text();
        jumlah = Number(netto) * Number(harga);
      }else{
        var qty = $(this).parent().prev().text();
        jumlah = Number(qty) * Number(harga);
      }

      // tampilkan jumlah
      $(this).parent().next().autoNumeric('set',jumlah);

      // hitung total
      hitungTotal();
    });

    // hitung total
    function hitungTotal(){
      var total = 0;
      $('.row-material').each(function(){
        var jumlah = $(this).children('td:last').autoNumeric('get');
        total = Number(total) + Number(jumlah);
      });

      $('.label-total').autoNumeric('set',total);

      // hitung potongan

      var potongan = 0;
      $('.input-potongan').each(function(){
        potongan = Number(potongan) + Number($(this).autoNumeric('get'));
      });

      // itung saldo
      var saldo = Number(total) - Number(potongan);

      $('.label-saldo').autoNumeric('set',saldo);
    }

    // input potongan keyup
    $(document).on('keyup','.input-potongan',function(){
      hitungTotal();
    });

    // SAVE PAYROLL
    $('#btn-save').click(function(){
      var payroll = {   
                        "payroll_id":"",
                        "karyawan_id":"",
                        "start_date":"",
                        "end_date":"",
                        "payment_date":"",
                        "total":"",
                        "potongan_bahan":"",
                        "potongan_bon":"",
                        "sisa_bayaran":"",
                        "dp":"",
                        "saldo":""
                   };
      payroll.payroll_id = $('input[name=payroll_id]').val();
      payroll.karyawan_id = $('input[name=driver]').data('driverid');
      payroll.payment_date = $('input[name=payment_date]').val();
      payroll.start_date = $('input[name=start_date]').val();
      payroll.end_date = $('input[name=end_date]').val();
      payroll.total = $('.label-total').autoNumeric('get');
      payroll.potongan_bahan = $('input[name=potongan_bahan]').autoNumeric('get');
      payroll.potongan_bon = $('input[name=potongan_bon]').autoNumeric('get');
      payroll.sisa_bayaran = $('input[name=sisa_bayaran]').autoNumeric('get');
      payroll.dp = $('input[name=input_dp]').autoNumeric('get');
      payroll.saldo = $('.label-saldo').autoNumeric('get');

      // alert(JSON.stringify(payroll));

      var payroll_detail = JSON.parse('{"detail" : [] }');
      var can_save = true;

      $('.row-material').each(function(i,data){
        var row = $(this);
        var col = $(this).children('td:first');
        var harga_on_row = col.next().next().next().next().next().next().next().children('input').autoNumeric('get');
        // alert(row.data('materialid'));
        payroll_detail.detail.push({
            material_id:row.data('materialid'),
            pekerjaan_id:row.data('pekerjaanid'),
            kalkulasi:row.data('kalkulasi'),
            volume:col.next().next().next().next().text(),
            netto:col.next().next().next().next().next().text(),
            rit:col.next().next().next().next().next().next().text(),
            harga:harga_on_row,
            total:col.next().next().next().next().next().next().next().next().autoNumeric('get')

        });

         if(harga_on_row == 0 || harga_on_row == ""){
                can_save = false;
            }  

        // // submitting data
        // var newform = $('<form>').attr('method','POST').attr('action','payroll/driver/insert');
        // newform.append($('<input>').attr('type','hidden').attr('name','payroll').val(JSON.stringify(payroll)));
        // newform.append($('<input>').attr('type','hidden').attr('name','payroll_detail').val(JSON.stringify(payroll_detail)));
        // newform.submit();

      });

      // submitting data
      if(can_save){
        var newform = $('<form>').attr('method','POST').attr('action','payroll/driver/update');
        newform.append($('<input>').attr('type','hidden').attr('name','payroll').val(JSON.stringify(payroll)));
        newform.append($('<input>').attr('type','hidden').attr('name','payroll_detail').val(JSON.stringify(payroll_detail)));
        newform.submit();
      }else{
        alert('Lengkapi data yang kosong.');
      }

      // alert(JSON.stringify(payroll_detail));


    });

    // format auto numeric uang
    $('.uang').autoNumeric('init',{
        vMin:'0',
        vMax:'9999999999'
    });
    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'))
    });

})(jQuery);
</script>
@append
