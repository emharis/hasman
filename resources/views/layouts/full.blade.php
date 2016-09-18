<!DOCTYPE html>
<html>
    <head>
        <base href="{{ URL::to('/') }}/"/>
        <meta charset="utf-8">
            <meta content="IE=edge" http-equiv="X-UA-Compatible">
                <title>
                    Baja Agung APP
                </title>
                <!-- Tell the browser to be responsive to screen width -->
                <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
                    <!-- Bootstrap 3.3.5 -->
                    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
                        <!-- Font Awesome -->
                        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
                            <!-- Ionicons -->
                            <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
                                <!-- Theme style -->
                                <link href="css/AdminLTE.min.css" rel="stylesheet">
                                    <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
                                    <link href="css/skins/_all-skins.min.css" rel="stylesheet">
                                        <!-- FAVICON -->
                                        <link href="favicon.ico" rel="shortcut icon" type="image/x-icon">
                                            <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
                                            <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
                                            <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
                                            @yield('styles')
                                        </link>
                                    </link>
                                </link>
                            </link>
                        </link>
                    </link>
                </meta>
            </meta>
        </meta>
    </head>
    <body class="hold-transition skin-blue layout-top-nav">
        <div class="wrapper">
            
            <!-- Full Width Column -->
            <div class="content-wrapper">
                @yield('content')
                <!-- /.container -->
            </div>
            <!-- /.content-wrapper -->
            <!-- <footer class="main-footer">
                <div class="container">
                    <div class="pull-right hidden-xs">
                        <b>
                            Version
                        </b>
                        2.3.0
                    </div>
                    <strong>
                        Copyright © 2014-2015
                        <a href="http://almsaeedstudio.com">
                            Almsaeed Studio
                        </a>
                        .
                    </strong>
                    All rights reserved.
                </div>
                <!-- /.container -->
            </footer> -->
        </div>
        <!-- ./wrapper -->
        <!-- jQuery 2.1.4 -->
        <script src="plugins/jQuery/jQuery-2.1.4.min.js">
        </script>
        <!-- Bootstrap 3.3.5 -->
        <script src="bootstrap/js/bootstrap.min.js">
        </script>
        <!-- SlimScroll -->
        <script src="plugins/slimScroll/jquery.slimscroll.min.js">
        </script>
        <!-- FastClick -->
        <script src="plugins/fastclick/fastclick.min.js">
        </script>
        <!-- AdminLTE App -->
        <script src="js/app.min.js">
        </script>
        <!-- AdminLTE for demo purposes -->
        <!--<script src="js/demo.js"></script>-->
        <script>
            (function ($) {
                $('a.sidebar-toggle').click(function () {
                    //update status sidebar toggle
                    $.get('sidebar-update');
                });
            })(jQuery);
        </script>
        @yield('scripts')
    </body>
</html>
