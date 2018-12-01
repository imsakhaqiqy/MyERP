@extends('layouts.app')

@section('page_title')
  Sales Order Detail
@endsection

@section('page_header')
  <h1>
    Sales Order
    <small>Sales Order Detail</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('sales-order') }}"><i class="fa fa-files-o"></i></i> Sales Order</a></li>
    <li class="active">{{ $sales_order->code }}</li>
  </ol>
@endsection

@section('content')

  <ul class="nav nav-tabs">
    <li class="active">
      <a data-toggle="tab" href="#section-general-information"><i class="fa fa-desktop"></i>&nbsp;General Information</a>
    </li>
    <li>
      <a data-toggle="tab" href="#section-invoice"><i class="fa fa-bookmark"></i>&nbsp;Invoice</a>
    </li>
    <li>
      <a data-toggle="tab" href="#section-invoice-payment"><i class="fa fa-bookmark-o"></i>&nbsp;Invoice Payments</a>
    </li>
    <li>
      <a data-toggle="tab" href="#section-return"><i class="fa fa-reply"></i>&nbsp;Return</a>
    </li>
  </ul>
  <div class="tab-content">
    <!--General Information-->
    <div id="section-general-information" class="tab-pane fade in active">
      <div class="row">
        <div class="col-lg-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">General Information</h3>
              <div class="pull-right">
                <a href="{{ url('sales-order/'.$sales_order->id.'/printDO') }}" class="btn btn-default btn-xs">
                    <i class="fa fa-file"></i>&nbsp;Delivery Order
                </a>
                <a href="{{ url('sales-order/'.$sales_order->id.'/printPdf') }}" class="btn btn-default btn-xs">
                  <i class='fa fa-print'></i>&nbsp;Print
                </a>
              </div>

            </div><!-- /.box-header -->
            <div class="box-body">

              <div class="row">
                  <div class="col-md-2"><strong>Code</strong></div>
                  <div class="col-md-6"><strong>{{ $sales_order->code }}</strong></div>
              </div>
              <br/>

              <div class="table-responsive">
                  <table class="table table-bordered" id="table-selected-products">
                  <thead>
                    <tr>
                        <th style="width:15%;background-color:#3c8dbc;color:white">Family</th>
                        <th style="width:15%;background-color:#3c8dbc;color:white">Code</th>
                        <th style="width:20%;background-color:#3c8dbc;color:white">Description</th>
                        <th style="width:15%;background-color:#3c8dbc;color:white">Unit</th>
                        <th style="width:15%;background-color:#3c8dbc;color:white">Quantity</th>
                        <th style="width:20%;background-color:#3c8dbc;color:white">Category</th>
                    </tr>
                  </thead>
                  <tbody>
                      @if(count($row_display))
                          @foreach($row_display as $row)
                          <?php $sum = 0;?>
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
                                <td><strong class="target_sum"></strong></td>
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
                                    <?php $sum += $or['quantity']; ?>
                                </td>
                                <td>{{ $or['category'] }}</td>
                              </tr>
                              @endforeach
                              <tr style="display:none">
                                <td colspan="6" class="sum">{{ $sum }}</td>
                              </tr>
                          @endforeach
                    @else
                    <tr id="tr-no-product-selected">
                      <td>There are no product</td>
                    @endif
                  </tbody>
                  <tfoot>

                  </tfoot>
                </table>
              </div>

              <div class="row">
                <div class="col-md-3">Customer Name</div>
                <div class="col-md-1">:</div>
                <div class="col-md-8">
                  {{ $sales_order->customer->name }}
                </div>
              </div>
              <br/>
              <div class="row">
                <div class="col-md-3">Created At</div>
                <div class="col-md-1">:</div>
                <div class="col-md-8">
                  {{ $sales_order->created_at }}
                </div>
              </div>
              <br/>
              <div class="row">
                <div class="col-md-3">Status</div>
                <div class="col-md-1">:</div>
                <div class="col-md-3">
                  {{ strtoupper($sales_order->status) }}
                </div>
                <div class="col-md-5">
                  {!! Form::open(['url'=>'sales-order/updateStatus','role'=>'form','class'=>'form-inline','id'=>'form-update-sales-order-status', 'method'=>'POST']) !!}
                    <select name="status" id="status">
                      <option value="posted" <?php echo $sales_order->status == 'posted' ? 'selected':'' ;?>>Posted</option>
                      <option value="processing" <?php echo $sales_order->status == 'processing' ? 'selected':'' ;?>>Processing</option>
                      <option value="delivering" <?php echo $sales_order->status == 'delivering' ? 'selected':'' ;?>>Delivering</option>
                      <option value="cancelled" <?php echo $sales_order->status == 'cancelled' ? 'selected':'' ;?>>Cancelled</option>
                      <option value="completed" <?php echo $sales_order->status == 'completed' ? 'selected':'' ;?>>Completed</option>
                    </select>
                    <input type="hidden" name="sales_order_id" value="{{ $sales_order->id}}" />
                    <button type="submit" class="btn btn-info btn-xs">Update Status</button>
                  {!! Form::close() !!}
                  <br/>
                  <div class="alert alert-info">
                    <p>
                      <i class="fa fa-info-circle"></i>&nbsp;
                      Invoices can be made if this status "Processing".
                    </p>
                  </div>
                </div>
              </div>
              <br/>
              <div class="row">
                <div class="col-md-3">Notes</div>
                <div class="col-md-1">:</div>
                <div class="col-md-8">
                  {!! nl2br($sales_order->notes) !!}
                </div>
              </div>
              <br/>
              <div class="row">
                <div class="col-md-3">Driver Name</div>
                <div class="col-md-1">:</div>
                <div class="col-md-8">
                  {!! nl2br($sales_order->driver->name) !!}
                </div>
              </div>
              <br/>
              <div class="row">
                <div class="col-md-3">Vehicle Number</div>
                <div class="col-md-1">:</div>
                <div class="col-md-8">
                  {!! nl2br($sales_order->vehicle->number_of_vehicle) !!}
                </div>
              </div>
            </div><!-- /.box-body -->

          </div><!-- /.box -->
        </div>
      </div>

    </div>
    <!-- ENDSection General Information-->

    <!-- Section Invoice -->
    <div id="section-invoice" class="tab-pane fade">
      <div class="row">
        <div class="col-lg-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Invoice <small>Invoice that related with this sales order</small></h3>
              <div class="pull-right">
                @if($sales_order->status == 'processing')
                @if($invoice->count() < 1)
                <a href="{{ URL::to('sales-order-invoice/'.$sales_order->id.'/create')}}" class="btn btn-default btn-xs">
                    <i class='fa fa-bookmark'></i>&nbsp;Create Invoice
                </a>
                @endif
                @endif
              </div>

            </div><!-- /.box-header -->
            <div class="box-body">
              @if($invoice->count() > 0)
                <div class="table-responsive">
                  <table class="table">
                    <tr>
                      <td>Invoice Code</td>
                      <td>:</td>
                      <td>
                        <a href="{{ url('sales-order-invoice/'.$sales_order->sales_order_invoice->id.'') }}" title="Click to see the detail">
                          {{ $sales_order->sales_order_invoice->code }}
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>Bill Price</td>
                      <td>:</td>
                      <td>{{ number_format($sales_order->sales_order_invoice->bill_price) }}</td>
                    </tr>
                    <tr>
                      <td>Paid Price</td>
                      <td>:</td>
                      <td>{{ number_format($sales_order->sales_order_invoice->paid_price) }}</td>
                    </tr>
                    <tr>
                      <td>Created Date</td>
                      <td>:</td>
                      <td>{{ $sales_order->sales_order_invoice->created_at }}</td>
                    </tr>
                    <tr>
                      <td>Due Date</td>
                      <td>:</td>
                      <td>{{ $sales_order->sales_order_invoice->due_date }}</td>
                    </tr>
                    <tr>
                      <td>Status</td>
                      <td>:</td>
                      <td>
                        {{ strtoupper($sales_order->sales_order_invoice->status) }}
                      </td>
                    </tr>
                  </table>
                </div>
              @else
                <div class="alert alert-info">
                  <p>
                    <i class="fa fa-info-circle"></i>&nbsp;
                    There is no invoice related with this sales order, you can make it by click the button "Input Invoice".
                  </p>
                </div>
              @endif
            </div><!-- /.box-body -->
            <div class="box-footer clearfix">

            </div>
          </div><!-- /.box -->
        </div>
      </div>
    </div>
    <!-- ENDSection Invoice -->

    <!-- Section Invoice Payment -->
    <div id="section-invoice-payment" class="tab-pane fade">
      <div class="row">
        <div class="col-lg-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Invoice Payments <small>Related payment with the sales order invoice</small></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table" id="table-sales-invoice-payment">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Payment Date</th>
                      <th>Payment Method</th>
                      <th>Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(count($sales_order->sales_order_invoice))
                      @if($sales_order->sales_order_invoice->sales_invoice_payment->count())
                        <?php  $payment_row = 0; ?>
                        @foreach($sales_order->sales_order_invoice->sales_invoice_payment as $payment)
                        <tr>
                          <td>{{ $payment_row +=1 }}</td>
                          <td>{{ $payment->created_at }}</td>
                          <td>{{ $payment->payment_method->name }}</td>
                          <td>{{ number_format($payment->amount) }}</td>
                        </tr>
                        @endforeach
                      @endif
                    @endif
                  </tbody>
                </table>
              </div>
            </div><!-- /.box-body -->
            <div class="box-footer clearfix"></div>
          </div><!-- /.box -->
        </div>
      </div>
    </div>
    <!-- ENDSection Invoice Payment -->

    <!-- Section Return -->
    <div id="section-return" class="tab-pane fade">
      <div class="row">
        <div class="col-lg-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"> Sales Order Return <small>Related return with this sales order</small></h3>
              <div class="pull-right">
                <a href="{{ URL::to('sales-return/create/?sales_order_id='.$sales_order->id.'') }}" class="btn btn-default btn-xs">
                    <i class='fa fa-bookmark'></i>&nbsp;Create Return
                </a>
              </div>

            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">

                  <table class="table">
                    <thead>
                      <tr>
                        <th>Product</th>
                        <th>Returned Quantity</th>
                        <th>Notes</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                    @if($sales_returns->count() > 0)

                      @foreach($sales_returns as $sales_return)
                      <tr>
                        <td> {{ $sales_return->product->name }}</td>
                        <td> {{ $sales_return->quantity }}</td>
                        <td> {{ $sales_return->notes }}</td>
                        <td> {{ ucwords($sales_return->status) }}</td>
                      </tr>
                      @endforeach
                    @else
                    <tr>
                      <td colspan="4">
                        <p class="alert alert-info"><i class="fa fa-info-circle"></i>&nbsp;There is no related return to this purchase order</p>
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
    <!-- ENDSection Return -->
  </div>
@endsection

@section('additional_scripts')
    <script type="text/javascript">
        var sum = document.getElementsByClassName('sum');
        for(var a = 0; a < sum.length; a++){
            document.getElementsByClassName('target_sum')[a].innerHTML = document.getElementsByClassName('sum')[a].innerHTML;
        }

    </script>
@endsection
