@extends('layouts.app')

@section('page_title')
    Vehicle
@endsection

@section('page_header')
    <h1>
        Vehicle
        <small>Add New Vehicle</small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('vehicle') }}"><i class="fa fa-dashboard"></i> Vehicle</a></li>
        <li class="active"><i></i>Create</li>
    </ol>
@endsection

@section('content')
    {!! Form::open(['route'=>'vehicle.store','role'=>'form','class'=>'form-horizontal','id'=>'form-create-vehicle','files'=>true]) !!}
        <div class="row">
            <div class="col-md-12">
                <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                    <div class="box-header with-border">
                        <h3 class="box-title">Basic Information</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group{{ $errors->has('code') ? ' has-errors' : '' }}">
                            {!! Form::label('code','Code',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-4">
                                {!! Form::text('code',$code_fix,['class'=>'form-control','placeholder'=>'Code of the vehicle','id'=>'code', 'readonly']) !!}
                                @if ($errors->has('code'))
                                  <span class="help-block">
                                    <strong>{{ $errors->first('code') }}</strong>
                                  </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('vehicle_category') ? ' has-error' : '' }}">
                            {!! Form::label('vehicle_cat','Vehicle',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-4">
                                {{ Form::select('vehicle_category',$vehicle_cat,null,['class'=>'form-control','placeholder'=>'Select Category','id'=>'vehicle_category']) }}
                                @if($errors->has('vehicle_category'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('vehicle_category') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('number_of_vehicle') ? ' has-error' : '' }}">
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
                          <div class="col-sm-10">
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

@section('additional_scripts')
  <script type="text/javascript">
    $('#btn-submit-vehicle').click(function(){
      $(this).attr('disable', 'disabled');
    })
  </script>
@endsection
