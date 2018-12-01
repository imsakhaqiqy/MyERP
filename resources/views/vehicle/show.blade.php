@extends('layouts.app')

@section('page_title')
  Vehicle Detail
@endsection

@section('page_header')
    <h1>
        Vehicle
        <small>Detail Vehicle</small>
    </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('vehicle') }}"><i class="fa fa-dashboard"></i> Vehicle</a></li>
    <li class="active"><i></i> {{ $vehicle->code }}</li>
  </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bars"></i>&nbsp;General Informations</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tr>
                                <td style="width:15%">Code</td>
                                <td>:</td>
                                <td>{{ $vehicle->code }}</td>
                            </tr>
                            <tr>
                                <td style="width:15%">Vehicle</td>
                                <td>:</td>
                                <td>
                                    @if($vehicle->category == 'motorcycle')
                                    {{ "Motorcycle" }}
                                    @elseif($vehicle->category == 'truck')
                                    {{ "Truck" }}
                                    @elseif($vehicle->category == 'pick_up')
                                    {{ "Pick Up" }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="width:15%">Number of Vehicle</td>
                                <td>:</td>
                                <td>{{ $vehicle->number_of_vehicle }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="box-footer clearfix">

                </div>
            </div>
        </div>
    </div>
@endsection
