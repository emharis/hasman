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


</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="payroll/staff" >Staff Payroll</a>
        <i class="fa fa-angle-double-right" ></i>
        {{$data->payroll_number}}
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            {{-- <label> <small>Sales Order</small> <h4 style="font-weight: bolder;margin-top:0;padding-top:0;margin-bottom:0;padding-bottom:0;" >New</h4></label> --}}
            
            <button class="btn btn-primary" id="btn-validate" data-href="payroll/staff/validate/{{$data->id}}" >Validate</button>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Paid</a>
            
            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-blue" >Open</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Draft</a>
        </div>
        <div class="box-body">
          <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->payroll_number}}</h3></label>
          <input type="hidden" name="payroll_id" value="{{$data->id}}">
            <table class="table" >
                <tbody>
                    <tr>
                        <td class="col-lg-2">
                            <label>Staff</label>
                        </td>
                        <td class="col-lg-4" >
                            <input type="text" name="staff" autofocus class="form-control " data-staffid="{{$data->karyawan_id}}" required readonly value="{{$data->nama_karyawan}}">
                        </td>
                        <td class="col-lg-2" >
                            <label>Payment Date</label>
                        </td>
                        <td class="col-lg-4" >
                            <input type="text" name="payment_date" class="input-tanggal form-control" value="{{$data->payment_date_formatted}}" required >
                        </td>
                    </tr>
                    <tr>
                      <td>
                        <label>Start Date</label>
                      </td>
                      <td>
                        <input type="text" name="start_date" class="input-tanggal form-control" readonly value="{{$data->start_date_formatted}}"  />
                      </td>
                      <td>
                        <label>End Date</label>
                      </td>
                      <td>
                        <input type="text" name="end_date" class="form-control input-tanggal" readonly value="{{$data->end_date_formatted}}" />
                      </td>
                    </tr>
                </tbody>
            </table>

            

        </div><!-- /.box-body -->
        
    </div><!-- /.box -->

    <div class="box box-solid data-payroll" >
        <div class="box-header with-border" >
             <h4 class="page-header " style="font-size:14px;color:#3C8DBC"><strong>PAYROLL DETAILS</strong></h4>
        </div>
        <div class="box-body" >
            <table id="table-payroll" class="table table-condensed   " >
                <thead>
                    <tr>
                        <th colspan="2" class="col-sm-5 col-md-5 col-lg-5" >
                            <label>Earnings</label>
                            <label class="pull-right" >Amount</label>
                        </th>
                        <th></th>
                        <th colspan="2" class="col-sm-5 col-md-5 col-lg-5" >
                            <label>Deduction</label>
                            <label class="pull-right" >Amount</label>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Basic pay per day</td>
                        <td class="text-right uang" id="label-basic-pay" >{{$data->basicpay}}</td>
                        <td></td>
                        <td>Potongan Bon</td>
                        <td class="text-right col-sm-2 col-md-2 col-lg-2" >
                            <input type="text" class="form-control uang text-right" name="input_potongan" id="input-potongan" value="{{$data->potongan_bon}}" >
                        </td>
                    </tr>
                    <tr>
                        <td>Day work</td>
                        <td class="text-right" id="label-day-work" >{{(int)$data->daywork}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr style="background-color: whitesmoke;" >
                        <td>
                            <label>Total Earnings</label>
                        </td>
                        <td id="label-total-pendapatan" class="uang text-right" >{{$data->daywork * $data->basicpay}}</td>
                        <td></td>
                        <td>
                            <label>Total Deductions</label>
                        </td>
                        <td id="label-total-potongan" class="uang text-right" >{{$data->potongan_bon}}</td>
                    </tr>
                    <tr style="background-color: whitesmoke;" >
                        <td  ></td>
                        <td></td>
                        <td ></td>
                        <td style="border-top: solid darkgrey thin;" >
                            <label><h4>Net Pay</h4></label>
                        </td>
                        <td style="border-top: solid darkgrey thin;" class="text-right" >
                            <h4 class="uang" id="label-net-pay">{{$data->saldo}}</h4>
                        </td>
                    </tr>
                    {{-- <tr></tr> --}}

                </tbody>
            </table>
        </div>
        <div class="box-footer  " >
            <button type="submit" class="btn btn-primary" id="btn-save" >Save</button>
            <a class="btn btn-danger" id="btn-cancel-save" href="payroll/staff" >Close</a>
        </div>
    </div>

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

    // START DATE CHANGE
    $('input[name=start_date]').change(function(){
      // sett start date on end_date
      $('input[name=end_date]').datepicker('remove');
      $('input[name=end_date]').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        startDate : $('input[name=start_date]').val()
      });
    });
    // END OF START DATE CHANGE

    // SET AUTOCOMPLETE STAFF
    $('input[name=staff]').autocomplete({
        serviceUrl: 'api/get-auto-complete-staff',
        params: {  'nama': function() {
                        return $('input[name=staff]').val();
                    }
                },
        onSelect:function(suggestions){
            // set data staff
            $('input[name=staff]').data('staffid',suggestions.data);

            // get data pekerjaan
            // fillSelectPekerjaan(suggestions.data);

            // // enablekan select pekerjaan
            // $('select[name=pekerjaan]').removeAttr('disabled');
            // $('#btn-add-pekerjaan').removeAttr('disabled');

            //set data pekerjaan id
            $('form[name=form_create_pekerjaan] input[name=staff_id]').val(suggestions.data);
        }

    });

    // format auto numeric uang
      $('.uang').autoNumeric('init',{
          vMin:'0',
          vMax:'9999999999'
      });

      $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
      });


    // GET DATA DELIVERY DRIVER
    $('#btn-show').click(function(){
      var staff_id = $('input[name=staff]').data('staffid');
      var staff = $('input[name=staff]').val();
      var tanggal = $('input[name=payment_date]').val();
      var start_date = $('input[name=start_date]').val();
      var end_date = $('input[name=end_date]').val();

      if(staff_id != "" && staff != "" && tanggal != "" && start_date != "" && end_date != ""){

        var url = 'payroll/staff/get-workday/' + staff_id + "/" + start_date + "/" + end_date;

        $.get(url,null,function(res){
            var data_do = JSON.parse(res);
            
            // tampilkan data ke table payroll
            $('#label-basic-pay').autoNumeric('set',data_do.gaji_pokok);
            $('#label-day-work').text(data_do.daywork);

            $('#label-total-pendapatan').autoNumeric('set',data_do.gaji_pokok * data_do.daywork);
            $('#label-net-pay').autoNumeric('set',data_do.gaji_pokok * data_do.daywork);

            // disable input
          $('input[name=staff]').attr('readonly','readonly');
          $('input[name=payment_date]').attr('readonly','readonly');
          $('input[name=start_date]').attr('readonly','readonly');
          $('input[name=end_date]').attr('readonly','readonly');
          $('#btn-show').attr('disabled','disabled');

            // tampilkan detai payroll
            $('.data-payroll').removeClass('hide');

            // focus ke input potongan
            $('input[name=input_potongan]').focus();


          // // BERSIHKAN DATA TOTAL & potongan
          // $('.label-total').text('');
          // $('.label-saldo').text('');
          // $('input[name=potongan_bahan]').val('');
          // $('input[name=potongan_bon]').val('');
          // $('input[name=sisa_bayaran]').val('');
          // $('input[name=input_dp]').val('');

          // // tampilkan table data attendance
          // $('.').removeClass('hide');
          // $('.').fadeIn(250);

        });
        // location.href = url;
      }else{
        alert('Lengkapi data yang kosong.');
      }
    });

    // KALKULASI JUMLAH
    $(document).on('keyup','#input-potongan',function(){
        var basic_pay = $('#label-basic-pay').autoNumeric('get');
        var daywork = $('#label-day-work').text();
        var potongan = $(this).autoNumeric('get');

        if(potongan > (basic_pay * daywork)){
            alert('Jumlah potongan melebihi total pendapatan');
            $(this).autoNumeric('set',0);
            $('#label-net-pay').autoNumeric('set',basic_pay * daywork);
        }else{
            var net_pay = basic_pay * daywork - potongan;

            $('#label-net-pay').autoNumeric('set',net_pay);
            $('#label-total-potongan').autoNumeric('set',potongan);

        }

        
      // var harga = $(this).autoNumeric('get');
      // var kalkulasi = $(this).data('kalkulasi');
      // var jumlah = 0;
      // if(kalkulasi == 'K'){
      //   var vol = $(this).parent().prev().prev().prev().text();
      //   jumlah = Number(vol) * Number(harga);
      // }else if(kalkulasi == 'T'){
      //   var netto = $(this).parent().prev().prev().text();
      //   jumlah = Number(netto) * Number(harga);
      // }else{
      //   var qty = $(this).parent().prev().text();
      //   jumlah = Number(qty) * Number(harga);
      // }

      // // tampilkan jumlah
      // $(this).parent().next().autoNumeric('set',jumlah);

      // // hitung total
      // hitungTotal();
    });

    

    // input potongan keyup
    // $(document).on('keyup','.input-potongan',function(){
    //   hitungTotal();
    // });

    // SAVE PAYROLL
    $('#btn-save').click(function(){
      var payroll = {
                      "payroll_id":"",
                      "karyawan_id":"",
                       "start_date":"",
                       "end_date":"",
                       "payment_date":"",
                       "basic_pay":"",
                       "daywork":"",
                       "net_pay":"",
                       "potongan_bon":"",
                   };
      payroll.payroll_id = $('input[name=payroll_id]').val();
      payroll.karyawan_id = $('input[name=staff]').data('staffid');
      payroll.payment_date = $('input[name=payment_date]').val();
      payroll.start_date = $('input[name=start_date]').val();
      payroll.end_date = $('input[name=end_date]').val();
      payroll.basic_pay = $('#label-basic-pay').autoNumeric('get');
      payroll.daywork = $('#label-day-work').text();
      payroll.potongan_bon = $('input[name=input_potongan').autoNumeric('get');
      payroll.net_pay = $('#label-net-pay').autoNumeric('get');
      

      // alert(JSON.stringify(payroll));
      
      // submitting data
      // if(){
        var newform = $('<form>').attr('method','POST').attr('action','payroll/staff/update');
        newform.append($('<input>').attr('type','hidden').attr('name','payroll').val(JSON.stringify(payroll)));
        // newform.append($('<input>').attr('type','hidden').attr('name','payroll_detail').val(JSON.stringify(payroll_detail)));
        newform.submit();
      // }else{
      //   alert('Lengkapi data yang kosong.');
      // }
        

      // alert(JSON.stringify(payroll_detail));


    });

    // VALIDATE PAYROLL
    $('#btn-validate').click(function(){
      // alert('oj');
      location.href = $(this).data('href');
    });

})(jQuery);
</script>
@append
