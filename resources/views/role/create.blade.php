@extends('layouts.app')

@section('page_title')
    Role
@endsection

@section('page_header')
    <h1>
        Role
        <small>Add New Role</small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('role') }}"><i class="fa fa-dashboard"></i> Role</a></li>
        <li class="active"><i></i>Create</li>
    </ol>
@endsection

@section('content')
    {!! Form::open(['route'=>'role.store','role'=>'form','class'=>'form-horizontal','id'=>'form-create-role']) !!}
        <div class="row">
            <div class="col-lg-8">
                <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                    <div class="box-header with-border">
                        <h3 class="box-title">Basic Information</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            {!! Form::label('code','Code',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('code',null,['class'=>'form-control','placeholder'=>'Code of the role','id'=>'code']) !!}
                                @if($errors->has('code'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('code') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            {!! Form::label('name','Name',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Name of the role','id'=>'name']) !!}
                                @if($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            {!! Form::label('description','Description',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('description',null,['class'=>'form-control','placeholder'=>'Description of the role','id'=>'description']) !!}
                                @if($errors->has('description'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('','',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                <a href="{{ url('role') }}" class="btn btn-default">
                                    <i class="fa fa-repeat"></i>&nbsp;Cancel
                                </a>
                                <button type="submit" class="btn btn-info" id="btn-submit-cash">
                                    <i class="fa fa-save"></i>&nbsp;Submit
                                </button>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer clearfix">

                    </div> <!-- /.box-footer -->
                </div>
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
