@extends('layouts.app')

@section('page_title')
  Purchase Return
@endsection

@section('page_header')
  <h1>
    Purchase Return
    <small>Purchase Return List</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('purchase-return') }}"><i class="fa fa-dashboard"></i> Purchase Return</a></li>
    <li class="active"><i></i>Index</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Purchase Return</h3>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
          <table class="table table-striped table-hover" id="table-purchase-return">
            <thead>
              <tr style="background-color:#3c8dbc;color:white">
                <th style="width:5%;">#</th>
                <th style="width:15%;">Code</th>
                <th style="width:15%;">PO Code</th>
                <th style="width:15%;">Returned Qty</th>
                <th style="width:15%;">Created At</th>
                <th style="width:10%;">Status</th>
                <th style="width:15%;">Supplier Name</th>
                <th style="width:10%;text-align:center;">Actions</th>
              </tr>
            </thead>
            <thead id="searchid">
                <tr>
                    <th style="width:5%;"></th>
                    <th style="width:15%;">Code</th>
                    <th style="width:15%;">PO Code</th>
                    <th style="width:15%;">Returned Qty</th>
                    <th style="width:15%;">Created At</th>
                    <th style="width:10%;">Status</th>
                    <th style="width:15%;">Supplier Name</th>
                    <th style="width:10%;text-align:center;"></th>
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

  <!--Modal Delete purchase-return-->
  <div class="modal fade" id="modal-delete-purchase-return" tabindex="-1" role="dialog" aria-labelledby="modal-delete-purchase-returnLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      {!! Form::open(['url'=>'deletePurchaseReturn', 'method'=>'post']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-delete-purchase-returnLabel">Delete Purchase Return Confirmation</h4>
        </div>
        <div class="modal-body">
          You are going to remove purchase-return&nbsp;<b id="purchase-return-name-to-delete"></b>
          <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
          </p>
          <input type="hidden" id="purchase_return_id" name="purchase_return_id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal Delete purchase-return-->

<!--Modal Send purchase-return-->
  <div class="modal fade" id="modal-send-purchase-return" tabindex="-1" role="dialog" aria-labelledby="modal-send-purchase-returnLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      {!! Form::open(['url'=>'sendPurchaseReturn', 'method'=>'post', 'id'=>'form-send-purchase-return']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-send-purchase-returnLabel">Send Purchase Return Confirmation</h4>
        </div>
        <div class="modal-body">
          This purchase return status will be changed to "Sent".
          <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;The product will be returned to the supplier.
          </p>
          <input type="hidden" id="id_to_be_send" name="id_to_be_send">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger" id="btn-send-purchase-return">Send</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal Send purchase-return-->

<!--Modal complete purchase-return-->
  <div class="modal fade" id="modal-complete-purchase-return" tabindex="-1" role="dialog" aria-labelledby="modal-complete-purchase-returnLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      {!! Form::open(['url'=>'completePurchaseReturn', 'method'=>'post', 'id'=>'form-complete-purchase-return']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-send-purchase-returnLabel">Complete Purchase Return Confirmation</h4>
        </div>
        <div class="modal-body">
          This return status will be changed to completed
          <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;The product will be re-added to the inventory
          </p>
          <input type="hidden" id="id_to_be_completed" name="id_to_be_completed">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger" id="btn-complete-purchase-return">Complete</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal complete purchase-return-->


@endsection


@section('additional_scripts')
  <script type="text/javascript">
    var tablePurchaseReturn =  $('#table-purchase-return').DataTable({
      processing :true,
      serverSide : true,
      ajax : '{!! route('datatables.getPurchaseReturns') !!}',
      columns :[
        { data: 'rownum', name: 'rownum', searchable:false},
        { data: 'product_id', name: 'product.name' },
        { data: 'purchase_order_id', name: 'purchase_order.code' },
        { data: 'quantity', name: 'quantity' },
        { data: 'created_at', name: 'created_at' },
        { data: 'status', name: 'status' },
        { data: 'supplier_name', name: 'supplier_name', searchable: false},
        { data: 'actions', name: 'actions', orderable:false, searchable:false, className:'dt-center' },
      ],
    });

    // Delete button handler
    tablePurchaseReturn.on('click', '.btn-delete-purchase-return', function (e) {
      var id = $(this).attr('data-id');
      var code = $(this).attr('data-text');
      $('#purchase_return_id').val(id);
      $('#purchase-return-name-to-delete').text(code);
      $('#modal-delete-purchase-return').modal('show');
    });

      // Setup - add a text input to each header cell
    $('#searchid th').each(function() {
          if ($(this).index() != 0 && $(this).index() != 7) {
              $(this).html('<input class="form-control" type="text" placeholder="Search" data-id="' + $(this).index() + '" />');
          }

    });
    //Block search input and select
    $('#searchid input').keyup(function() {
      tablePurchaseReturn.columns($(this).data('id')).search(this.value).draw();
    });
    //ENDBlock search input and select

    //Handler send purchase return
    tablePurchaseReturn.on('click', '.btn-send-purchase-return', function (e) {
      var id = $(this).attr('data-id');
      $('#id_to_be_send').val(id);
      $('#modal-send-purchase-return').modal('show');
    });

    $('#form-send-purchase-return').on('submit', function(){
      $('#btn-send-purchase-return').prop('disabled', true);
    });
    //ENDHandler send purchase return

    //Handler complete purchase return
    tablePurchaseReturn.on('click', '.btn-complete-purchase-return', function (e) {
      var id = $(this).attr('data-id');
      $('#id_to_be_completed').val(id);
      $('#modal-complete-purchase-return').modal('show');
    });
    //ENDHandler complete purchase return

  </script>
@endsection
