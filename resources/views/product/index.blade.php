@extends('layouts.app')

@section('page_title')
  Products
@endsection

@section('page_header')
  <h1>
    Products
    <small>Product Lists</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('product') }}"><i class="fa fa-dashboard"></i> Products</a></li>
    <li class="active"><i></i>Index</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Products</h3>
          <a href="{{ URL::to('product/create')}}" class="btn btn-primary pull-right" title="Create new product">
            <i class="fa fa-plus"></i>&nbsp;Add New
          </a>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
          <table class="table table-bordered" id="table-product">
            <thead>
              <tr>
                <th style="width:5%;">#</th>
                <th style="width:10%;">Code</th>
                <th>Product Name</th>
                <th style="width:20%;">Category</th>
                <th style="width:10%;">Unit</th>
                <th style="width:10%;">Stock</th>
                <th style="width:10%;text-align:center;">Actions</th>
              </tr>
            </thead>
            <thead id="searchid">
              <tr>
                <th style="width:5%;"></th>
                <th>Code</th>
                <th>Product Name</th>
                <th>Category</th>
                <th style="width:10%;">Unit</th>
                <th style="width:10%;"></th>
                <th></th>
              </tr>
            </thead>
            <tbody>

            </tbody>
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
      {!! Form::open(['url'=>'deleteProduct', 'method'=>'post', 'id'=>'form-delete-product']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-delete-productLabel">Konfirmasi</h4>
        </div>
        <div class="modal-body">
          You are going to remove product&nbsp;<b id="product-name-to-delete"></b>
          <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
          </p>
          <input type="hidden" id="product_id" name="product_id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger" id="btn-confirm-delete-product">Delete</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal Delete product-->
@endsection

@section('additional_scripts')
  <script type="text/javascript">
    var tableProduct =  $('#table-product').DataTable({
      processing :true,
      serverSide : true,
      ajax : '{!! route('datatables.getProducts') !!}',
      columns :[
        {data: 'rownum', name: 'rownum', searchable:false},
        { data: 'code', name: 'code' },
        { data: 'name', name: 'name' },
        { data: 'category_id', name: 'category_id' },
        { data: 'unit_id', name: 'unit_id'},
        { data: 'stock', name: 'stock'},
        { data: 'actions', name: 'actions', orderable:false, searchable:false },
      ],

    });

    // Delete button handler
    tableProduct.on('click', '.btn-delete-product', function (e) {
      var id = $(this).attr('data-id');
      var name = $(this).attr('data-text');
      $('#product_id').val(id);
      $('#product-name-to-delete').text(name);
      $('#modal-delete-product').modal('show');
    });


      // Setup - add a text input to each header cell
    $('#searchid th').each(function() {
          if ($(this).index() != 0 && $(this).index() != 5) {
              $(this).html('<input class="form-control" type="text" placeholder="Search" data-id="' + $(this).index() + '" />');
          }
          if ($(this).index() == 3) {

            var category_selections_builder ='<select class="form-control" data-id="' + $(this).index() + '">';
                category_selections_builder+='<option value="">-- All Categories --</option>';
                  @foreach($category_selections as $category)
                    category_selections_builder+='<option value="{{ $category->id }}">{{ $category->name }}</option>';
                  @endforeach
                category_selections_builder+='</select>';

              $(this).html(category_selections_builder);
          }

    });
    //Block search input and select
    $('#searchid input').keyup(function() {
      tableProduct.columns($(this).data('id')).search(this.value).draw();
    });
    $('#searchid select').change(function () {
      if($(this).val() == ""){
        tableProduct.columns($(this).data('id')).search('').draw();
      }
      else{
        tableProduct.columns($(this).data('id')).search(this.value).draw();
      }
    });
    //ENDBlock search input and select


    //Delete product process
    $('#form-delete-product').on('submit', function(){
      $('#btn-confirm-delete-product').prop('disabled', true);
    });

  </script>
@endsection
