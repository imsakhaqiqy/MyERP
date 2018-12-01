@extends('layouts.app')

@section('page_title')
  List Giro
@endsection

@section('page_header')
  <h1>
    List Giro
    <small>Giro List</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('purchase-giro') }}"><i class="fa fa-dashboard"></i> List Giro</a></li>
    <li class="active"><i></i>Index</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">List Giro</h3>
          <!-- <a href="{{ URL::to('invoice-term/create')}}" class="btn btn-primary pull-right" title="Create new invoice term">
            <i class="fa fa-plus"></i>&nbsp;Add New
          </a> -->
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
          <table class="display" id="table-list-giro">
            <thead>
              <tr>
                <th style="width:5%;background-color:#3c8dbc;color:white">#</th>
                <th style="width:15%;background-color:#3c8dbc;color:white">No.Giro</th>
                <th style="width:15%;background-color:#3c8dbc;color:white">Bank Name</th>
                <th style="width:15%;background-color:#3c8dbc;color:white">Liquid date</th>
                <th style="width:10%;background-color:#3c8dbc;color:white">Amount</th>
                <th style="width:10%;background-color:#3c8dbc;color:white">Status</th>
                <th style="width:15%;background-color:#3c8dbc;color:white">Created At</th>
                <th style="width:15%;text-align:center;background-color:#3c8dbc;color:white">Actions</th>
              </tr>
            </thead>
            <thead id="searchid">
                <tr>
                    <th style="width:5%;"></th>
                    <th style="width:15%;">No.Giro</th>
                    <th style="width:15%;">Bank Name</th>
                    <th style="width:15%;">Liquid date</th>
                    <th style="width:10%;">Amount</th>
                    <th style="width:10%;">Status</th>
                    <th style="width:15%;">Created At</th>
                    <th style="width:15%;text-align:center;"></th>
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

  <!--Modal Delete invoice-term-->
  <div class="modal fade" id="modal-approve-purchase-giro" tabindex="-1" role="dialog" aria-labelledby="modal-approve-purchase-giroLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      {!! Form::open(['url'=>'approvePurchaseGiro', 'method'=>'post']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-approve-purchase-giroLabel">Confirmation</h4>
        </div>
        <div class="modal-body">
          You are going to this approve payment giro&nbsp;<b id="payment-invoice-giro-to-approve"></b>
          <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
          </p>
          <input type="hidden" id="purchase_giro_id" name="purchase_giro_id">
          <input type="hidden" id="purchase_giro_no_giro" name="purchase_giro_no_giro">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Approve</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal Delete invoice-term-->


@endsection


@section('additional_scripts')
  <script type="text/javascript">
    var tableGiro =  $('#table-list-giro').DataTable({
      processing :true,
      serverSide : true,
      ajax : '{!! route('datatables.getPurchaseGiros') !!}',
      columns :[
        {data: 'rownum', name: 'rownum', searchable:false},
        { data: 'no_giro', name: 'no_giro' },
        { data: 'bank', name: 'bank' },
        { data: 'tanggal_cair', name: 'tanggal_cair' },
        { data: 'amount', name: 'amount' },
        { data: 'status', name: 'status' },
        { data: 'created_at', name: 'created_at' },
        { data: 'actions', name: 'actions', orderable:false, searchable:false, className:'dt-center' },
      ],
    });

    // Delete button handler
    tableGiro.on('click', '.btn-approve-giro', function (e) {
      var id = $(this).attr('data-id');
      var status = $(this).attr('data-status');
      var no_giro = $(this).attr('data-text');
      $('#purchase_giro_id').val(id);
      $('#payment-invoice-giro-to-approve').text(no_giro);
      $('#purchase_giro_no_giro').val(no_giro);
      if(status == 'pos'){
            $('#modal-approve-purchase-giro').modal('show');
      }
      return false;
    });

      // Setup - add a text input to each header cell
    $('#searchid th').each(function() {
          if ($(this).index() != 0 && $(this).index() != 7) {
              $(this).html('<input class="form-control" type="text" placeholder="Search" data-id="' + $(this).index() + '" />');
          }

    });
    //Block search input and select
    $('#searchid input').keyup(function() {
      tableGiro.columns($(this).data('id')).search(this.value).draw();
    });
    //ENDBlock search input and select
  </script>
@endsection
