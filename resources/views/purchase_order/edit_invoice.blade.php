@extends('layouts.app')

@section('page_title')
  Purchase Order Invoice
@endsection

@section('page_header')
  <h1>
    Purchase Order Invoice
    <small>Edit Purchase Order Invoice</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('purchase-order') }}"><i class="fa fa-dashboard"></i> Purchase Order </a></li>
    <li><a href="{{ URL::to('purchase-order/'.$purchase_order_invoice->purchase_order->id) }}"><i class="fa fa-dashboard"></i> {{ $purchase_order_invoice->purchase_order->code }} </a></li>
    <li>Invoice</li>
    <li><a href="{{ URL::to('purchase-order-invoice/'.$purchase_order_invoice->id.'') }}"><i class="fa fa-dashboard"></i> {{ $purchase_order_invoice->code }}</a></li>
    <li class="active">Edit</li>
  </ol>
@endsection

@section('content')

  <!-- Row Invoice-->
  <div class="row">
    <div class="col-lg-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">{{ $purchase_order_invoice->code }}</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          {!! Form::model($purchase_order_invoice, ['route'=>['purchase-order-invoice.update', $purchase_order_invoice->id], 'id'=>'form-edit-purchase-order-invoice', 'class'=>'form-horizontal','method'=>'put', 'files'=>true]) !!}
          <div class="table-responsive" style="max-height:500px">
            <table class="table table-striped table-hover" id="table-selected-products">
                <thead>
                    <tr style="background-color:#3c8dbc;color:white">
                      <th style="width:15%;">Family</th>
                      <th style="width:20%;">Name</th>
                      <th style="width:20%;">Description</th>
                      <th style="width:10%;">Unit</th>
                      <th style="width:5%;">Qty</th>
                      <th style="width:10%;">Category</th>
                      <th style="width:10%;">Price Per Unit</th>
                      <th style="width:10%;">Price</th>
                    </tr>
                </thead>
              <tbody>
                  @if(count($row_display))
                      @foreach($row_display as $row)
                        <?php $sum_qty = 0; $sum = 0; ?>
                          <tr>
                            <td>
                                <strong>
                                {{ $row['family'] }}<br>
                                </strong>
                                <input type="hidden" name="parent_product_id[]" value="{{ $row['main_product_id'] }} " />
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
                                <input type="text" name="price_per_unit[]" value="{{ number_format($or['price_per_unit']) }}" class="price_per_unit form-control">
                              </td>
                              <td>
                                <input type="text" name="price[]" value="{{ number_format($or['price']) }}" class="price form-control" readonly>
                                <?php $sum += $or['price']; ?>
                              </td>
                          </tr>
                          @endforeach
                          <tr style="display:none">
                            <td colspan="4" class="sum">{{ number_format($sum) }}</td>
                            <td colspan="3" class="sum_qty">{{ $sum_qty }}</td>
                          </tr>
                      @endforeach
                @else
                <!-- <tr id="tr-no-product-selected">
                  <td colspan="7">There are no product</td>
                </tr> -->
                @endif
              </tbody>
            </table>

          </div>
          <br>
            <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
              {!! Form::label('code', 'Code', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-6">
                {!! Form::text('code',null,['class'=>'form-control', 'placeholder'=>'Code of the invoice', 'id'=>'code']) !!}
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
                {!! Form::text('bill_price',null,['class'=>'form-control', 'placeholder'=>'Bill price of the invoice', 'id'=>'bill_price', 'readonly']) !!}
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
                {!! Form::date('term',null,['class'=>'form-control', 'placeholder'=>'Bill price of the invoice', 'id'=>'term']) !!}
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
                {!! Form::textarea('notes',null,['class'=>'form-control', 'placeholder'=>'Paid price for the invoice', 'id'=>'notes']) !!}
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
                <a href="{{ url('purchase-order-invoice') }}" class="btn btn-default">
                  <i class="fa fa-repeat"></i>&nbsp;Cancel
                </a>&nbsp;
                <button type="submit" class="btn btn-info" id="btn-submit-purchase-order-invoice">
                  <i class="fa fa-save"></i>&nbsp;Submit
                </button>
              </div>
            </div>
            {!! Form::hidden('purchase_order_invoice_id', $purchase_order_invoice->id) !!}
            {!! Form::hidden('purchase_order_invoice_code', $purchase_order_invoice->code) !!}
            {!! Form::hidden('purchase_order_id', $purchase_order_invoice->purchase_order->id) !!}
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

    $('.price_per_unit').autoNumeric('init',{
      aSep:',',
      aDec:'.'
    });

    //set autonumeric to price classes field
    $('.price').autoNumeric('init',{
        aSep:',',
        aDec:'.'
    });

    //set autonumeric to price classes field
    $('.price_parent').autoNumeric('init',{
        aSep:',',
        aDec:'.'
    });

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

  //Block handle form create purchase order submission
    $('#form-edit-purchase-order-invoice').on('submit', function(event){
      $('#btn-submit-purchase-order-invoice').attr('disable','disabled');
    });
  //ENDBlock handle form create purchase order submission
  </script>
  <script type="text/javascript">
      var sum = document.getElementsByClassName('sum');
      for(var a = 0; a < sum.length; a++){
        document.getElementsByClassName('price_parent')[a].value = document.getElementsByClassName('sum')[a].innerHTML;
        document.getElementsByClassName('target_qty')[a].innerHTML = document.getElementsByClassName('sum_qty')[a].innerHTML;
      }

  </script>

@endSection
