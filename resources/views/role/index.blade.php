@extends('layouts.app')

@section('page_title')
  Role
@endsection

@section('page_header')
  <h1>
    Role
    <small>Role List</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="#"><i class="fa fa-dashboard"></i> Role </a></li>
    <li class="active"><i></i>Index</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Role List</h3>
          <a href="{{ URL::to('role/create')}}" class="btn btn-primary pull-right" title="Add new role">
            <i class="fa fa-plus"></i>&nbsp;Add New
          </a>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
          <table class="display" id="table-role">
            <thead>
              <tr>
                <th style="width:5%;background-color:#3c8dbc;color:white">#</th>
                <th style="width:80%;background-color:#3c8dbc;color:white">Role Name</th>
                <th style="width:15%;text-align:center;background-color:#3c8dbc;color:white">Actions</th>
              </tr>
            </thead>
            <thead id="searchid">
              <tr>
                <th style="width:5%;"></th>
                <th style="width:80%;">Role Name</th>
                <th style="width:15%;text-align:center;"></th>
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
<!--Modal Delete role-->
  <div class="modal fade" id="modal-delete-role" tabindex="-1" role="dialog" aria-labelledby="modal-delete-roleLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      {!! Form::open(['url'=>'deleteRole', 'method'=>'post']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-delete-roleLabel">Delete role Confirmation</h4>
        </div>
        <div class="modal-body">
          You are going to remove role&nbsp;<b id="role-name-to-delete"></b>
          <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
          </p>
          <input type="hidden" id="role_id" name="role_id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal Delete role-->
@endsection

@section('additional_scripts')
  <script type="text/javascript">
  var tableRole =  $('#table-role').DataTable({
      processing :true,
      serverSide : true,
      ajax : '{!! route('datatables.getRoles') !!}',
      columns :[
        {data: 'rownum', name: 'rownum', searchable:false, orderable:false},
        { data: 'name', name: 'name' },
        { data: 'actions', name: 'actions', orderable:false, searchable:false },
      ],

    });

    // Delete button handler
    tableRole.on('click', '.btn-delete-role', function(e){
      var id = $(this).attr('data-id');
      var name = $(this).attr('data-text');
      $('#role_id').val(id);
      $('#role-name-to-delete').text(name);
      $('#modal-delete-role').modal('show');
    });

      // Setup - add a text input to each header cell
      $('#searchid th').each(function() {
            if ($(this).index() != 0 && $(this).index() != 2) {
                $(this).html('<input class="form-control" type="text" placeholder="Search" data-id="' + $(this).index() + '" />');
            }

      });
      //Block search input and select
      $('#searchid input').keyup(function() {
        tableRole.columns($(this).data('id')).search(this.value).draw();
      });
      //ENDBlock search input and select
  </script>
@endsection
