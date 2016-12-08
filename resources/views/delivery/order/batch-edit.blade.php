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

    #table-do-master tr td{
        vertical-align: top;
    }

</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="delivery/order" >Delivery Orders</a> 
        <i class="fa fa-angle-double-right" ></i> 
        Batch Edit 
        <i class="fa fa-angle-double-right" ></i> 
        {{$sales_order->order_number}}
        
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >Batch Edit : {{$sales_order->order_number}}</h3></label>
        </div>
        <div class="box-body">
            <table class="table" id="table-do-master" >
                <tbody>
                    <tr>
                        <td class="col-lg-2">
                            <label>SO Ref#</label>
                        </td>
                        <td class="col-lg-4" >
                            {{$sales_order->order_number}}
                        </td>
                        <td class="col-lg-2" >
                            <label>SO Date</label>
                        </td>
                        <td class="col-lg-4" id="label-sales-order-date" >
                            {{$sales_order->order_date_formatted}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Customer</label>
                        </td>
                        <td>
                            {{ $sales_order->customer}}
                        </td>
                        <td>
                            <label>Pekerjaan</label>
                        </td>
                        <td>
                            @if($sales_order->pekerjaan)
                                {{$sales_order->pekerjaan}}<br/>
                                @if($sales_order->alamat_pekerjaan != '')
                                    {{$sales_order->alamat_pekerjaan}}
                                @endif
                                @if($sales_order->desa != "" )
                                 {{', ' . $sales_order->desa . ', ' . $sales_order->kecamatan}} <br/>
                                {{$sales_order->kabupaten . ', ' . $sales_order->provinsi }}
                                @endif
                            @else
                                -
                            @endif
                            {{-- <input type="text" name="delivery_date" class="form-control input-date" value=""> --}}
                        </td>
                        
                    </tr>
                    
                </tbody>
            </table>

            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>DATA DELIVERY ORDER</strong></h4>

            <table class="table table-bordered table-condensed" id="table-data-do" >
                <thead>
                    <tr>
                        <th style="width: 25px;">NO</th>
                        <th  >DO REF#</th>
                        <th  >MATERIAL</th>
                        <th >STATUS</th>
                        <th >DELIVERY DATE</th>
                        <th class="col-sm-3" >ARMADA</th>
                        <th class="col-sm-3" >LOKASI GALIAN</th>
                        <th  ></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rownum=1; ?>

                    @foreach($delivery_order as $dt)
                        <?php $readonly = ($dt->status == 'V' || $dt->status == 'DN') ? 'disabled':''; ?>
                        <tr class="do-row" data-id="{{$dt->id}}" data-donumber="{{$dt->delivery_order_number}}" >
                            <td>
                                {{$rownum++}}
                            </td>
                            <td>
                                {{$dt->delivery_order_number}}
                            </td>
                            <td>
                                {{$dt->material}}
                            </td>
                            <td class="text-center">
                                 @if($dt->status == 'D')
                                    <label class="label label-warning" >DRAFT</label>
                                @elseif($dt->status == 'O')
                                    <label class="label label-primary" >OPEN</label>
                                @elseif($dt->status == 'V')
                                    <label class="label bg-maroon" >VALIDATED</label>
                                @elseif($dt->status == 'DN')
                                    <label class="label label-success" >DONE</label>
                                @else
                                    <label class="label label-danger" >ERROR</label>
                                @endif
                            </td>
                            <td>
                                <input type="text" name="delivery_date" class="form-control" value="{{$dt->delivery_date == "" ? $dt->order_date_formatted : $dt->delivery_date_formatted }}" {{$readonly}} >
                            </td>
                            <td>
                                {{-- <input type="text" name="armada" class="form-control" data-id="{{$dt->armada_id != "" ? $dt->armada_id   : ''}}" value="{{$dt->armada != "" ? '['. $dt->kode_armada . '] ' . $dt->nopol .' - [' . $dt->kode_karyawan .'] ' . $dt->karyawan : ''}}" {{$readonly}}> --}}
                                
                                {{-- <input type="text" name="armada" class="form-control" data-id="{{$dt->armada_id != "" ? $dt->armada_id   : ''}}" value="{{$dt->armada != "" ?  $dt->nopol . ' - ' . $dt->karyawan : ''}}" {{$readonly}}> --}}

                                {!! Form::select('armada',$selectArmada,null,['class'=>'form-control','data-selected'=>$dt->armada_id]) !!}
                            </td>
                            <td>
                                {{-- <input type="text" name="lokasi_galian" class="form-control" data-id="{{$dt->lokasi_galian_id != "" ? $dt->lokasi_galian_id   : ''}}" value="{{$dt->lokasi_galian_id != "" ? '['. $dt->kode_lokasi_galian . '] ' . $dt->lokasi_galian : ''}}" {{$readonly}} > --}}
                                
                                {{-- <input type="text" name="lokasi_galian" class="form-control" data-id="{{$dt->lokasi_galian_id != "" ? $dt->lokasi_galian_id   : ''}}" value="{{$dt->lokasi_galian_id != "" ? $dt->lokasi_galian : ''}}" {{$readonly}} > --}}

                                {!! Form::select('lokasi_galian',$selectGalian,null,['class'=>'form-control','data-selected'=>$dt->lokasi_galian_id]) !!}
                            </td>
                            <td class="text-center" >
                                {{-- @if($dt->status != 'V' && $dt->status != 'DN')
                                    <a class="btn btn-primary btn-xs btn-save-do" data-toggle="tooltip" title="Save" ><i class="fa fa-save" ></i></a>
                                @endif --}}

                                
                                    <a class="btn bg-maroon btn-xs btn-validate-do {{$dt->status == 'O' ? '' : 'hide'}}" data-toggle="tooltip" title="Validate" ><i class="fa fa-check" ></i></a>
                                {{-- @endif --}}

                                {{-- @if($dt->status == 'V' || $dt->status == 'DN') --}}
                                    <a class="btn btn-success btn-xs btn-print-do {{($dt->status == 'V' || $dt->status == 'DN') ? '' : 'hide'}}" data-toggle="tooltip" title="Cetak Surat Jalan" ><i class="fa fa-print" ></i></a>
                                {{-- @endif --}}
                                
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div><!-- /.box-body -->
        <div class="box-footer" >
            <button type="submit" class="btn btn-primary" id="btn-save" >Save</button>
            <a class="btn btn-danger" id="btn-cancel-save" href="delivery/order" >Close</a>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->

<div class="example-modal">
    <div class="modal" id="modal-validate">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button> --}}
            <h4 class="modal-title">Validate Delivery Order</h4>
          </div>
          <form name="form_validate" method="POST" action="delivery/order/to-validate" >
            <input type="hidden" name="delivery_id" value=""  >
            <div class="modal-body">
                <table class="table table-bordered table-condensed" id="table-kalkulasi" >
                    <tbody>
                        <tr>
                            <td><label>DO Ref#</label></td>
                            <td>
                                <input type="text" autocomplete="off" name="do_number" readonly class="form-control"  >
                            </td>
                        </tr>
                        <tr>
                            <td><label>No Nota</label></td>
                            <td>
                                <input type="text" autocomplete="off" name="no_nota_timbang" class="form-control" value="CUST/" >
                            </td>
                        </tr>
                        <tr>
                            <td><label>Kalkulasi</label></td>
                            <td>
                                <select name="kalkulasi" class="form-control" >
                                    <option value="R" >Ritase</option>
                                    <option value="K" >Kubikasi</option>
                                    <option value="T" >Tonase</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="row-kubikasi" >
                            <td>
                                <label>Panjang</label>
                                </td>
                            <td>
                                <input type="text" name="panjang" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-kubikasi" >
                            <td><label>Lebar</label></td>
                            <td>
                                <input type="text" name="lebar" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-kubikasi" >
                            <td><label>Tinggi</label></td>
                            <td>
                                <input type="text" name="tinggi" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-kubikasi" >
                            <td><label>Volume</label></td>
                            <td>
                                <input type="text" name="volume" class="form-control text-right " readonly>
                            </td>
                        </tr>
                        <tr class="row-tonase" >
                            <td><label>Gross</label></td>
                            <td>
                                <input type="text" name="gross" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-tonase" >
                            <td><label>Tarre</label></td>
                            <td>
                                <input type="text" name="tarre" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-tonase" >
                            <td><label>Netto</label></td>
                            <td>
                                <input type="text" name="netto" class="form-control text-right" readonly>
                            </td>
                        </tr>
                        <tr class="row-price" >
                            <td>
                                <label>Unit Price</label>
                            </td>
                            <td>
                                <input type="text" name="unit_price" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-price" >
                            <td>
                                <label>Total</label>
                            </td>
                            <td>
                                <input type="text" name="total" class="form-control text-right" readonly>
                            </td>
                        </tr>

                    </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
              </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
