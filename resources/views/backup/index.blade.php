@extends('layouts.app')

@section('page_title')
    Backup
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
                    <h3>Backup Data</h3>
                </div>
                <div class="box-body">
                    {!! Form::open(['route'=>'backup.store','role'=>'form','class'=>'form-horizontal','id'=>'form-create-backup','files'=>true]) !!}
                      <div class="form-group">
                        <div class="col-sm-10">
                          <!-- <a href="{{ url('dashboard') }}" class="btn btn-default">
                            <i class="fa fa-repeat"></i>&nbsp;Cancel
                          </a>&nbsp; -->
                          <button type="submit" class="btn btn-info" id="btn-submit-vehicle">
                            <i class="fa fa-save"></i>&nbsp;Proses
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
