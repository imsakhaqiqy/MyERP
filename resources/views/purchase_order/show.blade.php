@extends('layouts.app')

@section('page_title')
  Purchase Order Detail
@endsection

@section('page_header')
  <h1>
    Purchase Order
    <small>Purchase Order Detail</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('purchase-order') }}"><i class="fa fa-cart-arrow-down"></i> Purchase Order</a></li>
    <li class="active">{{ $purchase_order->code }}</li>
  </ol>
@endsection

@section('content')
  <ul class="nav nav-tabs" role="tablist">
    <li class="active" role="presentation" >
      <a data-toggle="tab" aria-controls="section-general-information" href="#section-general-information" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none"><i class="fa fa-desktop"></i>&nbsp;General Information</a>
    </li>
    <li role="presentation">
      <a data-toggle="tab"aria-controls="section-invoice" href="#section-invoice" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none"><i class="fa fa-bookmark"></i>&nbsp;Invoice</a>
    </li>
    <li>
      <a data-toggle="tab" href="#section-invoice-payment" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none"><i class="fa fa-bookmark-o"></i>&nbsp;Invoice Payment</a>
    </li>
    <li>
      <a data-toggle="tab" href="#section-return" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none"><i class="fa fa-reply"></i>&nbsp;Return</a>
    </li>
  </ul>
   <div class="tab-content">
    <!--General Information-->
    <div id="section-general-information" class="tab-pane fade in active" role="tabpane">
      <!-- Row Products-->
      <br>
      <div class="row">
        <div class="col-lg-12">
          <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
            <div class="box-header with-border">
              <h3 class="box-title">General Information</h3>
              <div class="pull-right">
                <a href="{{ url('purchase-order/'.$purchase_order->id.'/printPdf') }}" class="btn btn-default btn-xs">
                  <i class='fa fa-print'></i>&nbsp;Print
                </a>
              </div>

            </div><!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                  <div class="col-md-2"><strong>Code</strong></div>
                  <div class="col-md-1">:</div>
                  <div class="col-md-3">
                    {{ $purchase_order->code }}
                  </div>
                  <div class="col-md-2"><strong>Supplier Name</strong></div>
                  <div class="col-md-1">:</div>
                  <div class="col-md-3">
                    {{ $purchase_order->supplier->name }}
                  </div>
              </div><br/>
              <div class="row">
                <div class="col-md-2"><strong>Notes</strong></div>
                <div class="col-md-1">:</div>
                <div class="col-md-3">
                  {!! nl2br($purchase_order->notes) !!}
                </div>
              </div><br/>
              <div class="row">
                <div class="col-md-2"><strong>Created At</strong></div>
                <div class="col-md-1">:</div>
                <div class="col-md-3">
                  {{ $purchase_order->created_at }}
                </div>
                <div class="col-md-2"><strong>Status</strong></div>
                <div class="col-md-1">:</div>
                <div class="col-md-3">
                  {{ strtoupper($purchase_order->status) }}
                  @if($purchase_order->status == 'posted')
                    <button id="btn-accept" class="btn btn-xs btn-warning" data-id="{{ $purchase_order->id }}" data-text="{{ $purchase_order->code }}" title="Click to accept this purchase order">
                      <i class="fa fa-sign-in"></i>&nbsp;Accept
                    </button>
                    <br/>
                    <br/>
                    <div class="alert alert-info">
                      <p>
                        <i class="fa fa-info-circle"></i>&nbsp;
                        Invoices can be made if this status "Accept".
                      </p>
                    </div>
                  @endif
                  @if($purchase_order->status == 'accepted')
                    <button id="btn-complete" class="btn btn-xs btn-default" style="background-color:#4CAF50;color:white" data-id="{{ $purchase_order->id }}" data-text="{{ $purchase_order->code }}" title="Click to complete this purchase order">
                      <i class="fa fa-sign-in"></i>&nbsp;Complete
                    </button>
                    <br/>
                    <br/>
                    <div class="alert alert-default" style="background-color:#4CAF50;color:white">
                      <p>
                        <i class="fa fa-info-circle"></i>&nbsp;
                        Invoices can already be made.
                      </p>
                    </div>
                  @endif
                </div>
              </div><br/>

            </div><!-- /.box-body -->

          </div><!-- /.box -->
        </div>
      </div>
      <!-- ENDRow Products-->
      <div class="row">
          <div class="col-lg-12">
              <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                  <div class="box-header with-border">
                      <h3 class="box-title">Product List</h3>
                  </div>
                  <div class="box-body">
                      <div class="table-responsive" style="max-height:500px">
                        <table class="table table-bordered" id="table-selected-products">
                          <thead>
                            <tr style="">
                                <th style="width:15%;">Family</th>
                                <th style="width:15%;">Code</th>
                                <th style="width:20%;">Description</th>
                                <th style="width:15%;">Unit</th>
                                <th style="width:15%;">Quantity</th>
                                <th style="width:20%;">Category</th>
                            </tr>
                          </thead>
                          <tbody>
                              @if(count($row_display))
                                  @foreach($row_display as $row)
                                  <?php $sum_qty = 0; ?>
                                      <tr>
                                        <td><strong>{{ $row['family'] }}</strong></td>
                                        <td>
                                            <strong>
                                                {{ $row['main_product'] }}
                                            </strong>
                                            @if($row['image'] != NULL)
                                            <a href="#" class="thumbnail">
                                                {!! Html::image('img/products/thumb_'.$row['image'].'', $row['image']) !!}
                                            </a>
                                            @else
                                            <a href="#" class="thumbnail">
                                                {!! Html::image('files/default/noimageavailable.jpeg', 'No Image') !!}
                                            </a>
                                            @endif
                                        </td>
                                        <td><strong>{{ $row['description'] }}</strong></td>
                                        <td><strong>{{ $row['unit'] }}</strong></td>
                                        <td><strong class="target_qty"></strong></td>
                                        <td><strong>{{ $row['category'] }}</strong></td>
                                      </tr>
                                      @foreach($row['ordered_products'] as $or)
                                      <tr>
                                        <td>{{ $or['family'] }}</td>
                                        <td>{{ $or['code'] }} </td>
                                        <td>{{ $or['description'] }} </td>
                                        <td>{{ $or['unit'] }} </td>
                                        <td>
                                            {{ $or['quantity'] }}
                                            <?php $sum_qty += $or['quantity']; ?>
                                        </td>
                                        <td>{{ $or['category'] }}</td>
                                      </tr>
                                      @endforeach
                                      <tr style="display:none">
                                        <td colspan="6" class="sum_qty">{{ $sum_qty }}</td>
                                      </tr>
                                  @endforeach
                            @else
                            <tr id="tr-no-product-selected">
                              <td colspan="6">There are no product</td>
                           </tr>
                            @endif
                          </tbody>
                          <tfoot>

                          </tfoot>
                        </table>
                      </div>
                  </div>
                  <div class="box-footer clearfix">

                  </div>
              </div>
          </div>
      </div>
    </div>

    <!--Section Invoice-->
    <div id="section-invoice" class="tab-pane fade">
      <br>
      <!-- Row Invoice-->
      <div class="row">
        <div class="col-lg-12">
          <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
            <div class="box-header with-border">
              <h3 class="box-title">Invoice <small>Invoice that related with this purchase order</small></h3>
              <div class="pull-right">
                @if($purchase_order->status == 'posted' || $purchase_order->status =='completed')

                @endif
                @if($purchase_order->status=='accepted' && count($purchase_order->purchase_order_invoice) == 0)
                  <a href="{{ URL::to('purchase-order-invoice/'.$purchase_order->id.'/create')}}" class="btn btn-default btn-xs">
                    <i class='fa fa-bookmark'></i>&nbsp;Input Invoice
                  </a>
                @endif
              </div>

            </div><!-- /.box-header -->
            <div class="box-body">
              @if($invoice->count() > 0)

                <div class="table-responsive">

                  <table class="table">
                    <tr>
                      <td><strong>Invoice Code</strong></td>
                      <td>:</td>
                      <td>
                        <a href="{{url('purchase-order-invoice/'.$purchase_order->purchase_order_invoice->id.'')}}" title="Click to view the detail of the invoice">
                          {{ $purchase_order->purchase_order_invoice->code }}
                        </a>
                      </td>
                    </tr>
                    <tr>

                    </tr>
                    <tr>
                      <td><strong>Bill Price</strong></td>
                      <td>:</td>
                      <td>{{ number_format($purchase_order->purchase_order_invoice->bill_price) }}</td>
                    </tr>
                    <tr>
                      <td><strong>Paid Price</strong></td>
                      <td>:</td>
                      <td>{{ number_format($purchase_order->purchase_order_invoice->paid_price) }}</td>
                    </tr>
                    <tr>
                      <td><strong>Created Date</strong></td>
                      <td>:</td>
                      <td>{{ $purchase_order->purchase_order_invoice->created_at }}</td>
                    </tr>
                    <tr>
                      <td><strong>Due Date</strong></td>
                      <td>:</td>
                      <td>{{ $purchase_order->purchase_order_invoice->term }}</td>
                    </tr>
                    <tr>
                      <td><strong>Status</strong></td>
                      <td>:</td>
                      <td>{{ ucwords($purchase_order->purchase_order_invoice->status) }}</td>
                    </tr>
                    <tr>
                      <td><strong>Notes</strong></td>
                      <td>:</td>
                      <td>{{ $purchase_order->purchase_order_invoice->notes }}</td>
                    </tr>
                  </table>
                </div>
              @else
                <div class="alert alert-info">
                  <p>
                    <i class="fa fa-info-circle"></i>&nbsp;
                    There is no invoice related with this purchase order, you can make it by click the button "Input Invoice".
                  </p>
                </div>
              @endif
            </div><!-- /.box-body -->
            <div class="box-footer clearfix">

            </div>
          </div><!-- /.box -->
        </div>
      </div>
      <!-- ENDRow Invoice-->
    </div>

    <!-- Section Invoice Payment -->
    <div id="section-invoice-payment" class="tab-pane fade">
      <br>
      <div class="row">
        <div class="col-lg-12">
          <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
            <div class="box-header with-border">
              <h3 class="box-title">Invoice Payments <small>Related payment with the purchase order invoice</small></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                  <table class="table table-bordered" id="table-purchase-invoice-payment-transfer">
                    <thead>
                      <tr style="">
                        <th>No</th>
                        <th>Payment Date</th>
                        <th>Payment Method</th>
                        <th>Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                        @if(count($purchase_order->purchase_order_invoice) > 0)
                        @if($purchase_order->purchase_order_invoice->purchase_invoice_payment->count())
                          <?php  $payment_row = 0; ?>
                          @foreach($purchase_order->purchase_order_invoice->purchase_invoice_payment as $payment)
                          <tr>
                            <td>{{ $payment_row +=1 }}</td>
                            <td>{{ $payment->created_at }}</td>
                            <td>{{ $payment->payment_method->name }}</td>
                            <td>{{ number_format($payment->amount) }}</td>
                          </tr>
                          @endforeach
                        @endif
                        @else
                        <tr>
                          <td colspan="4">
                            <p class="alert alert-info"><i class="fa fa-info-circle"></i>&nbsp;There is no related invoice payment to this purchase order</p>
                          </td>
                        </tr>
                        @endif

                    </tbody>
                  </table>
                </div>
            </div><!-- /.box-body -->
            <div class="box-footer clearfix">

            </div>
          </div><!-- /.box -->
        </div>
      </div>
    </div>
    <!-- ENDSection Invoice Payment -->

    <!--Section Return-->
    <div id="section-return" class="tab-pane fade">
      <br>
      <!-- Row Return-->
      <div class="row">
        <div class="col-lg-12">
          <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
            <div class="box-header with-border">
              <h3 class="box-title">Related Return</h3>
              @if($purchase_order->purchase_order_invoice == '')

              @else
              <div class="pull-right">
                <a href="{{ url('purchase-return/create/?purchase_order_id='.$purchase_order->id.'') }}" class="btn btn-default btn-xs">
                  <i class='fa fa-reply'></i>&nbsp;Create Return
                </a>
              </div>
              @endif
            </div><!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table table-bordered">
                  @if($purchase_returns->count() > 0)
                  <?php $no = 1; ?>
                  <thead>
                    <tr>
                      <th style="width:5%;">#</th>
                      <th style="width:25%;">Code</th>
                      <th style="width:25%;">Returned Quantity</th>
                      <th style="width:25%;">Notes</th>
                      <th style="width:20%;">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($purchase_returns as $purchase_return)
                    <tr>
                      <td> {{ $no++ }}</td>
                      <td> {{ $purchase_return->product->name }}</td>
                      <td> {{ $purchase_return->quantity }}</td>
                      <td> {{ $purchase_return->notes }}</td>
                      <td> {{ ucwords($purchase_return->status) }}</td>
                    </tr>
                    @endforeach
                  @else
                  <tr>
                    <td colspan="4">
                      <p class="alert alert-info"><i class="fa fa-info-circle"></i>&nbsp;Return can be made if an invoice has been created</p>
                    </td>
                  </tr>
                  @endif
                  </tbody>
                </table>
              </div>
            </div><!-- /.box-body -->
            <div class="box-footer clearfix">

            </div>
          </div><!-- /.box -->
        </div>
      </div>
      <!-- ENDRow Return-->
    </div>
  </div>

  <!--Modal Accept purchase-order-->
  <div class="modal fade" id="modal-accept-purchase-order" tabindex="-1" role="dialog" aria-labelledby="modal-accept-purchase-orderLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      {!! Form::open(['url'=>'acceptPurchaseOrder', 'method'=>'post']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-accept-purchase-orderLabel">Accept Purchase Order Confirmation</h4>
        </div>
        <div class="modal-body">
          <b id="purchase-order-name-to-accept"></b> status will be changed to Accepted
          <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
          </p>
          <input type="hidden" id="id_to_be_accepted" name="id_to_be_accepted">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Accept</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal Accept purchase-order-->

