@extends('layouts.app')

@section('page_title')
  User Detail
@endsection

@section('page_header')
  <h1>
    User
    <small>User Detail</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('user') }}"><i class="fa fa-dashboard"></i> User </a></li>
    <li class="active"><i></i>User Detail</li>
  </ol>
@endsection

@section('content')

  <div class="row">
    <div class="col-md-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">User Detail</h3>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
          <table class="table">
            <tr>
              <td><b>Name</b></td>
              <td>{{ $user->name }}</td>
            </tr>
            <tr>
              <td><b>Email</b></td>
              <td>{{ $user->email }}</td>
            </tr>
            <tr>
              <td><b>Created At</b></td>
              <td>{{ $user->created_at }}</td>
            </tr>
          </table>
        </div><!-- /.box-body -->
        <div class="box-footer clearfix"></div>
      </div><!-- /.box -->
    </div>
</div>

@endsection

@section('additional_scripts')

@endsection
