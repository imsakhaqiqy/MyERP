@extends('layouts.app')

@section('page_title')
  Permission
@endsection

@section('page_header')
  <h1>
    Permission
    <small>Edit Permission</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="#"><i class="fa fa-dashboard"></i> Permission </a></li>
    <li class="active"><i></i>Edit</li>
  </ol>
@endsection

@section('content')

  {!! Form::model($permission, ['route'=>['permission.update', $permission->id], 'class'=>'form-horizontal','method'=>'put', 'files'=>true]) !!}
    <div class="row">
      <div class="col-md-12">
        <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
          <div class="box-header with-border">
            <h3 class="box-title">Basic Informations</h3>
          </div><!-- /.box-header -->
          <div class="box-body">
            <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
              {!! Form::label('slug', 'Slug', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                {!! Form::text('slug',null,['class'=>'form-control', 'placeholder'=>'slug of the permission', 'id'=>'slug', 'readonly'=>true]) !!}
                @if ($errors->has('slug'))
                  <span class="help-block">
                    <strong>{{ $errors->first('slug') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
              {!! Form::label('description', 'Description', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                {!! Form::textarea('description',null,['class'=>'form-control', 'placeholder'=>'Description of the permission', 'id'=>'description']) !!}
                @if ($errors->has('description'))
                  <span class="help-block">
                    <strong>{{ $errors->first('description') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('', '', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                <a href="{{ url('permission') }}" class="btn btn-default">
                  <i class="fa fa-repeat"></i>&nbsp;Cancel
                </a>&nbsp;
                <button type="submit" class="btn btn-info" id="btn-submit-permission">
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

@endsection
