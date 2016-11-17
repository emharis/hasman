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
         <a href="report/delivery" >Delivery Reports</a> <i class="fa fa-angle-double-right" ></i> 
         Show Data Reports 
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border"  >
            <h3 class="box-title">Delivery Report</h3>
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
                        <td class="col-sm-1 col-md-1 col-lg-1">
                            <label>Tanggal</label>
                        </td>
                        <td>:</td>
                        <td  >
                            {{'[' .$start_date .']' .' - ' . '['.$end_date.']'}}
                        </td>
                        <td class="col-sm-1 col-md-1 col-lg-1" >
                            {{-- @if($kalkulasi != 'A'  ) --}}
                                <label>Kalkulasi</label>
                            {{-- @endif --}}
                        </td>
                        <td>
                            {{-- @if($kalkulasi != 'A'  ) --}}
                                :
                            {{-- @endif --}}
                        </td>                        
                        <td  >
                            @if($kalkulasi == 'T')
                                Tonase
                            @elseif($kalkulasi == 'K')
                                Kubikasi
                            @elseif($kalkulasi == 'R')
                                Ritase
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="col-sm-1 col-md-1 col-lg-1" >
                            <label>Customer</label>
                        </td>
                        <td>:</td>
                        <td  >
                            {{$customer->nama}}
                        </td>
                        <td>
                            @if($pekerjaan_id > 0)
                                <label>Pekerjaan</label>
                            @endif
                        </td>
                        <td>
                            @if($pekerjaan_id > 0)
                                :
                            @endif
                        </td>                        
                        <td>
                            @if($pekerjaan_id > 0)
                                {{$pekerjaan->nama}}
                            @endif
                        </td>
                    </tr>
                    @if($pekerjaan_id > 0)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <label>Alamat</label>
                        </td>
                        <td>:</td>
                        <td>
                            {{$pekerjaan->alamat .', ' . $pekerjaan->desa }}<br/>
                            {{$pekerjaan->kecamatan .', ' . $pekerjaan->kabupaten }}
                        </td>
                    </tr>
                    @endif
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
                        <th>Ref#</th>
                        <th>SO Date</th>
                        <th>Delivery Date</th>
                        {{-- <th>Status</th> --}}
                        {{-- <th>Customer</th> --}}
                        @if($pekerjaan_id == 0)
                            <th>Pekerjaan</th>
                        @endif
                        <th>Driver</th>
                        <th>Nopol</th>
                        <th>Material</th>
                        <th>Kal</th>
                        <th>Vol</th>
                        <th>Netto</th>
                        <th>Qty</th>
                        {{-- <th>Total</th> --}}
                        {{-- <th>Amount Due</th> --}}
                    </tr>
                </thead>
                <tbody>
                    <?php $total_ritase=0; ?>
                    <?php $total_tonase=0; ?>
                    <?php $total_kubikasi=0; ?>
                    @foreach($data as $dt )

                        @if($dt->kalkulasi == 'R')
                            <?php $total_ritase+=$dt->qty; ?>
                        @elseif($dt->kalkulasi == 'T')
                            <?php $total_tonase+=$dt->netto; ?>                            
                        @else 
                            <?php $total_kubikasi+=$dt->volume; ?>
                        @endif

                        <tr>
                            <td>{{$rownum++}}</td>
                            <td>
                                {{$dt->delivery_order_number}}
                            </td>
                            <td>
                                {{$dt->order_date_formatted}}
                            </td>
                            <td>
                                {{$dt->delivery_date_formatted}}
                            </td>
                            @if($pekerjaan_id == 0)
                                <td>
                                    {{$dt->pekerjaan}}
                                </td>
                            @endif
                            <td>
                                {{$dt->karyawan}}
                            </td>
                            <td>
                                @if($dt->nopol != "")
                                    {{$dt->nopol}}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                {{$dt->material}}
                            </td>
                            <td>
                                {{$dt->kalkulasi}}
                            </td>
                            <td class="text-right">
                                @if($dt->kalkulasi == 'K')
                                {{$dt->volume}}
                                @else
                                -
                                @endif
                            </td>
                            <td class="text-right">
                                @if($dt->kalkulasi == 'T')
                                    {{$dt->netto}}
                                @else
                                -
                                @endif
                            </td>
                            <td class="text-right" >
                                @if($dt->kalkulasi == 'R')
                                    {{$dt->qty}}
                                @else
                                -
                                @endif
                            </td>
                           
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <td class="text-right" style="background-color: #ecf0f5;border-color: #DDDDDD!important;" colspan="{{$pekerjaan_id == 0 ? 9 : 8}}" ><label>TOTAL</label></td>
                    <td class="text-right" style="background-color: #ecf0f5;border-color: #DDDDDD!important;" >{{$total_kubikasi}}</td>
                    <td class="text-right" style="background-color: #ecf0f5;border-color: #DDDDDD!important;">{{$total_tonase}}</td>
                    <td class="text-right" style="background-color: #ecf0f5;border-color: #DDDDDD!important;">{{$total_ritase}}</td>
                    
                </tfoot>
            </table>
            
        </div><!-- /.box-body -->
        <div class="box-footer" >
            <a class="btn btn-danger" href="report/delivery" >Close</a>
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