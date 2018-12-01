@extends('layouts.app')

@section('page_title')
  Driver
@endsection

@section('page_header')
  <h1>
    Driver
    <small>Edit Driver</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('driver') }}"><i class="fa fa-dashboard"></i> Driver</a></li>
    <li> {{ $driver->code }}</li>
    <li class="active"><i></i> Edit</li>
  </ol>
@endsection

@section('content')
  {!! Form::model($driver, ['route'=>['driver.update', $driver->id], 'class'=>'form-horizontal','method'=>'put', 'files'=>true]) !!}
  <div class="row">
    <div class="col-md-7">
      <!--BOX Basic Informations-->
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Basic Information</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
            {!! Form::label('code', 'Code', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              {!! Form::text('code',null,['class'=>'form-control', 'placeholder'=>'Code of the driver', 'id'=>'code', 'readonly']) !!}
              @if ($errors->has('code'))
                <span class="help-block">
                  <strong>{{ $errors->first('code') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            {!! Form::label('name', 'Name', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              {!! Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Name of the driver', 'id'=>'name']) !!}
              @if ($errors->has('name'))
                <span class="help-block">
                  <strong>{{ $errors->first('name') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group">
              {!! Form::label('', '', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              <a href="{{ url('driver') }}" class="btn btn-default">
                <i class="fa fa-repeat"></i>&nbsp;Cancel
              </a>&nbsp;
              <button type="submit" class="btn btn-info" id="btn-submit-driver">
                <i class="fa fa-save"></i>&nbsp;Submit
              </button>
            </div>
          </div>
        </div><!-- /.box-body -->
      </div>
      <!--ENDBOX Basic Informations-->
    </div>
    <div class="col-md-5">
      <!--BOX Basic Informations-->
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Primary Information</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="form-group{{ $errors->has('primary_phone_number') ? ' has-error' : '' }}">
            {!! Form::label('contact_number', 'Phone', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              {!! Form::text('contact_number',null,['class'=>'form-control', 'placeholder'=>'Primary phone of the driver', 'id'=>'contact_number']) !!}
              @if ($errors->has('contact_number'))
                <span class="help-block">
                  <strong>{{ $errors->first('contact_number') }}</strong>
                </span>
              @endif
            </div>
          </div>
        </div><!-- /.box-body -->
      </div>
      <!--ENDBOX Basic Informations-->
    </div>
  </div>
  {!! Form::close() !!}
@endsection

@section('additional_scripts')
  <script type="text/javascript">
    $('#btn-submit-driver').click(function(){
      $(this).attr('disable', 'disabled');
    })
  </script>
@endsection
