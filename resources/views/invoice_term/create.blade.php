@extends('layouts.app')

@section('page_title')
  Invoice Term
@endsection

@section('page_header')
  <h1>
    Invoice Term
    <small>Add New Invoice Term</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('invoice-term') }}"><i class="fa fa-dashboard"></i> Invoice Term</a></li>
    <li class="active"><i></i>Create</li>
  </ol>
@endsection

@section('content')
  {!! Form::open(['route'=>'invoice-term.store','role'=>'form','class'=>'form-horizontal','id'=>'form-create-invoice-term','files'=>true]) !!}
  <div class="row">
    <div class="col-lg-7">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Basic Information</h3>

        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            {!! Form::label('name', 'Periode', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              {!! Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Periode of the invoice term', 'id'=>'name']) !!}
              @if ($errors->has('name'))
                <span class="help-block">
                  <strong>{{ $errors->first('name') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('day_many') ? ' has-error' : '' }}">
            {!! Form::label('day_many', 'Day Many(s)', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              {!! Form::text('day_many',null,['class'=>'form-control', 'placeholder'=>'The day many count in defined periode', 'id'=>'day_many']) !!}
              @if ($errors->has('day_many'))
                <span class="help-block">
                  <strong>{{ $errors->first('day_many') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group">
              {!! Form::label('', '', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              <a href="{{ url('invoice-term') }}" class="btn btn-default">
                <i class="fa fa-repeat"></i>&nbsp;Cancel
              </a>&nbsp;
              <button type="submit" class="btn btn-info" id="btn-submit-invoice-term">
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

  <script type="text/javascript">
    $('#form-create-invoice-term').on('submit', function(){
      $('#btn-submit-invoice-term').prop('disabled', true);
    });
  </script>

@endsection
