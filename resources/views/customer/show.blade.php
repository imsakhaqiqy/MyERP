@extends('layouts.app')

@section('page_title')
  Customer Detail
@endsection

@section('page_header')
  <h1>
    Customer
    <small>Detail Customer</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('customer') }}"><i class="fa fa-dashboard"></i> Customer</a></li>
    <li class="active"><i></i> {{ $customer->code }}</li>
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
                <td style="width:20%;">Code</td>
                <td>:</td>
                <td>{{ $customer->code }}</td>
              </tr>
              <tr>
                <td style="width:20%;">Name</td>
                <td>:</td>
                <td> {{ $customer->name }}</td>
              </tr>
              <tr>
                <td style="width:20%;">Phone</td>
                <td>:</td>
                <td> {{ $customer->phone_number }}</td>
              </tr>
              <tr>
                <td style="width:20%;">Address</td>
                <td>:</td>
                <td> {!! nl2br($customer->address) !!}</td>
              </tr>
              <tr>
                <td style="width:20%;">Term</td>
                <td>:</td>
                <td> {{ $customer->invoice_term->name }}</td>
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
