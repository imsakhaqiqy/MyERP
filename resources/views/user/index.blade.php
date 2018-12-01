@extends('layouts.app')

@section('page_title')
  User
@endsection

@section('page_header')
  <h1>
    User
    <small>User List</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="#"><i class="fa fa-dashboard"></i> User </a></li>
    <li class="active"><i></i>Index</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">User List</h3>
          <a href="{{ URL::to('user/create')}}" class="btn btn-primary pull-right" title="Add new user">
            <i class="fa fa-plus"></i>&nbsp;Add New
          </a>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
          <table class="table table-bordered" id="table-user">
            <thead>
              <tr>
                <th style="width:5%;background-color:#3c8dbc;color:white">#</th>
                <th style="width:30%;background-color:#3c8dbc;color:white">Name</th>
                <th style="width:20%;background-color:#3c8dbc;color:white">Role</th>
                <th style="width:30%;background-color:#3c8dbc;color:white">Email</th>
                <th style="width:15%;text-align:center;background-color:#3c8dbc;color:white">Actions</th>
              </tr>
            </thead>
            <thead id="searchid">
              <tr>
                <th style="width:5%;"></th>
                <th style="width:30%;">Name</th>
                <th style="width:20%;">Role</th>
                <th style="width:30%;">Email</th>
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
<!--Modal Delete user-->
  <div class="modal fade" id="modal-delete-user" tabindex="-1" role="dialog" aria-labelledby="modal-delete-userLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      {!! Form::open(['url'=>'deleteUser', 'method'=>'post']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-delete-userLabel">Delete User Confirmation</h4>
        </div>
        <div class="modal-body">
          You are going to remove user&nbsp;<b id="user-name-to-delete"></b>
          <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
          </p>
          <input type="hidden" id="user_id" name="user_id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal Delete user-->
@endsection

@section('additional_scripts')
  <script type="text/javascript">
    var tableUser =  $('#table-user').DataTable({
      processing :true,
      serverSide : true,
      ajax : '{!! route('datatables.getUsers') !!}',
      columns :[
        {data: 'rownum', name: 'rownum', searchable:false, orderable:false},
        { data: 'name', name: 'name' },
        { data: 'role', name: 'role' },
        { data: 'email', name: 'email' },
        { data: 'actions', name: 'actions', orderable:false, searchable:false },
      ],

    });

    // Delete button handler
    tableUser.on('click', '.btn-delete-user', function(e){
      var id = $(this).attr('data-id');
      var name = $(this).attr('data-text');
      $('#user_id').val(id);
      $('#user-name-to-delete').text(name);
      $('#modal-delete-user').modal('show');
    });

      // Setup - add a text input to each header cell
    $('#searchid th').each(function() {
      if ($(this).index() != 0 && $(this).index() != 4) {
          $(this).html('<input class="form-control" type="text" placeholder="Search" data-id="' + $(this).index() + '" />');
      }
    });
    //Block search input and select
    $('#searchid input').keyup(function() {
      tableUser.columns($(this).data('id')).search(this.value).draw();
    });
    $('#searchid select').change(function () {
      if($(this).val() == ""){
        tableUser.columns($(this).data('id')).search('').draw();
      }
      else{
        tableUser.columns($(this).data('id')).search(this.value).draw();
      }
    });
    //ENDBlock search input and select


    //Delete user process
    $('#form-delete-user').on('submit', function(){
      $('#btn-confirm-delete-user').prop('disabled', true);
    });

  </script>
@endsection
