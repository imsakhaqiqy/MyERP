@extends('layouts.app')

@section('page_title')
    Product
@endsection

@section('page_header')
    <h1>
        Product
        <small> Product Detail</small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('main-product') }}"><i class="fa fa-dashboard"></i> Product</a></li>
        <li class="active"><i></i>{{ $main_product->name }}</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bars"></i>&nbsp;General Information</h3>
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
                                <div class="col-sm-8 availability"></div>
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
                <h3 class="box-title">Sub Product</h3>
              </div><!-- /.box-header -->
              <div class="box-body">
                  <div class="box-responsive" style="max-height:500px">
                    <table class="table table-bordered" id="table-product">
                        <thead>
                            <tr style="">
                              <th style="width:10%;">Family</th>
                              <th style="width:15%;">Name</th>
                              <th style="width:15%;">Description</th>
                              <th style="width:10%;">Unit</th>
                              <th style="width:15%;">Stock</th>
                              <th style="width:15%;display:none">Stock Minumum</th>
                              <th style="width:20%;">Category</th>
                            </tr>
                        </thead>
                      <tbody>
                          <tr>
                              <td>{{ $main_product->family->name }}</td>
                              <td>
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
                              <td class="availability"></td>
                              <td style="display:none"></td>
                              <td>{{ $main_product->category->name}}</td>
                          </tr>
                          <?php $no = 1; $sum = 0;?>
                          @foreach($product as $key)
                            <tr>
                                <td>{{ $main_product->family->name }}</td>
                                <td>{{ $key->name }}</td>
                                <td>{{ $key->description }}</td>
                                <td>{{ $main_product->unit->name}} </td>
                                <td>{{ $key->stock }}</td>
                                <td style="display:none">{{ $key->minimum_stock }}</td>
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

              </div>
            </div><!-- /.box -->
        </div>
    </div>
@endsection

@section('additional_scripts')
    <script type="text/javascript">
        $('.availability').text($('#sum_availability').text());
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
