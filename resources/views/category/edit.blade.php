@extends('layouts.app')

@section('page_title')
    Category Product
@endsection

@section('page_header')
  <h1>
    Category Product
    <small>Edit Category Product</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('category') }}"><i class="fa fa-dashboard"></i> Category Product</a></li>
    <li><a href="{{ URL::to('category/'.$category->id.'') }}"><i class="fa fa-dashboard"></i> {{ $category->name }}</a></li>
    <li class="active"><i></i> Edit</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    {!! Form::model($category,['route'=>['category.update', $category], 'class'=>'form-horizontal', 'method'=>'put']) !!}
      <div class="col-md-9">
        <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
          <div class="box-header with-border">
            <h3 class="box-title">Basic Information</h3>
          </div><!-- /.box-header -->
          <div class="box-body">
            <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
              {!! Form::label('code', 'Code', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-5">
                {!! Form::text('code',null,['class'=>'form-control', 'placeholder'=>'Code of the category', 'id'=>'code', 'readonly']) !!}
                @if ($errors->has('code'))
                  <span class="help-block">
                    <strong>{{ $errors->first('code') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              {!! Form::label('name', 'Category Name', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-5">
                {!! Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Name of the category', 'id'=>'name']) !!}
                @if ($errors->has('name'))
                  <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group{{ $errors->has('family_id') ? ' has-error' : '' }}">
              {!! Form::label('family_id', 'Family', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-5">
                {!! Form::select('family_id',$family,null,['class'=>'form-control', 'placeholder'=>'Family of the category', 'id'=>'family_id']) !!}
                @if ($errors->has('family_id'))
                  <span class="help-block">
                    <strong>{{ $errors->first('family_id') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('', '', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                <a href="{{ url('category') }}" class="btn btn-default">
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
