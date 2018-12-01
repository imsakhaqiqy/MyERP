@extends('layouts.app')

@section('page_title')
    Chart Account
@endsection

@section('page_header')
    <h1>
        Chart Account
        <small>Chart Account List</small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('chart-account') }}"><i class="fa fa-dashboard"></i> Chart Account</a></li>
        <li class="active"><i></i>Index</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3 class="box-title">Chart Account</h3>
                    <a href="{{ URL::to('chart-account/create') }}" class="btn btn-primary pull-right" title="Create new chart account">
                        <i class="fa fa-plus"></i>&nbsp;Add new
                    </a>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table class="table table-striped table-hover" id="table-chart-account">
                        <thead>
                            <tr style="background-color:#3c8dbc;color:white">
                                <th style="width:5%;">#</th>
                                <th style="width:35%;">Name</th>
                                <th style="width:15%;">Account Number</th>
                                <th style="width:35%;">Description</th>
                                <th style="width:10%;text-align:center;">Actions</th>
                            </tr>
                        </thead>
                        <thead id="searchid">
                            <tr>
                                <th style="width:5%"></th>
                                <th style="width:35%">Name</th>
                                <th style="width:15%">Account Number</th>
                                <th style="width:35%">Description</th>
                                <th style="width:10%;text-align:center"></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">

                </div>
            </div>
        </div>
    </div>

    <!--Modal Delete chart_account-->
    <div class="modal fade" id="modal-delete-chart-account" tabindex="-1" role="dialog" aria-labelledby="modal-delete-chartAccountLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
        {!! Form::open(['url'=>'deleteChartAccount', 'method'=>'post']) !!}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modal-delete-chartAccountLabel">Confirmation</h4>
          </div>
          <div class="modal-body">
            You are going to remove chart account&nbsp;<b id="chart-account-name-to-delete"></b>
            <br/>
            <p class="text text-danger">
              <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
            </p>
            <input type="hidden" id="chart_account_id" name="chart_account_id">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete</button>
          </div>
        {!! Form::close() !!}
        </div>
      </div>
    </div>
  <!--ENDModal Delete chart account -->
@endsection

@section('additional_scripts')
    <script type="text/javascript">
        var tableChartAccount = $('#table-chart-account').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('datatables.getChartAccounts') !!}',
            columns: [
                {data: 'rownum', name: 'rownum', searchable: false},
                {data: 'name', name: 'name'},
                {data: 'account_number', name: 'account_number'},
                {data: 'description', name: 'description'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false},
            ],
            "order" : [[1, "asc"]]
        });

        //Delete button handler
        tableChartAccount.on('click', '.btn-delete-chart-account', function (e) {
          var id = $(this).attr('data-id');
          var name = $(this).attr('data-text');
          $('#chart_account_id').val(id);
          $('#chart-account-name-to-delete').text(name);
          $('#modal-delete-chart-account').modal('show');
        });

        // setup - add a text input to each header cell
        $('#searchid th').each(function(){
            if($(this).index() != 0 && $(this).index() != 4){
                $(this).html('<input class="form-control type="text" placeholder="Search" data-id="'+$(this).index()+'"/>');
            }
        });
    </script>
@endsection
