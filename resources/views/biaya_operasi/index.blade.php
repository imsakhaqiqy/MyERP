@extends('layouts.app')

@section('page_title')
    Jurnal Umum
@endsection

@section('page_header')
    <h1>
        Jurnal Umum
        <small>Jurnal Umum List</small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('biaya-operasi') }}"><i class="fa fa-dashboard"></i> Jurnal Umum</a></li>
        <li class="active"><i></i> Index</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3 class="box-title">Jurnal Umum</h3>
                    <a href="{{ URL::to('biaya-operasi/create') }}" class="btn btn-primary pull-right" title="Create new jurnal umum">
                        <i class="fa fa-plus"></i>&nbsp;Add New
                    </a>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-striped table-hover" id="table-beban-operasi">
                        <thead>
                            <tr style="background-color:#3c8dbc;color:white">
                                <th style="width:5%;">#</th>
                                <th style="width:20%;">No.Transaction</th>
                                <th style="width:30%;">Memo</th>
                                <th style="width:15%;">Amount</th>
                                <th style="width:15%;">Created At</th>
                                <th style="width:15%;">Actions</th>
                            </tr>
                        </thead>
                        <thead id="searchid">
                            <tr>
                                <th></th>
                                <th>Account Number</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Created At</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix">

                </div>
            </div>
        </div>
    </div>

    <!--Modal Delete trans-->
    <div class="modal fade" id="modal-delete-trans-chart-account" tabindex="-1" role="dialog" aria-labelledby="modal-delete-transChartAccountLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
        {!! Form::open(['url'=>'deleteTransChartAccount', 'method'=>'post']) !!}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modal-delete-transChartAccountLabel">Confirmation</h4>
          </div>
          <div class="modal-body">
            You are going to remove transaction chart account&nbsp;<b id="trans-chart-account-name-to-delete"></b>
            <br/>
            <p class="text text-danger">
              <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
            </p>
            <input type="hidden" id="trans_id" name="trans_id">
            <input type="hidden" id="trans_memo" name="trans_memo">
            <input type="hidden" id="trans_chart_account_id" name="trans_chart_account_id">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete</button>
          </div>
        {!! Form::close() !!}
        </div>
      </div>
    </div>
  <!--ENDModal Delete bank-->
@endsection

@section('additional_scripts')
    <script type="text/javascript">
        var tableTrans = $('#table-beban-operasi').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('datatables.getTransactionChartAccounts') !!}',
            columns: [
                {data: 'rownum', name: 'rownum', searchable: false},
                {data: 'id', name: 'id'},
                {data: 'description', name: 'description'},
                {data: 'amount', name: 'amount'},
                {data: 'created_at', name: 'created_at'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'dt-center'},
            ],
        });

        // Delete button handler
        tableTrans.on('click', '.btn-delete-trans-chart-account', function (e) {
          var id = $(this).attr('data-id');
          var name = $(this).attr('data-text');
          var memo = $(this).attr('data-memo');
          var chartAccountid = $(this).attr('data-sub-account-id');
          $('#trans_id').val(id);
          $('#trans-chart-account-name-to-delete').text(name);
          $('#trans_memo').val(memo);
          $('#trans_chart_account_id').val(chartAccountid);
          $('#modal-delete-trans-chart-account').modal('show');
        });

        //setup - add
        $('#searchid th').each(function() {
              if ($(this).index() != 0 && $(this).index() != 5) {
                  $(this).html('<input class="form-control" type="text" placeholder="Search" data-id="' + $(this).index() + '" />');
              }

        });
        //Block search input and select
        $('#searchid input').keyup(function() {
          tableTrans.columns($(this).data('id')).search(this.value).draw();
        });
        //ENDBlock search input and select
    </script>
@endsection
