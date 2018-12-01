@extends('layouts.app')

@section('page_title')
    Vehicle
@endsection

@section('page_header')
    <h1>
        Vehicle
        <small>Edit Vehicle</small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('vehicle') }}"><i class="fa fa-dashboard"></i> Vehicle</a></li>
        <li>{{ $vehicle->code }}</li>
        <li active="active"><i></i>Edit</li>
    </ol>
@endsection

@section('content')
    {!! Form::model($vehicle,['route'=>['vehicle.update',$vehicle->id],'class'=>'form-horizontal','method'=>'put','files'=>true]) !!}
    <div class="row">
        <div class="col-md-12">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3 class="box-title">Basic Information</h3>
                </div>
                <div class="box-body">
                    <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                        {!! Form::label('code','Code',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-4">
                            {!! Form::text('code',null,['class'=>'form-control','placeholder'=>'Code of the vehicle','id'=>'code', 'readonly']) !!}
                            @if($errors->has('code'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('code') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                        {!! Form::label('vehicle_cat','Vehicle',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-4">
                            {{ Form::select('category',$vehicle_cat,null,['class'=>'form-control','id'=>'category']) }}
                            @if($errors->has('category'))
                            <span class="help-block">
                                <strong>{{ $errors->first('category') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('number_of_vehicle') ? ' has-errors' : '' }}">
                        {!! Form::label('number_of_vehicle','Number of Vehicle',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-4">
                            {!! Form::text('number_of_vehicle',null,['class'=>'form-control','placeholder'=>'Number of the vehicle','id'=>'number_of_vehicle']) !!}
                            @if ($errors->has('number_of_vehicle'))
                              <span class="help-block">
                                <strong>{{ $errors->first('number_of_vehicle') }}</strong>
                              </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('', '', ['class'=>'col-sm-2 control-label']) !!}
                      <div class="col-sm-4">
                        <a href="{{ url('vehicle') }}" class="btn btn-default">
                          <i class="fa fa-repeat"></i>&nbsp;Cancel
                        </a>&nbsp;
                        <button type="submit" class="btn btn-info" id="btn-submit-vehicle">
                          <i class="fa fa-save"></i>&nbsp;Submit
                        </button>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
