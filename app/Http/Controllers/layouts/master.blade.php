<!DOCTYPE html>
<html>
    <head>
        <base href="{{ URL::to('/') }}/" />
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Baja Agung APP</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"> --}}
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="css/skins/_all-skins.min.css">
        <!-- FAVICON -->
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <!-- Bootstrap Arrow Button -->
        <link rel="stylesheet" href="bootstrap/css/bootstrap-arrow-button.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <style>
            .table > tbody > tr > td, .table > thead > tr > th{
                vertical-align: middle;
            }

            table.table-bordered    {
                border-color: #AFAFB6;
            }
            table.table-bordered > thead > tr > th {
                border-color: #CACACA;
            }
            table.table-bordered > tbody > tr > td{
                border-color:#DDDDDD;
            }
            /*Table Stipe color*/
            .table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
               background-color: #EEF0F0;
            }

            /*table border*/
            /*.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th {
               border-top: none;
               border-bottom: none;
            }*/

            /*table hover*/
            /*.table-hover > tbody > tr:hover > th {
              background-color: red;
            }*/

            .table-hover>tbody>tr:hover>td, .table-hover>tbody>tr:hover>th {
              background-color: #3C8DBC;
              color:#eeeeee;
            }

            /*clear table top border */
            .table-clear > tbody > tr > td, .table-clear > thead > tr > th{
                border-top: none;
            }

            /*jadikan button flat tanpa rounding*/
            .btn {
              border-radius:1;
            }

            /*ganti warna button-primary hover di header box*/
            .box .box-header > a.btn.btn-primary:hover {
                background-color: #367fa9;
                /*background-color: red;*/
            }

            /*ganti warna button-danger hover di header box*/
            .box .box-header > a.btn.btn-danger:hover {
                background-color: #d73925;
                /*background-color: red;*/
            }

            /*format autocomplete input*/
            .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
            .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
            .autocomplete-selected { background: #FFE291; }
            .autocomplete-suggestions strong { font-weight: normal; color: red; }
            .autocomplete-group { padding: 2px 5px; }
            .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }

            /*Content Header di perkecil*/
            .content-header > h1{
                font-size: 18px;
            }
        </style>

        @yield('styles')
    </head>
    <body class="hold-transition skin-blue sidebar-mini {{$sidebar_collapse->value == '1' ? 'sidebar-collapse' : ''}}">
        <!-- Site wrapper -->
        <div class="wrapper">

            @include('layouts.header')

            <!-- =============================================== -->

            <!-- Left side column. contains the sidebar -->
            @include('layouts.sidebar')

            <!-- =============================================== -->

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                @yield('content')
            </div><!-- /.content-wrapper -->

            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Datamatic Tech Id</b>
                </div>
                <strong>Copyright &copy; 2016 <a href="">Baja Agung</a>.</strong> All rights reserved.
            </footer>

        </div><!-- ./wrapper -->

        <!-- jQuery 2.1.4 -->
        <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <!-- SlimScroll -->
        <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <!-- FastClick -->
        <script src="plugins/fastclick/fastclick.min.js"></script>
        <!-- AdminLTE App -->
        <script src="js/app.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <!--<script src="js/demo.js"></script>-->
        @yield('scripts')
        
        <script>
            (function ($) {
                $('a.sidebar-toggle').click(function () {
                    //update status sidebar toggle
                    $.get('sidebar-update');
                });
            })(jQuery);
        </script>
        

    </body>
</html>
