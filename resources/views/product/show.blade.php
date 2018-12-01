@extends('layouts.app')

@section('page_title')
  Products
@endsection

@section('page_header')
  <h1>
    Products
    <small> Product Detail</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('product') }}"><i class="fa fa-dashboard"></i> Products</a></li>
    <li class="active"><i></i>{{ $product->code }}</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">{{ $product->code }}</h3>
          
        </div><!-- /.box-header -->
        <div class="box-body">

          <div class="row">
            <div class="col-md-3">
              @if($product->image != NULL)
                <a href="#" class="thumbnail">
                  {!! Html::image('img/products/thumb_'.$product->image.'', $product->image) !!}
                </a>
              @else
                <a href="#" class="thumbnail">
                  {!! Html::image('files/default/noimageavailable.jpeg', 'No Image') !!}
                </a>
              @endif
            </div>
            <div class="col-md-9">
              <div class="row">
                <div class="col-sm-2">Code</div>
                <div class="col-sm-1">:</div>
                <div class="col-sm-3">{{ $product->code }}</div>
              </div>
              <p></p>
              <div class="row">
                <div class="col-sm-2">Name</div>
                <div class="col-sm-1">:</div>
                <div class="col-sm-3">{{ $product->name }}</div>
              </div>
              <p></p>
              <div class="row">
                <div class="col-sm-2">Category</div>
                <div class="col-sm-1">:</div>
                <div class="col-sm-3">{{ $product->category->name }}</div>
              </div>
              <p></p>
              <div class="row">
                <div class="col-sm-2">Unit</div>
                <div class="col-sm-1">:</div>
                <div class="col-sm-3">{{ $product->unit->name }}</div>
              </div>
              <p></p>
              <div class="row">
                <div class="col-sm-2">Stock</div>
                <div class="col-sm-1">:</div>
                <div class="col-sm-3">{{ $product->stock }}</div>
              </div>
              <p></p>
              <div class="row">
                <div class="col-sm-2">Availability</div>
                <div class="col-sm-1">:</div>
                <div class="col-sm-3">
                  @if($product->stock == 0)
                    <button class="btn btn-danger btn-sm">Empty</button>
                  @elseif($product->stock == $product->minimum_stock)
                    <button class="btn btn-warning">Critical</button>
                  @elseif($product->stock < $product->minimum_stock)
                    <button class="btn btn-info">Need an order</button>
                  @else
                    <button class="btn btn-success">Available</button>
                  @endif
                </div>
              </div>
              <p></p>
              <div class="row">
                <div class="col-sm-2">Created At</div>
                <div class="col-sm-1">:</div>
                <div class="col-sm-3">{{ $product->created_at }}</div>
              </div>
              <p></p>
            </div>
          </div>
          
        </div><!-- /.box-body -->
        <div class="box-footer clearfix">
          
        </div>
      </div><!-- /.box -->
    
    </div>
  </div>

@endsection

@section('additional_scripts')
  
@endsection