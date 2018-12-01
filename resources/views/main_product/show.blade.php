@extends('layouts.app')

@section('page_title')
    Products
@endsection

@section('page_header')
    <h1>
        Products
        <small>Product Detail</small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('main-product') }}"><i class="fa fa-dashboard"></i> Products</a></li>
        <li class="active"><i></i>{{ $main_product->name }}</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $main_product->name }}</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            @if($main_product->image != NULL)
                            <a href="#" class="thumbnail">
                                {!! Html::image('img/products/thumb_'.$main_product->image.'', $main_product->image) !!}
                            </a>
                            @else
                            <a href="#" class="thumbnail">
                                {!! Html::image('files/default/noimageavailable.jpeg', 'No Image') !!}
                            </a>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-sm-3">Name</div>
                                <div class="col-sm-1">:</div>
                                <div class="col-sm-8">{{ $main_product->name }}</div>
                            </div>
                            <p></p>
                            <div class="row">
                                <div class="col-sm-3">Family</div>
                                <div class="col-sm-1">:</div>
                                <div class="col-sm-8">{{ $main_product->family->name }}</div>
                            </div>
                            <p></p>
                            <div class="row">
                                <div class="col-sm-3">Category</div>
                                <div class="col-sm-1">:</div>
                                <div class="col-sm-8">{{ $main_product->category->name }}</div>
                            </div>
                            <p></p>
                            <div class="row">
                                <div class="col-sm-3">Unit</div>
                                <div class="col-sm-1">:</div>
                                <div class="col-sm-8" id="main-product-unit">{{ $main_product->unit->name }}</div>
                            </div>
                            <p></p>
                            <div class="row">
                                <div class="col-sm-3">Availability</div>
                                <div class="col-sm-1">:</div>
                                <div class="col-sm-8" id="availability"></div>
                            </div>
                            <p></p>
                            <div class="row">
                                <div class="col-sm-3">Created At</div>
                                <div class="col-sm-1">:</div>
                                <div class="col-sm-8">{{ $main_product->created_at }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer clearfix">

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
              <div class="box-header with-border">
                <h3 class="box-title">Sub Products</h3>
              </div><!-- /.box-header -->
              <div class="box-body">
                  <div class="table-responsive">
                    {!! Form::open(['url'=>'main-product.store_product','role'=>'form','class'=>'form-horizontal','id'=>'form-create-main-product']) !!}
                    <table class="table table-striped table-hover" id="table-product">
                        <thead>
                            <tr style="background-color:#3c8dbc;color:white">
                              <th style="width:10%;">Family</th>
                              <th style="width:15%;">Code</th>
                              <th style="width:15%;">Description</th>
                              <th style="width:10%;">Unit</th>
                              <th style="width:15%;">Stock</th>
                              <th style="width:15%;;display:none">Stock Minumum</th>
                              <th style="width:20%;">Category</th>
                            </tr>
                        </thead>
                      <tbody>
                          <tr>
                              <td>{{ $main_product->family->name }}</td>
                              <td>
                                  <input type="hidden" name="parent_id" value="{{ $main_product->id}}">
                                  {{ $main_product->name }}
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
                              <td></td>
                              <td>{{ $main_product->unit->name}}</td>
                              <td>
                                  <input type="text" name="stock_parent" value="" class="stock_parent form-control">
                              </td>
                              <td style="display:none">
                                  <input type="text" name="stock_minimum_parent" value="0">
                              </td>
                              <td>{{ $main_product->category->name}}</td>
                          </tr>
                          <?php $no = 1; $sum = 0;?>
                          @foreach($product as $key)
                            <tr>
                                <td>{{ $main_product->family->name }}</td>
                                <td>
                                    <input type="hidden" name="id[]" value="{{ $key->id}}">
                                    {{ $key->name }}
                                </td>
                                <td>{{ $key->description }}</td>
                                <td>{{ $main_product->unit->name}} </td>
                                <td>
                                    <input type="text" name="stock[]" value="" class="stock form-control">
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
                      <a href="{{ url('product') }}" class="btn btn-default">
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

    <!-- MODAL VIEW SUB PRODUCT -->
    <div class="modal fade" id="modal-view-sub-product" tabindex="-1" role="dialog" aria-labelledby="modal-view-subProductLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="modal-view-subProductLabel">View Sub Product</h4>
            </div>
            <div class="modal-body">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Basic Informations</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-4">Name</div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-7" id="view-product-name"></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">Description</div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-7" id="view-product-description"></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">Stock</div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-7" id="view-product-stock"></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">Minimum Stock</div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-7" id="view-product-minimum-stock"></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">Unit</div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-7" id="view-product-unit"></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">Created At</div>
                            <div class="col-lg-1">:</div>
                            <div class="col-lg-7" id="view-product-created-at"></div>
                        </div>
                    </div>
                    <div class="box-footer clearfix">

                    </div>
                </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal END -->

    <!-- MODAL EDIT SUB PRODUCT -->
    <div class="modal fade" id="modal-edit-sub-product" tabindex="-1" role="dialog" aria-labelledby="modal-edit-subProductLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="modal-edit-subProductLabel">Edit Sub Product</h4>
            </div>
            <div class="modal-body">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Basic Informations</h3>
                    </div>
                    {!! Form::open(['url'=>'main-product.update_product','role'=>'form','class'=>'form-horizontal','id'=>'form-update-sub-product','method'=>'put']) !!}
                    <div class="box-body">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                          {!! Form::label('name', 'Name', ['class'=>'col-sm-3 control-label']) !!}
                          <div class="col-sm-9">
                            {!! Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Name of the product', 'id'=>'product-name-edit']) !!}
                            @if ($errors->has('name'))
                              <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                              </span>
                            @endif
                          </div>
                        </div>
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                          {!! Form::label('description', 'Description', ['class'=>'col-sm-3 control-label']) !!}
                          <div class="col-sm-9">
                            {!! Form::text('description',null,['class'=>'form-control', 'placeholder'=>'Name of the product', 'id'=>'product-description-edit']) !!}
                            @if ($errors->has('description'))
                              <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                              </span>
                            @endif
                          </div>
                        </div>
                    </div>
                    <div class="box-header with-border">
                        <h3 class="box-title">Stock Informations</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group{{ $errors->has('stock') ? ' has-error' : '' }}">
                          {!! Form::label('stock', 'Stock', ['class'=>'col-sm-3 control-label']) !!}
                          <div class="col-sm-9">
                            {!! Form::text('stock',null,['class'=>'form-control', 'placeholder'=>'Stock of the product', 'id'=>'product-stock-edit']) !!}
                            @if ($errors->has('stock'))
                              <span class="help-block">
                                <strong>{{ $errors->first('stock') }}</strong>
                              </span>
                            @endif
                          </div>
                        </div>
                        <div class="form-group{{ $errors->has('minimum_stock') ? ' has-error' : '' }}">
                          {!! Form::label('minimum_stock', 'Minimum Stock', ['class'=>'col-sm-3 control-label']) !!}
                          <div class="col-sm-9">
                            {!! Form::text('minimum_stock',null,['class'=>'form-control', 'placeholder'=>'Minimum stock availability', 'id'=>'product-minimum-stock-edit']) !!}
                            @if ($errors->has('minimum_stock'))
                              <span class="help-block">
                                <strong>{{ $errors->first('minimum_stock') }}</strong>
                              </span>
                            @endif
                            {!! Form::hidden('product_id',null,['id'=>'product-id-edit']) !!}
                          </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('','',['class'=>'col-sm-3 control-label']) !!}
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary" data-dismiss="modal">
                                    <i class="fa fa-repeat"></i>&nbsp;Cancel
                                </button>
                                <button type="submit" class="btn btn-info" id="btn-submit-product-edit">
                                    <i class="fa fa-save"></i>&nbsp;Submit
                                </button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <div class="box-footer clearfix">

                    </div>
                </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal END -->
    <!-- MODAL END -->

    <!--Modal Delete product-->
    <div class="modal fade" id="modal-delete-sub-product" tabindex="-1" role="dialog" aria-labelledby="modal-delete-subproductLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
        {!! Form::open(['url'=>'main-product.destroy_product', 'method'=>'post', 'id'=>'form-delete-sub-product']) !!}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modal-delete-subproductLabel">Konfirmasi</h4>
          </div>
          <div class="modal-body">
            You are going to remove sub product&nbsp;<b id="sub-product-name-to-delete"></b>
            <br/>
            <p class="text text-danger">
              <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
            </p>
            <input type="hidden" id="sub_product_id_delete" name="sub_product_id_delete">
            <input type="hidden" id="main_product_id_delete" name="main_product_id_delete">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger" id="btn-confirm-delete-sub-product">Delete</button>
          </div>
        {!! Form::close() !!}
        </div>
      </div>
    </div>
  <!--ENDModal Delete product-->
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
        $('#availability').text($('#sum_availability').text());
        $('#table-product').on('click','.btn-view-sub-product',function(){
            $('#view-product-name').text($(this).attr('data-text'));
            $('#view-product-description').text($(this).attr('data-description'));
            $('#view-product-stock').text($(this).attr('data-stock'));
            $('#view-product-minimum-stock').text($(this).attr('data-minimum-stock'));
            $('#view-product-unit').text($('#main-product-unit').text());
            $('#view-product-created-at').text($(this).attr('data-created-at'));
            $('#modal-view-sub-product').modal('show');
        });
        $('#table-product').on('click','.btn-edit-sub-product',function(){
            $('#product-name-edit').val($(this).attr('data-text'));
            $('#product-description-edit').val($(this).attr('data-description'));
            $('#product-stock-edit').val($(this).attr('data-stock'));
            $('#product-minimum-stock-edit').val($(this).attr('data-minimum-stock'));
            $('#product-id-edit').val($(this).attr('data-id'));
            $('#modal-edit-sub-product').modal('show');
        });
        $('#table-product').on('click','.btn-delete-sub-product',function(){
            $('#sub-product-name-to-delete').text($(this).attr('data-text'));
            $('#sub_product_id_delete').val($(this).attr('data-id'));
            $('#main_product_id_delete').val($(this).attr('data-main-product-id'));
            $('#modal-delete-sub-product').modal('show');
        });
    </script>
@endsection
