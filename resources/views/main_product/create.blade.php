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
        <li><a href="{{ URL::to('main-product') }}"><i class="fa fa-dashboard"></i> Product</a></li>
        <li class="active"><i></i> Create</li>
    </ol>
@endsection

@section('content')
            {!! Form::open(['route'=>'main-product.store','role'=>'form','class'=>'form-horizontal','id'=>'form-create-main-product','files'=>true]) !!}
            <div class="row">
                <div class="col-md-8">
                    <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                        <div class="box-header with-border">
                            <h3 class="box-title">Basic Information</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                              {!! Form::label('code', 'Name', ['class'=>'col-sm-2 control-label']) !!}
                              <div class="col-sm-10">
                                {!! Form::text('code',null,['class'=>'form-control', 'placeholder'=>'Code of the product', 'id'=>'code']) !!}
                                @if ($errors->has('code'))
                                  <span class="help-block">
                                    <strong>{{ $errors->first('code') }}</strong>
                                  </span>
                                @endif
                              </div>
                            </div>
                            <div class="form-group">
                              {!! Form::label('sub_code','Sub Code', ['class'=>'col-sm-2 control-label']) !!}
                              <div class="col-sm-5">
                                <div class="input-group{{ $errors->has('mulai_dari') ? ' has-error' : '' }}">
                                    <span class="input-group-addon">Mulai Dari</span>
                                    {!! Form::text('mulai_dari',null,['class'=>'form-control', 'placeholder'=>'Start of sub product', 'id'=>'mulai_dari']) !!}
                                    @if ($errors->has('mulai_dari'))
                                      <span class="help-block">
                                        <strong>{{ $errors->first('mulai_dari') }}</strong>
                                      </span>
                                    @endif
                                </div>
                              </div>
                              <div class="col-sm-5">
                                  <div class="input-group{{ $errors->has('sebanyak') ? ' has-error' : '' }}">
                                    <span class="input-group-addon">Sebanyak</span>
                                    {!! Form::text('sebanyak',null,['class'=>'form-control', 'placeholder'=>'Sebanyak of sub product', 'id'=>'sebanyak']) !!}
                                    @if ($errors->has('sebanyak'))
                                      <span class="help-block">
                                        <strong>{{ $errors->first('sebanyak') }}</strong>
                                      </span>
                                    @endif
                                </div>
                              </div>
                            </div>
                            <div class="form-group{{ $errors->has('deskripsi') ? ' has-error' : '' }}">
                              {!! Form::label('deskripsi', 'Deskripsi', ['class'=>'col-sm-2 control-label']) !!}
                              <div class="col-sm-10">
                                {!! Form::text('deskripsi',null,['class'=>'form-control', 'placeholder'=>'Deskripsi of the product', 'id'=>'deskripsi']) !!}
                                @if ($errors->has('deskripsi'))
                                  <span class="help-block">
                                    <strong>{{ $errors->first('deskripsi') }}</strong>
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
                            <div class="form-group">
                                {!! Form::label('', '', ['class'=>'col-sm-2 control-label']) !!}
                              <div class="col-sm-10">
                                <a href="{{ url('main-product') }}" class="btn btn-default">
                                  <i class="fa fa-repeat"></i>&nbsp;Cancel
                                </a>&nbsp;
                                <button type="submit" class="btn btn-info" id="btn-submit-product">
                                  <i class="fa fa-save"></i>&nbsp;Submit
                                </button>
                              </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                      <div class="box-body">

                      </div>
                    </div> -->
                </div>
                <div class="col-md-4">
                  <!--BOX Image-->
                  <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                    <div class="box-header with-border">
                      <h3 class="box-title">Product Image</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">

                      <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                        {!! Form::label('image', 'Image', ['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                          {{ Form::file('image', ['class']) }}
                          @if ($errors->has('image'))
                            <span class="help-block">
                              <strong>{{ $errors->first('image') }}</strong>
                            </span>
                          @endif
                          <span style="color:red">*Max size 10 mb</span>
                        </div>
                      </div>
                    </div><!-- /.box-body -->
                  </div>
                  <!--ENDBOX Image-->
                  <!--BOX Category and Family-->
                  <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                    <div class="box-header with-border">
                      <h3 class="box-title">Category and Family</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                      <div class="form-group{{ $errors->has('family_id') ? ' has-error' : '' }}">
                        {!! Form::label('family_id', 'Family', ['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                          {{ Form::select('family_id', $family_options, null, ['class'=>'form-control', 'placeholder'=>'Select Family', 'id'=>'family_id']) }}
                          @if ($errors->has('family_id'))
                            <span class="help-block">
                              <strong>{{ $errors->first('family_id') }}</strong>
                            </span>
                          @endif
                        </div>
                      </div>
                      <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
                        {!! Form::label('category_id', 'Category', ['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            <select name="category_id" id="category_id" class="form-control">
                                <option>Select Category</option>
                            </select>
                          @if ($errors->has('category_id'))
                            <span class="help-block">
                              <strong>{{ $errors->first('category_id') }}</strong>
                            </span>
                          @endif
                        </div>
                      </div>
                    </div><!-- /.box-body -->
                  </div>
                  <!--ENDBOX Category and Family-->

                </div>
            </div>
            {!! Form::close() !!}



    </div>
@endsection

@section('additional_scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#family_id').change(function(){
                var family = $('#family_id').val();
                var token = $("meta[name='csrf-token']").attr('content');
                $.ajax({
                    url:"{!!URL::to('callCategory')!!}",
                    type:'POST',
                    data:"family="+family+'&_token='+token,
                    beforeSend: function(){

                    } ,
                    success:function(data){
                        console.log(data);
                        $('#category_id').html(data);
                    }
                });
            })
        });
    </script>
@endsection
