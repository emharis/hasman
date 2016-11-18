@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
{{-- <link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/> --}}
<link href="plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css"/>

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
            Delivery Reports
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
                <table class="table table-condensed table-bordered" >
                    <tbody>
                        <tr>
                            <td class="col-sm-2 col-md-2 col-lg-2" >
                                <label>Tanggal</label>
                            </td>
                            <td class="col-sm-4 col-md-4 col-lg-4" >
                                <input type="text" class="form-control input-date text-center" name="date_range">
                            </td>
                            <td class="col-sm-4 col-md-4 col-lg-4" >
                                {{-- <input type="text" class="form-control input-date" name="end_date"> --}}
                            </td>
                        </tr>
                        <tr>
                            <td class="col-sm-2 col-md-2 col-lg-2" >
                                <label>Kalkulasi</label>
                            </td>
                            <td  >
                                <select name="kalkulasi" class="form-control" >
                                    <option value="A">Semua Kalkulasi</option>
                                    <option value="R">Ritase</option>
                                    <option value="K">Kubikasi</option>
                                    <option value="T">Tonase</option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                        

                        {{-- FILTER BY CUSTOMER --}}
                        <tr >
                            <td >
                                <div class="radio" >
                                    <label>
                                        <input type="radio" name="rd_filter_by" value="by_customer" >
                                        <b>Per Customer</b>
                                    </label>
                                </div>

                            </td>
                            <td >
                                <div class="input-group">
                                    <span class="input-group-addon">Customer</span>
                                    {!! Form::select('customer',$select_customer,null,['class'=>'form-control filter_by_customer','disabled ']) !!}
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">Pekerjaan</span>
                                    {!! Form::select('pekerjaan',[],null,['class'=>'form-control filter_by_customer','disabled ']) !!}
                                </div>
                            </td>
                        </tr>
                       
                        {{-- ============================================================= --}}

                        <tr >
                            <td >
                                <div class="radio" >
                                    <label>
                                        <input type="radio" name="rd_filter_by" value="by_lokasi_galian" >
                                        <b>Per Lokasi Galian</b>
                                    </label>
                                </div>

                            </td>
                            <td >
                                {!! Form::select('lokasi_galian',$select_lokasi_galian,null,['class'=>'form-control','disabled ']) !!}
                            </td>
                            <td>
                                
                            </td>
                        </tr>

                        <tr >
                            <td >
                                <div class="radio" >
                                    <label>
                                        <input type="radio" name="rd_filter_by" value="by_driver" >
                                        <b>Per Driver/Armada</b>
                                    </label>
                                </div>

                            </td>
                            <td >
                                {!! Form::select('driver',$select_driver,null,['class'=>'form-control','disabled ']) !!}
                            </td>
                            <td>
                                
                            </td>
                        </tr>

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
<script src="plugins/bootstrap-daterangepicker/moment.js" type="text/javascript"></script>
<script src="plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
{{-- <script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script> --}}

