@extends('layouts.app')

@section('page_title')
    Sales Order Return
@endsection

@section('page_header')
    <h1>
        Sales Order
        <small>Create sales order return </small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ url('sales-order') }}"><i class="fa fa-dashboard"></i> Sales Order</a></li>
        <li>{{ $sales_order->code }}</li>
        <li><a href="{{ url('sales-return/create?sales_order_id='.$sales_order->id) }}"><i class="fa fa-dashboard"></i> Return</a></li>
        <li class="active"><i></i> Create</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3 class="box-title">Create Sales Order Return</h3>
                </div><!-- /.box header -->
                <div class="box-body table-responsive" style="height:500px">
                    {!! Form::open(['route'=>'sales-order.store','role'=>'form','class'=>'form-horizontal','id'=>'form-create-sales-order-return']) !!}
                    <table class="table table-striped table-hover" id="table-selected-sales">
                        <thead>
                            <tr style="background-color:#3c8dbc;color:white">
                                <th style="width:5%;">#</th>
                                <th style="width:10%;">Family</th>
                                <th style="width:15%;">Code</th>
                                <th style="width:10%;">Description</th>
                                <th style="width:10%;">Unit</th>
                                <th style="width:5%;">Quantity</th>
                                <th style="width:15%;">Category</th>
                                <th style="width:5%;">Price/item</th>
                                <th style="width:5%;">Price</th>
                                <th style="width:10%;">Returned Qty</th>
                                <th style="width:10%;">Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($row_display))
                                @foreach($row_display as $row)
                                    <tr style="display:none">
                                      <td colspan="2">
                                          <strong>
                                              {{ $row['family'] }}
                                          </strong>
                                          <input type="hidden" name="parent_product_id[]" value="{{ $row['main_product_id'] }}"/>
                                          <select name="inventory_account[]" id="inventory_account" class="col-md-12" style="">
                                            @foreach(list_account_inventory('52') as $as)
                                              @if($as->name == 'PERSEDIAAN'.' '.$row['family'])
                                              <option value="{{ $as->id}}">{{ $as->account_number }}&nbsp;&nbsp;{{ $as->name}}</option>
                                              @endif
                                            @endforeach
                                          </select><br/><br/><br/>
                                          <select name="return_account[]" id="return_account" class="col-md-12" style="">
                                              @foreach(list_parent('61') as $return_account)
                                                @if($return_account->name == 'RETURN PENJUALAN'.' '.$row['family'])
                                                <option value="{{ $return_account->id}}">{{ $return_account->account_number }}&nbsp;&nbsp;{{ $return_account->name}}</option>
                                                @endif
                                              @endforeach
                                          </select><br/><br/>
                                          <select name="cost_goods_account[]" id="cost_goods_account" class="col-md-12" style="">
                                              @foreach(list_parent('63') as $cost_goods_account)
                                                @if($cost_goods_account->name == 'HARGA POKOK PENJUALAN'.' '.$row['family'])
                                                <option value="{{ $cost_goods_account->id}}">{{ $cost_goods_account->account_number }}&nbsp;&nbsp;{{ $cost_goods_account->name}}</option>
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
                                      <td><strong>{{ $row['quantity'] }}</strong></td>
                                      <td><strong>{{ $row['category'] }}</strong></td>
                                      <td></td>
                                      <td></td>
                                      <td>{{ Form::hidden('parent_return[]',null,['class'=>'parent_return form-control']) }}</td>
                                      <td></td>
                                    </tr>
                                    @foreach($row['ordered_products'] as $or)
                                    <tr id="row_sales_{{$or['product_id'] }}">
                                      <td>{{ Form::checkbox('product_id[]',$or['product_id'],false,['class'=>'sales-id-checkbox']) }}</td>
                                      <td>
                                          {{ $or['family'] }}
                                          <input type="hidden" name="child_product_id[]" value="{{ $or['product_id'] }}" class="child_product_id" disabled/>
                                          {{ Form::hidden('main_product_id_return[]',$row['main_product_id'],['class'=>'main_product_id_return form-control','disabled']) }}
                                          {{ Form::hidden('amount_return_per_unit[]',null,['class'=>'price_per_unit form-control','disabled']) }}
                                          <input type="hidden" name="parent_sum_inventory_cost[]" value="{{ ($row['sum_inventory_cost_first']+$row['sum_price_purchase'])/($row['sum_inventory_quantity_first']+$row['sum_qty_purchase']) }}"/>
                                      </td>
                                      <td>{{ $or['code'] }} </td>
                                      <td>{{ $or['description'] }} </td>
                                      <td>{{ $or['unit'] }} </td>
                                      <td class="salesed_qty">{{ $or['quantity'] }}</td>
                                      <td>{{ $or['category'] }}</td>
                                      <td class="price_item">{{ number_format($or['price_per_unit']) }}</td>
                                      <td>{{ number_format($or['price']) }}</td>
                                      <td>
                                          {{ Form::text('returned_quantity[]',null,['class'=>'returned_quantity form-control','disabled']) }}
                                      </td>
                                      <td>{{ Form::text('notes[]',null,['class'=>'notes form-control','disabled']) }}</td>
                                    </tr>
                                    @endforeach

                                @endforeach
                          @else
                          <tr id="tr-no-product-selected">
                            <td>There are no product</td>
                          </tr>
                          @endif
                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </div><!-- /.box body -->
                <div class="box-footer clearfix">
                    <div class="form-group">
                        {!! Form::label('','',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            <a href="{{ url('sales-order/'.$sales_order->id)}}" class="btn btn-default">
                                <i class="fa fa-repeat"></i>&nbsp;Cancel
                            </a>&nbsp;
                            <button type="submit" class="btn btn-info" id="btn-submit-sales-return">
                                <i class="fa fa-save"></i>&nbsp;Submit
                            </button>
                        </div>
                    </div>
                    {!! Form::hidden('sales_order_id', $sales_order->id) !!}
                    {!! Form::hidden('sales_order_invoice_id',$so_id->id) !!}
                    {!! Form::hidden('sales_order_invoice_code',$so_id->code) !!}
                    {!! Form::close() !!}
                </div><!-- /.box footer -->
            </div>
        </div>
    </div>
@endsection

@section('additional_scripts')

{!! Html::script('js/autoNumeric.js') !!}
<script type="text/javascript">
    $('.returned_quantity').autoNumeric('init',{
        aSep:'.',
        aDec:',',
        aPad:false
    });
</script>
<!-- Block Compare Control returned quantity to sales quantity -->
<script type="text/javascript">
    $('.returned_quantity').on('keyup', function(){
        var salesed_qty = parseInt($(this).parent().parent().find('.salesed_qty').html());
        var the_value = parseInt($(this).val());
        var price_item = $(this).parent().parent().find('.price_item').html().replace(/,/gi,'');
        var price_per_unit = parseInt($(this).parent().parent().find('.price_per_unit').val(the_value*price_item));
        if(the_value > salesed_qty){
            alertify.error('Returned quantity can not be greater than salesed quantity');
            $('#btn-submit-sales-return').prop('disabled',true);
        }else{
            $('#btn-submit-sales-return').prop('disabled',false);
        }
        return false;
    });

</script>
<!--ENDBlock Compare control returned quantity to sales quantity-->

<script type="text/javascript">
    $('.sales-id-checkbox').on('click',function(){
        if($(this).is(':checked') == true){
            $(this).parent().parent().find('.returned_quantity').prop('disabled',false);
            $(this).parent().parent().find('.child_product_id').prop('disabled',false);
            $(this).parent().parent().find('.main_product_id_return').prop('disabled',false);
            $(this).parent().parent().find('.price_per_unit').prop('disabled',false);
            $(this).parent().parent().find('.notes').prop('disabled',false);
            $(this).parent().parent().find('.returned_quantity').focus();
        }
        else{
            $(this).parent().parent().find('.returned_quantity').prop('disabled',true);
            $(this).parent().parent().find('.returned_quantity').val('');
            $(this).parent().parent().find('.child_product_id').prop('disabled',true);
            $(this).parent().parent().find('.main_product_id_return').prop('disabled',true);
            $(this).parent().parent().find('.price_per_unit').prop('disabled',true);
            $(this).parent().parent().find('.notes').prop('disabled',true);
            $(this).parent().parent().find('.notes').val('');
        }
    });
</script>

<script type="text/javascript">
    //Block handle form create sales return submission
    $('#form-create-sales-order-return').on('submit', function(event){
        event.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            url: '{!! URL::to('storeSalesReturn') !!}',
            type: 'POST',
            data: $(this).serialize(),
            beforeSend : function(){
                //$('#btn-submit-sales-return').prop('disabled',true);
            },
            success : function(response){
                if(response == 'storeSalesReturnOk'){
                    window.location.href = '{{ URL::to("sales-return") }}';
                }
                else{
                    //$('btn-submit-sales-return').prop('disabled',false);
                    alertify.error(response);
                    console.log(response);
                }
            },
            error : function(data){
                var htmlErrors = '<p>Error : </p>';
                errors = data.responseJSON;
                $.each(errors, function(index, value){
                    htmlErrors+= '<p>'+value+'</p>';
                });
                alertify.set('notifier', 'delay', 0);
                alertify.error(htmlErrors);
                $('#btn-submit-sales-return').prop('disabled',false);
            }
        });
    });
</script>
@endsection
