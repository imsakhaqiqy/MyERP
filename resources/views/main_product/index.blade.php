@extends('layouts.app')

@section('page_title')
    Product
@endsection

@section('page_header')
    <h1>
        Product
        <small>Product List</small>
    </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('main-product') }}"><i class="fa fa-dashboard"></i> Product</a></li>
    <li class="active"><i></i>Index</li>
  </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3 class="box-title">Product</h3>
                    <a href="{{ URL::to('main-product/create') }}" class="btn btn-primary pull-right" title="Create new product">
                        <i class="fa fa-plus"></i>&nbsp;Add New
                    </a>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-striped table-hover" id="table-main-product">
                        <thead>
                            <tr style="background-color:#3c8dbc;color:white">
                                <th style="width:5%;">#</th>
                                <th style="width:15%;">Code</th>
                                <th style="width:20%;">Name</th>
                                <th style="width:10%;">Family</th>
                                <th style="width:15%;">Category</th>
                                <th style="width:10%;">Unit</th>
                                <th style="width:15%;">Description</th>
                                <th style="width:10%;text-align:center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix">

                </div>
            </div>
        </div>
    </div>

    <!--Modal Delete product-->
    <div class="modal fade" id="modal-delete-main-product" tabindex="-1" role="dialog" aria-labelledby="modal-delete-mainproductLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
        {!! Form::open(['url'=>'deleteMainProduct', 'method'=>'post', 'id'=>'form-delete-main-product']) !!}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modal-delete-mainproductLabel">Confirmation</h4>
          </div>
          <div class="modal-body">
            You are going to remove product&nbsp;<b id="main-product-name-to-delete"></b>
            <br/>
            <p class="text text-danger">
              <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
            </p>
            <input type="hidden" id="main_product_id" name="main_product_id">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger" id="btn-confirm-delete-main-product">Delete</button>
          </div>
        {!! Form::close() !!}
        </div>
      </div>
    </div>
  <!--ENDModal Delete product-->
@endsection

@section('additional_scripts')
  <script type="text/javascript">
    var tableMainProduct =  $('#table-main-product').DataTable({
      processing :true,
      serverSide : true,
      ajax : '{!! route('datatables.getMainProducts') !!}',
      columns :[
        { data: 'rownum', name: 'rownum', searchable:false},
        { data: 'code', name: 'code' },
        { data: 'name', name: 'name' },
        { data: 'family_id', name: 'family_id' },
        { data: 'category_id', name: 'category_id' },
        { data: 'unit_id', name: 'unit_id'},
        { data: 'description', name: 'description', searchable:false},
        { data: 'actions', name: 'actions', orderable:false, searchable:false },
      ],


    });

    // Delete button handler
    tableMainProduct.on('click', '.btn-delete-main-product', function (e) {
      var id = $(this).attr('data-id');
      var name = $(this).attr('data-text');
      $('#main_product_id').val(id);
      $('#main_product-name-to-delete').text(name);
      $('#modal-delete-main-product').modal('show');
    });

    // setup - add a text input to each header cell
    $('#searchid th').each(function(){
        if($(this).index() != 0 && $(this).index() != 7){
            $(this).html('<input class="form-control type="text" placeholder="Search" data-id="'+$(this).index()+'"/>');
        }
    });

  </script>
@endsection
