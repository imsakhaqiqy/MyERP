@extends('layouts.app')

@section('page_title')
  Create Sales Order Invoice
@endsection

@section('page_header')
  <h1>
    Sales Order
    <small>Create Invoice</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('sales-order/') }}"><i class="fa fa-dashboard"></i> Sales Orders</a></li>
    <li><a href="{{ URL::to('sales-order/'.$sales_order->id.'') }}"><i class="fa fa-dashboard"></i> {{ $sales_order->code }}</a></li>
    <li><a href="{{ URL::to('sales-order-invoice') }}"><i class="fa fa-dashboard"></i> Invoices</a></li>
    <li class="active">Create</li>
  </ol>
@endsection

@section('content')

  <!-- Row Invoice-->
  <div class="row">
    <div class="col-lg-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Form Invoice</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          {!! Form::open(['route'=>'sales-order-invoice.store','role'=>'form','class'=>'form-horizontal','id'=>'form-create-sales-order-invoice','files'=>true]) !!}

            <div class="table-responsive" style="max-height:500px">
              <table class="table table-striped table-hover" id="table-selected-products">
                  <thead>
                      <tr style="background-color:#3c8dbc;color:white">
                        <th style="width:10%;">Family</th>
                        <th style="width:20%;">Name</th>
                        <th style="width:15%;">Description</th>
                        <th style="width:8%;">Unit</th>
                        <th style="width:7%;">Qty</th>
                        <th style="width:15%;">Category</th>
                        <th style="width:10%;">Price Per Unit</th>
                        <th style="width:20%;">Price</th>
                      </tr>
                  </thead>
                  <tbody>

                      @if(count($row_display))
                          @foreach($row_display as $row)
                          <?php $sum_qty = 0; $sum = 0; ?>
                              <tr style="display:none">
                                <td>
                                  <input type="text" name="parent_product_id[]" value="{{ $row['main_product_id'] }}"/>
                                  <input type="text" name="parent_sum_stock[]" value="{{ $row['sum_stock'] }}"/>
                                  <input type="text" name="parent_sum_inventory_cost[]" value="{{ ($row['sum_inventory_cost_first']+$row['sum_price_purchase'])/($row['sum_inventory_quantity_first']+$row['sum_qty_purchase']) }}"/>
                                  <input type="text" name="parent_sum_quantity[]" class="parent_sum_quantity"/>
                                  {{ $row['family'] }}<br>
                                  <select name="inventory_account[]" id="inventory_account" class="col-md-12" style="display:none">
                                    @foreach(list_account_inventory('52') as $as)
                                      @if($as->name == 'PERSEDIAAN'.' '.$row['family'])
                                        <option value="{{ $as->id}}">{{ $as->account_number }}&nbsp;&nbsp;{{ $as->name }}</option>
                                      @endif
                                    @endforeach
                                  </select><br/><br/>
                                  <select name="sales_order_account[]" id="sales_order_account" class="col-md-12" style="display:none">
                                      @foreach(list_parent('61') as $sales_account)
                                        @if($sales_account->name == 'PENJUALAN'.' '.$row['family'])
                                          <option value="{{ $sales_account->id}}">{{ $sales_account->account_number }}&nbsp;&nbsp;{{ $sales_account->name }}</option>
                                        @endif
                                      @endforeach
                                  </select><br/><br/>
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
                                <td>{{ $row['description'] }}</td>
                                <td>{{ $row['unit'] }}</td>
                                <td class="target_qty">{{ $sum_qty }}</td>
                                <td>{{ $row['category'] }}</td>
                                <td></td>
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
                                <td>{{ $or['code'] }}</td>
                                <td>{{ $or['description'] }} </td>
                                <td>{{ $or['unit'] }} </td>
                                <td>
                                  <input type="hidden" name="quantity[]" value="{{ $or['quantity'] }}" class="quantity">
                                  {{ $or['quantity'] }}
                                  <?php $sum_qty += $or['quantity']; ?>
                                </td>
                                <td>{{ $or['category'] }}</td>
                                <td>
                                  <input type="text" name="price_per_unit[]" class="price_per_unit form-control">
                                </td>
                                <td>
                                  <input type="text" name="price[]" class="price form-control" readonly>
                                </td>
                              </tr>
                              @endforeach
                              <tr style="display:none">
                                <td colspan="3" class="sum"></td>
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
            <br>
            <div class="form-group{{ $errors->has('bill_price') ? ' has-error' : '' }}">
              {!! Form::label('bill_price', 'Bill Price', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-6">
                {!! Form::text('bill_price','',['class'=>'form-control', 'placeholder'=>'', 'id'=>'bill_price', 'readonly']) !!}
                @if ($errors->has('bill_price'))
                  <span class="help-block">
                    <strong>{{ $errors->first('bill_price') }}</strong>
                  </span>
                @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('ppn') ? ' has-error' : '' }}">
              {!! Form::label('ppn', 'PPN', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-6">
                <select name="ppn" class="form-control">
                  <option value="">Select PPN</option>
                  <option value="0">0%</option>
                  <option value="10">10%</option>
                  <option value="15">15%</option>
                  <option value="20">20%</option>
                </select>
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
                <select name="ppn_hidden" class="form-control">
                  <option value="">Hidden PPN</option>
                  <option value="yes">YES</option>
                  <option value="no">NO</option>
                </select>
                @if ($errors->has('ppn_hidden'))
                  <span class="help-block">
                    <strong>{{ $errors->first('ppn_hidden') }}</strong>
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
                        <option value="{{ $as->id}}">{{ $as->account_number }}&nbsp;&nbsp;{{ $as->name }}</option>
                    @endif
                  @endforeach
                  </select>
              </div>
            </div>

            <div class="form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
              {!! Form::label('notes', 'Notes', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-6">
                {!! Form::textarea('notes',null,['class'=>'form-control', 'placeholder'=>'Sales order invoice notes', 'id'=>'notes']) !!}
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
                <a href="{{ url('sales-order/'.$sales_order->id) }}" class="btn btn-default">
                  <i class="fa fa-repeat"></i>&nbsp;Cancel
                </a>&nbsp;
                <button type="submit" class="btn btn-info" id="btn-submit-sales-order-invoice">
                  <i class="fa fa-save"></i>&nbsp;Submit
                </button>
              </div>
            </div>
            {!! Form::hidden('sales_order_id', $sales_order->id) !!}
          {!! Form::close() !!}
        </div><!-- /.box-body -->

      </div><!-- /.box -->
    </div>
  </div>
  <!-- ENDRow Invoice-->






@endsection


@section('additional_scripts')

  {!! Html::script('js/autoNumeric.js') !!}
  <script type="text/javascript">
    $('#bill_price').autoNumeric('init',{
        aSep:',',
        aDec:'.'
    });
    $('#paid_price').autoNumeric('init',{
        aSep:',',
        aDec:'.'
    });
    //set autonumeric to price_per_unit classes field
    $('.price_per_unit').autoNumeric('init',{
        aSep:',',
        aDec:'.'
    });
    //set autonumeric to price classes field
    $('.price').autoNumeric('init',{
        aSep:',',
        aDec:'.'
    });

    $('.price_parent').autoNumeric('init',{
        aSep:',',
        aDec:'.'
    });

    //Block handle price value on price per unit keyup event
    $('.price_per_unit').on('keyup', function(){

      var quantity = $(this).parent().parent().find('.quantity').val();
      var the_price = 0;
      if($(this).val() == ''){
        the_price = 0;
      }
      else{
        the_price = parseFloat($(this).val().replace(/,/g, ''))*quantity;
      }

      $(this).parent().parent().find('.price').val(the_price).autoNumeric('update',{
          aSep:',',
          aDec:'.'
      });
      fill_the_bill_price();
    });

    function fill_the_bill_price(){

      var sum = 0;
      $(".price").each(function(){
          sum += +$(this).val().replace(/,/g, '');
      });
      $("#bill_price").val(sum);
      $('#bill_price').autoNumeric('update',{
          aSep:',',
          aDec:'.'
      });
    }
  </script>

  <script type="text/javascript">
  //Block handle form create Sales Order submission
    $('#form-create-sales-order-invoice').on('submit', function(event){
      event.preventDefault();
      var data = $(this).serialize();
      $.ajax({
          url: '{!!URL::to('storeSalesOrderInvoice')!!}',
          type : 'POST',
          data : $(this).serialize(),
          beforeSend : function(){
            $('#btn-submit-sales-order-invoice').prop('disabled', true);
            //$('#btn-submit-sales-order-invoice').hide();
          },
          success : function(response){
            if(response.msg == 'storeSalesOrderInvoiceOk'){
                window.location.href= '{{ URL::to('sales-order') }}/'+response.sales_order_id;
            }
            else{
              $('#btn-submit-sales-order-invoice').prop('disabled', false);
              console.log(response);
            }
          },
          error:function(data){
            var htmlErrors = '<p>Error : </p>';
            errors = data.responseJSON;
            $.each(errors, function(index, value){
              htmlErrors+= '<p>'+value+'</p>';
            });
            alertify.set('notifier', 'delay',0);
            alertify.error(htmlErrors);
            $('#btn-submit-sales-order-invoice').prop('disabled', false);
        }
      });
    });
  //ENDBlock handle form create Sales Order submission
  </script>
  <script type="text/javascript">
      var sum = document.getElementsByClassName('sum_qty');
      for(var a = 0; a < sum.length; a++){
        document.getElementsByClassName('parent_sum_quantity')[a].value = document.getElementsByClassName('sum_qty')[a].innerHTML;
        document.getElementsByClassName('target_qty').innerHTML = document.getElementsByClassName('sum_qty')[a].innerHTML;
      }
  </script>

@endSection
