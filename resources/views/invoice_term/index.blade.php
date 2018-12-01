@extends('layouts.app')

@section('page_title')
  Invoice Term
@endsection

@section('page_header')
  <h1>
    Invoice Term
    <small>Invoice Term List</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('invoice-term') }}"><i class="fa fa-dashboard"></i> Invoice Term</a></li>
    <li class="active"><i></i>Index</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Invoice Term</h3>
          <a href="{{ URL::to('invoice-term/create')}}" class="btn btn-primary pull-right" title="Create new invoice term">
            <i class="fa fa-plus"></i>&nbsp;Add New
          </a>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
          <table class="display" id="table-invoice-term">
            <thead>
              <tr>
                <th style="width:5%;background-color:#3c8dbc;color:white">#</th>
                <th style="width:45%;background-color:#3c8dbc;color:white">Periode</th>
                <th style="width:40%;background-color:#3c8dbc;color:white">Day Many(s)</th>
                <th style="width:10%;text-align:center;background-color:#3c8dbc;color:white">Actions</th>
              </tr>
            </thead>
            <thead id="searchid">
                <tr>
                  <th style="width:5%;"></th>
                  <th style="width:45%;">Name</th>
                  <th style="width:40%;">Day Many(s)</th>
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

  <!--Modal Delete invoice-term-->
  <div class="modal fade" id="modal-delete-invoice-term" tabindex="-1" role="dialog" aria-labelledby="modal-delete-invoice-termLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      {!! Form::open(['url'=>'deleteInvoiceTerm', 'method'=>'post']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-delete-invoice-termLabel">Confirmation</h4>
        </div>
        <div class="modal-body">
          You are going to remove invoice term&nbsp;<b id="invoice-term-name-to-delete"></b>
          <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
          </p>
          <input type="hidden" id="invoice_term_id" name="invoice_term_id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal Delete invoice-term-->


@endsection


@section('additional_scripts')
  <script type="text/javascript">
    var tableInvoiceTerm =  $('#table-invoice-term').DataTable({
      processing :true,
      serverSide : true,
      ajax : '{!! route('datatables.getInvoiceTerms') !!}',
      columns :[
        {data: 'rownum', name: 'rownum', searchable:false},
        { data: 'name', name: 'name' },
        { data: 'day_many', name: 'day_many' },
        { data: 'actions', name: 'actions', orderable:false, searchable:false, className:'dt-center' },
      ],
    });

    // Delete button handler
    tableInvoiceTerm.on('click', '.btn-delete-invoice-term', function (e) {
      var id = $(this).attr('data-id');
      var code = $(this).attr('data-text');
      $('#invoice_term_id').val(id);
      $('#invoice-term-name-to-delete').text(code);
      $('#modal-delete-invoice-term').modal('show');
    });

      // Setup - add a text input to each header cell
    $('#searchid th').each(function() {
          if ($(this).index() != 0 && $(this).index() != 3) {
              $(this).html('<input class="form-control" type="text" placeholder="Search" data-id="' + $(this).index() + '" />');
          }

    });
    //Block search input and select
    $('#searchid input').keyup(function() {
      tableInvoiceTerm.columns($(this).data('id')).search(this.value).draw();
    });
    //ENDBlock search input and select
  </script>
@endsection
