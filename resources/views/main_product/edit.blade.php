@extends('layouts.app')

@section('page_title')
    Product
@endsection

@section('page_header')
    <h1>
        Product
        <small>Edit Product</small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('main-product') }}"><i class="fa fa-dashboard"> Product</i></a></li>
        <li>{{ $main_product->name }}</li>
        <li class="active"><i></i> Edit</li>
    </ol>
@endsection

@section('content')
{!! Form::model($main_product,['route'=>['main-product.update', $main_product->id], 'class'=>'form-horizontal','method'=>'put','files'=>true]) !!}
<div class="row">
    <div class="col-md-8">
        <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
            <div class="box-header with-border">
                <h3 class="box-title">Basic Information</h3>
            </div>
            <div class="box-body">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                  {!! Form::label('name', 'Name', ['class'=>'col-sm-2 control-label']) !!}
                  <div class="col-sm-10">
                    {!! Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Name of the product', 'id'=>'name']) !!}
                    @if ($errors->has('name'))
                      <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                      </span>
                    @endif
                  </div>
                </div>
                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                  {!! Form::label('description', 'Description', ['class'=>'col-sm-2 control-label']) !!}
                  <div class="col-sm-10">
                    {!! Form::text('description',null,['class'=>'form-control', 'placeholder'=>'Description of the product', 'id'=>'description']) !!}
                    @if ($errors->has('description'))
                      <span class="help-block">
                        <strong>{{ $errors->first('description') }}</strong>
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
            </div>
        </div>
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
                {{ Form::select('category_id', $category_options, null, ['class'=>'form-control', 'placeholder'=>'Select category', 'id'=>'category_id']) }}
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
                @if($main_product->image != NULL)
                  <a href="#" class="thumbnail">
                    {!! Html::image('img/products/thumb_'.$main_product->image.'', $main_product->image) !!}
                  </a>
                @endif
                {{ Form::file('image', ['class']) }}
                @if ($errors->has('image'))
                  <span class="help-block">
                    <strong>{{ $errors->first('image') }}</strong>
                  </span>
                @endif
              </div>
            </div>
        </div><!-- /.box-body -->
      </div>
      <!--ENDBOX Image-->
      <!--BOX Category and Family-->

    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
          <div class="box-header with-border">
            <h3 class="box-title">Sub Product</h3>
          </div><!-- /.box-header -->
          <div class="box-body">
              <div class="box-responsive" style="max-height:500px">
                <table class="table table-hover table-striped" id="table-product">
                    <thead>
                        <tr>
                          <th style="width:10%;background-color:#3c8dbc;color:white">Family</th>
                          <th style="width:15%;background-color:#3c8dbc;color:white">Code</th>
                          <th style="width:15%;background-color:#3c8dbc;color:white">Description</th>
                          <th style="width:10%;background-color:#3c8dbc;color:white">Unit</th>
                          <th style="width:15%;background-color:#3c8dbc;color:white">Stock</th>
                          <th style="width:15%;background-color:#3c8dbc;color:white;display:none">Stock Minumum</th>
                          <th style="width:20%;background-color:#3c8dbc;color:white">Category</th>
                        </tr>
                    </thead>
                  <tbody>
                      <tr>
                          <td>{{ $main_product->family->name }}</td>
                          <td>
                              <input type="hidden" name="parent_id" value="{{ $main_product->id}}">
                              <span id="parent_code">{{$main_product->name}}</span>
                              @if($main_product->image != NULL)
                              <a href="#" class="thumbnail">
                                  {!! Html::image('img/products/thumb_'.$main_product->image.'', $main_product->image) !!}
                              </a>
                              @else
                              <a href="#" class="thumbnail">
                                  {!! Html::image('files/default/noimageavailable.jpeg', 'No Image') !!}
                              </a>
                              @endif
                              </td>
                          <td id="parent_description"></td>
                          <td>{{ $main_product->unit->name}}</td>
                          <td>
                              <input type="text" name="stock_parent" id="stock_parent" class="stock_parent form-control">
                          </td>
                          <td style="display:none">
                              <input type="text" name="stock_minimum_parent" value="0" id="minimum_stock_parent">
                          </td>
                          <td>{{ $main_product->category->name}}</td>
                      </tr>
                      <?php $no = 1; $sum = 0;?>
                      @foreach($product as $key)
                        <tr id="row_child_{{$key->id }}">
                            <td>{{ $main_product->family->name }}</td>
                            <td>
                                <input type="hidden" name="id[]" value="{{ $key->id}}">
                                <input type="hidden" name="child_code_hidden[]" value="{{ $key->name}}" class="child_code_hidden">
                                <span class="child_code">{{ $key->name }}</span>
                            </td>
                            <td id="child_description">{{ $key->description }}</td>
                            <td>{{ $main_product->unit->name}} </td>
                            <td>
                                <input type="text" name="stock[]" value="{{ $key->stock }}" class="stock form-control">
                            </td>
                            <td style="display:none">
                                <input type="text" name="stock_minimum[]" value="{{ $key->minimum_stock }}">
                            </td>
                            <td>{{ $main_product->category->name}}</td>
                        </tr>
                        @if($key->stock)
                            <?php $sum += $key->stock; ?>
                        @endif
                      @endforeach
                      <p id="sum_availability" style="display:none"><?php echo $sum; ?></p>
                  </tbody>
                </table>
            </div>
          </div><!-- /.box-body -->
          <div class="box-footer clearfix">
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
          {!! Form::close() !!}
        </div><!-- /.box -->
    </div>
</div>
@endsection

@section('additional_scripts')
    {!! Html::script('js/autoNumeric.js') !!}
    <script type="text/javascript">
      $('.stock').autoNumeric('init',{
        aSep:'',
        aDec:'.'
      });
      $('.stock_parent').autoNumeric('init',{
        aSep:'',
        aDec:'.',
      });
    </script>
    <script type="text/javascript">
        $('#name').on('keyup',function(){
            $('#parent_code').text($('#name').val());
            var childCode = document.getElementsByClassName('child_code');
            var childCode = document.getElementsByClassName('child_code_hidden');
            var x;
            for(x = 0; x < childCode.length; x++){
                document.getElementsByClassName('child_code')[x].innerHTML = $('#name').val()+'.'+'0'+(x+1);
                document.getElementsByClassName('child_code_hidden')[x].value = $('#name').val()+'.'+'0'+(x+1);
            }
        });

        $('#stock_parent').val($('#sum_availability').text());
        $('#minimum_stock_parent').val(3);
        $('#description').val($('#child_description').text());
        $('#parent_description').text($('#child_description').text());
    </script>
@endsection
