@extends('layouts.app')

@section('page_title')
  Asset
@endsection

@section('page_header')
  <h1>
    Asset
    <small>Asset List</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('asset') }}"><i class="fa fa-dashboard"></i> Asset</a></li>
    <li class="active"><i></i>Index</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Asset</h3>
          <a href="{{ URL::to('asset/create')}}" class="btn btn-primary pull-right" title="Create new asset">
            <i class="fa fa-plus"></i>&nbsp;Add New
          </a>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
          <table class="table table-striped table-hover" id="table-asset">
            <thead>
              <tr style="background-color:#3c8dbc;color:white">
                <th style="width:5%;">#</th>
                <th style="width:15%;">Code</th>
                <th style="width:20%;">Name</th>
                <th style="width:15%;">Purchase</th>
                <th style="width:15%;">Amount</th>
                <th style="width:15%;">Periode</th>
                <th style="width:15%;text-align:center;">Actions</th>
              </tr>
            </thead>
            <thead id="searchid">
              <tr>
                  <th style="width:5%;"></th>
                  <th style="width:15%;">Code</th>
                  <th style="width:20%;">Name</th>
                  <th style="width:15%;">Purchase</th>
                  <th style="width:15%;">Amount</th>
                  <th style="width:15%;">Periode</th>
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

  <!--Modal Delete asset-->
  <div class="modal fade" id="modal-delete-asset" tabindex="-1" role="dialog" aria-labelledby="modal-delete-assetLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      {!! Form::open(['url'=>'deleteAsset', 'method'=>'post']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-delete-assetLabel">Confirmation</h4>
        </div>
        <div class="modal-body">
          You are going to remove asset&nbsp;<b id="asset-name-to-delete"></b>
          <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
          </p>
          <input type="hidden" id="asset_id" name="asset_id">
          <input type="hidden" id="asset_description" name="asset_description">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal Delete asset-->
@endsection

@section('additional_scripts')
  <script type="text/javascript">
    var tableAsset =  $('#table-asset').DataTable({
      processing :true,
      serverSide : true,
      ajax : '{!! route('datatables.getAssets') !!}',
      columns :[
        {data: 'rownum', name: 'rownum', searchable:false},
        { data: 'code', name: 'code' },
        { data: 'name', name: 'name' },
        { data: 'date_purchase', name: 'date_purchase' },
        { data: 'amount', name: 'amount' },
        { data: 'periode', name: 'periode' },
        { data: 'actions', name: 'actions', orderable:false, searchable:false, className:'dt-center'},
      ],
      "order" : [[1, "asc"]]


    });

    // Delete button handler
    tableAsset.on('click', '.btn-delete-asset', function (e) {
      var id = $(this).attr('data-id');
      var name = $(this).attr('data-text');
      $('#asset_id').val(id);
      $('#asset_description').val(name);
      $('#asset-name-to-delete').text(name);
      $('#modal-delete-asset').modal('show');
    });

      // Setup - add a text input to each header cell
    $('#searchid th').each(function() {
          if ($(this).index() != 0 && $(this).index() != 6) {
              $(this).html('<input class="form-control" type="text" placeholder="Search" data-id="' + $(this).index() + '" />');
          }

    });
    //Block search input and select
    $('#searchid input').keyup(function() {
      tableBank.columns($(this).data('id')).search(this.value).draw();
    });
    //ENDBlock search input and select

  </script>
@endsection
