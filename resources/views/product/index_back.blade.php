@extends('layouts.app')

@section('page_title')
  Produk
@endsection

@section('page_header')
  <h1>
    Produk
    <small>Daftar Produk</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('product') }}"><i class="fa fa-dashboard"></i> Produk</a></li>
    <li class="active"><i></i>Index</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Produk</h3>
          <a href="{{ URL::to('product/create')}}" class="btn btn-primary pull-right" title="Create new product">
            <i class="fa fa-plus"></i>&nbsp;Add New
          </a>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table class="table table-bordered" id="table-product">
            <tr>
              <th style="width:5%;">#</th>
              <th style="width:10%;">Code</th>
              <th>Nama Produk</th>
              <th style="width:15%;">Kategori</th>
              <th style="width:10%;text-align:center;">Aksi</th>
            </tr>
            @if($products->count() >0)
              @foreach($products as $product)
              <tr>
                <td>#</td>
                <td>{{ $product->code }}</td>
                <td>
                  <a href="{{ url('product/'.$product->id) }}">{{ $product->name }}</a>
                </td>
                <td>{{ $product->category->name }}</td>
                <td style="text-align:center;">
                  <a href="{{ url('product/'.$product->id.'/edit') }}" class="btn btn-info btn-xs" title="Klik untuk mengedit produk ini">
                    <i class="fa fa-edit"></i>
                  </a>&nbsp;
                  <button type="button" class="btn btn-danger btn-xs btn-delete-product" data-id="{{ $product->id }}" data-text="{{$product->name}}">
                    <i class="fa fa-trash"></i>
                  </button>
                </td>
              </tr>
              @endforeach
            @else
            <tr>
              <td colspan="5">Tidak ada produk terdaftar</td>
            </tr>
            @endif
          </table>
        </div><!-- /.box-body -->
        <div class="box-footer clearfix">
          
        </div>
      </div><!-- /.box -->
    
    </div>
  </div>

  <!--Modal Delete product-->
  <div class="modal fade" id="modal-delete-product" tabindex="-1" role="dialog" aria-labelledby="modal-delete-productLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      {!! Form::open(['url'=>'deleteProduct', 'method'=>'post']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-delete-productLabel">Konfirmasi</h4>
        </div>
        <div class="modal-body">
          Anda akan menghapus produk <b id="product-name-to-delete"></b>
          <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;Proses menghapus tidak bisa dibatalkan
          </p>
          <input type="hidden" id="product_id" name="product_id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal Delete product-->
@endsection

@section('additional_scripts')
  <script type="text/javascript">
    $('.btn-delete-product').on('click', function(){
      var id = $(this).attr('data-id');
      var name = $(this).attr('data-text');
      $('#product_id').val(id);
      $('#product-name-to-delete').text(name);
      $('#modal-delete-product').modal('show');
    });
  </script>
@endsection