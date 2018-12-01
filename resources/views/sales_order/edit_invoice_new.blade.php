@extends('layouts.app')

@section('page_title')
    Sales Order Invoice
@endsection

@section('page_header')
    <h1>
        Sales Order Invoice
        <small>Edit Sales Order Invoice</small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('sales-order') }}"><i class="fa fa-dashboar"></i> Sales Order</a></li>
        <li><a href="{{ URL::to('sales-order/'.$sales_order_invoice->sales_order->id) }}"><i class="fa fa-dashboard"></i> {{ $sales_order_invoice->sales_order->code }}</a></li>
        <li>Invoice</li>
        <li><a href="{{ URL::to('sales-order-invoice/'.$sales_order_invoice->id.'') }}"><i class="fa fa-dashboard"></i> {{ $sales_order_invoice->code }} </a></li>
        <li class="active">Edit</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $sales_order_invoice->code }}</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    {!! Form::model($sales_order_invoice,['route'=>['sales-order-invoice.update',$sales_order_invoice->id],'id'=>'form-edit-sales-order-invoice','class'=>'form-horizontal','method'=>'put','files'=>true]) !!}
                        <div class="table-responsive" style="max-height:500px">
                            <table class="table table-striped table-hover" id="table-selected-products">
                                <thead>
                                    <tr style="background-color:#3c8dbc;color:white">
                                      <th style="width:10%;">Family</th>
                                      <th style="width:20%;">Name</th>
                                      <th style="width:20%;">Description</th>
                                      <th style="width:8%;">Unit</th>
                                      <th style="width:7%;">Qty</th>
                                      <th style="width:15%;">Category</th>
                                      <th style="width:10%;">Price Per Unit</th>
                                      <th style="width:10%;">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(count($row_display))
                                    @foreach($row_display as $row)
                                        <?php $sum_qty = 0; $sum = 0; ?>
                                        <tr style="display:none">
                                          <td>
                                              <strong>
                                                  {{ $row['family'] }}
                                              </strong>
                                              <input type="hidden" name="parent_product_id[]" value="{{ $row['main_product_id'] }} " />
                                              <input type="hidden" name="parent_sum_stock[]" value="{{ $row['sum_stock'] }}"/>
                                              <input type="text" name="parent_sum_inventory_cost[]" value="{{ ($row['sum_inventory_cost_first']+$row['sum_price_purchase'])/($row['sum_inventory_quantity_first']+$row['sum_qty_purchase']) }}"/>
                                              <input type="hidden" name="parent_sum_quantity[]" class="parent_sum_quantity"/>
                                              <select name="inventory_account[]" id="inventory_account" class="col-md-12" style="display:none">
                                                  @foreach(list_account_inventory('52') as $inventory_account)
                                                      @if($inventory_account->name == 'PERSEDIAAN'.' '.$row['family'])
                                                          <option value="{{ $inventory_account->id}}">{{ $inventory_account->account_number }}&nbsp;&nbsp;{{ $inventory_account->name }}</option>
                                                      @endif
                                                  @endforeach
                                              </select><br><br><br>
                                              <select name="sales_order_account[]" id="sales_account" class="col-md-12" style="display:none">
                                                  @foreach(list_parent('61') as $sales_account)
                                                      @if($sales_account->name == 'PENJUALAN'.' '.$row['family'])
                                                          <option value="{{ $sales_account->id}}">{{ $sales_account->account_number }}&nbsp;&nbsp;{{ $sales_account->name }}</option>
                                                      @endif
                                                  @endforeach
                                              </select><br><br>
                                              <select name="cost_goods_account[]" id="cost_goods_account" class="col-md-12" style="display:none">
                                                  @foreach(list_parent('63') as $cost_goods_account)
                                                      @if($cost_goods_account->name == 'HARGA POKOK PENJUALAN'.' '.$row['family'])
                                                          <option value="{{ $cost_goods_account->id}}">{{ $cost_goods_account->account_number }}&nbsp;&nbsp;{{ $cost_goods_account->name }}</option>
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
                                          <td><strong class="target_qty"></strong></td>
                                          <td><strong>{{ $row['category'] }}</strong></td>
                                          <td><strong></strong></td>
                                          <td>
                                              <input type="hidden" name="price_parent[]" class="price_parent">
                                          </td>
                                        </tr>
                                        @foreach($row['ordered_products'] as $or)
                                        <tr>
                                          <td>
                                            <input type="hidden" name="product_id[]" value="{{ $or['product_id'] }} " />
                                            <input type="hidden" name="main_product_id_child[]" value="{{ $row['main_product_id'] }} " />
                                            {{ $or['family'] }}
                                          </td>
                                          <td>{{ $or['code'] }} </td>
                                          <td>{{ $or['description'] }} </td>
                                          <td>{{ $or['unit'] }} </td>
                                          <td>
                                            <input type="hidden" name="quantity[]" value="{{ $or['quantity'] }}" class="quantity">
                                            {{ $or['quantity'] }}
                                            <?php $sum_qty += $or['quantity']; ?>
                                          </td>
                                          <td>{{ $or['category'] }}</td>
                                          <td>
                                            <input type="text" name="price_per_unit[]" class="price_per_unit form-control" value="{{ number_format($or['price_per_unit'])}}">
                                          </td>
                                          <td>
                                            <input type="text" name="price[]" class="price form-control" value="{{ number_format($or['price'])}}" readonly>
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
                              </tr>
                              @endif

                                </tbody>
                            </table>
                        </div>

                        <div class="form-group{{ $errors->has('bill_price') ? ' has-error' : '' }}">
                          {!! Form::label('bill_price', 'Bill Price', ['class'=>'col-sm-2 control-label']) !!}
                          <div class="col-sm-6">
                            {!! Form::text('bill_price',null,['class'=>'form-control', 'placeholder'=>'Bill price of the invoice', 'id'=>'bill_price', 'readonly']) !!}
                            @if ($errors->has('bill_price'))
                              <span class="help-block">
                                <strong>{{ $errors->first('bill_price') }}</strong>
                              </span>
                            @endif
                          </div>
                        </div>

                        <div class="form-group{{ $errors->has('select_account') ? ' has-error' : '' }}" style="display:none">
                          {!! Form::label('select_account', 'Accounts Receivable', ['class'=>'col-sm-2 control-label']) !!}
                          <div class="col-sm-6">
                              <select name="select_account" id="select_account" class="form-control">
                              @foreach(list_account_hutang('49') as $as)
                                  @if($as->name == 'PIUTANG DAGANG IDR')
                                  <option value="{{ $as->id }}">{{ $as->account_number }}&nbsp;&nbsp;{{ $as->name }}</option>
                                  @endif
                              @endforeach
                              </select>
                          </div>
                        </div>

                        <div class="form-group{{ $errors->has('ppn') ? ' has-error' : '' }}">
                          {!! Form::label('ppn', 'PPN', ['class'=>'col-sm-2 control-label']) !!}
                          <div class="col-sm-6">
                              {{ Form::select('persen_ppn', ['0'=>'0%','10'=>'10%','15'=>'15%','20'=>'20%'] ,null, ['class'=>'form-control', 'placeholder'=>'Select PPN', 'id'=>'unit_id']) }}
                            @if ($errors->has('ppn'))
                              <span class="help-block">
                                <strong>{{ $errors->first('ppn') }}</strong>
                              </span>
                            @endif
                          </div>
                        </div>

                        <div class="form-group{{ $errors->has('ppn_hidden') ? ' has-error' : '' }}">
                          {!! Form::label('ppn_hidden', 'Hidden PPN', ['class'=>'col-sm-2 control-label']) !!}
                          <div class="col-sm-6">
                            {{ Form::select('ppn_hidden', ['yes'=>'YES','no'=>'NO'], null, ['class'=>'form-control', 'placeholder'=>'Select Hidden PPN', 'id'=>'unit_id']) }}
                            @if ($errors->has('ppn_hidden'))
                              <span class="help-block">
                                <strong>{{ $errors->first('ppn_hidden') }}</strong>
                              </span>
                            @endif
                          </div>
                        </div>

                        <div class="form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
                          {!! Form::label('notes', 'Notes', ['class'=>'col-sm-2 control-label']) !!}
                          <div class="col-sm-6">
                            {!! Form::textarea('notes',null,['class'=>'form-control', 'placeholder'=>'Notes for the invoice', 'id'=>'notes']) !!}
                            @if ($errors->has('notes'))
                              <span class="help-block">
                                <strong>{{ $errors->first('notes') }}</strong>
                              </span>
                            @endif
                          </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('', '', ['class'=>'col-sm-2 control-label']) !!}
                          <div class="col-sm-6">
                            <a href="{{ url('sales-order-invoice') }}" class="btn btn-default">
                              <i class="fa fa-repeat"></i>&nbsp;Cancel
                            </a>&nbsp;
                            <button type="submit" class="btn btn-info" id="btn-submit-sales-order-invoice">
                              <i class="fa fa-save"></i>&nbsp;Submit
                            </button>
                          </div>
                        </div>
                        {!! Form::hidden('sales_order_invoice_id', $sales_order_invoice->id) !!}
                        {!! Form::hidden('sales_order_invoice_code', $sales_order_invoice->code) !!}
                        {!! Form::hidden('sales_order_id', $sales_order_invoice->sales_order->id) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection


@section('additional_scripts')
    {!! Html::script('js/autoNumeric.js') !!}
    <script type="text/javascript">
        $('.price_per_unit').autoNumeric('init',{
            aSep:',',
            aDec:'.'
        });
        $('.price').autoNumeric('init',{
            aSep:',',
            aDec:'.'
        });
        $('.price_parent').autoNumeric('init',{
            aSep:',',
            aDec:'.'
        });
        $('#bill_price').autoNumeric('init',{
            aSep:',',
            aDec:'.'
        });

        //block handle price value on price per unit keyup event
        $('.price_per_unit').on('keyup',function(){
            var quantity = $(this).parent().parent().find('.quantity').val();
            var the_price = 0;
            if($(this).val() == ''){
                the_price = 0;
            }else{
                the_price = parseFloat($(this).val().replace(/,/g,''))*quantity;
            }
            $(this).parent().parent().find('.price').val(the_price).autoNumeric('update',{
                aSep:',',
                aDec:'.'
            });
            fill_the_bill_price();
        });

        function fill_the_bill_price(){
            var sum = 0;
            $('.price').each(function(){
                sum += +$(this).val().replace(/,/g,'');
            });
            $('#bill_price').val(sum);
            $('#bill_price').autoNumeric('update',{
                aSep:',',
                aDec:'.'
            });
        }
    </script>

    <script type="text/javascript">
        var sum = document.getElementsByClassName('sum');
        for(var a = 0; a < sum.length; a++){
          document.getElementsByClassName('price_parent')[a].value = document.getElementsByClassName('sum')[a].innerHTML;
          document.getElementsByClassName('parent_sum_quantity')[a].value = document.getElementsByClassName('sum_qty')[a].innerHTML;
        }

    </script>
@endsection
