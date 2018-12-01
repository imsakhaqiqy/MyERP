@extends('layouts.app')

@section('page_title')
  Bank
@endsection

@section('page_header')
  <h1>
    Bank
    <small>Add New Bank</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('bank') }}"><i class="fa fa-dashboard"></i> Bank</a></li>
    <li class="active"><i></i>Create</li>
  </ol>
@endsection

@section('content')
  {!! Form::open(['route'=>'bank.store','role'=>'form','class'=>'form-horizontal','id'=>'form-create-bank','files'=>true]) !!}
  <div class="row">
    <div class="col-lg-8">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Basic Information</h3>

        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
            {!! Form::label('code', 'Code', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              {!! Form::text('code',$code_fix,['class'=>'form-control', 'placeholder'=>'Code of the bank', 'id'=>'code', 'readonly']) !!}
              @if ($errors->has('code'))
                <span class="help-block">
                  <strong>{{ $errors->first('code') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            {!! Form::label('name', 'Name', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              {!! Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Name of the bank', 'id'=>'name']) !!}
              @if ($errors->has('name'))
                <span class="help-block">
                  <strong>{{ $errors->first('name') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('account_name') ? ' has-error' : '' }}">
            {!! Form::label('account_name', 'Account Name', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              {!! Form::text('account_name',null,['class'=>'form-control', 'placeholder'=>'Account Name of the bank', 'id'=>'account_name']) !!}
              @if ($errors->has('account_name'))
                <span class="help-block">
                  <strong>{{ $errors->first('account_name') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('account_number') ? ' has-error' : '' }}">
            {!! Form::label('account_number', 'Account Number', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              {!! Form::text('account_number',null,['class'=>'form-control', 'placeholder'=>'Account Number of the bank', 'id'=>'account_number']) !!}
              @if ($errors->has('account_number'))
                <span class="help-block">
                  <strong>{{ $errors->first('account_number') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('value') ? ' has-error' : '' }}">
            {!! Form::label('value', 'Value', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              {!! Form::text('value',null,['class'=>'form-control', 'placeholder'=>'Initial value of the bank', 'id'=>'value']) !!}
              @if ($errors->has('value'))
                <span class="help-block">
                  <strong>{{ $errors->first('value') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group">
              {!! Form::label('', '', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              <a href="{{ url('bank') }}" class="btn btn-default">
                <i class="fa fa-repeat"></i>&nbsp;Cancel
              </a>&nbsp;
              <button type="submit" class="btn btn-info" id="btn-submit-bank">
                <i class="fa fa-save"></i>&nbsp;Submit
              </button>
            </div>
          </div>
        </div><!-- /.box-body -->
        <div class="box-footer clearfix">

        </div>
      </div><!-- /.box -->

    </div>
  </div>
  {!! Form::close() !!}
@endsection

@section('additional_scripts')
{!! Html::script('js/autoNumeric.js') !!}
<script type="text/javascript">
    $('#value').autoNumeric('init',{
        aSep:',',
        aDec:'.'
    });
</script>
@endsection
