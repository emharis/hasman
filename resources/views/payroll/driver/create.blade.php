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


</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="purchase/order" >Driver Payroll</a>
        <i class="fa fa-angle-double-right" ></i>
        New
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            {{-- <label> <small>Sales Order</small> <h4 style="font-weight: bolder;margin-top:0;padding-top:0;margin-bottom:0;padding-bottom:0;" >New</h4></label> --}}
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >New</h3></label>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Open</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-blue" >Draft</a>
        </div>
        <div class="box-body">
            <table class="table" >
                <tbody>
                    <tr>
                        <td class="col-lg-2">
                            <label>Driver</label>
                        </td>
                        <td class="col-lg-4" >
                            <input type="text" name="driver" autofocus class="form-control " data-driverid="" required>
                        </td>
                        <td class="col-lg-2" >
                            <label>Payment Date</label>
                        </td>
                        <td class="col-lg-4" >
                            <input type="text" name="payment_date" class="input-tanggal form-control" value="{{date('d-m-Y')}}" required>
                        </td>
                    </tr>
                    {{-- <tr>
                        <td>
                            <label>Date from</label>
                        </td>
                        <td>
                            <input type="text" name="date_from" class="form-control input-tanggal" readonly>
                        </td>
                        <td>
                          <label>to</label>
                        </td>
                        <td>
                          <input type="text" name="date_to" class="form-control input-tanggal" readonly>
                        </td>
                    </tr> --}}
                    {{-- <tr>
                        <td class="col-lg-2">
                            <label>Salesperson</label>
                        </td>
                        <td class="col-lg-4" >
                            <input type="text" name="purchaseperson" class="form-control " data-purchasepersonid="" required >
                        </td>
                        <td class="col-lg-2" ></td>
                        <td class="col-lg-2 hide" >
                            <label>Jatuh Tempo</label>
                        </td>
                        <td class="col-lg-2 hide" >
                            <input type="text" name="jatuh_tempo"  class="input-tanggal form-control" value="" >
                        </td>
                    </tr> --}}
                    <tr>
                      <td></td>
                      <td>
                        <button class="btn btn-primary" id="btn-get-data-delivery" >Show</button>
                      </td>
                      <td></td>
                      <td></td>
                    </tr>
                </tbody>
            </table>

            <h4 class="page-header data-delivery hide" style="font-size:14px;color:#3C8DBC"><strong>DELIVERY DETAILS</strong></h4>

            <table id="table-delivery" class="table table-bordered table-condensed data-delivery hide" >
                <thead>
                    <tr>
                        <th style="width:25px;" >NO</th>
                        <th>MATERIAL</th>
                        <th>PEKERJAAN</th>
                        <th>TUJUAN</th>
                        <th>KALKULASI</th>
                        <th>VOL</th>
                        <th>NETTO</th>
                        <th>RIT</th>
                        <th>HARGA</th>
                        <th>JUMLAH</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <div class="row data-delivery hide" >
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
                                <td >
                                   <input style="font-size:14px;" type="text" name="potongan_bahan" class="input-potongan input-sm form-control text-right input-clear uang">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" >
                                    <label>Potongan Bon :</label>
                                </td>
                                <td >
                                   <input style="font-size:14px;" type="text" name="potongan_bon" class="input-potongan  input-sm form-control text-right input-clear uang">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" >
                                    <label>Sisa Bayaran Kemarin :</label>
                                </td>
                                <td >
                                   <input style="font-size:14px;" type="text" name="sisa_bayaran" class="input-potongan  input-sm form-control text-right input-clear uang">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" >
                                    <label>DP :</label>
                                </td>
                                <td >
                                   <input style="font-size:14px;" type="text" name="input_dp" class="input-potongan input-sm form-control text-right input-clear uang">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" style="border-top:solid darkgray 1px;" >
                                    Saldo :
                                </td>
                                <td class="label-saldo text-right uang" style="font-size:18px;font-weight:bold;border-top:solid darkgray 1px;" >

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
        <div class="box-footer data-delivery hide" >
            <button type="submit" class="btn btn-primary" id="btn-save" >Save</button>
            <a class="btn btn-danger" id="btn-cancel-save" >Cancel</a>
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

    // function fillSelectPekerjaan(driver_id){
    //     $.get('api/get-select-pekerjaan/' + driver_id,null,function(datares){
    //             var data_pekerjaan = JSON.parse(datares);
    //             // insert select option
    //             $('select[name=pekerjaan]').empty();
    //             $.each(data_pekerjaan,function(i,data){
    //                 $('select[name=pekerjaan]').append($('<option>').val(i).text(data));
    //             });
    //             $('select[name=pekerjaan]').val([]);
    //
    //             //Initialize Select2 Elements
    //             $(".select2").select2();
    //         });
    // }
    // END OF SET AUTOCOMPLETE CUSTOMER

    // // SET AUTOCOMPLETE MATERIAL
    // $('input[name=purchaseperson]').autocomplete({
    //     serviceUrl: 'purchase/order/get-purchaseperson',
    //     params: {  'nama': function() {
    //                     return $('input[name=purchaseperson]').val();
    //                 }
    //             },
    //     onSelect:function(suggestions){
    //         // set data driver
    //         $('input[name=purchaseperson]').data('purchasepersonid',suggestions.data);
    //     }

    // });
    // END OF SET AUTOCOMPLETE MATERIAL

    // SET AUTOCOMPLETE PROVINSI
    // $('input[name=provinsi]').autocomplete({
    //     serviceUrl: 'api/get-auto-complete-provinsi',
    //     params: {
    //                 'nama': function() {
    //                     return $('input[name=provinsi]').val();
    //                 }
    //             },
    //     onSelect:function(suggestions){
    //         // // set data driver
    //         $('input[name=provinsi_id]').val(suggestions.data);
    //     }
    //
    // });
    // END OF SET AUTOCOMPLETE PROVINSI

    // SET AUTOCOMPLETE KABUPATEN
    // $('input[name=kabupaten]').autocomplete({
    //     serviceUrl: 'api/get-auto-complete-kabupaten',
    //     params: {
    //                 'nama': function() {
    //                     return $('input[name=kabupaten]').val();
    //                 },
    //                 'provinsi_id': function() {
    //                     return $('input[name=provinsi_id]').val();
    //                 },
    //
    //             },
    //     onSelect:function(suggestions){
    //         // // set data driver
    //         $('input[name=kabupaten_id]').val(suggestions.data);
    //     }
    //
    // });
    // END OF SET AUTOCOMPLETE KABUPATEN

    // SET AUTOCOMPLETE KECAMATAN
    // $('input[name=kecamatan]').autocomplete({
    //     serviceUrl: 'api/get-auto-complete-kecamatan',
    //     params: {
    //                 'nama': function() {
    //                     return $('input[name=kecamatan]').val();
    //                 },
    //                 'kabupaten_id': function() {
    //                     return $('input[name=kabupaten_id]').val();
    //                 },
    //
    //             },
    //     onSelect:function(suggestions){
    //         // // set data driver
    //         $('input[name=kecamatan_id]').val(suggestions.data);
    //     }
    //
    // });
    // END OF SET AUTOCOMPLETE KECAMATAN

    // SET AUTOCOMPLETE DESA
    // $('input[name=desa]').autocomplete({
    //     serviceUrl: 'api/get-auto-complete-desa',
    //     params: {
    //                 'nama': function() {
    //                     return $('input[name=desa]').val();
    //                 },
    //                 'kecamatan_id': function() {
    //                     return $('input[name=kecamatan_id]').val();
    //                 },
    //
    //             },
    //     onSelect:function(suggestions){
    //         // // set data driver
    //         $('input[name=desa_id]').val(suggestions.data);
    //         // alert($('input[name=desa]').data('id'));
    //
    //     }
    //
    // });
    // END OF SET AUTOCOMPLETE DESA

    // -----------------------------------------------------
    // SET AUTO NUMERIC
    // =====================================================
    // $('input[name=unit_price], input[name=subtotal], input[name=disc], .label-total, .label-total-subtotal').autoNumeric('init',{
    //     vMin:'0',
    //     vMax:'9999999999'
    // });
    // END OF AUTONUMERIC

    // FUNGSI REORDER ROWNUMBER
    // function rownumReorder(){
    //     var rownum=1;
    //     $('#table-product > tbody > tr.row-product').each(function(){
    //         $(this).children('td:first').text(rownum++);
    //     });
    // }
    // END OF FUNGSI REORDER ROWNUMBER

    // ~BTN ADD ITEM
    // var first_col;
    // var input_product;
    // var input_qty_on_hand;
    // var input_qty;
    // var input_unit_price;
    // var input_sup;
    // var input_subtotal;
    // var label_satuan;
    //
    // $('#btn-add-item').click(function(){
    //     // tampilkan form add new item
    //     var newrow = $('#row-add-product').clone();
    //     newrow.addClass('row-product');
    //     newrow.removeClass('hide');
    //     newrow.removeAttr('id');
    //     first_col = newrow.children('td:first');
    //     input_product = first_col.next().children('input');
    //     // input_qty_on_hand = first_col.next().next().children('input');
    //     input_qty = first_col.next().next().next().children('input');
    //     label_satuan = first_col.next().next();
    //     input_unit_price = first_col.next().next().next().next().children('input');
    //     // input_sup = first_col.next().next().next().next().next().children('input');
    //     input_subtotal = first_col.next().next().next().next().next().children('input');
    //
    //     // tambahkan newrow ke table
    //     $(this).parent().parent().prev().after(newrow);
    //
    //     // // format auto numeric
    //     // input_unit_price.autoNumeric('init',{
    //     // // $('.input-unit-price').autoNumeric('init',{
    //     //     vMin:'0',
    //     //     vMax:'9999999999'
    //     // });
    //     // input_sup.autoNumeric('init',{
    //     // // $('.input-unit-price').autoNumeric('init',{
    //     //     vMin:'0',
    //     //     vMax:'9999999999'
    //     // });
    //     // input_subtotal.autoNumeric('init',{
    //     // // $('.input-subtotal').autoNumeric('init',{
    //     //     vMin:'0',
    //     //     vMax:'9999999999'
    //     // });
    //
    //     // Tampilkan & Reorder Row Number
    //     rownumReorder();
    //
    //     // format autocomplete
    //     input_product.autocomplete({
    //         serviceUrl: 'api/get-auto-complete-product',
    //         params: {
    //                     'nama' : function() {
    //                                 return input_product.val();
    //                             },
    //                     // 'exceptdata':JSON.stringify(getExceptionData())
    //                 },
    //         onSelect:function(suggestions){
    //             input_product.data('productid',suggestions.data);
    //             input_product.data('kode',suggestions.kode);
    //
    //             // disable input_product
    //             input_product.attr('readonly','readonly');
    //
    //             // tampilkan satuan
    //             label_satuan.text(suggestions.unit);
    //
    //             // get quantity on hand dan tampilkan ke input-quantity-on-hand
    //             // input_product.parent().next().children('input').val(suggestions.stok);
    //             // input_qty_on_hand.val(suggestions.stok);
    //
    //             // set maks input-quanity
    //             // input_product.parent().next().next().children('input').attr('max',suggestions.stok);
    //             // input_qty.attr('max',suggestions.stok);
    //
    //             // get unit_price & tampikan ke input-unit-price
    //             // input_product.parent().next().next().children('input').autoNumeric('set',suggestions.harga_jual);
    //             // input_unit_price.autoNumeric('set',suggestions.harga_jual);
    //
    //             //set SUP default unit price
    //             // input_sup.autoNumeric('set',suggestions.harga_jual);
    //
    //              // input_unit_price.autoNumeric('init',{
    //             input_unit_price.autoNumeric('init',{
    //                 vMin:'0',
    //                 vMax:'9999999999'
    //             });
    //             input_subtotal.autoNumeric('init',{
    //                 vMin:'0',
    //                 vMax:'9999999999'
    //             });
    //
    //             // fokuskan ke input quantity
    //             // input_product.parent().next().children('input').focus();
    //             // alert('ok');
    //             input_qty.focus();
    //             // alert('done');
    //
    //         }
    //     });
    //
    //
    //
    //     // fokuskan ke input product
    //     input_product.focus();
    //
    //     return false;
    // });
    // END OF ~BTN ADD ITEM

    // // HITUNG SUBTOTAL
    // $(document).on('keyup','.input-unit-price, .input-quantity',function(){
    //     // generateInput($(this));
    //
    //     // cek qty apakah melebihi batas QOH
    //     // alert(input_qty.val() +' ' + input_qty_on_hand.val());
    //     // if(Number(input_qty.val()) > Number(input_qty_on_hand.val())){
    //     //     alert('Quantity melebihi QOH.');
    //     //     input_qty.val('');
    //     //     input_qty.focus();
    //     // }else{
    //         calcSubtotal($(this));
    //     // }
    //
    // });
    // $(document).on('input','.input-quantity',function(){
    //     calcSubtotal($(this));
    // });

    // function generateInput(inputElm){
    //     first_col = inputElm.parent().parent().children('td:first');
    //     input_product = first_col.next().children('input');
    //     // input_qty_on_hand = first_col.next().next().children('input');
    //     input_qty = first_col.next().next().next().children('input');
    //     input_unit_price = first_col.next().next().next().next().children('input');
    //     // input_sup = first_col.next().next().next().next().next().children('input');
    //     input_subtotal = first_col.next().next().next().next().next().children('input');
    // }
    //
    // function calcSubtotal(inputElm){
    //     generateInput(inputElm);
    //
    //     // hitung sub total
    //     var subtotal = Number(input_qty.val()) * Number(input_unit_price.autoNumeric('get'));
    //     // alert(subtotal);
    //
    //     // tampilkan sub total
    //     input_subtotal.autoNumeric('set',subtotal);
    //
    //     // hitung TOTAL
    //     hitungTotal();
    // }
    // END HITUNG SUBTOTAL

    // FUNGSI HITUNG TOTAL KESELURUHAN
    // function hitungTotal(){
    //     var disc = $('input[name=disc]').autoNumeric('get');
    //     var subtotal = 0;
    //     $('input.input-subtotal').each(function(){
    //         if($(this).parent().parent().hasClass('row-product')){
    //             subtotal += Number($(this).autoNumeric('get'));
    //         }
    //     });
    //     // tampilkan subtotal dan total
    //     $('.label-total-subtotal').autoNumeric('set',subtotal);
    //     $('.label-total').autoNumeric('set',Number(subtotal) - Number(disc));
    // }
    // END OF FUNGSI HITUNG TOTAL KESELURUHAN

    // INPUT DISC ON KEYUP
    // $(document).on('keyup','input[name=disc]',function(){
    //     hitungTotal();
    // });
    // END OF INPUT DISC ON KEYUP

    // DELETE ROW PRODUCT
    // $(document).on('click','.btn-delete-row-product',function(){
    //     var row = $(this).parent().parent();
    //     row.fadeOut(250,null,function(){
    //         row.remove();
    //         // reorder row number
    //         rownumReorder();
    //         // hitung total
    //         hitungTotal();
    //     });
    //
    //     return false;
    // });
    // END OF DELETE ROW PRODUCT


    // BTN CANCEL SAVE
    // $('#btn-cancel-save').click(function(){
    //     // if(confirm('Anda akan membabtalkan transaksi ini?')){
    //         location.href = "purchase/order";
    //     // }else
    //     // {
    //     //
    //     // return false
    //     // }
    // });
    // END OF BTN CANCEL SAVE


    // BTN SAVE TRANSACTION
    // $('#btn-save').click(function(){
    //     // cek kelengkapan data
    //     var po_master = {"driver_id":"",
    //                      // "purchaseperson_id":"",
    //                      "driver_ref":"",
    //                      "order_date":"",
    //                      // "pekerjaan_id":"",
    //                      // "note":"",
    //                      "subtotal":"",
    //                      "disc":"",
    //                      "total":""
    //                  };
    //     // set po_master data
    //     po_master.driver_id = $('input[name=driver]').data('driverid');
    //     // po_master.purchaseperson_id = $('input[name=purchaseperson]').data('purchasepersonid');
    //     // po_master.no_inv = $('input[name=no_inv]').val();
    //     po_master.order_date = $('input[name=tanggal]').val();
    //     po_master.driver_ref = $('input[name=driver_ref]').val();
    //     // po_master.pekerjaan_id = $('select[name=pekerjaan]').val();
    //     // po_master.jatuh_tempo = $('input[name=jatuh_tempo]').val();
    //     // po_master.note = $('textarea[name=note]').val();
    //     po_master.subtotal = $('.label-total-subtotal').autoNumeric('get');
    //     po_master.total = $('.label-total').autoNumeric('get');
    //     po_master.disc = $('input[name=disc]').autoNumeric('get');
    //
    //     // get data product;
    //     var po_product = JSON.parse('{"product" : [] }');
    //
    //     // set data barang
    //     $('input.input-product').each(function(){
    //         if($(this).parent().parent().hasClass('row-product')){
    //             generateInput($(this));
    //
    //             if(input_product.data('productid') != ""
    //                 // && input_qty_on_hand.val() != ""
    //                 // && Number(input_qty_on_hand.val()) > 0
    //                 && input_qty.val() != ""
    //                 && Number(input_qty.val()) > 0
    //                 // &&input_unit_price.val() != ""
    //                 // && Number(input_unit_price.autoNumeric('get')) > 0
    //                 // && input_sup.val() != ""
    //                 // && Number(input_sup.autoNumeric('get')) > 0
    //                 // && input_subtotal.val() != ""
    //                 // && Number(input_subtotal.autoNumeric('get')) > 0
    //                 ){
    //
    //                 po_product.product.push({
    //                     id:input_product.data('productid'),
    //                     // qoh:input_qty_on_hand.val(),
    //                     qty:input_qty.val(),
    //                     unit_price : input_unit_price.autoNumeric('get'),
    //                     // sup_price:input_sup.autoNumeric('get'),
    //                     // subtotal:input_subtotal.autoNumeric('get')
    //                 });
    //
    //             }
    //
    //         }
    //     });

        // save ke database
        // alert(po_product.product.length);
        // alert('Pekerjaan id : ' + po_master.pekerjaan_id);
    //     if(po_master.driver_id != ""
    //         && $('input[name=driver]').val() != ""
    //         && $('input[name=driver]').val() != null
    //         && po_master.order_date != ""
    //         && po_master.order_date != null
    //         // && po_master.pekerjaan_id != ""
    //         // && po_master.pekerjaan_id != null
    //         && po_product.product.length > 0){
    //
    //         var newform = $('<form>').attr('method','POST').attr('action','purchase/order/insert');
    //             newform.append($('<input>').attr('type','hidden').attr('name','po_master').val(JSON.stringify(po_master)));
    //             newform.append($('<input>').attr('type','hidden').attr('name','po_product').val(JSON.stringify(po_product)));
    //             newform.submit();
    //
    //     }else{
    //         alert('Lengkapi data yang kosong');
    //     }
    //
    //
    //     return false;
    // });
    // END OF BTN SAVE TRANSACTION


    // CEK INPUT CUSTOMER APAKAH KOSONG ATAU TIDAK
    // $('input[name=driver]').keyup(function(){
    //     if($(this).val() == ""){
    //         // disable input pekerjaan
    //         $('select[name=pekerjaan]').empty();
    //         $('select[name=pekerjaan]').attr('disabled','disabled');
    //         $('$btn-add-pekerjaan').addClass('disabled');
    //     }
    // });
    // END OF CEK INPUT CUSTOMER APAKAH KOSONG ATAU TIDAK

    // SAVE ADD PEKERJAAN
    // $('form[name=form_create_pekerjaan]').ajaxForm(function(res){
    //     fillSelectPekerjaan($('form[name=form_create_pekerjaan] input[name=driver_id]').val());
    //     // close modal
    //     $('#modal-pekerjaan').modal('hide');
    // });
    // END OF SAVE ADD PEKERJAAN





    // // $('#btn-test').click(function(){
    // //     hitungTotal();
    // //     return false;
    // // });
    // // END OF FUNGSI HITUNG TOTAL KESELURUHAN


    // GET DATA DELIVERY DRIVER
    $('#btn-get-data-delivery').click(function(){
      var driver_id = $('input[name=driver]').data('driverid');
      var driver = $('input[name=driver]').val();
      var tanggal = $('input[name=payment_date]').val();
      if(driver_id != "" && driver != "" && tanggal != ""){
        var url = 'payroll/driver/get-delivery-order/'+driver_id+"/"+tanggal;

        $.get(url,null,function(res){
          $('.data-delivery').hide();
          $('#table-delivery tbody').empty();

          // alert('ok');
          var data_do = JSON.parse(res);
          var table_delivery = $('#table-delivery tbody');
          var rownum=1;
          $.each(data_do,function(){
            table_delivery.append($('<tr>').addClass('row-material').append($('<td>').text(rownum++))
                                  .append($('<td>').html($(this)[0].material))
                                  .append($('<td>').html($(this)[0].pekerjaan))
                                  .append($('<td>').html($(this)[0].kecamatan))
                                  .append($('<td>').html($(this)[0].kalkulasi == 'K' && 'KUBIKASI' || $(this)[0].kalkulasi == 'T' && 'TONASE' || "RITASE" ))
                                  .append($('<td>').html($(this)[0].kalkulasi == 'K' && $(this)[0].sum_volume || '-'))
                                  .append($('<td>').html($(this)[0].kalkulasi == 'T' && $(this)[0].sum_netto || '-'))
                                  .append($('<td>').html($(this)[0].kalkulasi == 'R'&& $(this)[0].sum_qty || '-'))
                                  .append($('<td>').addClass('col-sm-2 col-md-2 col-lg-2').append($('<input data-kalkulasi="' + $(this)[0].kalkulasi + '" class="form-control text-right uang input-harga-on-row"  />')))
                                  .append($('<td>').addClass('uang col-total-on-row col-sm-2 col-md-2 col-lg-2 text-right'))
                                  );
          });

          // tampilkan table data delivery
          $('.data-delivery').removeClass('hide');
          $('.data-delivery').fadeIn(250);

          // format auto numeric uang
          $('.uang').autoNumeric('init',{
              vMin:'0',
              vMax:'9999999999'
          });

          // disable input
          $('input[name=driver]').attr('readonly','readonly');
          $('input[name=payment_date]').attr('readonly','readonly');

        });
        // location.href = url;
      }else{
        alert('Lengkapi data yang kosong.');
      }
    });

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

})(jQuery);
</script>
@append
