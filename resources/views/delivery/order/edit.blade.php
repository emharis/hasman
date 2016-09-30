@extends('layouts.master')

@section('styles')
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
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
        {{$data->delivery_order_number}} 
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            
            @if($data->status != 'D')
                <a  class="btn btn-primary btn-sm" href="delivery/order/validate/{{$data->id}}" id="btn-validate" >Validate</a>
                <a class="btn btn-success btn-sm" >Print</a>
            @else
                
                {{-- Form Header --}}
                <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->delivery_order_number}}</h3></label>
            @endif

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Validated</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled {{$data->status == 'O' ? 'bg-blue' : 'bg-gray'}}" >Open</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled {{$data->status == 'D' ? 'bg-blue' : 'bg-gray'}}" >Draft</a>
        </div>
        <div class="box-body">
            
            @if($data->status != 'D')
            {{-- Form Header --}}
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->delivery_order_number}}</h3></label>
            @endif

            <input type="hidden" name="delivery_order_id" value="{{$data->id}}">
            <input type="hidden" name="delivery_order_number" value="{{$data->delivery_order_number}}">
            <table class="table" id="table-do-master" >
                <tbody>
                    <tr>
                        <td class="col-lg-2">
                            <label>Sales Order Number</label>
                        </td>
                        <td class="col-lg-4" >
                            {{$data->order_number}}
                        </td>
                        <td class="col-lg-2" >
                            <label>Order Date</label>
                        </td>
                        <td class="col-lg-4" >
                            {{$data->order_date_formatted}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Customer</label>
                        </td>
                        <td>
                            {{'[' . $data->kode_customer .'] ' . $data->customer}}
                        </td>
                        <td>
                            <label>Delivery Date</label>
                        </td>
                        <td>
                            <input type="text" name="delivery_date" class="form-control input-date" value="{{$data->delivery_date_formatted ? $data->delivery_date_formatted : date('d-m-Y')}}">
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            <label>Pekerjaan</label>
                        </td>
                        <td>
                            {{$data->pekerjaan}}<br/>
                            {{$data->alamat_pekerjaan .', ' . $data->desa . ', ' . $data->kecamatan}} <br/>
                            {{$data->kabupaten . ', ' . $data->provinsi }}
                        </td>
                        <td>
                            <label>Lokasi Galian</label>
                        </td>
                        <td>
                            <input type="text" name="lokasi_galian" class="form-control" value="{{$data->lokasi_galian ?  '[' . $data->kode_lokasi_galian . '] ' . $data->lokasi_galian : ''}}" autofocus >
                            <input type="hidden" name="lokasi_galian_id" value="{{$data->lokasi_galian_id}}" >
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Armada/Driver</label>
                        </td>
                        <td colspan="3" >
                            <input type="text" name="armada" class="form-control" value="{{ $data->armada ? '['.$data->kode_armada . '] ' . $data->armada . ' - ' . $data->nopol . ' - [' . $data->kode_karyawan . '] ' . $data->karyawan  : ''}}" >
                            <input type="hidden" name="armada_id" value="{{$data->armada_id}}" >
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" >
                            <textarea maxlength="250" name="keterangan" class="form-control" rows="2" placeholder="Keterangan" >{{$data->keterangan}}</textarea>
                        </td>
                    </tr>
                    
                </tbody>
            </table>

            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

            <table id="table-product" class="table table-bordered table-condensed" >
                <thead>
                    <tr>
                        <th style="width:25px;" >NO</th>
                        <th  >MATERIAL</th>
                        <th class="col-lg-1" >QUANTITY</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>{{'[' .$data->kode_material . '] ' . $data->material}}</td>
                        <td>1</td>
                    </tr>
                </tbody>
            </table>

        </div><!-- /.box-body -->
        <div class="box-footer" >
            <button type="submit" class="btn btn-primary" id="btn-save" >Save</button>
            <a class="btn btn-danger" id="btn-cancel-save" href="{{URL::previous()}}" >Cancel</a>
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
          <form name="form_create_pekerjaan" method="POST" action="delivery/order/to-validate" >
            <input type="hidden" name="delivery_id" value="{{$data->id}}"  >
            <div class="modal-body">
                <table class="table table-bordered table-condensed" id="table-kalkulasi" >
                    <tbody>
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
                                <input type="text" name="volume" class="form-control text-right " disabled>
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
                                <input type="text" name="netto" class="form-control text-right" disabled>
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
                                <input type="text" name="total" class="form-control text-right" disabled>
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
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {
    // HIDE SOME ELEMENT
    $('.row-tonase, .row-kubikasi, .row-price').hide();
    $('select[name=kalkulasi]').val([]);

    // SET AUTONUMERIC
    $('input[name=panjang], input[name=lebar], input[name=tinggi], input[name=gross], input[name=tarre], input[name=volume], input[name=netto]').autoNumeric('init');

    $('input[name=unit_price], input[name=total]').autoNumeric('init',{
        vMin:'0',
        vMax:'9999999999'
    });
    // END OF SET AUTONUMERIC

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
            $('input[name=provinsi_id]').val(suggestions.data);
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
                        return $('input[name=provinsi_id]').val();
                    },
                    
                },
        onSelect:function(suggestions){
            // // set data supplier
            $('input[name=kabupaten_id]').val(suggestions.data);
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
                        return $('input[name=kabupaten_id]').val();
                    },
                    
                },
        onSelect:function(suggestions){
            // // set data supplier
            $('input[name=kecamatan_id]').val(suggestions.data);
        }

    });
    // END OF SET AUTOCOMPLETE KECAMATAN

    // SET AUTOCOMPLETE DESA
    $('input[name=desa]').autocomplete({
        serviceUrl: 'api/get-auto-complete-desa',
        params: {  
                    'nama': function() {
                        return $('input[name=desa]').val();
                    },
                    'kecamatan_id': function() {
                        return $('input[name=kecamatan_id]').val();
                    },
                    
                },
        onSelect:function(suggestions){
            // // set data supplier
            $('input[name=desa_id]').val(suggestions.data);
            // alert($('input[name=desa]').data('id'));

        }

    });
    // END OF SET AUTOCOMPLETE DESA

    // SET AUTOCOMPLETE LOKASI GALIAN
    $('input[name=lokasi_galian]').autocomplete({
        serviceUrl: 'api/get-auto-complete-lokasi-galian',
        params: {  'nama': function() {
                        return $('input[name=lokasi_galian]').val();
                    }
                },
        onSelect:function(suggestions){
            // set data customer
            $('input[name=lokasi_galian_id]').val(suggestions.data);
        }

    });
    // END OF SET AUTOCOMPLETE LOKASI GALIAN

    // SET AUTOCOMPLETE ARMADA/DRIVER
    $('input[name=armada]').autocomplete({
        serviceUrl: 'api/get-auto-complete-armada',
        params: {  'nama': function() {
                        return $('input[name=armada]').val();
                    }
                },
        onSelect:function(suggestions){
            // set data customer
            $('input[name=armada_id]').val(suggestions.data);
        }

    });
    // END OF SET AUTOCOMPLETE ARMADA/DRIVER

    // SAVE DELIVERY ORDER
    $('#btn-save').click(function(){
        var delivery_order_id = $('input[name=delivery_order_id]').val();
        var armada_id = $('input[name=armada_id]').val();
        var lokasi_galian_id = $('input[name=lokasi_galian_id]').val();
        var keterangan = $('textarea[name=keterangan]').val();
        var delivery_date = $('input[name=delivery_date]').val();

        if(armada_id != ""
            && lokasi_galian_id != ""
            && delivery_date != ""
            // && alamat != ""
            // && provinsi_id != ""
            // && kabupaten_id != ""
            // && kecamatan_id != ""
            // && desa_id != ""
            ){

            var doForm = $('<form>').attr('method','POST').attr('action','delivery/order/update');
            doForm.append($('<input>').attr('type','hidden').attr('name','delivery_order_id').val(delivery_order_id));
            doForm.append($('<input>').attr('type','hidden').attr('name','armada_id').val(armada_id));
            doForm.append($('<input>').attr('type','hidden').attr('name','lokasi_galian_id').val(lokasi_galian_id));
            doForm.append($('<input>').attr('type','hidden').attr('name','keterangan').val(keterangan));
            doForm.append($('<input>').attr('type','hidden').attr('name','delivery_date').val(delivery_date));
            doForm.submit();

        }else{
            alert('Lengkapi data yang kosong.');
        }

        
    });
    // END OF SAVE DELIVERY ORDER

    // SET DATEPICKER
    $('.input-date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
    });
    // END OF SET DATEPICKER

    // VALIDATE DELOIVERY ORDER
    $('#btn-validate').click(function(){
        // set auto kode no nota
        $('input[name=no_nota_timbang]').val('CUST/'+$('input[name=delivery_order_number]').val());

        $('#modal-validate').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#modal-validate input[name=no_nota_timbang]').focus();

        return false;
    });
    // END OF VALIDATE DELOIVERY ORDER

    // KALKULASI DO
    $('select[name=kalkulasi]').change(function(){
        // clear input
        $('#table-kalkulasi input:not([name=no_nota_timbang])').val('');

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

})(jQuery);
</script>
@append