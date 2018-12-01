@extends('layouts.app')

@section('page_title')
  Purchase Order Return
@endsection

@section('page_header')
  <h1>
    Purchase Order Return
    <small>Add New Purchase Order Return</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('purchase-order') }}"><i class="fa fa-cart-arrow-down"></i> Purchase Order</a></li>
    <li><a href="{{ URL::to('purchase-order/'.$purchase_order->id) }}">{{ $purchase_order->code }}</a></li>
    <li><a href="{{ URL::to('purchase-return') }}"></i>Return</a></li>
    <li class="active"><i></i> Create</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Basic Information</h3>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
          {!! Form::open(['route'=>'purchase-return.store','role'=>'form','class'=>'form-horizontal','id'=>'form-create-purchase-return']) !!}
          <table class="table table-striped table-hover" id="table-selected-products">
            <thead>
              <tr style="background-color:#3c8dbc;color:white">
                  <th style="width:5%;">#</th>
                  <th style="width:10%;">Family</th>
                  <th style="width:15%;">Code</th>
                  <th style="width:10%;">Description</th>
                  <th style="width:10%;">Unit</th>
                  <th style="width:5%;">Qty</th>
                  <th style="width:15%;">Category</th>
                  <th style="width:10%;">Price</th>
                  <th style="width:10%;">Return Qty</th>
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
                          <td><strong></strong></td>
                          <td><strong>{{ $row['category'] }}</strong></td>
                          <td></td>
                          <td>{{ Form::hidden('parent_return[]',null,['class'=>'parent_return form-control']) }}</td>
                          <td></td>
                        </tr>
                        @foreach($row['ordered_products'] as $or)
                        <tr id="row_sales_{{$or['product_id'] }}">
                          <td>{{ Form::checkbox('product_id[]',$or['product_id'],false,['class'=>'purchase-id-checkbox']) }}</td>
                          <td>
                              {{ $or['family'] }}
                              <input type="hidden" name="child_product_id[]" value="{{ $or['product_id'] }}" class="child_product_id" disabled/>
                              {{ Form::hidden('main_product_id_return[]',$row['main_product_id'],['class'=>'main_product_id_return form-control','disabled']) }}
                              {{ Form::hidden('amount_return_per_unit[]',null,['class'=>'price_per_unit form-control','disabled']) }}
                          </td>
                          <td>{{ $or['code'] }} </td>
                          <td>{{ $or['description'] }} </td>
                          <td>{{ $or['unit'] }} </td>
                          <td class="purchased_qty">{{ $or['quantity'] }}</td>
                          <td>{{ $or['category'] }}</td>
                          <td class="price">{{ number_format($or['price']) }}</td>
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

        </div><!-- /.box-body -->
        <div class="box-footer clearfix">
          <div class="form-group">
              {!! Form::label('', '', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              <a href="{{ url('purchase-order/'.$purchase_order->id) }}" class="btn btn-default">
                <i class="fa fa-repeat"></i>&nbsp;Cancel
              </a>&nbsp;
              <button type="submit" class="btn btn-info" id="btn-submit-purchase-return">
                <i class="fa fa-save"></i>&nbsp;Submit
              </button>
            </div>
          </div>
          {!! Form::hidden('purchase_order_id', $purchase_order->id) !!}
          {!! Form::hidden('purchase_order_invoice_id',$po_id->id) !!}
          {!! Form::hidden('purchase_order_invoice_code',$po_id->code) !!}
          {!! Form::close() !!}
        </div>

      </div><!-- /.box -->

    </div>
  </div>


@endsection


@section('additional_scripts')

{!! Html::script('js/autoNumeric.js') !!}
<script type="text/javascript">
    $('.returned_quantity').autoNumeric('init',{
        aSep:'',
        aDec:'.',
        aPad:false
    });
</script>
<!--Block Compare Control returned quantity to purchased quantity-->
<script type="text/javascript">
  $('.returned_quantity').on('keyup', function(){
    var purchased_qty = parseInt($(this).parent().parent().find('.purchased_qty').html());
    //var pur_qty = $(this).parent().parent().find('.purchased_qty').text();
    var the_value = parseInt($(this).val());
    var price_item = $(this).parent().parent().find('.price').html().replace(/,/gi,'');
    var price_per_unit = parseInt($(this).parent().parent().find('.price_per_unit').val(the_value*price_item/purchased_qty));
    if(the_value > purchased_qty){
      alertify.error('Returned quantity can not be greater than purchased quantity');
      $('#btn-submit-purchase-return').prop('disabled', true);
    }else{
      $('#btn-submit-purchase-return').prop('disabled', false);
    }
    return false;
  });
</script>
<!--ENDBlock Compare Control returned quantity to purchased quantity-->


<script type="text/javascript">
  $('.purchase-id-checkbox').on('click', function(){
    if($(this).is(':checked') == true){
      $(this).parent().parent().find('.returned_quantity').prop('disabled', false);
      $(this).parent().parent().find('.child_product_id').prop('disabled',false);
      $(this).parent().parent().find('.main_product_id_return').prop('disabled',false);
      $(this).parent().parent().find('.price_per_unit').prop('disabled',false);
      $(this).parent().parent().find('.notes').prop('disabled', false);
      $(this).parent().parent().find('.returned_quantity').focus();
    }
    else{
      $(this).parent().parent().find('.returned_quantity').prop('disabled', true);
      $(this).parent().parent().find('.returned_quantity').val('');
      $(this).parent().parent().find('.child_product_id').prop('disabled',true);
      $(this).parent().parent().find('.main_product_id_return').prop('disabled',true);
      $(this).parent().parent().find('.price_per_unit').prop('disabled',true);
      $(this).parent().parent().find('.notes').val('');
      $(this).parent().parent().find('.notes').prop('disabled', true);
    }
  });
</script>

<script type="text/javascript">
  //Block handle form create purchase return submission
    $('#form-create-purchase-return').on('submit', function(event){
      event.preventDefault();
      var data = $(this).serialize();
      $.ajax({
          url: '{!! URL::to('storePurchaseReturn')!!}',
          type : 'POST',
          data : $(this).serialize(),
          beforeSend : function(){
            $('#btn-submit-purchase-return').prop('disabled', true);
            //$('#btn-submit-purchase-return').hide();
          },
          success : function(response){
            if(response == 'storePurchaseReturnOk'){
                window.location.href= '{{ URL::to("purchase-return") }}';
            }
            else{
              $('#btn-submit-purchase-return').prop('disabled', false);
              alertify.error(response);
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
            $('#btn-submit-purchase-return').prop('disabled', false);

        }
      });
    });
  //ENDBlock handle form create purchase order submission
</script>

@endsection
