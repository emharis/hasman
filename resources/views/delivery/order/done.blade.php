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
            
            {{-- @if($data->status != 'D') --}}
                {{-- <a  class="btn btn-danger btn-sm" href="delivery/order/reconcile/{{$data->id}}" id="btn-reconcile" >Reconcile</a> --}}
                <a class="btn btn-success btn-sm" >Print</a>
            {{-- @else --}}
                
                {{-- Form Header --}}
                {{-- <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->delivery_order_number}}</h3></label> --}}
            {{-- @endif --}}

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-blue" >Done</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Validated</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Open</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Draft</a>
        </div>
        <div class="box-body">
            
            @if($data->status != 'D')
            {{-- Form Header --}}
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->delivery_order_number}}</h3></label>
            @endif

            <input type="hidden" name="delivery_order_id" value="{{$data->id}}">
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
                            {{$data->delivery_date_formatted }}
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            <label>Pekerjaan</label>
                        </td>
                        <td>
                             @if($data->pekerjaan)
                                {{$data->pekerjaan}}<br/>
                                @if($data->alamat_pekerjaan != '')
                                    {{$data->alamat_pekerjaan}}
                                @endif
                                @if($data->desa != "" )
                                 {{', ' . $data->desa . ', ' . $data->kecamatan}} <br/>
                                {{$data->kabupaten . ', ' . $data->provinsi }}
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <label>Lokasi Galian</label>
                        </td>
                        <td>
                            {{ '[' . $data->kode_lokasi_galian . '] ' . $data->lokasi_galian }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Armada/Driver</label>
                        </td>
                        <td colspan="3" >
                            {{ '['.$data->kode_armada . '] '  . $data->nopol . ' - [' . $data->kode_karyawan . '] ' . $data->karyawan }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" >
                            <label>Keterangan</label> <br/>
                            {{$data->keterangan}}
                        </td>
                    </tr>
                    
                </tbody>
            </table>

            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

            @if($data->kalkulasi == 'K')
                {{-- TABLE KUBIKASI --}}
                <table id="table-product" class="table table-bordered table-condensed" >
                <thead>
                    <tr>
                        <th rowspan="2" style="width:25px;" >NO</th>
                        <th rowspan="2" >MATERIAL</th>
                        <th colspan="3" class="text-center" >UKURAN</th>
                        <th rowspan="2" >VOLUME</th>
                        <th rowspan="2" >UNIT PRICE</th>
                        <th rowspan="2" >TOTAL</th>
                    </tr>
                    <tr>
                        <th class="text-center" >P</th>
                        <th class="text-center" >L</th>
                        <th class="text-center" >T</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>{{'[' .$data->kode_material . '] ' . $data->material}}</td>
                        <td class="text-right" >{{$data->panjang}}</td>
                        <td class="text-right" >{{$data->lebar}}</td>
                        <td class="text-right">{{$data->tinggi}}</td>
                        <td class="text-right">{{$data->volume}}</td>
                        <td class="text-right">{{number_format($data->unit_price,0,'.',',')}}</td>
                        <td class="text-right">{{number_format($data->total,0,'.',',')}}</td>
                    </tr>
                </tbody>
            </table>
                {{-- END OF TABLE KUBIKASI --}}
            @elseif($data->kalkulasi == 'T')
            {{-- TABLE TONASE --}}
            <table id="table-product" class="table table-bordered table-condensed" >
                <thead>
                    <tr>
                        <th rowspan="2" style="width:25px;" >NO</th>
                        <th rowspan="2" >MATERIAL</th>
                        <th colspan="2" class="text-center" >UKURAN</th>
                        <th rowspan="2" >NETTO</th>
                        <th rowspan="2" >UNIT PRICE</th>
                        <th rowspan="2" >TOTAL</th>
                    </tr>
                    <tr>
                        <th class="text-center" >GROSS</th>
                        <th class="text-center" >TARE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>{{'[' .$data->kode_material . '] ' . $data->material}}</td>
                        <td class="text-right" >{{$data->gross}}</td>
                        <td class="text-right" >{{$data->tarre}}</td>
                        <td class="text-right">{{$data->netto}}</td>
                        <td class="text-right">{{number_format($data->unit_price,0,'.',',')}}</td>
                        <td class="text-right">{{number_format($data->total,0,'.',',')}}</td>
                    </tr>
                </tbody>
            </table>
            {{-- END OF TABLE TONASE --}}
            @else
            {{-- TABLE RITASE --}}
            <table id="table-product" class="table table-bordered table-condensed" >
                <thead>
                    <tr>
                        <th  style="width:25px;" >NO</th>
                        <th >MATERIAL</th>
                        <th  class="text-center" >QTY</th>
                        <th  >UNIT PRICE</th>
                        <th  >TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>{{'[' .$data->kode_material . '] ' . $data->material}}</td>
                        <td class="text-right" >{{$data->qty}}</td>
                        <td class="text-right">{{number_format($data->unit_price,0,'.',',')}}</td>
                        <td class="text-right">{{number_format($data->total,0,'.',',')}}</td>
                    </tr>
                </tbody>
            </table>
            {{-- END OF TABLE RITASE --}}
            @endif

            

        </div><!-- /.box-body -->
        <div class="box-footer" >
            {{-- <button type="submit" class="btn btn-primary" id="btn-save" >Save</button> --}}
            <a class="btn btn-danger" id="btn-cancel-save" href="delivery/order" >Close</a>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->

<div class="example-modal">
    <div class="modal" id="modal-validate">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Validate Delivery Order</h4>
          </div>
          <form name="form_create_pekerjaan" method="POST" action="delivery/order/to-validate" >
            <input type="hidden" name="delivery_id" value="{{$data->id}}"  >
            <div class="modal-body">
                <table class="table table-bordered table-condensed" >
                    <tbody>
                        <tr>
                            <td>No Nota</td>
                            <td>
                                <input type="text" autocomplete="off" name="no_nota_timbang" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <td>Kalkulasi</td>
                            <td>
                                <select name="kalkulasi" class="form-control" >
                                    <option value="R" >Ritase</option>
                                    <option value="K" >Kubikasi</option>
                                    <option value="T" >Tonase</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="row-kubikasi" >
                            <td>Panjang</td>
                            <td>
                                <input type="text" name="panjang" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-kubikasi" >
                            <td>Lebar</td>
                            <td>
                                <input type="text" name="lebar" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-kubikasi" >
                            <td>Tinggi</td>
                            <td>
                                <input type="text" name="tinggi" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-kubikasi" >
                            <td>Volume</td>
                            <td>
                                <input type="text" name="volume" class="form-control text-right " disabled>
                            </td>
                        </tr>
                        <tr class="row-tonase" >
                            <td>Gross</td>
                            <td>
                                <input type="text" name="gross" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-tonase" >
                            <td>Tarre</td>
                            <td>
                                <input type="text" name="tarre" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-tonase" >
                            <td>Netto</td>
                            <td>
                                <input type="text" name="netto" class="form-control text-right" disabled>
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
    $('.row-tonase, .row-kubikasi').hide();
    $('select[name=kalkulasi]').val([]);

    // SET AUTONUMERIC
    $('input[name=panjang], input[name=lebar], input[name=tinggi], input[name=gross], input[name=tarre], input[name=volume], input[name=netto]').autoNumeric('init');
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
        if($(this).val() == 'R'){
            $('.row-kubikasi, .row-tonase').hide()
        }else if($(this).val() == 'K'){
            $('.row-kubikasi').show();
            $('.row-tonase').hide();
        }else{
            $('.row-kubikasi').hide();
            $('.row-tonase').show();
        }
    });
    // END OF KALKULASI DO

    // CALCULATE KUBIKASI
    $('input[name=panjang], input[name=lebar], input[name=tinggi]').keyup(function(){
        
        var panjang = $('input[name=panjang]').autoNumeric('get');

        
        var lebar = $('input[name=lebar]').autoNumeric('get');
        // alert(lebar);
        var tinggi = $('input[name=tinggi]').autoNumeric('get');
        // alert(tinggi);
        var volume = Number(panjang) * Number(lebar) * Number(tinggi);
        // alert('volume ' + volume);
        $('input[name=volume]').autoNumeric('set',volume);
    });
    // END OF CALCULATE KUBIKASI

    // RECONCILE/PEMBATALAN VALIDASI
    //$('#btn-reconcile').click(function(){
    //    if(confirm('Anda akan membatalkan data ini?')){

    //    }else{
    //        return false;    
    //    }
        
    });
    // END OF RECONCILE/PEMBATALAN VALIDASI


})(jQuery);
</script>
@append