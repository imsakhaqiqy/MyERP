@extends('layouts.app')

@section('page_title')
  Purchase Order Invoice
@endsection

@section('page_header')
  <h1>
    Purchase Order Invoice
    <small>Detail Purchase Order Invoice</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('purchase-order') }}"><i class="fa fa-cart-arrow-down"></i> Purchase Order </a></li>
    <li><a href="{{ URL::to('purchase-order/'.$purchase_order_invoice->purchase_order->id) }}"> {{ $purchase_order_invoice->purchase_order->code }} </a></li>
    <li><a href="{{ URL::to('purchase-order-invoice') }}">Invoice</a></li>
    <li class="active">{{ $purchase_order_invoice->code }}</li>
  </ol>
@endsection

@section('content')

  <!-- Row Invoice-->
  <div class="row">
    <div class="col-lg-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">{{ $purchase_order_invoice->code }}</h3>
          <div class="pull-right">
            <!--Show button create payment only when invoice status is NOT completed yet-->
            @if($purchase_order_invoice->status != "completed")
            <a href="{{ url('purchase-order-invoice/'.$purchase_order_invoice->id.'/payment/create') }}" class="btn btn-default btn-xs" title="Create payment for this invoice">
              <i class='fa fa-money'></i>&nbsp;Input Payment
            </a>
            @endif
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="table-responsive" style="max-height:500px">
            <table class="table table-bordered" id="table-selected-products">
                <thead>
                    <tr style="">
                      <th style="width:15%;">Family</th>
                      <th style="width:20%;">Code</th>
                      <th style="width:20%;">Description</th>
                      <th style="width:10%;">Unit</th>
                      <th style="width:7%;">Qty</th>
                      <th style="width:15%;">Category</th>
                      <th style="width:13%;">Price</th>
                    </tr>
                </thead>
              <tbody>
                  @if(count($row_display))
                      @foreach($row_display as $row)
                        <?php $sum = 0; $sum_qty = 0;?>
                          <tr>
                            <td>
                                <strong>
                                    {{ $row['family'] }}
                                </strong>
                                <select name="inventory_account[]" id="inventory_account" class="col-md-12" style="display:none">
                                    @foreach(list_account_inventory('52') as $as)
                                        @if($as->name == 'PERSEDIAAN'.' '.$row['family'])
                                            <option value="{{ $as->id}}">{{ $as->account_number }}&nbsp;&nbsp;{{ $as->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
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
                            <td><strong class="target_qty">{{ $row['quantity'] }}</strong></td>
                            <td><strong>{{ $row['category'] }}</strong></td>
                            <td><strong class="target_sum"></strong></td>
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
                            <td>
                                {{ number_format($or['price']) }}
                                <?php $sum += $or['price']; ?>
                            </td>
                          </tr>
                          @endforeach
                          <tr style="display:none">
                            <td colspan="3" class="sum">{{ number_format($sum) }}</td>
                            <td colspan="3" class="sum_qty">{{ $sum_qty }}</td>
                          </tr>
                      @endforeach
                @else
                <tr id="tr-no-product-selected">
                  <td>There are no product</td>
                @endif
              </tbody>
            </table>
          </div>
          <br>
          <table class="table">
            <tr>
              <td style="width:30%;"><strong>Bill Price</strong></td>
              <td id="bill_price">{{ number_format($purchase_order_invoice->bill_price) }}</td>
            </tr>
            <tr>
              <td style="width:30%;"><strong>Paid Price</strong></td>
              <td id="paid_price">{{ number_format($purchase_order_invoice->paid_price) }}</td>
            </tr>
            <tr style="display:none">
              <td style="width:30%;"><strong>Accounts Payable</strong></td>
              <td id="paid_price">
                  <select name="inventory_account[]" id="inventory_account" class="col-md-4">
                  @foreach(list_account_hutang('56') as $as)
                      @if($as->name == 'HUTANG DAGANG IDR')
                          <option value="{{ $as->id}}">{{ $as->account_number }}&nbsp;&nbsp;{{ $as->name }}</option>
                      @endif
                  @endforeach
                 </select>
              </td>
            </tr>
            <tr>
              <td style="width:30%;"><strong>Status</strong></td>
              <td>
                {{ strtoupper($purchase_order_invoice->status) }}
                @if($purchase_order_invoice->status =='uncompleted')
                  <p></p>
                  <button id="btn-pay-invoice" class="btn btn-xs btn-primary" title="Click to pay this invoice" data-id="{{ $purchase_order_invoice->id}}" data-text="{{ $purchase_order_invoice->code }}">
                    <i class="fa fa-money"></i>&nbsp;Complete
                  </button>
                @endif
              </td>
            </tr>
            <tr>
              <td style="width:30%;"><strong>Due Date</strong></td>
              <td>{{ $purchase_order_invoice->term }}</td>
            </tr>
            <tr>
              <td style="width:30%;"><strong>Notes</strong></td>
              <td>{{ $purchase_order_invoice->notes }}</td>
            </tr>
          </table>



        </div><!-- /.box-body -->
        <div class="box-footer clearfix">

        </div>

      </div><!-- /.box -->
    </div>
  </div>
  <!-- ENDRow Invoice-->


  <!--Modal pay purchase-order-invoice-->
  <div class="modal fade" id="modal-pay-purchase-order-invoice" tabindex="-1" role="dialog" aria-labelledby="modal-pay-purchase-order-invoiceLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      {!! Form::open(['url'=>'completePurchaseInvoice', 'method'=>'post']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-pay-purchase-order-invoiceLabel">Confirmation</h4>
        </div>
        <div class="modal-body">
          <b id="purchase-order-invoice-name-to-pay"></b> is going to be completed
          <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
          </p>
          <input type="hidden" id="purchase_order_invoice_id" name="purchase_order_invoice_id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Ok</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal pay purchase-order-invoice-->

<!--Modal Complete invoice-->
  <div class="modal fade" id="modal-select-account" tabindex="-1" role="dialog" aria-labelledby="modal-select-accountLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      {!! Form::open(['url'=>'completePurchaseAccount', 'method'=>'post']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-select-accountLabel">Confirmation</h4>
        </div>
        <div class="modal-body">
            <b id="select-account-name-to-send"></b> is going to be selected
            <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
          </p>
          <input type="text" id="select_account_id" name="select_account_id">
          <input type="text" id="amount_hutang" name="amount_hutang">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Ok</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal Complete invoice-->

@endsection


@section('additional_scripts')

  <script type="text/javascript">
    // Delete button handler
    $('#btn-pay-invoice').on('click', function (e) {
      var id = $(this).attr('data-id');
      var code = $(this).attr('data-text');
      $('#purchase_order_invoice_id').val(id);
      $('#purchase-order-invoice-name-to-pay').text(code);
      $('#modal-pay-purchase-order-invoice').modal('show');
    });

    $('#btn-select-account').on('click',function(){
        //$('#select-account-name-to-send').text($('#select_account').text());
        $('#select_account_id').val($('#select_account').val());
        $('#amount_hutang').val($('#bill_price').text().replace(/,/gi,'')-$('#paid_price').text().replace(/,/gi,''));
        $('#modal-select-account').modal('show');
    });
  </script>
  <script type="text/javascript">
      var sum = document.getElementsByClassName('sum');
      for(var a = 0; a < sum.length; a++){
        document.getElementsByClassName('target_sum')[a].innerHTML = document.getElementsByClassName('sum')[a].innerHTML;
        document.getElementsByClassName('target_qty')[a].innerHTML = document.getElementsByClassName('sum_qty')[a].innerHTML;
      }
  </script>
@endSection