<script type="text/javascript">
(function ($) {

    // SET DATEPICKER
    // $('.input-date').datepicker({
    //     format: 'dd-mm-yyyy',
    //     todayHighlight: true,
    //     autoclose: true
    // });
    // $('input[name=start_date]').change(function(){
    //     $('input[name=end_date]').datepicker('remove');
    //     $('.input-date').datepicker({
    //         format: 'dd-mm-yyyy',
    //         todayHighlight: true,
    //         autoclose: true,
    //         startDate:$('input[name=start_date]').val()
    //     });
    // });

    $('input[name=date_range]').daterangepicker({
            "autoApply": true,
            locale: {
              format: 'DD/MM/YYYY'
            },
        }, 
        function(start, end, label) {
            // alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            start_date = start.format('DD-MM-YYYY');
            end_date = end.format('DD-MM-YYYY');
        });
    // END OF SET DATEPICKER

    // select to null
    $('select').val([]);
    $('input[name=date_range]').val('');

    // SELECT CUSTOMER CHANGE
    $('select[name=customer]').change(function(){
        // get data pekerjaan
        var customer_id = $(this).val();
        var url = 'api/get-pekerjaan-by-customer/'+customer_id;
        $('select[name=pekerjaan]').empty();
        $('select[name=pekerjaan]').val([]);
        // add semua option
            $('select[name=pekerjaan]')
                    .append($("<option></option>")
                    .attr("value",0)
                    .text('Semua Pekerjaan'));

        $.get(url,null,function(res){
            var select_data = JSON.parse(res);
            $.each(select_data,function(i){
                $('select[name=pekerjaan]')
                    .append($("<option></option>")
                    .attr("value",this.id)
                    .text(this.nama));
            });
        });
    });
    // END OF SELECT CUSTOMER CHANGE

    // FILTER BY customer
    $('input[name=rd_filter_by]').change(function(){
        rd_filter_by = $(this).val();
        if(rd_filter_by == 'by_customer'){
            // enable filter by customer
            $('.filter_by_customer').removeAttr('disabled');
            // empty select pekerjaan
            $('select[name=pekerjaan]').empty();

            // disable select lokasi galian
            $('select[name=lokasi_galian]').attr('disabled','disabled');

            // disable select driver
            $('select[name=driver]').attr('disabled','disabled');
        }else if(rd_filter_by == 'by_lokasi_galian'){
            // disable filter by customer
            $('.filter_by_customer').attr('disabled','disabled');
            // $('.filter_by_customer').val([]);

            // enable kan select lokasi galian
            $('select[name=lokasi_galian]').removeAttr('disabled');

            // disable select lokasi galian
            $('select[name=driver]').attr('disabled','disabled');

        }else if(rd_filter_by == 'by_driver'){
            // disable filter by customer
            $('.filter_by_customer').attr('disabled','disabled');
            

            // enable kan select lokasi galian
            $('select[name=lokasi_galian]').attr('disabled','disabled');

            // disable select lokasi galian
            $('select[name=driver]').removeAttr('disabled');
        }

        $('select[name=customer]').val([]);
        $('select[name=pekerjaan]').val([]);
        $('select[name=lokasi_galian]').val([]);
        $('select[name=driver]').val([]);
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

    // SUBMIT FORM
    var start_date;
    var end_date;
    var rd_filter_by;
    $('#btn-submit').click(function(){

        var kalkulasi = $('select[name=kalkulasi]').val();

        if(start_date != "" 
                && start_date != undefined 
                && end_date != "" 
                && end_date != undefined 
                && kalkulasi != undefined
                && kalkulasi != ""
                ){

            // submit form
            var newform = $('<form>').attr('method','POST');
            newform.attr('action','report/delivery/report-by-date');

            if(rd_filter_by == 'by_customer'){
                var customer_id = $('select[name=customer]').val();
                var pekerjaan_id = $('select[name=pekerjaan]').val();

                newform.attr('action','report/delivery/report-by-customer');
                newform.append($('<input>').attr('type','hidden').attr('name','pekerjaan_id').val(pekerjaan_id));
                newform.append($('<input>').attr('type','hidden').attr('name','customer_id').val(customer_id));

            }else if(rd_filter_by == 'by_lokasi_galian'){
                newform.attr('action','report/delivery/report-by-lokasi-galian');

                var lokasi_galian_id = $('select[name=lokasi_galian]').val();
                newform.append($('<input>').attr('type','hidden').attr('name','lokasi_galian_id').val(lokasi_galian_id));
                
            }else if(rd_filter_by == 'by_driver'){
                newform.attr('action','report/delivery/report-by-driver');

                var driver_id = $('select[name=driver]').val();
                newform.append($('<input>').attr('type','hidden').attr('name','driver_id').val(driver_id));
                
            }
            

            newform.append($('<input>').attr('type','hidden').attr('name','start_date').val(start_date));
            newform.append($('<input>').attr('type','hidden').attr('name','end_date').val(end_date));
            newform.append($('<input>').attr('type','hidden').attr('name','kalkulasi').val(kalkulasi));
            newform.submit();

            // ------------------------------
        }else{
            alert('Lengkapi data yang kosong');
        }

        return false;

        
    });

    

})(jQuery);
</script>
@append
