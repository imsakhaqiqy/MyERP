@extends('layouts.app')

@section('page_title')
    Chart Account
@endsection

@section('page_header')
    <h1>
        Chart Account
        <small>Add New Chart Account</small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('chart-account') }}"><i class="fa fa-dashboard"></i> Chart Account</a></li>
        <li class="active"><i></i> Create</li>
    </ol>
@endsection

@section('content')
    {!! Form::open(['route'=>'chart-account.store','role'=>'form','class'=>'form-horizontal','id'=>'form-create-chart-account']) !!}
        <div class="row">
            <div class="col-lg-7">
                <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                    <div class="box-header with-border">
                        <h3 class="box-title">Basic Information</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
                            {!! Form::label('name','Name',['class'=>'col-sm-3 control-label']) !!}
                            <div class="col-sm-9">
                                {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Name of the chart account','id'=>'name']) !!}
                                @if($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('account_number') ? ' has-error' : ''}}">
                            {!! Form::label('account_number','Account Number',['class'=>'col-sm-3 control-label']) !!}
                            <div class="col-sm-9">
                                {!! Form::text('account_number',null,['class'=>'form-control','placeholder'=>'Account Number of the chart account','id'=>'number_account']) !!}
                                @if($errors->has('account_number'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('account_number') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : ''}}">
                            {!! Form::label('description','Description',['class'=>'col-sm-3 control-label']) !!}
                            <div class="col-sm-9">
                                {!! Form::text('description',null,['class'=>'form-control','placeholder'=>'Description of the chart account','id'=>'description']) !!}
                                @if($errors->has('description'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer clearfix">
                        <div class="form-group">
                            {!! Form::label('','',['class'=>'col-sm-3 control-label']) !!}
                            <div class="col-sm-9">
                                <a href="{{ url('chart-account') }}" class="btn btn-primary">
                                    <i class="fa fa-repeat"></i>&nbsp;Cancel
                                </a>
                                <button type="submit" class="btn btn-info" id="btn-submit-chart-account">
                                    <i class="fa fa-save"></i>&nbsp;Submit
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>



@endsection
