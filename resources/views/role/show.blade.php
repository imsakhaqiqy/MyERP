@extends('layouts.app')

@section('page_title')
  Role
@endsection

@section('page_header')
  <h1>
    Role
    <small>Role Detail</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('role') }}"><i class="fa fa-dashboard"></i> Role </a></li>
    <li class="active"><i></i>{{ $role->name }}</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        {!! Form::open(['url'=>'update-role-permission','role'=>'form','class'=>'form-horizontal','id'=>'form-update-role-permission', 'method'=>'POST']) !!}
        <div class="box-header with-border">
          <h3 class="box-title">{{ $role->name }}</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="table-responsive" style="max-height:500px">
            <table class="table table-striped table-hover" id="table-role-permission">
              <thead>
                <tr style="background-color:#3c8dbc;color:white">
                  <th style="width:15%;text-align:center;">
                    <button id="btn-check-uncheck-all" type="button" data-state="1"><text id="btn-check-uncheck-actor" style="color:black">Check ALL</text></button>
                  </th>
                  <th style="width:25%;">Permission Slug</th>
                  <th style="">Description</th>
                </tr>
              </thead>

              <tbody>

                @foreach($permissions as $permission)
                <tr>
                  <td style="text-align:center">
                    @if($role->permissions->contains('slug', $permission->slug))
                      <input type="checkbox" name="permission_id[]" class="permission_id" value="{{ $permission->id }}" checked>
                    @else
                      <input type="checkbox" name="permission_id[]" class="permission_id" value="{{ $permission->id }}">
                    @endif
                  </td>
                  <td>{{ $permission->slug }}</td>
                  <td>{{ $permission->description }}</td>

                </tr>

                @endforeach
              </tbody>
              <tfoot>

              </tfoot>
            </table>
          </div>
          <input type="hidden" name="role_id" value="{{ $role->id }}" />
          <button type="submit" class="btn btn-info pull-right">Save</button>
        </div><!-- /.box-body -->
        <div class="box-footer clearfix"></div>
      </div><!-- /.box -->
      {!! Form::close() !!}
    </div>
</div>

@endsection

@section('additional_scripts')
  <script type="text/javascript">
    $('#btn-check-uncheck-all').on('click', function(event){
      event.preventDefault();
      if($(this).attr('data-state') == "1"){
        $('.permission_id').prop('checked', true);
        $('#btn-check-uncheck-all').attr('data-state', '2');
        $('#btn-check-uncheck-actor').html("Uncheck All");
      }
      else{
        $('.permission_id').prop('checked', false);
        $('#btn-check-uncheck-all').attr('data-state', '1');
        $('#btn-check-uncheck-actor').html("Check All");
      }
    });


 </script>
@endsection
