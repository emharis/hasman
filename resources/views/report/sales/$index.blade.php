@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>

<style>
    #table-data > tbody > tr{
        cursor:pointer;
    }

    .table > tbody > tr > td >  .checkbox {
        margin-top: 0;
        margin-bottom: 0;
    }
</style>

@append

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Sales Reports
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box box-solid">
            <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
                <label><h3 style="margin:0;padding:0;font-weight:bold;" >Report Options</h3></label>
            </div>
            <div class="box-body">
                <table class="table table-condensed" >
                    <tbody>
                        <tr>
                            <td>
                                <label>Order Date</label>
                            </td>
                            <td>
                                <input type="text" class="form-control input-date" name="start_date">
                            </td>
                            <td>
                                <input type="text" class="form-control input-date" name="end_date">
                            </td>
                        </tr>
                        <tr>
                            <td>                                
                                <div class="checkbox"  >
                                    <label>
                                        <input type="checkbox" name="ck_detail_per_material" data-filter='filter_by_detail_material'>
                                        <b>Detail per material</b>
                                    </label>
                                </div>
                            </td>
                            <td colspan="2" >
                                
                            </td>
                        </tr>
                        <tr>
                            <td>
                                
                                <div class="checkbox" >
                                    <label>
                                        <input type="checkbox" name="ck_customer" data-filter='filter_by_customer'>
                                        <b>Customer</b>
                                    </label>
                                </div>
                            </td>
                            <td colspan="2" >
                                {!! Form::select('customer',$select_customer,null,['class'=>'form-control','disabled filter_by_customer']) !!}
                            </td>
                        </tr>
                        <tr>
                            <td>                                
                                <div class="checkbox" >
                                    <label>
                                        <input type="checkbox" name="ck_galian" data-filter='filter_by_galian'>
                                        <b>Lokasi Galian</b>
                                    </label>
                                </div>
                            </td>
                            <td colspan="2" >
                                {!! Form::select('lokasi_galian',$select_lokasi_galian,null,['class'=>'form-control','disabled filter_by_galian']) !!}
                            </td>
                        </tr>
                        
                        {{-- <tr>
                            <td>
                                <div class="checkbox" >
                                    <label>
                                        <input type="checkbox" name="ck_status" data-filter='filter_by_status'> 
                                        <b>Status</b>
                                    </label>   
                                </div>
                                
                            </td>
                            <td colspan="2" >
                                {!! Form::select('status',['O'=>'Open','V'=>'Validated','D'=>'Done'],null,['class'=>'form-control','disabled filter_by_customer']) !!}
                            </td>
                        </tr> --}}
                        <tr>
                            <td></td>
                            <td colspan="2" >
                                <button class="btn btn-primary " id="btn-submit" >Submit</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </section><!-- /.content -->
@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

    // SET DATEPICKER
    $('.input-date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    $('input[name=start_date]').change(function(){
        $('input[name=end_date]').datepicker('remove');
        $('.input-date').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            startDate:$('input[name=start_date]').val()
        });
    });
    // END OF SET DATEPICKER

    // SUBMIT FORM
    $('#btn-submit').click(function(){
        var filter_by_customer = $('input[name=ck_customer]').prop('checked');
        var filter_by_status = $('input[name=ck_status]').prop('checked');

        // alert(filter_by_customer)

        // filter by date range
        var start_date = $('input[name=start_date]').val();
        var end_date = $('input[name=end_date]').val();

        var ck_detail = $('input[name=ck_detail_per_material]').prop('checked');
        var ck_customer = $('input[name=ck_customer]').prop('checked');

        var newform = $('<form>').attr('method','POST');

        if(ck_detail && ck_customer){
            var customer_id = $('select[name=customer]').val();
            newform.attr('action','report/sales/report-by-customer-detail')
            newform.append($('<input>').attr('type','hidden').attr('name','customer_id').val(customer_id));
        }else if(ck_detail){
            // hanya tanggal
            newform.attr('action','report/sales/report-by-date-detail')
                        
        }else if(ck_customer){
            var customer_id = $('select[name=customer]').val();
            newform.attr('action','report/sales/report-by-customer')
            newform.append($('<input>').attr('type','hidden').attr('name','customer_id').val(customer_id));            
        }else{
            // hanya tanggal
            newform.attr('action','report/sales/report-by-date')
            
        }

        newform.append($('<input>').attr('type','hidden').attr('name','start_date').val(start_date));
        newform.append($('<input>').attr('type','hidden').attr('name','end_date').val(end_date));
        newform.submit();

        // if(filter_by_customer && filter_by_status){
        //     // filter by status & customer
        // }else if(filter_by_status){
        //     // filter by status

        // }else if(filter_by_customer){
        //     // filter by customer
        //     var customer = $('select[name=customer]').val();
        //     var newform = $('<form>').attr('method','POST').attr('action','report/purchase/filter-by-date-n-customer');
        //     newform.append($('<input>').attr('type','hidden').attr('name','start_date').val(start_date));
        //     newform.append($('<input>').attr('type','hidden').attr('name','end_date').val(end_date));
        //     newform.append($('<input>').attr('type','hidden').attr('name','customer').val(customer));
        //     newform.submit();
        // }else{            

        //     var newform = $('<form>').attr('method','POST').attr('action','report/purchase/filter-by-date');
        //     newform.append($('<input>').attr('type','hidden').attr('name','start_date').val(start_date));
        //     newform.append($('<input>').attr('type','hidden').attr('name','end_date').val(end_date));
        //     newform.submit();
        // }
    });

    // FILTER BY customer
    $('input[name=ck_customer]').change(function(){
        if($('input[name=ck_customer]').prop('checked')){
            // enable input customer
            $('select[name=customer]').removeAttr('disabled');
        }else{
            $('select[name=customer]').attr('disabled','disabled');
        }
    });

    // FILTER BY LOKASI GALIAN
    $('input[name=ck_galian]').change(function(){
        if($('input[name=ck_galian]').prop('checked')){
            // enable input customer
            $('select[name=lokasi_galian]').removeAttr('disabled');
        }else{
            $('select[name=lokasi_galian]').attr('disabled','disabled');
        }
    });

    // FILTER BY STATUS
    $('input[name=ck_customer]').change(function(){
        if($('input[name=ck_customer]').prop('checked')){
            // enable input customer
            $('select[name=customer]').removeAttr('disabled');
        }else{
            $('select[name=customer]').attr('disabled','disabled');
        }
    });

})(jQuery);
</script>
@append
