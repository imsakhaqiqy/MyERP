@extends('layouts.app')

@section('page_title')
  Customers
@endsection

@section('page_header')
  <h1>
    Customer
    <small>Customer List</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('customer') }}"><i class="fa fa-dashboard"></i> Customer</a></li>
    <li class="active"><i></i>Index</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Customer</h3>
          <a href="{{ URL::to('customer/create')}}" class="btn btn-primary pull-right" title="Create new customer">
            <i class="fa fa-plus"></i>&nbsp;Add New
          </a>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="table-responsive">
            <table class="display" id="table-customer">
              <thead>
                <tr>
                  <th style="width:5%;background-color:#3c8dbc;color:white">#</th>
                  <th style="width:10%;background-color:#3c8dbc;color:white">Code</th>
                  <th style="width:20%;background-color:#3c8dbc;color:white">Name</th>
                  <th style="width:15%;background-color:#3c8dbc;color:white">Phone</th>
                  <th style="width:25%;background-color:#3c8dbc;color:white">Address</th>
                  <th style="width:15%;background-color:#3c8dbc;color:white">Term</th>
                  <th style="width:10%;text-align:center;background-color:#3c8dbc;color:white">Actions</th>
                </tr>
              </thead>
              <thead id="searchid">
                <tr>
                  <th style="width:5%;"></th>
                  <th style="width:10%;">Code</th>
                  <th style="width:20%;">Name</th>
                  <th style="width:15%;">Phone</th>
                  <th style="width:25%;">Address</th>
                  <th style="width:15%">Term</th>
                  <th style="width:10%;text-align:center;"></th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
        </div><!-- /.box-body -->
        <div class="box-footer clearfix">

        </div>
      </div><!-- /.box -->

    </div>
  </div>

  <!--Modal Delete customer-->
  <div class="modal fade" id="modal-delete-customer" tabindex="-1" role="dialog" aria-labelledby="modal-delete-customerLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      {!! Form::open(['url'=>'deleteCustomer', 'method'=>'post']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-delete-customerLabel">Confirmation</h4>
        </div>
        <div class="modal-body">
          You are going to remove customer&nbsp;<b id="customer-name-to-delete"></b>
          <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
          </p>
          <input type="hidden" id="customer_id" name="customer_id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal Delete customer-->
@endsection

@section('additional_scripts')
  <script type="text/javascript">
    var tableCustomer =  $('#table-customer').DataTable({
      processing :true,
      serverSide : true,
      ajax : '{!! route('datatables.getCustomers') !!}',
      columns :[
        {data: 'rownum', name: 'rownum', searchable:false},
        { data: 'code', name: 'code' },
        { data: 'name', name: 'name' },
        { data: 'phone_number', name: 'phone_number' },
        { data: 'address', name: 'address'},
        { data: 'invoice_term_id', name: 'invoice_term_id'},
        { data: 'actions', name: 'actions', orderable:false, searchable:false, 'className':'dt-center'},
      ],
      "order" : [[1, "asc"]]


    });

    // Delete button handler
    tableCustomer.on('click', '.btn-delete-customer', function (e) {
      var id = $(this).attr('data-id');
      var name = $(this).attr('data-text');
      $('#customer_id').val(id);
      $('#customer-name-to-delete').text(name);
      $('#modal-delete-customer').modal('show');
    });

      // Setup - add a text input to each header cell
    $('#searchid th').each(function() {
          if ($(this).index() != 0 && $(this).index()!=6) {
              $(this).html('<input class="form-control" type="text" placeholder="Search" data-id="' + $(this).index() + '" />');
          }

    });
    //Block search input and select
    $('#searchid input').keyup(function() {
      tableCustomer.columns($(this).data('id')).search(this.value).draw();
    });
    //ENDBlock search input and select

  </script>
  @endsection
