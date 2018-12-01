@extends('layouts.app')

@section('page_title')
  Driver
@endsection

@section('page_header')
  <h1>
    Driver
    <small>Add New Driver</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('driver') }}"><i class="fa fa-dashboard"></i> Driver</a></li>
    <li class="active"><i></i> Create</li>
  </ol>
@endsection

@section('content')
  {!! Form::open(['route'=>'driver.store','role'=>'form','class'=>'form-horizontal','id'=>'form-create-driver','files'=>true]) !!}
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
              {!! Form::text('code',$code_fix,['class'=>'form-control', 'placeholder'=>'Code of the driver', 'id'=>'code', 'readonly']) !!}
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
          <!-- <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
            {!! Form::label('address', 'Address', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              {!! Form::textarea('address',null,['class'=>'form-control', 'placeholder'=>'Address of the supplier', 'id'=>'address']) !!}
              @if ($errors->has('address'))
                <span class="help-block">
                  <strong>{{ $errors->first('address') }}</strong>
                </span>
              @endif
            </div>
          </div> -->

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
          <!-- <div class="form-group{{ $errors->has('pic_name') ? ' has-error' : '' }}">
            {!! Form::label('pic_name', 'PIC', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              {!! Form::text('pic_name',null,['class'=>'form-control', 'placeholder'=>'PIC name of the supplier', 'id'=>'pic_name']) !!}
              @if ($errors->has('pic_name'))
                <span class="help-block">
                  <strong>{{ $errors->first('pic_name') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('primary_email') ? ' has-error' : '' }}">
            {!! Form::label('primary_email', 'Email', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              {!! Form::text('primary_email',null,['class'=>'form-control', 'placeholder'=>'Primary email of the supplier', 'id'=>'primary_email']) !!}
              @if ($errors->has('primary_email'))
                <span class="help-block">
                  <strong>{{ $errors->first('primary_email') }}</strong>
                </span>
              @endif
            </div>
          </div> -->
          <div class="form-group{{ $errors->has('primary_phone_number') ? ' has-error' : '' }}">
            {!! Form::label('primary_phone_number', 'Phone', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              {!! Form::text('primary_phone_number',null,['class'=>'form-control', 'placeholder'=>'Phone of the driver', 'id'=>'primary_phone_number']) !!}
              @if ($errors->has('primary_phone_number'))
                <span class="help-block">
                  <strong>{{ $errors->first('primary_phone_number') }}</strong>
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
