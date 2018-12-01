@extends('layouts.app')

@section('page_title')
    Category Product Detail
@endsection

@section('page_header')
  <h1>
    Category Product
    <small>Detail Category Product</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('category') }}"><i class="fa fa-dashboard"></i> Category Product</a></li>
    <li class="active"><i></i> {{ $category->name }}</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-4">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Basic Information</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table class="table">
            <tr>
              <td><b>Code</b></td>
              <td>{{ $category->code }}</td>
            </tr>
            <tr>
              <td><b>Category Name</b></td>
              <td>{{ $category->name }}</td>
            </tr>

          </table>
        </div><!-- /.box-body -->
        <div class="box-footer clearfix">

        </div>
      </div><!-- /.box -->
    </div>
    <div class="col-lg-8">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Product</h3>&nbsp;
          <small>Products are categorized {{ $category->name }}</small>
        </div><!-- /.box-header -->
        <div class="box-body">

          @if($category->count() > 0)
            <table class="table table-responsive">
              <tr>
                <th>Code</th>
                <th>Name</th>
              </tr>
              @foreach($category->main_products as $product)
              <tr>
                <td>{{ $product->code }}</td>
                <td>{{ $product->name }}</td>
              </tr>
              @endforeach
            </table>
          @else
            <p class="alert alert-info">
              <i class="fa fa-info-circle"></i>&nbsp;Tidak ada produk dalam kategori ini, klik tombol tambah produk untuk menambahkan
            </p>
            <p>

            </p>
          @endif
        </div><!-- /.box-body -->
        <div class="box-footer clearfix">
          <a href="#" class="btn btn-link">
            Tambah Produk
          </a>
        </div>
      </div><!-- /.box -->
    </div>
  </div>

@endsection

@section('additional_scripts')

@endsection
