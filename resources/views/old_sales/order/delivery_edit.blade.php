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
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="sales/order" >Sales Orders</a> 
         <i class="fa fa-angle-double-right" ></i> 
         <a href="sales/order/edit/{{$data->sales_order_id}}" >{{$data->order_number}}</a> 
         <i class="fa fa-angle-double-right" ></i> 
         <a href="sales/order/delivery/{{$data->sales_order_id}}" >Delivery Orders</a> 
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
                <a  class="btn btn-primary" href="sales/order/delivery/validate/{{$data->id}}" >Validate</a>
                <a class="btn btn-success" >Print</a>
            @else
                
                {{-- Form Header --}}
                <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->delivery_order_number}}</h3></label>
            @endif

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Validated</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled {{$data->status == 'O' ? 'bg-blue' : 'bg-gray'}}" >Open</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled {{$data->status == 'D' ? 'bg-blue' : 'bg-gray'}}"" >Draft</a>
        </div>
        <div class="box-body">
            
            @if($data->status != 'D')
            {{-- Form Header --}}
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->delivery_order_number}}</h3></label>
            @endif

            <input type="hidden" name="delivery_order_id" value="{{$data->id}}">
            <table class="table" >
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
                            <input type="text" name="delivery_date" class="form-control input-date" value="{{$data->delivery_date_formatted}}">
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            <label>Armada/Driver</label>
                        </td>
                        <td colspan="3" >
                            <input type="text" name="armada" class="form-control" value="{{ $data->armada ? '['.$data->kode_armada . '] ' . $data->armada . ' - ' . $data->nopol . ' - [' . $data->kode_karyawan . '] ' . $data->karyawan  : ''}}" autofocus>
                            <input type="hidden" name="armada_id" value="{{$data->armada_id}}" >
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Lokasi Galian</label>
                        </td>
                        <td>
                            <input type="text" name="lokasi_galian" class="form-control" value="{{$data->lokasi_galian ?  '[' . $data->kode_lokasi_galian . '] ' . $data->lokasi_galian : ''}}" >
                            <input type="hidden" name="lokasi_galian_id" value="{{$data->lokasi_galian_id}}" >
                        </td>
                        <td>
                            <label>Alamat Pengiriman</label>
                        </td>
                        <td>
                            <input type="text" name="alamat" class="form-control" value="{{$data->alamat}}" >
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            <label>Provinsi</label>
                        </td>
                        <td>
                            <input type="text" name="provinsi" class="form-control" value="{{$data->provinsi}}">
                            <input type="hidden" name="provinsi_id" value="{{$data->provinsi_id}}" >
                        </td>
                        <td>
                            <label>Kabupaten</label>
                        </td>
                        <td>
                            <input type="text" name="kabupaten" class="form-control" value="{{$data->kabupaten}}" >
                            <input type="hidden" name="kabupaten_id" class="form-control" value="{{$data->kabupaten_id}}" >
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            <label>Kecamatan</label>
                        </td>
                        <td>
                            <input type="text" name="kecamatan" class="form-control" value="{{$data->kecamatan}}" >
                            <input type="hidden" name="kecamatan_id" value="{{$data->kecamatan_id}}" >
                        </td>
                        <td>
                            <label>Desa</label>
                        </td>
                        <td>
                            <input type="text" name="desa" class="form-control" value="{{$data->desa}}" >
                            <input type="hidden" name="desa_id" value="{{$data->desa_id}}" >
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" >
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan" >{{$data->keterangan}}</textarea>
                        </td>
                    </tr>
                    {{-- <tr>
                        <td class="col-lg-2">
                            <label>Salesperson</label>
                        </td>
                        <td class="col-lg-4" >
                            <input type="text" name="salesperson" class="form-control " data-salespersonid="" required >
                        </td>
                        <td class="col-lg-2" ></td>
                        <td class="col-lg-2 hide" >
                            <label>Jatuh Tempo</label>
                        </td>
                        <td class="col-lg-2 hide" >
                            <input type="text" name="jatuh_tempo"  class="input-tanggal form-control" value="" >
                        </td>
                    </tr> --}}
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
            <a class="btn btn-danger" id="btn-cancel-save" href="sales/order/delivery/{{$data->sales_order_id}}" >Cancel</a>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->

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
        var alamat = $('input[name=alamat]').val();
        var provinsi_id = $('input[name=provinsi_id]').val();
        var kabupaten_id = $('input[name=kabupaten_id]').val();
        var kecamatan_id = $('input[name=kecamatan_id]').val();
        var desa_id = $('input[name=desa_id]').val();
        var keterangan = $('input[name=keterangan]').val();
        var delivery_date = $('input[name=delivery_date]').val();

        if(armada_id != ""
            && lokasi_galian_id != ""
            && alamat != ""
            && provinsi_id != ""
            && kabupaten_id != ""
            && kecamatan_id != ""
            && desa_id != ""
            ){

            var doForm = $('<form>').attr('method','POST').attr('action','sales/order/delivery/update');
            doForm.append($('<input>').attr('type','hidden').attr('name','delivery_order_id').val(delivery_order_id));
            doForm.append($('<input>').attr('type','hidden').attr('name','armada_id').val(armada_id));
            doForm.append($('<input>').attr('type','hidden').attr('name','lokasi_galian_id').val(lokasi_galian_id));
            doForm.append($('<input>').attr('type','hidden').attr('name','alamat').val(alamat));
            doForm.append($('<input>').attr('type','hidden').attr('name','provinsi_id').val(provinsi_id));
            doForm.append($('<input>').attr('type','hidden').attr('name','kabupaten_id').val(kabupaten_id));
            doForm.append($('<input>').attr('type','hidden').attr('name','kecamatan_id').val(kecamatan_id));
            doForm.append($('<input>').attr('type','hidden').attr('name','desa_id').val(desa_id));
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

    // // CANCEL DO EDIT
    // $('#btn-cancel-save').click(function(){
    //     if(confirm('Anda akan membatalkan proses ini?')){

    //     }else{
    //         return false;
    //     }
    // });
    // // END OF CANCEL DO EDIT

})(jQuery);
</script>
@append