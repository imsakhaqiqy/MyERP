@extends('layouts.app')

@section('page_title')
  Permission
@endsection

@section('page_header')
  <h1>
    Permission
    <small>Permission List</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="#"><i class="fa fa-dashboard"></i> Permission </a></li>
    <li class="active"><i></i>Index</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Permission List</h3>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
          <table class="display" id="table-permission">
            <thead>
              <tr>
                <th style="width:5%;background-color:#3c8dbc;color:white">#</th>
                <th style="width:40%;background-color:#3c8dbc;color:white">Permission Slug</th>
                <th style="width:40%;background-color:#3c8dbc;color:white">Description</th>
                <th style="width:15%;background-color:#3c8dbc;color:white;text-align:center">Actions</th>
              </tr>
            </thead>
            <thead id="searchid">
              <tr>
                <th style="width:5%;"></th>
                <th style="width:40%;">Permission Slug</th>
                <th style="width:40%;">Description</th>
                <th style="width:15%;"></th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div><!-- /.box-body -->
        <div class="box-footer clearfix"></div>
      </div><!-- /.box -->
    </div>
</div>

@endsection

@section('additional_scripts')
  <script type="text/javascript">
  var tablePermission =  $('#table-permission').DataTable({
      processing :true,
      serverSide : true,
      ajax : '{!! route('datatables.getPermissions') !!}',
      columns :[
        {data: 'rownum', name: 'rownum', searchable:false, orderable:false},
        {data: 'slug', name: 'slug' },
        {data: 'description', name: 'description'},
        {data: 'actions', name: 'actions', searchable:false, orderable:false},
      ],
    });
    // Setup - add a text input to each header cell
  $('#searchid th').each(function() {
        if ($(this).index() != 0 && $(this).index() != 3) {
            $(this).html('<input class="form-control" type="text" placeholder="Search" data-id="' + $(this).index() + '" />');
        }

  });
  //Block search input and select
  $('#searchid input').keyup(function() {
    tablePermission.columns($(this).data('id')).search(this.value).draw();
  });
  //ENDBlock search input and select
  </script>
@endsection
