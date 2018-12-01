@extends('layouts.app')

@section('page_title')
  Invoice Term Detail
@endsection

@section('page_header')
  <h1>
    Invoice Term
    <small>Detail Invoice Term</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('invoice-term') }}"><i class="fa fa-dashboard"></i> Invoice Term</a></li>
    <li class="active"><i></i> {{ $invoice_term->name }}</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-7">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-bars"></i>&nbsp;General Information</h3>

        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <tr>
                <td style="width:20%;">Periode</td>
                <td>:</td>
                <td>{{ $invoice_term->name }}</td>
              </tr>
              <tr>
                <td style="width:20%;">Day Many(s)</td>
                <td>:</td>
                <td> {{ $invoice_term->day_many }}</td>
              </tr>
              <tr>
                <td style="width:20%;">Created At</td>
                <td>:</td>
                <td> {{ $invoice_term->created_at }}</td>
              </tr>
            </table>
          </div>
        </div>
        <div class="box-footer clearfix">

        </div>
      </div>
    </div>
  </div>
@endsection()
