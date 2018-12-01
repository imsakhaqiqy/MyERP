@extends('layouts.app')

@section('page_title')
    Family Product
@endsection

@section('page_header')
  <h1>
    Family Product
    <small>Edit Family Product</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('family') }}"><i class="fa fa-dashboard"></i> Family Product</a></li>
    <li><a href="{{ URL::to('family/'.$family->id.'') }}"><i class="fa fa-dashboard"></i> {{ $family->code }}</a></li>
    <li class="active"><i></i> Edit</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    {!! Form::model($family,['route'=>['family.update', $family], 'class'=>'form-horizontal', 'method'=>'put']) !!}
      <div class="col-md-9">
        <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
          <div class="box-header with-border">
            <h3 class="box-title">Basic Information</h3>
          </div><!-- /.box-header -->
          <div class="box-body">
            <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
              {!! Form::label('code', 'Code', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                {!! Form::text('code',null,['class'=>'form-control', 'placeholder'=>'Code of the family', 'id'=>'code', 'readonly']) !!}
                @if ($errors->has('code'))
                  <span class="help-block">
                    <strong>{{ $errors->first('code') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              {!! Form::label('name', 'Family Name', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                {!! Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Name of the family', 'id'=>'name']) !!}
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
                <a href="{{ url('family') }}" class="btn btn-default">
                  <i class="fa fa-repeat"></i>&nbsp;Cancel
                </a>&nbsp;
                <button type="submit" class="btn btn-info">
                  <i class="fa fa-save"></i>&nbsp;Submit
                </button>

              </div>
            </div>
          </div><!-- /.box-body -->

        </div>
      </div>
    {!! Form::close() !!}
  </div>
@endsection