<!-- /.modal -->
</div>


@stop

@section('scripts')
{{-- <script src="plugins/datatables/jquery.dataTables.min.js"></script> --}}
{{-- <script src="plugins/datatables/dataTables.bootstrap.min.js"></script> --}}
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<script src="plugins/select2/select2.full.min.js"></script>

<script type="text/javascript">
(function ($) {

    $('select[name=armada]').each(function(){
        if($(this).data('selected') == undefined || $(this).data('selected') == ''){
            $(this).val([]);
        }else{
            $(this).val($(this).data('selected'));
        }
        
    });

    $('select[name=lokasi_galian]').each(function(){
        if($(this).data('selected') == undefined || $(this).data('selected') == ''){
            $(this).val($(this).data('selected'));
        }
        
    });

    // format select2
    $('select[name=armada]').select2();
    $('select[name=lokasi_galian]').select2();

    var startDate = '{{$sales_order->order_date_formatted}}';
    $('input[name=delivery_date]').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        startDate: startDate
    });
    
    // $('input[name=delivery_date]').datepicker('setDate',startDate);

    // alert($('input[name=delivery_date]').datepicker('getStartDate'));

    $('input[name=lokasi_galian]').each(function(){
        var input_lokasi_galian = $(this);
        input_lokasi_galian.autocomplete({
            serviceUrl: 'api/get-auto-complete-lokasi-galian',
            params: {  'nama': function() {
                            return input_lokasi_galian.val();
                        }
                    },
            onSelect:function(suggestions){
                // set data customer
                // input_lokasi_galian.val(suggestions.data);
                $(this).data('id',suggestions.data);
            }

        });
        
    });

    $('input[name=armada]').each(function(){
        var input_armada = $(this);

        input_armada.autocomplete({
            serviceUrl: 'api/get-auto-complete-armada',
            params: {  'nama': function() {
                            return input_armada.val();
                        }
                    },
            onSelect:function(suggestions){
                // set data customer
                // $('input[name=armada_id]').val(suggestions.data);
                $(this).data('id',suggestions.data);
            }

        });
    });


    // $delivery_date = $req->delivery_date;
    //         $arr_tgl = explode('-',$delivery_date);
    //         $fix_delivery_date = new \DateTime();
    //         $fix_delivery_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 

    //         \DB::table('delivery_order')
    //             ->where('id',$req->delivery_order_id)
    //             ->update([
    //                     'armada_id' => $req->armada_id,
    //                     'lokasi_galian_id' => $req->lokasi_galian_id,
    //                     'keterangan' => $req->keterangan,
    //                     'status' => 'O',
    //                     'delivery_date' => $fix_delivery_date,
    //                 ]);

    // SAVE DO
    // $('.btn-save-do').click(function(){
    //     var btn = $(this);
    //     var row = btn.parent().parent();
    //     var data_id = row.data('id');

    //     var delivery_date = row.find('input[name=delivery_date]').val();
    //     var armada_id = row.find('input[name=armada]').data('id');
    //     var lokasi_galian_id = row.find('input[name=lokasi_galian]').data('id');

    //     // var newform = $('<form>').attr('method','POST').attr('action','delivery/order/update');
    //     // newform.append($('<input>').attr('type','hidden').attr('name','delivery_order_id').val(data_id));
    //     // newform.append($('<input>').attr('type','hidden').attr('name','delivery_date').val(delivery_date));
    //     // newform.append($('<input>').attr('type','hidden').attr('name','armada_id').val(armada_id));
    //     // newform.append($('<input>').attr('type','hidden').attr('name','lokasi_galian_id').val(lokasi_galian_id));
    //     // newform.append($('<input>').attr('type','hidden').attr('name','keterangan').val(''));
    //     // newform.submit();
        
    //     // // save do
    //     $.post('delivery/order/update',{
    //         delivery_order_id : data_id,
    //         delivery_date : delivery_date,
    //         armada_id : armada_id,
    //         lokasi_galian_id : lokasi_galian_id,
    //         keterangan : ''
    //     }).done(function(res){
    //         alert('done');
    //         alert(res);
    //     }).error(function(res){
    //         alert('error');
    //         alert(res);
    //     });
    // });

    
    

    // SAVE ALL WITH BATCH SAVE
    $('#btn-save').click(function(){
        var data_do = JSON.parse('{"do" : [] }');
        $('.do-row').each(function(){

            var row = $(this);

            // alert(row.find('input[name=armada]').data('id'));

            data_do.do.push({
                        id : row.data('id'),
                        armada_id : row.find('select[name=armada]').val(),
                        delivery_date : row.find('input[name=delivery_date]').val(),
                        lokasi_galian_id : row.find('select[name=lokasi_galian]').val(),
                        keterangan : ''
                    });
        });

        // alert(JSON.stringify(data_do.do));

       var newform = $('<form>').attr('action','delivery/order/batch-update').attr('method','POST');
       newform.append($('<input>').attr('name','data_delivery').attr('type','hidden').val(JSON.stringify(data_do)));
       newform.submit();
    });


    // validate do
    $('.btn-validate-do').click(function(){
        var btn = $(this);
        var row = btn.parent().parent();
        var data_id = row.data('id');
        var do_number = row.data('donumber');

        // set auto kode no nota
        $('input[name=no_nota_timbang]').val('CUST/'+do_number);
        $('input[name=do_number]').val(do_number);
        $('input[name=delivery_id]').val(data_id);

        autonumericIt();

        $('#modal-validate').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#modal-validate input[name=no_nota_timbang]').focus();

        return false;
    });

    // ----------- MODAL VALIDATE --------------------

    // AUTONUMERCI INPUT KALKULASI
    function autonumericIt(){
        $('input[name=panjang], input[name=lebar], input[name=tinggi], input[name=volume], input[name=gross], input[name=tarre], input[name=netto], input[name=unit_price], input[name=total]').autoNumeric('init',{
                vMin:'0.00',
                vMax:'9999999999.00'
            });
    }
    autonumericIt();

    // HIDE SOME ELEMENT
    $('.row-tonase, .row-kubikasi, .row-price').hide();
    $('select[name=kalkulasi]').val([]);

    // KALKULASI DO
    $('select[name=kalkulasi]').change(function(){
        // clear input
        $('#table-kalkulasi input[name=panjang]').val('');
        $('#table-kalkulasi input[name=lebar]').val('');
        $('#table-kalkulasi input[name=tinggi]').val('');
        $('#table-kalkulasi input[name=volume]').val('');
        $('#table-kalkulasi input[name=unit_price]').val('');
        $('#table-kalkulasi input[name=total]').val('');
        $('#table-kalkulasi input[name=gross]').val('');
        $('#table-kalkulasi input[name=tarre]').val('');
        $('#table-kalkulasi input[name=netto]').val('');

        if($(this).val() == 'R'){
            $('.row-kubikasi, .row-tonase').hide();
            $('.row-price').show();
        }else if($(this).val() == 'K'){
            $('.row-kubikasi').show();
            $('.row-tonase').hide();
            $('.row-price').show();
        }else{
            $('.row-kubikasi').hide();
            $('.row-tonase').show();
            $('.row-price').show();
        }
    });
    // END OF KALKULASI DO

    // CALCULATE KUBIKASI
    $('input[name=panjang], input[name=lebar], input[name=tinggi], input[name=unit_price]').keyup(function(){
        
        if($('select[name=kalkulasi]').val() == 'K'){
            var panjang = $('input[name=panjang]').autoNumeric('get');

            var lebar = $('input[name=lebar]').autoNumeric('get');
            // alert(lebar);
            var tinggi = $('input[name=tinggi]').autoNumeric('get');
            // alert(tinggi);
            var volume = Number(panjang) * Number(lebar) * Number(tinggi);
            // alert('volume ' + volume);
            $('input[name=volume]').autoNumeric('set',volume);
            // pembulatan volume
            volume = $('input[name=volume]').autoNumeric('get');

            // hitung total harga
            var price = $('input[name=unit_price]').autoNumeric('get');
            var total = Number(price) * Number(volume);

            $('input[name=total]').autoNumeric('set',total);
        }

        
    });
    // END OF CALCULATE KUBIKASI
    

    // CALCULATE TONASE
    $('input[name=gross], input[name=tarre], input[name=unit_price]').keyup(function(){
        // alert($('select[name=kalkulasi]').val());
        if($('select[name=kalkulasi]').val() == 'T'){
            var gross = $('input[name=gross]').autoNumeric('get');
        
            var tarre = $('input[name=tarre]').autoNumeric('get');
            // alert(lebar);
            var netto = Number(gross) - Number(tarre);
            
            $('input[name=netto]').autoNumeric('set',netto);

            // hitung total harga
            var price = $('input[name=unit_price]').autoNumeric('get');
            var total = Number(price) * Number(netto);

            $('input[name=total]').autoNumeric('set',total);
        }
        
    });
    // END CALCULATE TONASE

    // CALCULATE RITASE
    $('input[name=unit_price]').keyup(function(){
        // alert($('select[name=kalkulasi]').val());
        if($('select[name=kalkulasi]').val() == 'R'){
            var unit_price = $('input[name=unit_price]').autoNumeric('get');

            $('input[name=total]').autoNumeric('set',unit_price);
        }
        
    });
    // END CALCULATE RITASE

    // VALIDATE DELIVERY ORDER DO MENGGUNAKAN AJAX FORM
    $('form[name=form_validate]').ajaxForm({
        beforeSerialize : function($form, options){
            // reset auto numeric
            // alert('destroy auto numeric');
            
            var unit_price = $('form[name=form_validate]').find('input[name=unit_price]').autoNumeric('get');
            $('form[name=form_validate]').find('input[name=unit_price]').autoNumeric('destroy');
            $('form[name=form_validate]').find('input[name=unit_price]').val(unit_price);

            var panjang = $('form[name=form_validate]').find('input[name=panjang]').autoNumeric('get');
            $('form[name=form_validate]').find('input[name=panjang]').autoNumeric('destroy');
            $('form[name=form_validate]').find('input[name=panjang]').val(panjang);  

            var lebar = $('form[name=form_validate]').find('input[name=lebar]').autoNumeric('get');
            $('form[name=form_validate]').find('input[name=lebar]').autoNumeric('destroy');
            $('form[name=form_validate]').find('input[name=lebar]').val(lebar);    

            var tinggi = $('form[name=form_validate]').find('input[name=tinggi]').autoNumeric('get');
            $('form[name=form_validate]').find('input[name=tinggi]').autoNumeric('destroy');
            $('form[name=form_validate]').find('input[name=tinggi]').val(tinggi);  

            var volume = $('form[name=form_validate]').find('input[name=volume]').autoNumeric('get');
            $('form[name=form_validate]').find('input[name=volume]').autoNumeric('destroy');
            $('form[name=form_validate]').find('input[name=volume]').val(volume);  

            var gross = $('form[name=form_validate]').find('input[name=gross]').autoNumeric('get');
            $('form[name=form_validate]').find('input[name=gross]').autoNumeric('destroy');
            $('form[name=form_validate]').find('input[name=gross]').val(gross);  

            var tarre = $('form[name=form_validate]').find('input[name=tarre]').autoNumeric('get');
            $('form[name=form_validate]').find('input[name=tarre]').autoNumeric('destroy');
            $('form[name=form_validate]').find('input[name=tarre]').val(tarre); 

            var netto = $('form[name=form_validate]').find('input[name=netto]').autoNumeric('get');
            $('form[name=form_validate]').find('input[name=netto]').autoNumeric('destroy');
            $('form[name=form_validate]').find('input[name=netto]').val(netto);  

            var total = $('form[name=form_validate]').find('input[name=total]').autoNumeric('get');
            $('form[name=form_validate]').find('input[name=total]').autoNumeric('destroy');
            $('form[name=form_validate]').find('input[name=total]').val(total);            
            

            // $('.number-input-field').removeData('autonumeric');

            // alert('done destroy auto numeric');
            // return false;
        },
        beforeSubmit : function(){
            // close modal 
            $('#modal-validate').modal('toggle');
        },
        success : function(res){
            // clear & reset

            $('#table-kalkulasi select[name=kalkulasi]').val('R');
            $('#table-kalkulasi select[name=kalkulasi]').change();

            // cledar input validate
            $('#table-kalkulasi input[name=panjang]').val('');
            $('#table-kalkulasi input[name=lebar]').val('');
            $('#table-kalkulasi input[name=tinggi]').val('');
            $('#table-kalkulasi input[name=volume]').val('');
            $('#table-kalkulasi input[name=unit_price]').val('');
            $('#table-kalkulasi input[name=total]').val('');
            $('#table-kalkulasi input[name=gross]').val('');
            $('#table-kalkulasi input[name=tarre]').val('');
            $('#table-kalkulasi input[name=netto]').val('');

            var data_id = $('form[name=form_validate]').find('input[name=delivery_id]').val();
            var row = $('#table-data-do tbody tr[data-id=' + data_id + ']');

            // set read only to inputan
            row.find('input:text').attr('disabled','disabled');
            row.find('select').attr('disabled','disabled');

            // sembunyikan tombol validate &
            row.find('.btn-validate-do').addClass('hide');

            // tampilkan tombol print
            row.find('.btn-print-do').removeClass('hide');

            // ganti label status
            row.find('label.label').removeClass('label-primary');
            row.find('label.label').addClass('label-success');
            row.find('label.label').text('DONE');
        }
    });

    // $('form[name=form_validate]').submit(function(){
    //     var delivery_id = $('form[name=form_validate]').find('input[name=delivery_id]').val();
    //     var kalkulasi = $('form[name=form_validate]').find('select[name=kalkulasi]').val();

    //     alert(delivery_id);
    //     alert(kalkulasi);

    //      $('#modal-validate').modal('toggle');

    //     return false;
    // });

    

})(jQuery);
</script>
@append