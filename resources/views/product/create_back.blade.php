@extends('layouts.app')

@section('page_title')
  Product
@endsection

@section('page_header')
  <h1>
    Product
    <small>Add New Product</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('product') }}"><i class="fa fa-dashboard"></i> Products</a></li>
    <li class="active"><i></i> Create</li>
  </ol>
@endsection

@section('content')
  {!! Form::open(['route'=>'product.store','role'=>'form','class'=>'form-horizontal','id'=>'form-create-product','files'=>true]) !!}
  <div class="row">
    <div class="col-md-8">
      <!--BOX Basic Informations-->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Basic Informations</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
            {!! Form::label('code', 'Code', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              {!! Form::text('code',null,['class'=>'form-control', 'placeholder'=>'Code of the product', 'id'=>'code']) !!}
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
              {!! Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Name of the product', 'id'=>'name']) !!}
              @if ($errors->has('name'))
                <span class="help-block">
                  <strong>{{ $errors->first('name') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('unit_id') ? ' has-error' : '' }}">
            {!! Form::label('unit_id', 'Unit', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              {{ Form::select('unit_id', $unit_options, null, ['class'=>'form-control', 'placeholder'=>'Select unit', 'id'=>'unit_id']) }}
              @if ($errors->has('unit_id'))
                <span class="help-block">
                  <strong>{{ $errors->first('unit_id') }}</strong>
                </span>
              @endif
            </div>
          </div>
          
        </div><!-- /.box-body -->
      </div>
      <!--ENDBOX Basic Informations-->
    </div>
    <div class="col-md-4">
      <!--BOX category and image-->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Category and Picture</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
            {!! Form::label('category_id', 'Category', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              {{ Form::select('category_id', $category_options, null, ['class'=>'form-control', 'placeholder'=>'Select category', 'id'=>'category_id']) }}
              @if ($errors->has('category_id'))
                <span class="help-block">
                  <strong>{{ $errors->first('category_id') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
            {!! Form::label('image', 'Image', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              {{ Form::file('image', ['class']) }}
              @if ($errors->has('image'))
                <span class="help-block">
                  <strong>{{ $errors->first('image') }}</strong>
                </span>
              @endif
            </div>
          </div>
        </div><!-- /.box-body -->
      </div>
      <!--ENDBOX category and image-->
    </div>
  </div>

  <!--ROW Stock Information-->
  <div class="row">
    <div class="col-md-8">
      <!--BOX Stock Informations-->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Stock Informations</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="form-group{{ $errors->has('stock') ? ' has-error' : '' }}">
            {!! Form::label('stock', 'Stock', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              {!! Form::text('stock',null,['class'=>'form-control', 'placeholder'=>'Stock of the product', 'id'=>'stock']) !!}
              @if ($errors->has('stock'))
                <span class="help-block">
                  <strong>{{ $errors->first('stock') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('minimum_stock') ? ' has-error' : '' }}">
            {!! Form::label('minimum_stock', 'Minimum Stock', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              {!! Form::text('minimum_stock',null,['class'=>'form-control', 'placeholder'=>'Minimum stock availability', 'id'=>'minimum_stock']) !!}
              @if ($errors->has('minimum_stock'))
                <span class="help-block">
                  <strong>{{ $errors->first('minimum_stock') }}</strong>
                </span>
              @endif
            </div>
          </div>
          
        </div><!-- /.box-body -->
      </div>
      <!--ENDBOX Stock Informations-->
    </div>
  </div>
  <!--ENDROW Stock Information-->
  <!--ROW Submission-->
  <div class="row">
    <div class="col-md-8">
      <!--BOX submission buttons-->
      <div class="box">
        <div class="box-body">
          <div class="form-group">
              {!! Form::label('', '', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              <a href="{{ url('product') }}" class="btn btn-default">
                <i class="fa fa-repeat"></i>&nbsp;Cancel
              </a>&nbsp;
              <button type="submit" class="btn btn-info" id="btn-submit-product">
                <i class="fa fa-save"></i>&nbsp;Submit
              </button>
            </div>
          </div>
        </div>
      </div>
      <!--ENDBOX submission buttons-->
    </div>
  </div>
  <!--ENDROW Submission-->
  {!! Form::close() !!}
@endsection

@section('additional_scripts')
  <script type="text/javascript">
    $('#btn-submit-product').click(function(){
      $(this).attr('disable', 'disabled');
    })
  </script>
@endsection