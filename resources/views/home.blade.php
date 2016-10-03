@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Home
    </h1>
<!--    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
    </ol>-->
</section>

<!-- Main content -->
<section class="content">

    {{-- <!-- Default box -->
    <div class="box box-solid">
        <div class="box-body">
            
        </div>
    </div><!-- /.box --> --}}

    <div class="row" >
        <div class="col-xs-3 col-lg-3" >
            <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>{{$open_so_count}}</h3>
                  <p>Open Sales Orders</p>
                </div>
                <div class="icon">
                  <i class="fa fa-shopping-cart"></i>
                </div>
                <a href="sales/order/filter?filter_by=O" class="small-box-footer">
                  More info <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
        </div>

        <div class="col-xs-3 col-lg-3" >
            <div class="small-box bg-green">
                <div class="inner">
                  <h3>{{$open_do_count}}</h3>
                  <p>Delivery Orders to do</p>
                </div>
                <div class="icon">
                  <i class="fa fa-truck"></i>
                </div>
                <a href="delivery/order/filter?filter_by=D" class="small-box-footer">
                  More info <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
        </div>

        <div class="col-xs-3 col-lg-3" >
            <div class="small-box bg-red">
                <div class="inner">
                  <h3>{{$open_ci_count}}</h3>
                  <p>Open Customer Invoices</p>
                </div>
                <div class="icon">
                  <i class="fa fa-newspaper-o"></i>
                </div>
                <a href="invoice/customer/filter?filter_by=O" class="small-box-footer">
                  More info <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
        </div>



    </div>

</section><!-- /.content -->
@stop