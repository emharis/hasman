<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        {{-- <img src="img/logo-bg-white.png" class="img-responsive"> --}}
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="{{Request::is('home') ? 'active':''}}" >
                <a href="home"> <i class="fa fa-home"></i> <span>Home</span> </a>
            </li>
            
            
            
            <!--Menu Inventory-->
            <li class="treeview {{Request::is('master/*') ? 'active':''}}" >
                <a href="#">
                    <i class="fa fa-th"></i>
                    <span>Master</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('master/lokasi*') ? 'active':''}}" ><a href="master/lokasi"><i class="fa fa-circle-o"></i> Lokasi Galian</a></li>        
                    <li class="{{Request::is('master/armada*') ? 'active':''}}" ><a href="master/armada"><i class="fa fa-circle-o"></i> Armada</a></li>        
                    <li class="{{Request::is('master/jabatan*') ? 'active':''}}" ><a href="master/jabatan"><i class="fa fa-circle-o"></i> Jabatan</a></li>        
                    <li class="{{Request::is('master/karyawan*') ? 'active':''}}" ><a href="master/karyawan"><i class="fa fa-circle-o"></i> Karyawan</a></li>        
                    <li class="{{Request::is('master/supplier*') ? 'active':''}}" ><a href="master/supplier"><i class="fa fa-circle-o"></i> Supplier</a></li>        
                    <li class="{{Request::is('master/customer*') ? 'active':''}}" ><a href="master/customer"><i class="fa fa-circle-o"></i> Customer</a></li>        
                    <li class="{{Request::is('master/material*') ? 'active':''}}" ><a href="master/material"><i class="fa fa-circle-o"></i> Material</a></li>        
                    <li class="{{Request::is('master/alat*') ? 'active':''}}" ><a href="master/alat"><i class="fa fa-circle-o"></i> Alat Berat</a></li>        
                </ul>
            </li>

            {{-- <li class="treeview {{Request::is('purchase/*') ? 'active':''}}" >
                <a href="#">
                    <i class="fa fa-money"></i>
                    <span>Purchases</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('purchase/order*') ? 'active':''}}" ><a href="master/lokasi"><i class="fa fa-circle-o"></i> Purchase Orders</a></li>  
                </ul>
            </li> --}}

            <li class="treeview {{Request::is('sales/*') ? 'active':''}}" >
                <a href="#">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Sales</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('sales/order*') ? 'active':''}}" ><a href="sales/order"><i class="fa fa-circle-o"></i> Sales Orders</a></li>  
                </ul>
            </li>

            <li class="treeview {{Request::is('delivery/*') ? 'active':''}}" >
                <a href="#">
                    <i class="fa fa-truck"></i>
                    <span>Delivery</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('delivery/order*') ? 'active':''}}" ><a href="delivery/order"><i class="fa fa-circle-o"></i> Delivery Order</a></li>  
                </ul>
            </li>

            {{-- <li class="treeview {{Request::is('invoice/*') ? 'active':''}}" >
                <a href="#">
                    <i class="fa fa-newspaper-o"></i>
                    <span>Invoices</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('invoice/customer-invoice*') ? 'active':''}}" ><a href="invoice/customer-bill"><i class="fa fa-circle-o"></i> Customer Invoices</a></li>  
                    <li class="{{Request::is('invoice/supplier-invoice*') ? 'active':''}}" ><a href="invoice/supplier-bill"><i class="fa fa-circle-o"></i> Supplier Bills</a></li>  
                </ul>
            </li>
 --}}
            <li class="treeview {{Request::is('setting/*') ? 'active':''}}" >
                <a href="#">
                    <i class="fa fa-cogs"></i>
                    <span>Setting</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('setting/user*') ? 'active':''}}" ><a href="setting/user"><i class="fa fa-circle-o"></i> User</a></li>  
                </ul>
            </li>
           
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>