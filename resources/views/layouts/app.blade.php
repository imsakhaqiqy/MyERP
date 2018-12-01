<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title')</title>

    <!-- Bootstrap Core CSS -->
    {!! Html::style('css/bootstrap/bootstrap.css') !!}

    <!-- Admin LTE Core CSS -->
    {!! Html::style('css/admin-lte/AdminLTE.css') !!}

    <!-- Font Awesome CSS -->
    {!! Html::style('css/font-awesome/font-awesome.css') !!}

    <!-- Admin LTE skins CSS -->
    {!! Html::style('css/admin-lte/skins/skin-blue.min.css') !!}


    <!-- jquery datatables-->
    {!! Html::style('css/datatables/jquery.dataTables.css') !!}

    <!--Alertify-->
    {!! Html::style('css/alertify/alertify.css') !!}
      <!--
      <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
      -->


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Additional styles -->
    @yield('additional_styles')

</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      <!-- include the main header -->
      @include('layouts.partials.main_header')

      <!-- Include the sidebar -->
      @include('layouts.partials.sidebar')

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          @yield('page_header')
          @yield('breadcrumb')
        </section>

        <!-- Main content -->
        <section class="content">
          <!--Flash Session message-->
            <div class="row">
              <div class="col-md-12">
                @if(Session::has('successMessage'))
                  <div class="alert alert-success alert-dismissible" role="alert" id="alert-success">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <strong>Success...!</strong> <span id="success-info"> {{ Session::get('successMessage') }}</span>
                  </div>
                @endif
                @if(Session::has('errorMessage'))
                  <div class="alert alert-error alert-dismissible" role="alert" id="alert-error">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <strong>Error...!</strong> <span id="error-info"> {{ Session::get('errorMessage') }}</span>
                  </div>
                @endif
              </div>
            </div>
          <!--//Flash Session message-->
          @yield('content')
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <!-- Include the footer -->
      @include('layouts.partials.footer')

      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->


    <!-- Optionally, you can add Slimscroll and FastClick plugins.
         Both of these plugins are recommended to enhance the
         user experience. Slimscroll is required when using the
         fixed layout. -->



    <!-- jQuery -->
    {!! Html::script('js/jquery-1.12.4.js') !!}

    <!-- DataTables -->
    {!! Html::script('js/datatables/jquery.dataTables.js') !!}
      <!--
      <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
      -->


    <!-- Bootstrap Core JavaScript -->
    {!! Html::script('js/bootstrap/bootstrap.js') !!}

    <!-- Admin LTE Core JavaScript -->
    {!! Html::script('js/admin-lte/app.js') !!}

    {!! Html::script('js/alertify/alertify.js') !!}

    <!-- Additional javascript -->
    @yield('additional_scripts')


</body>

</html>
