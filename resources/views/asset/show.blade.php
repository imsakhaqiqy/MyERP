@extends('layouts.app')

@section('page_title')
  Asset Detail
@endsection

@section('page_header')
  <h1>
    Asset
    <small>Detail Asset </small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('asset') }}"><i class="fa fa-dashboard"></i> Asset</a></li>
    <li class="active"><i></i>{{ $asset->code }}</li>
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
              <td><b>Asset Code</b></td>
              <td>{{ $asset->code }}</td>
            </tr>
            <tr>
              <td><b>Purchase Date</b></td>
              <td>{{ $asset->date_purchase }}</td>
            </tr>
            <tr>
              <td><b>Name</b></td>
              <td>{{ $asset->name }}</td>
            </tr>
            <tr>
              <td><b>Notes</b></td>
              <td>{{ $asset->notes }}</td>
            </tr>
            <tr>
              <td><b>Amount</b></td>
              <td>{{ number_format($asset->amount) }}</td>
            </tr>
            <tr>
              <td><b>Residula Value</b></td>
              <td>{{ number_format($asset->residual_value) }}</td>
            </tr>
            <tr>
              <td><b>Periode</b></td>
              <td>{{ $asset->periode }}&nbsp;Bulan&nbsp;({{$asset->periode/12}}&nbsp;Tahun)</td>
            </tr>
            <tr>
              <td><b>Created At</b></td>
              <td>{{ $asset->created_at }}</td>
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

  </script>
@endsection
