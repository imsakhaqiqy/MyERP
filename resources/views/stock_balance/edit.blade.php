@extends('layouts.app')

@section('page_title')
  Stock Balance
@endsection

@section('page_header')
  <h1>
    Stock Balance
    <small>Edit Stock Balance</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('stock_balance') }}"><i class="fa fa-dashboard"></i> Stock Balance</a></li>
    <li> {{ $stock_balance->code }}</li>
    <li class="active"><i></i> Edit</li>
  </ol>
@endsection

@section('content')
    {!! Form::model($stock_balance, ['route'=>['stock_balance.update', $stock_balance->id], 'class'=>'form-horizontal','method'=>'put', 'files'=>true]) !!}
    <div class="row">
        <div class="col-md-3">

        </div>
        <div class="col-md-6">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bars"></i>&nbsp;Basic Information</h3>
                </div><!-- /.box header -->
                <div class="box-body">
                    <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                      {!! Form::label('code', 'Code', ['class'=>'col-sm-3 control-label']) !!}
                      <div class="col-sm-5">
                        {!! Form::text('code',null,['class'=>'form-control', 'id'=>'code', 'readonly']) !!}
                        @if ($errors->has('code'))
                          <span class="help-block">
                            <strong>{{ $errors->first('code') }}</strong>
                          </span>
                        @endif
                      </div>
                    </div>
                    <div class="form-group{{ $errors->has('created_by') ? ' has-error' : '' }}">
                      {!! Form::label('created_by', 'Created By', ['class'=>'col-sm-3 control-label']) !!}
                      <div class="col-sm-5">
                        {!! Form::text('creator[name]',null,['class'=>'form-control', 'id'=>'created_name']) !!}
                        {!! Form::hidden('created_by',null,['class'=>'form-control','id'=>'created_by']) !!}
                        {!! Form::hidden('id',null,['class'=>'form-control','id'=>'id']) !!}
                        @if ($errors->has('created_by'))
                          <span class="help-block">
                            <strong>{{ $errors->first('created_by') }}</strong>
                          </span>
                        @endif
                      </div>
                    </div>
                    <div class="form-group{{ $errors->has('created_by') ? ' has-error' : '' }}">
                      {!! Form::label('created_at', 'Created At', ['class'=>'col-sm-3 control-label']) !!}
                      <div class="col-sm-5">
                        {!! Form::text('created_at',null,['class'=>'form-control', 'id'=>'created_at']) !!}
                        @if ($errors->has('created_at'))
                          <span class="help-block">
                            <strong>{{ $errors->first('created_at') }}</strong>
                          </span>
                        @endif
                      </div>
                    </div>
                    <div class="form-group{{ $errors->has('updated_at') ? ' has-error' : '' }}">
                      {!! Form::label('updated_at', 'Updated At', ['class'=>'col-sm-3 control-label']) !!}
                      <div class="col-sm-5">
                        {!! Form::text('updated_at',null,['class'=>'form-control', 'id'=>'updated_at']) !!}
                        @if ($errors->has('updated_at'))
                          <span class="help-block">
                            <strong>{{ $errors->first('updated_at') }}</strong>
                          </span>
                        @endif
                      </div>
                    </div>
                </div>
                <div class="box-footer clearfix">
                </div><!-- ./box footer -->
            </div>
        </div>
        <div class="col-md-3">

        </div>
        <div class="col-md-12">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-bookmark-o"></i>&nbsp;Product Information</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive" style="max-height:500px">
                        <table class="table table-hover">
                            <thead>
                                <tr style="background-color:#3c8dbc;color:white">
                                    <th style="width:5%;">#</th>
                                    <th>Family</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Unit</th>
                                    <th>Category</th>
                                    <th>System Stock</th>
                                    <th>Real Stock</th>
                                    <th>Information</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $x =1; ?>
                                @foreach($dataList as $view)
                                    <tr>
                                        <td>{{ $x++ }}</td>
                                        <td>{{ \DB::table('families')->select('name')->where('id',$view->family_id)->value('name') }}</td>
                                        <td>{{ $view->name }}</td>
                                        <td>
                                            {{ $view->description }}
                                            <input type="hidden" name="product_id[]" value="{{ $view->product_id}}">
                                        </td>
                                        <td>{{ \DB::table('units')->select('name')->where('id',$view->unit_id)->value('name') }}</td>
                                        <td>{{ \DB::table('categories')->select('name')->where('id',$view->category_id)->value('name') }}</td>
                                        <td>
                                            {{ $view->system_stock }}
                                            <input type="hidden" name="system_stock[]" value="{{ $view->system_stock }}">
                                        </td>
                                        <td>@if($view->real_stock<$view->system_stock)
                                                <span style="color:red">
                                            @else
                                                <span style="color:green">
                                            @endif
                                            <input type="text" name="real_stock[]" value="{{ $view->real_stock }}"</span>
                                        </td>
                                        <td>
                                            <input type="text" name="information[]" value="{{ $view->information }}">
                                            <input type="hidden" name="stock_balance_id[]" value="{{ $view->stock_balance_id }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box-footer clearfix">
                    <div class="form-group">
                            {!! Form::label('', '', ['class'=>'col-sm-9 control-label']) !!}
                          <div class="col-sm-3">
                            <a href="{{ url('stock_balance') }}" class="btn btn-default">
                              <i class="fa fa-repeat"></i>&nbsp;Cancel
                            </a>&nbsp;
                            <button type="submit" class="btn btn-info" id="btn-submit-driver">
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
