@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

<style>
    #table-data > tbody > tr{
        cursor:pointer;
    }
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
         <a href="report/purchase" >Purchase Order Reports</a> <i class="fa fa-angle-double-right" ></i> 
         Show Data Reports 
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border"  >
            <h3 class="box-title">Purchase Order Report</h3>
              <div class="box-tools pull-right">
                <div class="btn-group pull-right">
                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Print <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                    {{-- <li><a href="#">Direct Print</a></li> --}}
                    <li><a target="_blank" href="report/purchase/filter-by-date/pdf/{{$start_date}}/{{$end_date}}">Pdf</a></li>
                    <li><a href="#">Xls</a></li>
                  </ul>
                </div>
              </div>
        </div>
        <div class="box-body">
            {{-- <div class="row" >
                <div class="col-sm-3 col-md-3 col-lg-3" >
                    <img src="img/logo.png" class="col-sm-12" >
                </div>
                <div class="col-sm-9 col-md-9 col-lg-9" >
                    <small class="pull-right" >Created at : {{date('d-m-Y H:i:s')}}</small><br/>
                    <label class="pull-right" ><h3><i class="ft-rupiah" ></i> Purchase Order Report</h3></label>

                </div>
            </div> --}}

            <table class="table" >
                <tbody>
                    <tr>
                        <td class="col-sm-2 col-md-2 col-lg-2">
                            <label>Start Date : </label>
                        </td>
                        <td  >
                            {{$start_date}}
                        </td>
                        <td class="col-sm-2 col-md-2 col-lg-2" >
                            <label>End Date :</label>
                        </td>
                        <td  >
                            {{$end_date}}    
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="5" ></td>
                    </tr>
                </tbody>
            </table>
            
            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed " >
                <thead>
                    <tr>
                        <th style="width:25px;">No</th>
                        <th >Ref#</th>
                        <th>PO Date</th>
                        <th>Supplier Ref#</th>
                        @if($is_detailed_report == 'true')
                            <th>Kode Product</th>
                            <th>Product</th>
                        @endif
                        <th>Supplier</th>
                        
                        @if($is_detailed_report == 'true')
                            <th>Qty</th>
                            <th>Unit</th>
                            <th>Harga Satuan</th>
                            <th>Total</th>
                        @else
                            <th>Status</th>
                            <th>Total</th>                        
                            <th>Amount Due</th>                        
                        @endif
                    </tr>
                </thead>
                <tbody> 
                    @if($is_detailed_report == 'true')
                        <?php $total_detail=0;?>
                    @else
                        <?php $total=0;?>                    
                        <?php $amount_due=0;?>
                    @endif

                    @foreach($data as $dt)

                    @if($is_detailed_report == 'true')
                        <?php $total_detail+=$dt->unit_price * $dt->qty;?>

                    @else
                        <?php $total+= $dt->total; ?>                    
                        <?php $amount_due+= $dt->amount_due; ?>  
                    @endif

                    <tr data-rowid="{{$rownum}}" data-id="{{$dt->id}}">
                        <td>{{$rownum++}}</td>
                        <td>
                            {{$dt->order_number}}
                        </td>
                        <td>
                            {{$dt->order_date_formatted}}
                        </td>
                        <td>
                            @if($dt->supplier_ref)
                                {{$dt->supplier_ref}}
                            @else
                            -
                            @endif
                        </td>
                        @if($is_detailed_report == 'true')
                            <td>
                                {{$dt->kode_product}}
                            </td>
                            <td>
                                {{$dt->product}}
                            </td>
                        @endif
                        <td>
                            {{$dt->supplier}}
                        </td>                        
                        @if($is_detailed_report == 'true')
                            <td class="text-right" >
                                {{$dt->qty}}
                            </td>
                            <td>
                                {{$dt->product_unit}}
                            </td>
                            <td class="text-right uang">
                                {{$dt->unit_price}}
                            </td>
                            <td class="text-right uang">
                                {{$dt->unit_price * $dt->qty}}
                            </td>
                        @else
                            <td>
                                @if($dt->status == 'O')
                                    Open
                                @elseif($dt->status == 'V')
                                    Validated
                                @else
                                    Done
                                @endif
                            </td>
                            <td class="text-right uang">
                                {{$dt->total}}
                            </td>
                            <td class="text-right uang">
                                {{$dt->amount_due}}
                            </td>
                        @endif
                        
                        
                    </tr>
                    @endforeach

                </tbody>
                <tfoot>
                    @if($is_detailed_report == 'true')
                        <tr style="background-color: whitesmoke;" >
                            <td colspan="10" class="text-right" style="border-color: #DDDDDD!important;" ><label>TOTAL</label></td>
                            <td class="text-right" style="border-color: #DDDDDD!important;" ><label class="uang" >{{$total_detail}}</label></td>
                        </tr>
                    @else
                        <tr style="background-color: whitesmoke;" >
                            <td colspan="6" class="text-right" style="border-color: #DDDDDD!important;" ><label>TOTAL</label></td>
                            <td class="text-right" style="border-color: #DDDDDD!important;" ><label class="uang" >{{$total}}</label></td>
                            <td class="text-right" style="border-color: #DDDDDD!important;" ><label class="uang" >{{$amount_due}}</label></td>
                        </tr>
                    @endif
                    
                </tfoot>
            </table>

            @if($is_detailed_report == 'true')
                <br/>
                <div class="row" >
                    <div class="col-sm-8 col-md-8 col-lg-8" ></div>
                    <div class="col-sm-4 col-md-4 col-lg-4" >
                        <table class="table " >
                            <tbody>
                                <tr style="border-top:solid #CACACA 2px;" >
                                    <td class="text-right" >Total</td>
                                    <td>:</td>
                                    <td class="text-right" >
                                        <label class="uang" >{{$total_detail}}</label>
                                    </td>                                
                                </tr>
                                <tr style="background-color:#EEF0F0;">
                                    <td class="text-right">
                                        <i>Paid </i>
                                    </td>
                                    <td>:</td>
                                    <td class="text-right">
                                        <i><span class="uang" >{{$total_detail - $total_amount_due}}</span></i>
                                    </td>
                                </tr>
                                <tr style="border-top:solid #CACACA 2px;" >
                                    <td class="text-right" >Amount Due</td>
                                    <td>:</td>
                                    <td class="text-right" >
                                        <label class="uang" >{{$total_amount_due}}</label>
                                    </td>                                
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div><!-- /.box-body -->
        <div class="box-footer" >
            <a class="btn btn-danger" href="report/purchase" >Close</a>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

    var TBL_KATEGORI = $('#table-data').DataTable({
        sort:false
    });

    // -----------------------------------------------------
    // SET AUTO NUMERIC
    // =====================================================
    $('.uang').autoNumeric('init',{
        vMin:'0',
        vMax:'9999999999'
    });
    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
    });
    // END OF AUTONUMERIC
    

})(jQuery);
</script>
@append