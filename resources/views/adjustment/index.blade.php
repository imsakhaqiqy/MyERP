@extends('layouts.app')

@section('page_title')
  Adjustment
@endsection

@section('page_header')
  <h1>
    Adjustment
    <small>Adjustment Product List</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('adjustment') }}"><i class="fa fa-dashboard"></i> Adjustment Product</a></li>
    <li class="active"><i></i>Index</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Adjustment</h3>
          <a href="{{ URL::to('product-adjustment/create')}}" class="btn btn-primary pull-right" title="Create new adjustment product">
            <i class="fa fa-plus"></i>&nbsp;Add New
          </a>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
          <table class="table table-hover table-striped" id="table-adjustment">
            <thead>
              <tr>
                <th style="width:5%;background-color:#3c8dbc;color:white">#</th>
                <th style="width:20%;background-color:#3c8dbc;color:white">Adjust No</th>
                <th style="width:15%;background-color:#3c8dbc;color:white">IN/OUT</th>
                <th style="width:15%;background-color:#3c8dbc;color:white">Qty</th>
                <th style="width:15%;background-color:#3c8dbc;color:white">Notes</th>
                <th style="width:15%;background-color:#3c8dbc;color:white">Created At</th>
                <th style="width:15%;text-align:center;background-color:#3c8dbc;color:white;">Actions</th>
              </tr>
            </thead>
            <thead id="searchid">
              <tr>
                  <th style="width:5%;"></th>
                  <th style="width:20%;">Adjust No</th>
                  <th style="width:15%;">IN/OUT</th>
                  <th style="width:15%;">Qty</th>
                  <th style="width:15%;">Notes</th>
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

  <!--Modal Delete supplier-->
  <div class="modal fade" id="modal-delete-adjustment" tabindex="-1" role="dialog" aria-labelledby="modal-delete-adjustmentLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      {!! Form::open(['url'=>'deleteAdjustment', 'method'=>'post']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-delete-adjustmentLabel">Confirmation</h4>
        </div>
        <div class="modal-body">
          You are going to remove adjustment&nbsp;<b id="adjustment-name-to-delete"></b>
          <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
          </p>
          <input type="hidden" id="adjustment_id" name="adjustment_id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal Delete supplier-->
@endsection

@section('additional_scripts')
  <script type="text/javascript">
    var tableAdjustment =  $('#table-adjustment').DataTable({
      processing :true,
      serverSide : true,
      ajax : '{!! route('datatables.getAdjustments') !!}',
      columns :[
        {data: 'rownum', name: 'rownum', searchable:false},
        { data: 'code', name: 'code' },
        { data: 'in_out', name: 'in_out' },
        { data: 'qty', name: 'qty'},
        { data: 'notes', name: 'notes'},
        { data: 'created_at', name: 'created_at'},
        { data: 'actions', name: 'actions', orderable:false, searchable:false, className:'dt-center'},
      ],
      "order" : [[1, "asc"]]


    });

    //Delete button handler
    tableAdjustment.on('click', '.btn-delete-product-adjustment', function (e) {
      var id = $(this).attr('data-id');
      var name = $(this).attr('data-text');
      $('#adjustment_id').val(id);
      $('#adjustment-name-to-delete').text(name);
      $('#modal-delete-adjustment').modal('show');
    });

      // Setup - add a text input to each header cell
    $('#searchid th').each(function() {
          if ($(this).index() != 0 && $(this).index() != 6) {
              $(this).html('<input class="form-control" type="text" placeholder="Search" data-id="' + $(this).index() + '" />');
          }

    });
    //Block search input and select
    $('#searchid input').keyup(function() {
       tableAdjustment.columns($(this).data('id')).search(this.value).draw();
     });
    //ENDBlock search input and select

  </script>
@endsection