<!--Modal complete purchase-order-->
  <div class="modal fade" id="modal-complete-purchase-order" tabindex="-1" role="dialog" aria-labelledby="modal-complete-purchase-orderLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      {!! Form::open(['url'=>'completePurchaseOrder', 'method'=>'post']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-complete-purchase-orderLabel">Complete Purchase Order Confirmation</h4>
        </div>
        <div class="modal-body">
          <b id="purchase-order-name-to-complete"></b> will be changed to completed
          <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
          </p>
          <input type="hidden" id="id_to_be_completed" name="id_to_be_completed">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">complete</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal Complete purchase-order-->

@endsection


@section('additional_scripts')
<script type="text/javascript">
        // $('#table-selected-products').DataTable({
        //     "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        //     // scrollY:        '50vh',
        //     // scrollCollapse: true,
        //     // paging:         false
        // });

        // $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        // $($.fn.dataTable.tables(true)).DataTable()
        //  .scroller.adjust();
        // });
</script>

<script type="text/javascript">
  //Accept
  $('#btn-accept').on('click', function(event){
    event.preventDefault();
    var id = $(this).attr('data-id');
      var code = $(this).attr('data-text');
      $('#id_to_be_accepted').val(id);
      $('#purchase-order-name-to-accept').text(code);
      $('#modal-accept-purchase-order').modal('show');
  });

  //Complete
  $('#btn-complete').on('click', function(event){
    event.preventDefault();
    var id = $(this).attr('data-id');
      var code = $(this).attr('data-text');
      $('#id_to_be_completed').val(id);
      $('#purchase-order-name-to-complete').text(code);
      $('#modal-complete-purchase-order').modal('show');
  });

  //tooltip bank transfer
  $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip({
          //title:"<p></p>",
          html: true
      });
  });
</script>

<script type="text/javascript">
    var sum = document.getElementsByClassName('sum_qty');
    for(var a = 0; a < sum.length; a++){
        document.getElementsByClassName('target_qty')[a].innerHTML = document.getElementsByClassName('sum_qty')[a].innerHTML;
    }

</script>

@endSection
