@extends('layouts.app')

@section('page_title')
  Unit Product
@endsection

@section('page_header')
  <h1>
    Unit Product
    <small>Add New Unit Product</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('unit') }}"><i class="fa fa-dashboard"></i> Unit Product</a></li>
    <li class="active"><i></i> Create</li>
  </ol>
@endsection

@section('content')
  {!! Form::open(['route'=>'unit.store','role'=>'form','class'=>'form-horizontal','id'=>'form-create-unit','files'=>true]) !!}
  <div class="row">
    <div class="col-md-12">
      <!--BOX Basic Informations-->
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Basic Information</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            {!! Form::label('name', 'Unit Name', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-6">
              {!! Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Name of the unit', 'id'=>'name']) !!}
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
              <a href="{{ url('unit') }}" class="btn btn-default">
                <i class="fa fa-repeat"></i>&nbsp;Cancel
              </a>&nbsp;
              <button type="submit" class="btn btn-info" id="btn-submit-unit">
                <i class="fa fa-save"></i>&nbsp;Submit
              </button>
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
    $('#btn-submit-unit').click(function(){
      $(this).attr('disable', 'disabled');
    })
  </script>
@endsection
