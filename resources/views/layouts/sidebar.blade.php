<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        {{-- <img src="img/logo-bg-white.png" class="img-responsive"> --}}
        <ul class="sidebar-menu">

            @foreach($sidemenu as $dt )
                @if(count($dt->childmenu) > 0)
                    <li class="treeview {{Request::is($dt->class_request) ? 'active':''}}" >
                        <a href="{{$dt->href}}">
                            <i class="{{$dt->icon}}"></i>
                            <span>{{$dt->title}}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            @foreach($dt->childmenu as $chd)
                                <li class="{{Request::is($chd->class_request) ? 'active':''}}" ><a href="{{$chd->href}}"><i class="fa fa-circle-o"></i> {{$chd->title }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @else

                    @if(!$dt->parentMenu)
                        <li class="{{Request::is($dt->class_request) ? 'active':''}}" >
                            <a href="{{$dt->href}}"> 
                                <i class="{{$dt->icon}}"></i> 
                                <span>{{$dt->title}} </span> 
                            </a>
                        </li>
                    @endif
                @endif
            @endforeach

            {{-- <li class="{{Request::is('home') ? 'active':''}}" >
                <a href="home"> <i class="fa fa-home"></i> <span>Home</span> </a>
            </li>
            
            <li class="treeview {{Request::is('master/*') ? 'active':''}}" >
                <a href="#">
                    <i class="fa fa-th"></i>
                    <span>Master</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('master/lokasi*') ? 'active':''}}" ><a href="master/lokasi"><i class="fa fa-circle-o"></i> Data Lokasi Galian</a></li>
                    <li class="{{Request::is('master/armada*') ? 'active':''}}" ><a href="master/armada"><i class="fa fa-circle-o"></i> Data Armada</a></li>
                    <li class="{{Request::is('master/jabatan*') ? 'active':''}}" ><a href="master/jabatan"><i class="fa fa-circle-o"></i> Data Jabatan</a></li>
                    <li class="{{Request::is('master/karyawan*') ? 'active':''}}" ><a href="master/karyawan"><i class="fa fa-circle-o"></i> Data Karyawan</a></li>
                    <li class="{{Request::is('master/supplier*') ? 'active':''}}" ><a href="master/supplier"><i class="fa fa-circle-o"></i> Data Supplier</a></li>
                    <li class="{{Request::is('master/customer*') ? 'active':''}}" ><a href="master/customer"><i class="fa fa-circle-o"></i> Data Customer</a></li>
                    <li class="{{Request::is('master/material*') ? 'active':''}}" ><a href="master/material"><i class="fa fa-circle-o"></i> Data Material</a></li>
                    <li class="{{Request::is('master/alat*') ? 'active':''}}" ><a href="master/alat"><i class="fa fa-circle-o"></i> Data Alat Berat</a></li>
                    <li class="{{Request::is('master/unit*') ? 'active':''}}" ><a href="master/unit"><i class="fa fa-circle-o"></i> Data Product Unit</a></li>
                    <li class="{{Request::is('master/product*') ? 'active':''}}" ><a href="master/product"><i class="fa fa-circle-o"></i> Data Product</a></li>
                </ul>
            </li>

            <li class="{{Request::is('purchase*') ? 'active':''}}" >
                <a href="purchase/order"> <i class="fa fa-calculator"></i> <span>Purchase Orders</span> </a>
            </li>

            <li class="{{Request::is('sales*') ? 'active':''}}" >
                <a href="sales/order"> <i class="fa fa-shopping-cart"></i> <span>Sales Orders</span> </a>
            </li>

            <li class="{{Request::is('delivery*') ? 'active':''}}" >
                <a href="delivery/order"> <i class="fa fa-truck"></i> <span>Delivery Orders</span> </a>
            </li>

            <li class="treeview {{Request::is('invoice/*') ? 'active':''}}" >
                <a href="#">
                    <i class="fa fa-newspaper-o"></i>
                    <span>Invoices</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('invoice/customer*') ? 'active':''}}" ><a href="invoice/customer"><i class="fa fa-circle-o"></i> Customer Invoices</a></li>
                    <li class="{{Request::is('invoice/supplier/bill*') ? 'active':''}}" ><a href="invoice/supplier/bill"><i class="fa fa-circle-o"></i> Supplier Bills</a></li>
                </ul>
            </li>

            <li class="{{Request::is('dailyhd') ? 'active':''}}" >
                <a href="dailyhd"> <i class="ft-excafator"></i> <span>Harian Alat Berat</span> </a>
            </li>

            <li class="{{Request::is('attendance/*') ? 'active':''}}" >
                <a href="attendance/attend"> <i class="fa fa-calendar-check-o"></i> <span>Attendance</span> </a>
            </li>

            <li class="treeview {{Request::is('payroll/*') ? 'active':''}}" >
                <a href="#">
                    <i class="fa fa-file-text-o"></i>
                    <span>Payroll</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('payroll/staff*') ? 'active':''}}" ><a href="payroll/staff"><i class="fa fa-circle-o"></i> Staff</a></li>
                    <li class="{{Request::is('payroll/driver*') ? 'active':''}}" ><a href="payroll/driver"><i class="fa fa-circle-o"></i> Driver</a></li>
                </ul>
            </li>

            <li class="{{Request::is('cashbook') ? 'active':''}}" >
                <a href="cashbook"> <i class="fa fa-book"></i> <span>Cashbook</span> </a>
            </li>

            <li class="treeview {{Request::is('report/*') ? 'active':''}}" >
                <a href="#">
                    <i class="fa fa-print"></i>
                    <span>Reports</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('report/purchase*') ? 'active':''}}" ><a href="report/purchase"><i class="fa fa-circle-o"></i> Purchases</a></li>
                    <li class="{{Request::is('report/sales*') ? 'active':''}}" ><a href="report/sales"><i class="fa fa-circle-o"></i> Sales</a></li>
                </ul>
            </li>

            <li class="treeview {{Request::is('setting/*') ? 'active':''}}" >
                <a href="#">
                    <i class="fa fa-cogs"></i>
                    <span>Setting</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('setting/user*') ? 'active':''}}" ><a href="setting/user"><i class="fa fa-circle-o"></i> User</a></li>
                </ul>
            </li> --}}

            <li style="background-color: #DD4B39;" >
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <i class="fa fa-bars" class=""></i> 
                    <span style="color:transparent;" >.</span> 
                </a>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
