@extends('layouts.app')

@section('page_title')
  Purchase Order
@endsection

@section('page_header')
  <h1>
    Purchase Order
    <small>Purchase Order List</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('purchase-order') }}"><i class="fa fa-cart-arrow-down"></i> Purchase Order</a></li>
    <li class="active"><i></i>Index</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Purchase Order</h3>
          <a href="{{ URL::to('purchase-order/create')}}" class="btn btn-primary pull-right" title="Add new purchase order">
            <i class="fa fa-plus"></i>&nbsp;Add New
          </a>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
          <table class="table table-striped table-hover" id="table-purchase-order">
            <thead>
              <tr style="background-color:#3c8dbc;color:white">
                <th style="width:5%;">#</th>
                <th style="width:10%;">Code</th>
                <th style="width:20%;">Supplier</th>
                <th style="width:15%;">Creator</th>
                <th style="width:20%;">Date Created</th>
                <th style="width:10%;">Status</th>
                <th style="width:10%;">Invoice</th>
                <th style="width:10%;text-align:center;">Actions</th>
              </tr>
            </thead>
            <thead id="searchid">
              <tr>
                  <th style="width:5%;"></th>
                  <th style="width:10%;">Code</th>
                  <th style="width:20%">Supplier</th>
                  <th style="width:15%">Creator</th>
                  <th style="width:20%;">Date Created</th>
                  <th style="width:10%;">Status</th>
                  <th style="width:10%;">Invoice</th>
                  <th style="width:10%;"></th>
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

  <!--Modal Delete purchase-order-->
  <div class="modal fade" id="modal-delete-purchase-order" tabindex="-1" role="dialog" aria-labelledby="modal-delete-purchase-orderLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      {!! Form::open(['url'=>'deletePurchaseOrder', 'method'=>'post']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-delete-purchase-orderLabel">Delete Purchase Order Confirmation</h4>
        </div>
        <div class="modal-body">
          You are going to remove purchase-order&nbsp;<b id="purchase-order-name-to-delete"></b>
          <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
          </p>
          <input type="hidden" id="purchase_order_id" name="purchase_order_id">
          <input type="hidden" name="payment_id" id="payment-id">
          <input type="hidden" name="bank_purchase_id" id="bank_purchase_id">
          <input type="hidden" name="id_invoice_delete" id="id_invoice_delete">
          <input type="hidden" name="code_invoice_delete" id="code_invoice_delete">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal Delete purchase-order-->

<!--Modal Accept purchase-order-->
  <div class="modal fade" id="modal-accept-purchase-order" tabindex="-1" role="dialog" aria-labelledby="modal-accept-purchase-orderLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      {!! Form::open(['url'=>'acceptPurchaseOrder', 'method'=>'post']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-accept-purchase-orderLabel">Accept Purchase Order Confirmation</h4>
        </div>
        <div class="modal-body">
          <b id="purchase-order-name-to-accept"></b> will be changed to Accepted
          <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
          </p>
          <input type="hidden" id="id_to_be_accepted" name="id_to_be_accepted">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Accept</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal Accept purchase-order-->

<!--Modal complete purchase-order-->
  <div class="modal fade" id="modal-complete-purchase-order" tabindex="-1" role="dialog" aria-labelledby="modal-complete-purchase-orderLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      {!! Form::open(['url'=>'completePurchaseOrder', 'method'=>'post']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-complete-purchase-orderLabel">Complete Purchase Order Confirmation</h4>
        </div>
        <div class="modal-body">
          <b id="purchase-order-name-to-complete"></b> will be changed to completed
          <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
          </p>
          <input type="hidden" id="id_to_be_completed" name="id_to_be_completed">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">complete</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal Complete purchase-order-->

@endsection


@section('additional_scripts')
  <script type="text/javascript">
    var tablePurchaseOrder =  $('#table-purchase-order').DataTable({
      processing :true,
      serverSide : true,
      ajax : '{!! route('datatables.getPurchaseOrders') !!}',
      columns :[
        {data: 'rownum', name: 'rownum', searchable:false},
        { data: 'code', name: 'code' },
        { data: 'supplier_id', name: 'supplier.name' },
        { data: 'creator', name: 'created_by.name' },
        { data: 'created_at', name: 'created_at' },
        { data: 'status', name: 'status' },
        { data: 'invoice', name: 'invoice', searchable: false},
        { data: 'actions', name: 'actions', orderable:false, searchable:false, className:'dt-center' },
      ],
    });

    // Delete button handler
    tablePurchaseOrder.on('click', '.btn-delete-purchase-order', function (e) {
      var id = $(this).attr('data-id');
      var code = $(this).attr('data-text');
      var idPayment = $(this).attr('data-id-payment');
      var idBankPurchase = $(this).attr('data-id-bank-purchase-payment');
      var idInvoice = $(this).attr('data-id-invoice');
      var codeInvoice = $(this).attr('data-code-invoice');
      $('#purchase_order_id').val(id);
      $('#payment-id').val(idPayment);
      $('#bank_purchase_id').val(idBankPurchase);
      $('#purchase-order-name-to-delete').text(code);
      $('#id_invoice_delete').val(idInvoice);
      $('#code_invoice_delete').val(codeInvoice);
      $('#modal-delete-purchase-order').modal('show');
    });

      // Setup - add a text input to each header cell
    $('#searchid th').each(function() {
          if ($(this).index() != 0 && $(this).index() != 7) {
              $(this).html('<input class="form-control" type="text" placeholder="Search" data-id="' + $(this).index() + '" />');
          }

    });
    //Block search input and select
    $('#searchid input').keyup(function() {
      tablePurchaseOrder.columns($(this).data('id')).search(this.value).draw();
    });
    //ENDBlock search input and select

    //Handler accept purchase order
    tablePurchaseOrder.on('click', '.btn-accept-purchase-order', function (e) {
      var id = $(this).attr('data-id');
      var code = $(this).attr('data-text');
      $('#id_to_be_accepted').val(id);
      $('#purchase-order-name-to-accept').text(code);
      $('#modal-accept-purchase-order').modal('show');
    });
    //ENDHandler accept purchase order

    //Handler complete purchase order
    tablePurchaseOrder.on('click', '.btn-complete-purchase-order', function (e) {
      var id = $(this).attr('data-id');
      var code = $(this).attr('data-text');
      $('#id_to_be_completed').val(id);
      $('#purchase-order-name-to-complete').text(code);
      $('#modal-complete-purchase-order').modal('show');
    });
    //ENDHandler complete purchase order
  </script>
@endsection
