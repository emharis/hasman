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
            <h3 class="box-title">Purchase Order Report <i class="ft-excafator" ></i></h3>
              <div class="box-tools pull-right">
                <div class="btn-group pull-right">
                  <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
            <div class="row" >
                <div class="col-sm-3 col-md-3 col-lg-3" >
                    <img src="img/logo.png" class="col-sm-12" >
                </div>
                <div class="col-sm-9 col-md-9 col-lg-9" >
                    <small class="pull-right" >Created at : {{date('d-m-Y H:i:s')}}</small><br/>
                    <label class="pull-right" ><h3><i class="ft-rupiah" ></i> Purchase Order Report</h3></label>

                </div>
            </div>

            <table class="table" >
                <tbody>
                    <tr>
                        <td class="col-sm-2 col-md-2 col-lg-2">
                            <label>Start Date :</label>
                        </td>
                        <td  >
                            {{-- {{$start_date}} --}}
                        </td>
                        <td class="col-sm-2 col-md-2 col-lg-2" >
                            <label>End Date :</label>
                        </td>
                        <td  >
                            {{-- {{$end_date}}     --}}
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
                        <th>Order Date</th>
                        <th>Supplier Ref#</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Total</th>                        
                    </tr>
                </thead>
                <tbody> 
                    <?php $total=0;?>
                    @foreach($data as $dt)
                    <?php $total+= $dt->total; ?>                    
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
                        <td>
                            {{$dt->supplier}}
                        </td>
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
                        
                    </tr>
                    @endforeach

                </tbody>
                <tfoot>
                    <tr style="background-color: whitesmoke;" >
                        <td colspan="6" class="text-right" style="border-color: #DDDDDD!important;" ><label>TOTAL</label></td>
                        <td class="text-right" style="border-color: #DDDDDD!important;" ><label class="uang" >{{$total}}</label></td>
                    </tr>
                </tfoot>
            </table>
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