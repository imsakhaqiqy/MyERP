@extends('layouts.app')

@section('page_title')
  Create Purchase Order Invoice
@endsection

@section('page_header')
  <h1>
    Purchase Order
    <small>Create Invoice</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('purchase-order/') }}"><i class="fa fa-cart-arrow-down"></i> Purchase Orders</a></li>
    <li><a href="{{ URL::to('purchase-order/'.$purchase_order->id.'') }}">{{ $purchase_order->code }}</a></li>
    <li><a href="{{ URL::to('purchase-order-invoice') }}">Invoices</a></li>
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
          {!! Form::open(['route'=>'purchase-order-invoice.store','role'=>'form','class'=>'form-horizontal','id'=>'form-create-purchase-order-invoice','files'=>true]) !!}

          <div class="table-responsive" style="max-height:500px">
            <table class="table table-striped table-hover" id="table-selected-products">
                <thead>
                    <tr style="background-color:#3c8dbc;color:white">
                      <th style="width:15%;">Family</th>
                      <th style="width:20%;">Name</th>
                      <th style="width:15%;">Description</th>
                      <th style="width:10%;">Unit</th>
                      <th style="width:5%;">Qty</th>
                      <th style="width:10%;">Category</th>
                      <th style="width:10%;">Price Per Unit</th>
                      <th style="width:15%;">Price</th>
                    </tr>
                </thead>
                <tbody>
                @if(count($row_display))
                    @foreach($row_display as $row)
                        <?php $sum_qty = 0; $sum = 0; ?>
                        <tr id="row_parent_id_{{$row['main_product_id']}}">
                          <td>
                              <input type="hidden" name="parent_product_id[]" value="{{ $row['main_product_id'] }} " />
                              {{ $row['family'] }}<br>
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
                          <td><strong class="target_qty">{{ $sum_qty }}</strong></td>
                          <td><strong>{{ $row['category'] }}</strong></td>
                          <td></td>
                          <td>
                              <input type="hidden" name="price_parent[]" class="price_parent" id="total_price_parent_{{$row['main_product_id']}}">
                          </td>
                        </tr>
                        @foreach($row['ordered_products'] as $or)
                        <?php $sum_child = 0;?>
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
                            <input type="text" name="price_per_unit[]" class="price_per_unit form-control" data-parent-product-id="{{ $row['main_product_id']}}">
                          </td>
                          <td>
                            <input type="text" name="price[]" class="price form-control" data-parent-product-id="{{ $row['main_product_id']}}" readonly>
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
            <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
              {!! Form::label('code', 'Code', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-6">
                {!! Form::text('code','',['class'=>'form-control', 'placeholder'=>'Code of the invoice', 'id'=>'code']) !!}
                @if ($errors->has('code'))
                  <span class="help-block">
                    <strong>{{ $errors->first('code') }}</strong>
                  </span>
                @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('bill_price') ? ' has-error' : '' }}">
              {!! Form::label('bill_price', 'Bill Price', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-6">
                {!! Form::text('bill_price',null,['class'=>'form-control', 'placeholder'=>'Bill price of the invoice', 'id'=>'bill_price']) !!}
                @if ($errors->has('bill_price'))
                  <span class="help-block">
                    <strong>{{ $errors->first('bill_price') }}</strong>
                  </span>
                @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('select_account') ? ' has-error' : '' }}" style="display:none">
              {!! Form::label('select_account', 'Accounts Payable', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-6">
                  <select name="select_account" id="select_account" class="form-control">
                  @foreach(list_account_hutang('56') as $as)
                    @if($as->name == 'HUTANG DAGANG IDR')
                        <option value="{{ $as->id}}">{{ $as->account_number }}&nbsp;&nbsp;{{ $as->name }}</option>
                    @endif
                  @endforeach
                  </select>
              </div>
            </div>

            <div class="form-group{{ $errors->has('term') ? ' has-error' : '' }}">
              {!! Form::label('term', 'Term Invoice', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-6">
                {!! Form::date('term',null,['class'=>'form-control', 'placeholder'=>'Term Invoice for the invoice', 'id'=>'term']) !!}
                @if ($errors->has('term'))
                  <span class="help-block">
                    <strong>{{ $errors->first('term') }}</strong>
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
                <a href="{{ url('purchase-order/'.$purchase_order->id) }}" class="btn btn-default">
                  <i class="fa fa-repeat"></i>&nbsp;Cancel
                </a>&nbsp;
                <button type="submit" class="btn btn-info" id="btn-submit-purchase-order-invoice">
                  <i class="fa fa-save"></i>&nbsp;Submit
                </button>
              </div>
            </div>
            {!! Form::hidden('purchase_order_id', $purchase_order->id) !!}
          {!! Form::close() !!}
        </div><!-- /.box-body -->
        <!-- <button id-"tes" onclick="tes()">TES</button> -->
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
    //set autonumeric to price_per_unit classes field
    $('.price_per_unit').autoNumeric('init',{
        aSep:',',
        aDec:'.'
    });
    //set autonumeric to price classes field
    $('.price_parent').autoNumeric('init',{
        aSep:',',
        aDec:'.'
    });
    $('.price').autoNumeric('init',{
        aSep:',',
        aDec:'.'
    });

    //block handle price value keyup event
    $('.price_per_unit').on('keyup',function(){
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
        sum_parent_price();
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

    function sum_parent_price(){
        var sum = 0;
        var dataParentId = 0;
        $('.price').each(function(){
            sum += +$(this).val().replace(/,/g,'');
            dataParentId = $(this).attr('data-parent-product-id');
            $('#total_price_parent_'+dataParentId).val(sum);

        })
        $(this).parent().find('#total_price_parent').remove();
    }

  </script>

  <script type="text/javascript">
  //Block handle form create purchase order submission
    $('#form-create-purchase-order-invoice').on('submit', function(event){
      event.preventDefault();
      var data = $(this).serialize();
      $.ajax({
          url: '{!!URL::to('storePurchaseOrderInvoice')!!}',
          type : 'POST',
          data : $(this).serialize(),
          beforeSend : function(){
            $('#btn-submit-purchase-order-invoice').prop('disabled', true);
            //$('#btn-submit-purchase-order-invoice').hide();
          },
          success : function(response){
            if(response.msg == 'storePurchaseOrderInvoiceOk'){
                window.location.href= '{{ URL::to('purchase-order') }}/'+response.purchase_order_id;
            }
            else{
              $('#btn-submit-purchase-order-invoice').prop('disabled', false);
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
            $('#btn-submit-purchase-order-invoice').prop('disabled', false);
        }
      });
    });
  //ENDBlock handle form create purchase order submission
  </script>

  <script type="text/javascript">
      var sum = document.getElementsByClassName('sum');
      for(var a = 0; a < sum.length; a++){
        document.getElementsByClassName('target_qty')[a].innerHTML = document.getElementsByClassName('sum_qty')[a].innerHTML;
      }

      function tes()
      {
          var sum_count = document.getElementsByClassName('sum');
          var b = [];
          for(var i = 0; i < sum_count.length; i++){
              b[i] = document.getElementsByClassName('sum_qty')[i].innerHTML;
              for(var j = 0; j < b[i]; j++ ){
                  document.getElementsByClassName('price_parent')[i].value += document.getElementsByClassName('price')[j].value;
              }
          }
      }
  </script>
@endSection
