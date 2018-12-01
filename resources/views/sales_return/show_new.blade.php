@extends('layouts.app')

@section('page_title')
    Sales Order return
@endsection

@section('page_header')
    <h1>
        Sales order
        <small>Sales Return Detail</small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('sales-order') }}"><i class="fa fa-dashboard"></i> Sales Order</a></li>
        <li><a href="{{ URL::to('sales-order/'.$sales_return->sales_order->id) }}">{{ $sales_return->sales_order->code }}</a></li>
        <li><a href="{{ URL::to('sales-return') }}"><i class="fa fa-dashboard"></i>Return</a></li>
        <li><i></i>{{ $sales_return->id }}</li>
        <li class="active"><i></i>Detail</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3 class="box-title">Sales Return Detail</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                  <th style="width:10%;">Family</th>
                                  <th style="width:15%;">Code</th>
                                  <th style="width:15%;">Description</th>
                                  <th style="width:5%;">Unit</th>
                                  <th style="width:5%;">Qty</th>
                                  <th style="width:10%;">Category</th>
                                  <th style="width:10%;">Price/item</th>
                                  <th style="width:10%;">Price</th>
                                  <th style="width:10%;">Returned Qty</th>
                                  <th style="width:10%;">Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $sales_return->product->main_product->family->name }}</td>
                                    <td>{{ $sales_return->product->name }}</td>
                                    <td>{{ $sales_return->product->description }}</td>
                                    <td>{{ $sales_return->product->main_product->unit->name }}</td>
                                    <td class="salesed_qty">
                                        {{ \DB::table('product_sales_order')->select('quantity')->where('product_id',$sales_return->product_id)->where('sales_order_id',$sales_return->sales_order_id)->value('quantity') }}
                                    </td>
                                    <td>{{ $sales_return->product->main_product->category->name }}</td>
                                    <td class="salesed_price_per_item">
                                      {{ number_format(\DB::table('product_sales_order')->select('price_per_unit')->where('product_id',$sales_return->product_id)->where('sales_order_id',$sales_return->sales_order_id)->value('price_per_unit')) }}
                                      <?php
                                      $sum_inventory_cost_first = \DB::table('transaction_chart_accounts')
                                                                  ->join('sub_chart_accounts','transaction_chart_accounts.sub_chart_account_id','=','sub_chart_accounts.id')
                                                                  ->where('sub_chart_accounts.name','=','PERSEDIAAN '.$sales_return->product->main_product->family->name)
                                                                  ->where('transaction_chart_accounts.type','=','masuk')
                                                                  ->where('transaction_chart_accounts.description','=','SALDO AWAL')
                                                                  ->sum('transaction_chart_accounts.amount');
                                      $sum_price_purchase = \DB::table('product_purchase_order')
                                                            ->join('products','product_purchase_order.product_id','=','products.id')
                                                            ->join('main_products','products.main_product_id','=','main_products.id')
                                                            ->where('main_products.family_id','=',$sales_return->product->main_product->family->id)
                                                            ->sum('price');
                                      $sum_inventory_quantity_first = \DB::table('transaction_chart_accounts')
                                                                  ->join('sub_chart_accounts','transaction_chart_accounts.sub_chart_account_id','=','sub_chart_accounts.id')
                                                                  ->where('sub_chart_accounts.name','=','PERSEDIAAN '.$sales_return->product->main_product->family->name)
                                                                  ->where('transaction_chart_accounts.type','=','masuk')
                                                                  ->where('transaction_chart_accounts.description','=','SALDO AWAL')
                                                                  ->sum('transaction_chart_accounts.memo');
                                      $sum_qty_purchase = \DB::table('product_purchase_order')
                                                            ->join('products','product_purchase_order.product_id','=','products.id')
                                                            ->join('main_products','products.main_product_id','=','main_products.id')
                                                            ->where('main_products.family_id','=',$sales_return->product->main_product->family->id)
                                                            ->sum('quantity');
                                      ?>
                                    </td>
                                    <td>
                                      {{ number_format(\DB::table('product_sales_order')->select('price')->where('product_id',$sales_return->product_id)->where('sales_order_id',$sales_return->sales_order_id)->value('price')) }}
                                    </td>
                                    <td class="returned_qty">{{ $sales_return->quantity }}</td>
                                    <td>{{ $sales_return->notes }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div><!-- /.table-responsive -->
                    <br/>
                    <div class="row">
                      <div class="col-md-3"><strong>SO Reference</strong></div>
                      <div class="col-md-1">:</div>
                      <div class="col-md-3">
                        <p>{{ $sales_return->sales_order->code }}</p>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><strong>Status</strong></div>
                        <div class="col-md-1">:</div>
                        <div class="col-md-3">
                            <p>{{ strtoupper($sales_return->status) }}</p>
                            @if($sales_return->status == 'posted')
                                <button type="button" id="btn-accept-sales-return" class="btn btn-warning btn-xs" data-id="{{ $sales_return->id }}" title="Change status to Accept">
                                    <i class="fa fa-sign-in"></i>&nbsp;Accept
                                </button>
                            @endif
                            @if($sales_return->status == 'accept')
                                <button type="button" id="btn-resent-sales-return" class="btn btn-success btn-xs" data-id="{{ $sales_return->id }}" title="Change status to Resent">
                                    <i class="fa fa-check"></i>&nbsp;Resent
                                </button>
                            @endif
                        </div>
                    </div><!-- /.row -->
                    <br/>
                    <div class="row">
                      <div class="col-md-3"><strong>Customer Name</strong></div>
                      <div class="col-md-1">:</div>
                      <div class="col-md-3">
                        <p>{{ $sales_return->sales_order->customer->name }}</p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3"><strong>Created At</strong></div>
                      <div class="col-md-1">:</div>
                      <div class="col-md-3">
                        <p>{{ $sales_return->created_at }}</p>
                      </div>
                    </div>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">

                </div><!-- /.box-footer -->
            </div><!-- /.box -->
        </div><!-- /.col-lg-12 -->
    </div><!-- /.row -->

    <!-- Modal send sales-return -->
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
              <input type="hidden" id="id_to_be_accept" name="id_to_be_accept">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-danger" id="btn-accept-sales-return">accept</button>
            </div>
          {!! Form::close() !!}
          </div>
        </div>
    </div>
    <!--ENDModal Accept purchase-return-->

    <!--Modal Resent sales-return-->
      <div class="modal fade" id="modal-resent-sales-return" tabindex="-1" role="dialog" aria-labelledby="modal-resent-sales-returnLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
          {!! Form::open(['url'=>'resentSalesReturn', 'method'=>'post', 'id'=>'form-resent-sales-return']) !!}
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="modal-resent-sales-returnLabel">Resent Sales Return Confirmation</h4>
            </div>
            <div class="modal-body">
              This return status will be changed to Resent
              <br/>
              <p class="text text-danger">
                <i class="fa fa-info-circle"></i>&nbsp;The product will be re-added to the inventory
              </p>
              <input type="hidden" id="id_to_be_resent" name="id_to_be_resent">
              <input type="hidden" id="id_sales_return_price_per_unit" name="sales_return_price_per_unit_to_complete" value="{{ ($sum_inventory_cost_first+$sum_price_purchase)/($sum_inventory_quantity_first+$sum_qty_purchase) }}">
              <input type="hidden" id="id_sales_return_price_per_unit_sales" name="sales_return_price_per_unit_sales">
              <input type="hidden" id="id_sales_return_quantity" name="sales_return_quantity_to_complete">
              <input type="hidden" name="sales_order_invoice_id_to_complete" value="{{ $sales_return->sales_order->sales_order_invoice->id}}">
              <select name="inventory_account" id="inventory_account" class="col-md-12" style="display:none">
                @foreach(list_account_inventory('52') as $as)
                    @if($as->name == 'PERSEDIAAN'.' '.$sales_return->product->main_product->family->name)
                    <option value="{{ $as->id}}">{{ $as->account_number }}&nbsp;&nbsp;{{ $as->name}}</option>
                    @endif
                @endforeach
              </select>
              <select name="cost_goods_account" id="cost_goods_account" class="col-md-12" style="display:none">
                  @foreach(list_parent('63') as $cost_goods_account)
                    @if($cost_goods_account->name == 'HARGA POKOK PENJUALAN'.' '.$sales_return->product->main_product->family->name)
                    <option value="{{ $cost_goods_account->id}}">{{ $cost_goods_account->account_number }}&nbsp;&nbsp;{{ $cost_goods_account->name}}</option>
                    @endif
                  @endforeach
              </select>
              <select name="sales_return_account" id="sales_return_account" class="col-md-12" style="display:none">
                  @foreach(list_parent('61') as $sales_return)
                    @if($sales_return->name == 'RETURN PENJUALAN'.' '.$sales_return->product->main_product->family->name)
                    <option value="{{ $sales_return->id}}">{{ $sales_return->account_number }}&nbsp;&nbsp;{{ $sales_return->name}}</option>
                    @endif
                  @endforeach
              </select>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-danger" id="btn-resent-sales-return">Complete</button>
            </div>
          {!! Form::close() !!}
          </div>
        </div>
      </div>
    <!--ENDModal complete sales-return-->
@endsection

@section('additional_scripts')

<script type="text/javascript">
  //Handler send purchase return
    $('#btn-accept-sales-return').on('click', function (e) {
      var id = $(this).attr('data-id');
      $('#id_to_be_accept').val(id);
      $('#modal-accept-sales-return').modal('show');
    });
</script>

<script type="text/javascript">
  //Handler send purchase return
    $('#btn-resent-sales-return').on('click', function (e) {
      var id = $(this).attr('data-id');
      $('#id_to_be_resent').val(id);
      $('#id_sales_return_price_per_unit_sales').val($('.salesed_price_per_item').text().replace(/,/gi,''));
      $('#id_sales_return_quantity').val($('.returned_qty').text());
      $('#modal-resent-sales-return').modal('show');
    });
</script>

@endsection
