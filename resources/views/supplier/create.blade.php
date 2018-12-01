@extends('layouts.app')

@section('page_title')
  Supplier
@endsection

@section('page_header')
  <h1>
    Supplier
    <small>Add New Supplier</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('supplier') }}"><i class="fa fa-dashboard"></i> Supplier</a></li>
    <li class="active"><i></i> Create</li>
  </ol>
@endsection

@section('content')
  {!! Form::open(['route'=>'supplier.store','role'=>'form','class'=>'form-horizontal','id'=>'form-create-supplier','files'=>true]) !!}
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
              {!! Form::text('code',$code_fix,['class'=>'form-control', 'placeholder'=>'Code of the supplier', 'id'=>'code', 'readonly']) !!}
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
              {!! Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Name of the supplier', 'id'=>'name']) !!}
              @if ($errors->has('name'))
                <span class="help-block">
                  <strong>{{ $errors->first('name') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
            {!! Form::label('address', 'Address', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              {!! Form::textarea('address',null,['class'=>'form-control', 'placeholder'=>'Address of the supplier', 'id'=>'address']) !!}
              @if ($errors->has('address'))
                <span class="help-block">
                  <strong>{{ $errors->first('address') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group">
              {!! Form::label('', '', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              <a href="{{ url('supplier') }}" class="btn btn-default">
                <i class="fa fa-repeat"></i>&nbsp;Cancel
              </a>&nbsp;
              <button type="submit" class="btn btn-info" id="btn-submit-supplier">
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
          <h3 class="box-title">Primary Contact Information</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="form-group{{ $errors->has('pic_name') ? ' has-error' : '' }}">
            {!! Form::label('pic_name', 'PIC', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              {!! Form::text('pic_name',null,['class'=>'form-control', 'placeholder'=>'PIC of the supplier', 'id'=>'pic_name']) !!}
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
              {!! Form::text('primary_email',null,['class'=>'form-control', 'placeholder'=>'Email of the supplier', 'id'=>'primary_email']) !!}
              @if ($errors->has('primary_email'))
                <span class="help-block">
                  <strong>{{ $errors->first('primary_email') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('primary_phone_number') ? ' has-error' : '' }}">
            {!! Form::label('primary_phone_number', 'Phone', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              {!! Form::text('primary_phone_number',null,['class'=>'form-control', 'placeholder'=>'Phone of the supplier', 'id'=>'primary_phone_number']) !!}
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
  <!--ENDROW Submission-->
  {!! Form::close() !!}
@endsection

@section('additional_scripts')
  {!! Html::script('js/autoNumeric.js') !!}
  <script type="text/javascript">
    $('#btn-submit-supplier').click(function(){
      $(this).attr('disable', 'disabled');
    })
    // $('#primary_phone_number').autoNumeric('init',{
    //     aSep:'',
    //     aDec:'-',
    // });
  </script>
@endsection
