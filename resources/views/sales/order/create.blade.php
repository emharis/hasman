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
        <a href="sales/order" >Sales Orders</a> <i class="fa fa-angle-double-right" ></i> New
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            {{-- <label> <small>Sales Order</small> <h4 style="font-weight: bolder;margin-top:0;padding-top:0;margin-bottom:0;padding-bottom:0;" >New</h4></label> --}}
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >New</h3></label>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-gray" >Done</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-gray" >Validated</a>

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
                            <label>Customer</label>
                        </td>
                        <td class="col-lg-4" >
                            <input type="text" name="customer" autofocus class="form-control " data-customerid="" required>
                        </td>
                        <td class="col-lg-2" ></td>
                        <td class="col-lg-2" >
                            <label>Order Date</label>
                        </td>
                        <td class="col-lg-2" >
                            <input type="text" name="tanggal" class="input-tanggal form-control" value="{{date('d-m-Y')}}" required>
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
                        {{-- <th class="col-lg-1" >SATUAN</th> --}}
                        <th class="col-lg-1" >QUANTITY</th>
                        {{-- <th class="col-lg-2" >UNIT PRICE</th> --}}
                        {{-- <th class="col-lg-2" >S.U.P</th> --}}
                        {{-- <th class="col-lg-2" >SUBTOTAL</th> --}}
                        <th style="width:50px;" ></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hide" id="row-add-product"  >
                        <td class="text-right" ></td>
                        <td>
                            <input autocomplete="off" type="text"  data-materialid="" data-kode="" class=" form-control input-product input-sm input-clear">
                        </td>
                        {{-- <td>
                            <input type="text" readonly autocomplete="off" class="form-control text-right input-quantity-on-hand input-sm input-clear">
                        </td> --}}
                        <td>
                            <input type="number" autocomplete="off" min="1" class="form-control text-right input-quantity input-sm input-clear">
                        </td>
                        {{-- <td>
                            <input autocomplete="off" type="text" class="text-right form-control input-unit-price input-sm input-clear" readonly="">
                        </td>
                        <td>
                            <input autocomplete="off" type="text" class="text-right form-control input-salesperson-unit-price input-sm input-clear">
                        </td>
                        <td>
                            <input autocomplete="off" type="text" readonly  class="text-right form-control input-subtotal input-sm input-clear">
                        </td> --}}
                        <td class="text-center" >
                            <a href="#" class="btn-delete-row-product" ><i class="fa fa-trash" ></i></a>
                        </td>
                    </tr>
                    <tr id="row-btn-add-item">
                        <td></td>
                        <td colspan="7" >
                            <a id="btn-add-item" href="#">Add an item</a>
                        </td>
                    </tr>
                    
                    
                </tbody>
            </table>

            {{-- <div class="row" >
                <div class="col-lg-8" >
                    <textarea name="note" class="form-control" rows="3" style="margin-top:5px;" placeholder="Note" ></textarea>
                    <i>* <span>Q.O.H : Quantity on Hand</span></i>
                    <i>&nbsp;|&nbsp;</i>
                    <i><span>S.U.P : Salesperson Unit Price</span></i>
                </div>
                <div class="col-lg-4" >
                    <table class="table table-condensed" >
                        <tbody>
                            <tr>
                                <td class="text-right">
                                    <label>Subtotal :</label>
                                </td>
                                <td class="label-total-subtotal text-right" >
                                    
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" >
                                    <label>Disc :</label>
                                </td>
                                <td >
                                   <input style="font-size:14px;" type="text" name="disc" class="input-sm form-control text-right input-clear"> 
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" style="border-top:solid darkgray 1px;" >
                                    Total :
                                </td>
                                <td class="label-total text-right" style="font-size:18px;font-weight:bold;border-top:solid darkgray 1px;" >
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-12" >
                    <button type="submit" class="btn btn-primary" id="btn-save" >Save</button>
                            <a class="btn btn-danger" id="btn-cancel-save" >Cancel</a>
                </div>
            </div> --}}



            {{-- <a id="btn-test" href="#" >TEST</a> --}}


        </div><!-- /.box-body -->
        <div class="box-footer" >
            <button type="submit" class="btn btn-primary" id="btn-save" >Save</button>
            <a class="btn btn-danger" id="btn-cancel-save" >Cancel</a>
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
    // SET DATEPICKER
    $('.input-tanggal').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    // END OF SET DATEPICKER

    // SET AUTOCOMPLETE CUSTOMER
    $('input[name=customer]').autocomplete({
        serviceUrl: 'api/get-auto-complete-customer',
        params: {  'nama': function() {
                        return $('input[name=customer]').val();
                    }
                },
        onSelect:function(suggestions){
            // set data customer
            $('input[name=customer]').data('customerid',suggestions.data);
        }

    });
    // END OF SET AUTOCOMPLETE CUSTOMER

    // // SET AUTOCOMPLETE MATERIAL
    // $('input[name=salesperson]').autocomplete({
    //     serviceUrl: 'sales/order/get-salesperson',
    //     params: {  'nama': function() {
    //                     return $('input[name=salesperson]').val();
    //                 }
    //             },
    //     onSelect:function(suggestions){
    //         // set data customer
    //         $('input[name=salesperson]').data('salespersonid',suggestions.data);
    //     }

    // });
    // END OF SET AUTOCOMPLETE MATERIAL

    // -----------------------------------------------------
    // SET AUTO NUMERIC
    // =====================================================
    $('input[name=unit_price], input[name=subtotal], input[name=disc], .label-total, .label-total-subtotal').autoNumeric('init',{
        vMin:'0',
        vMax:'9999999999'
    });
    // END OF AUTONUMERIC

    // FUNGSI REORDER ROWNUMBER
    function rownumReorder(){
        var rownum=1;
        $('#table-product > tbody > tr.row-product').each(function(){
            $(this).children('td:first').text(rownum++);
        });
    }
    // END OF FUNGSI REORDER ROWNUMBER

    // ~BTN ADD ITEM
    var first_col;
    var input_product;
    var input_qty_on_hand;
    var input_qty;
    var input_unit_price;
    var input_sup;
    var input_subtotal;
    $('#btn-add-item').click(function(){
        // tampilkan form add new item
        var newrow = $('#row-add-product').clone();
        newrow.addClass('row-product');
        newrow.removeClass('hide');
        newrow.removeAttr('id');
        first_col = newrow.children('td:first');
        input_product = first_col.next().children('input');
        // input_qty_on_hand = first_col.next().next().children('input');
        input_qty = first_col.next().next().children('input');
        // input_unit_price = first_col.next().next().next().next().children('input');
        // input_sup = first_col.next().next().next().next().next().children('input');
        // input_subtotal = first_col.next().next().next().next().next().next().children('input');

        // tambahkan newrow ke table
        $(this).parent().parent().prev().after(newrow);

        // // format auto numeric
        // input_unit_price.autoNumeric('init',{
        // // $('.input-unit-price').autoNumeric('init',{
        //     vMin:'0',
        //     vMax:'9999999999'
        // });
        // input_sup.autoNumeric('init',{
        // // $('.input-salesperson-unit-price').autoNumeric('init',{
        //     vMin:'0',
        //     vMax:'9999999999'
        // });
        // input_subtotal.autoNumeric('init',{
        // // $('.input-subtotal').autoNumeric('init',{
        //     vMin:'0',
        //     vMax:'9999999999'
        // });       

        // Tampilkan & Reorder Row Number
        rownumReorder();
       
        // format autocomplete
        input_product.autocomplete({
            serviceUrl: 'api/get-auto-complete-material',
            params: {  
                        'nama' : function() {
                                    return input_product.val();
                                },
                        // 'exceptdata':JSON.stringify(getExceptionData())
                    },
            onSelect:function(suggestions){
                input_product.data('materialid',suggestions.data);
                input_product.data('kode',suggestions.kode);
                
                // disable input_product
                input_product.attr('readonly','readonly');

                // get quantity on hand dan tampilkan ke input-quantity-on-hand
                // input_product.parent().next().children('input').val(suggestions.stok);
                // input_qty_on_hand.val(suggestions.stok);

                // set maks input-quanity
                // input_product.parent().next().next().children('input').attr('max',suggestions.stok);
                // input_qty.attr('max',suggestions.stok);

                // get unit_price & tampikan ke input-unit-price
                // input_product.parent().next().next().children('input').autoNumeric('set',suggestions.harga_jual);
                // input_unit_price.autoNumeric('set',suggestions.harga_jual);

                //set SUP default unit price
                // input_sup.autoNumeric('set',suggestions.harga_jual);

                // fokuskan ke input quantity
                // input_product.parent().next().children('input').focus();
                // alert('ok');
                input_qty.focus();
                // alert('done');

            }
        });

        

        // fokuskan ke input product
        input_product.focus();

        return false;
    });
    // END OF ~BTN ADD ITEM

    // // // HITUNG SUBTOTAL
    // $(document).on('keyup','.input-salesperson-unit-price, .input-quantity',function(){
    //     generateInput($(this));

    //     // cek qty apakah melebihi batas QOH
    //     // alert(input_qty.val() +' ' + input_qty_on_hand.val());
    //     if(Number(input_qty.val()) > Number(input_qty_on_hand.val())){
    //         alert('Quantity melebihi QOH.');
    //         input_qty.val('');
    //         input_qty.focus();
    //     }else{
    //         calcSubtotal($(this));
    //     }
        
    // });
    // $(document).on('input','.input-quantity',function(){
    //     calcSubtotal($(this));
    // });

    function generateInput(inputElm){
        first_col = inputElm.parent().parent().children('td:first');
        input_product = first_col.next().children('input');
        // input_qty_on_hand = first_col.next().next().children('input');
        input_qty = first_col.next().next().children('input');
        // input_unit_price = first_col.next().next().next().next().children('input');
        // input_sup = first_col.next().next().next().next().next().children('input');
        // input_subtotal = first_col.next().next().next().next().next().next().children('input');
    }

    function calcSubtotal(inputElm){
        generateInput(inputElm);

        // hitung sub total
        var subtotal = Number(input_qty.val()) * Number(input_sup.autoNumeric('get'));

        // tampilkan sub total
        input_subtotal.autoNumeric('set',subtotal);

        // hitung TOTAL
        hitungTotal();
    }
    // END HITUNG SUBTOTAL

    // // FUNGSI HITUNG TOTAL KESELURUHAN
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
    // // END OF FUNGSI HITUNG TOTAL KESELURUHAN

    // // INPUT DISC ON KEYUP
    // $(document).on('keyup','input[name=disc]',function(){
    //     hitungTotal();
    // });
    // // END OF INPUT DISC ON KEYUP

    // DELETE ROW PRODUCT
    $(document).on('click','.btn-delete-row-product',function(){
        var row = $(this).parent().parent();
        row.fadeOut(250,null,function(){
            row.remove();
            // reorder row number
            rownumReorder();
            // hitung total
            hitungTotal();
        });

        return false;
    });
    // END OF DELETE ROW PRODUCT

    
    // BTN CANCEL SAVE
    $('#btn-cancel-save').click(function(){
        if(confirm('Anda akan membabtalkan transaksi ini?')){
            location.href = "sales/order";
        }else
        {

        return false
        }
    });
    // END OF BTN CANCEL SAVE


    // BTN SAVE TRANSACTION
    $('#btn-save').click(function(){
        // cek kelengkapan data
        var so_master = {"customer_id":"",
                         // "salesperson_id":"",
                         "order_date":"",
                         // "note":"",
                         // "subtotal":"",
                         // "disc":"",
                         // "total":""
                     };
        // set so_master data
        so_master.customer_id = $('input[name=customer]').data('customerid');
        // so_master.salesperson_id = $('input[name=salesperson]').data('salespersonid');
        // so_master.no_inv = $('input[name=no_inv]').val();
        so_master.order_date = $('input[name=tanggal]').val();
        // so_master.jatuh_tempo = $('input[name=jatuh_tempo]').val();
        // so_master.note = $('textarea[name=note]').val();
        // so_master.subtotal = $('.label-total-subtotal').autoNumeric('get');
        // so_master.total = $('.label-total').autoNumeric('get');
        // so_master.disc = $('input[name=disc]').autoNumeric('get');

        // get data material;
        var so_material = JSON.parse('{"material" : [] }');

        // set data barant
        $('input.input-product').each(function(){
            if($(this).parent().parent().hasClass('row-product')){
                generateInput($(this));

                if(input_product.data('materialid') != "" 
                    // && input_qty_on_hand.val() != "" 
                    // && Number(input_qty_on_hand.val()) > 0 
                    && input_qty.val() != "" 
                    && Number(input_qty.val()) > 0 
                    // &&input_unit_price.val() != "" 
                    // && Number(input_unit_price.autoNumeric('get')) > 0 
                    // && input_sup.val() != "" 
                    // && Number(input_sup.autoNumeric('get')) > 0 
                    // && input_subtotal.val() != "" 
                    // && Number(input_subtotal.autoNumeric('get')) > 0 
                    ){

                    so_material.material.push({
                        id:input_product.data('materialid'),
                        // qoh:input_qty_on_hand.val(),
                        qty:input_qty.val(),
                        // unit_price : input_unit_price.autoNumeric('get'),
                        // sup_price:input_sup.autoNumeric('get'),
                        // subtotal:input_subtotal.autoNumeric('get')
                    });

                }
                
            }
        });

        // save ke database
        // alert(so_material.material.length);
        if(so_master.customer_id != "" 
            && $('input[name=customer]').val() != "" 
            // && so_master.salesperson_id != "" 
            // && $('input[name=salesperson]').val() != "" 
            && so_master.order_date != "" 
            && so_material.material.length > 0){

            var newform = $('<form>').attr('method','POST').attr('action','sales/order/insert');
                newform.append($('<input>').attr('type','hidden').attr('name','so_master').val(JSON.stringify(so_master)));
                newform.append($('<input>').attr('type','hidden').attr('name','so_material').val(JSON.stringify(so_material)));
                newform.submit();

        }else{
            alert('Lengkapi data yang kosong');
        }


        return false;
    });
    // END OF BTN SAVE TRANSACTION


    


    

    // // $('#btn-test').click(function(){
    // //     hitungTotal();
    // //     return false;
    // // });
    // // END OF FUNGSI HITUNG TOTAL KESELURUHAN

})(jQuery);
</script>
@append