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
         <a href="report/sales" >Sales Reports</a> <i class="fa fa-angle-double-right" ></i> 
         Show Data Reports 
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border"  >
            <h3 class="box-title">Sales Report</h3>
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
            
            <table class="table table-condensed" >
                <tbody>
                    <tr>
                        <td class="col-sm-2 col-md-2 col-lg-2">
                            <label>Tanggal : </label>
                        </td>
                        <td  >
                            {{'[' .$start_date .']' .' - ' . '['.$end_date.']'}}
                        </td>
                        <td>
                            <label>Pekerjaan</label>
                        </td>
                        <td>
                            {{$pekerjaan->nama}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Customer : </label>
                        </td>
                        <td>{{$customer->nama}}</td>
                        <td>
                            <label>Alamat</label>
                        </td>
                        <td>
                            {{$pekerjaan->alamat . ', ' . $pekerjaan->desa . ', ' . $pekerjaan->kecamatan .', ' . $pekerjaan->kabupaten}}
                        </td>
                    </tr>
                    
                    <tr>
                        <td colspan="5" ></td>
                    </tr>
                </tbody>
            </table>
            
            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed" >
                <thead>
                    <tr>
                        <th>No</th>
                        <th>SO Ref#</th>
                        <th>SO Date</th>
                        <th>Status</th>
                        {{-- <th>Customer</th> --}}
                        <th>Nopol</th>
                        {{-- <th class="col-sm-2 col-md-2 col-lg-2" >Pekerjaan</th> --}}
                        {{-- <th class="col-sm-2 col-md-2 col-lg-2" >Alamat</th> --}}
                        <th>Total</th>
                        <th>Amount Due</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dt )
                        <tr>
                            <td>{{$rownum++}}</td>
                            <td>
                                {{$dt->order_number}}
                            </td>
                            <td>
                                {{$dt->order_date_formatted}}
                            </td>
                            <td>
                                @if($dt->status =='O')
                                    OPEN
                                @elseif($dt->status =='V')
                                    VALIDATED
                                @elseif($dt->status =='D')
                                    DONE
                                @endif
                            </td>
                            {{-- <td>
                                {{$dt->customer}}
                            </td> --}}
                            <td>
                                @if($dt->nopol != "")
                                    {{$dt->nopol}}
                                @else
                                    -
                                @endif
                            </td>
                            {{-- <td>
                                @if($dt->pekerjaan != '')
                                    {{$dt->pekerjaan}}
                                @else 
                                    -
                                @endif
                            </td> --}}
                            {{-- <td>
                                @if($dt->alamat_pekerjaan != "")
                                    {!! $dt->alamat_pekerjaan .', ' . $dt->desa . ', <br/>'  . $dt->kecamatan . ', ' . $dt->kabupaten !!}
                                @else 
                                -
                                @endif
                            </td> --}}
                            <td class="text-right uang">
                                {{$dt->total}}
                            </td>
                            <td class="text-right uang">
                                {{$dt->amount_due}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right" style="background-color: #ecf0f5;border-color: #DDDDDD!important;" ><label>TOTAL</label></td>
                        <td class="text-right" style="background-color: #ecf0f5;border-color: #DDDDDD!important;" ><label class="uang" >{{$total != "" ? $total : 0}}</label></td>
                        <td class="text-right" style="background-color: #ecf0f5;border-color: #DDDDDD!important;" ><label class="uang" >{{$total_amount_due != "" ? $total_amount_due : 0}}</label></td>
                        
                    </tr>
                </tfoot>
            </table>
            
        </div><!-- /.box-body -->
        <div class="box-footer" >
            <a class="btn btn-danger" href="report/sales" >Close</a>
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