@extends('layouts.app')

@section('page_title')
    Restore
@endsection

@section('page_header')

@endsection

@section('breadcrumb')

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box" style="height:200px;box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3>Restore Data</h3>
                </div>
                <div class="box-body">
                    {!! Form::open(['route'=>'restore.store','role'=>'form','class'=>'form-horizontal','id'=>'form-create-restore','files'=>true]) !!}
                      <div class="form-group{{ $errors->has('restore') ? ' has-errors' : '' }}">
                          {!! Form::label('restore','Select File',['class'=>'col-sm-2 control-label']) !!}
                          <div class="col-sm-4">
                              {!! Form::file('restore',null,['class'=>'form-control','placeholder'=>'File of mysql','id'=>'restore']) !!}
                              @if ($errors->has('restore'))
                                <span class="help-block">
                                  <strong>{{ $errors->first('restore') }}</strong>
                                </span>
                              @endif
                          </div>
                      </div>
                      <div class="form-group">
                        {!! Form::label('','',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                          <!-- <a href="{{ url('dashboard') }}" class="btn btn-default">
                            <i class="fa fa-repeat"></i>&nbsp;Cancel
                          </a>&nbsp; -->
                          <button type="submit" class="btn btn-info" id="btn-submit-vehicle">
                            <i class="fa fa-save"></i>&nbsp;Restore
                          </button>
                        </div>
                      </div>
                    {!! Form::close() !!}
                </div>
                <div class="box-footer clearfix">

                </div>
            </div>
        </div>
    </div>
@endsection
