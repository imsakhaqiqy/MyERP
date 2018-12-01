@extends('layouts.app')

@section('page_title')
    Sales Return
@endsection

@section('page_header')
    <h1>
        Sales Return
        <small>Sales Return List</small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('sales-return') }}"><i class="fa fa-dashboard"></i> Sales Return</a></li>
        <li class="active"><i></i>Index</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3 class="box-title">Sales Return</h3>
                </div><!-- /.box-header-->
                <div class="box-body table-responsive">
                    <table class="table table-striped table-hover" id="table-sales-return">
                        <thead>
                            <tr style="background-color:#3c8dbc;color:white">
                                <th style="width:5%;">#</th>
                                <th style="width:15%;">Code</th>
                                <th style="width:15%;">PO Code</th>
                                <th style="width:15%;">Returned Qty</th>
                                <th style="width:15%;">Created At</th>
                                <th style="width:10%;">Status</th>
                                <th style="width:15%;">Customer Name</th>
                                <th style="width:10%;text-align:center;">Actions</th>
                            </tr>
                        </thead>
                        <thead id="searchid">
                            <tr>
                                <th style="width:5%;"></th>
                                <th style="width:15%;">Code</th>
                                <th style="width:15%;">PO Code</th>
                                <th style="width:15%;">Returned Qty</th>
                                <th style="width:15%;">Created At</th>
                                <th style="width:10%;">Status</th>
                                <th style="width:15%;">Customer Name</th>
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
        </div><!-- /.col-lg-12 -->
    </div><!-- /.row -->

    <!-- Modal Delete sales return -->
    <div class="modal fade" id="modal-delete-sales-return" tabindex="-1" role="dialog" aria-labelledby="modal-delete-sales-returnLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {!! Form::open(['url'=>'deleteSalesReturn','method'=>'post']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modal-delete-sales-returnLabel">Delete Sales Return Confirmation</h4>
                </div><!-- /.modal-header -->
                <div class="modal-body">
                    You are going to remove sales-return&nbsp;<b id="sales-return-name-to-delete"></b>
                    <br/>
                    <p class="text text-danger">
                        <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
                    </p>
                    <input type="hidden" id="sales_return_id" name="sales_return_id">
                </div><!-- /.modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div><!-- /.modal-footer -->
                {!! Form::close() !!}
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modalfade -->
    <!-- END Delete sales-return -->

    <!--Modal Aceept sales-return-->
      <div class="modal fade" id="modal-accept-sales-return" tabindex="-1" role="dialog" aria-labelledby="modal-accept-sales-returnLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
          {!! Form::open(['url'=>'acceptSalesReturn', 'method'=>'post', 'id'=>'form-accept-sales-return']) !!}
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="modal-accept-sales-returnLabel">Accept Sales Return Confirmation</h4>
            </div>
            <div class="modal-body">
              This sales return status will be changed to "Accept".
              <br/>
              <p class="text text-danger">
                <i class="fa fa-info-circle"></i>&nbsp;The product will be returned to the supplier.
              </p>
              <input type="text" id="id_to_be_accept" name="id_to_be_accept">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-danger" id="btn-accept-sales-return">Accept</button>
            </div>
          {!! Form::close() !!}
          </div>
        </div>
      </div>
    <!--ENDModal Accept purchase-return-->

    <!-- Modal Resent sales-return -->
    <div class="modal fade" id="modal-resent-sales-return" tabindex="-1" role="dialog" aria-labelledby="modal-resent-sales-returnLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {!! Form::open(['url'=>'resentSalesReturn','method'=>'post','id'=>'form-resent-sales-return']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modal-resent-sales-returnLabel">Resent Sales Return Confirmation</h4>
                </div><!-- /.modal-header -->
                <div class="modal-body">
                    This return status will be changed to resent
                    <br/>
                    <p class="text text-danger">
                        <i class="fa fa-info-circle"></i>&nbsp;The product will be re-added to the inventory
                    </p>
                    <input type="text" id="id_to_be_resent" name="id_to_be_resent">
                </div><!-- /.modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" id="btn-resent-sales-return">Resent</button>
                </div>
                {!! Form::close() !!}
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal fade -->
@endsection

@section('additional_scripts')
    <script type="text/javascript">
        var tableSalesReturn = $('#table-sales-return').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('datatables.getSalesReturns') !!}',
            columns: [
                {data: 'rownum', name: 'rownum', searchable: false},
                {data: 'product_id', name: 'product.name'},
                {data: 'sales_order_id', name: 'sales_order.code'},
                {data: 'quantity', name: 'quantity'},
                {data: 'created_at', name: 'created_at'},
                {data: 'status', name: 'status'},
                {data: 'customer_name', name: 'customer_name', searchable: false},
                {data: 'actions', name: 'actions', orderable:false, searchable:false, className:'dt-center'},
            ],
        });

        //Delete button handler
        tableSalesReturn.on('click','.btn-delete-sales-return', function(e){
            var id = $(this).attr('data-id');
            var code = $(this).attr('data-text');
            $('#sales_return_id').val(id);
            $('#sales-return-name-to-delete').text(code);
            $('#modal-delete-sales-return').modal('show');
        });

        //Setup - add a text to each header cell
        $('#searchid th').each(function() {
            if($(this).index() != 0 && $(this).index() != 7 ){
                $(this).html('<input class="form-control" type="text" placeholder="Search" data-id="' + $(this).index() + '" />');
            }
        });

        //Block search input and select
        $('#searchid input').keyup(function(){
            tableSalesReturn.columns($(this).data('id')).search(this.value).draw();
        });

        //Handler accept sales return
        tableSalesReturn.on('click','.btn-accept-sales-return',function(e){
            var id = $(this).attr('data-id');
            $('#id_to_be_accept').val(id);
            $('#modal-accept-sales-return').modal('show');
        });

        $('#form-accept-sales-return').on('submit',function(){
            $('#btn-accept-sales-return').prop('disabled', true);
        });

        //Handler resent sales return
        tableSalesReturn.on('click','.btn-resent-sales-return', function(e){
            var id = $(this).attr('data-id');
            $('#id_to_be_resent').val(id);
            $('#modal-resent-sales-return').modal('show');
        });
    </script>
@endsection
