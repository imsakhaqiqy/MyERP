@extends('layouts.app')

@section('page_title')
  Bank Detail
@endsection

@section('page_header')
  <h1>
    Bank
    <small>Detail Bank</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('bank') }}"><i class="fa fa-dashboard"></i> Bank</a></li>
    <li class="active"><i></i>{{ $bank->code }}</li>
  </ol>
@endsection

@section('content')

  <div class="row">
    <div class="col-lg-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-bars"></i>&nbsp;General Information</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table class="table">
            <tr>
              <td><b>Bank Code</b></td>
              <td>{{ $bank->code }}</td>
            </tr>
            <tr>
              <td><b>Bank Name</b></td>
              <td>{{ $bank->name }}</td>
            </tr>
            <tr>
              <td><b>Account Name</b></td>
              <td>{{ $bank->account_name }}</td>
            </tr>
            <tr>
              <td><b>Account Number</b></td>
              <td>{{ $bank->account_number }}</td>
            </tr>
            <tr>
              <td><b>Value</b></td>
              <td>{{ number_format($bank->value) }}</td>
            </tr>

          </table>
        </div><!-- /.box-body -->
        <div class="box-footer clearfix">

        </div>
      </div><!-- /.box -->
    </div>
  </div>
@endsection

@section('additional_scripts')
  {!! Html::script('js/autoNumeric.js') !!}
  <script type="text/javascript">
    $('#value').autoNumeric('init',{
        aSep:',',
        aDec:'.'
    });
  </script>
@endsection
