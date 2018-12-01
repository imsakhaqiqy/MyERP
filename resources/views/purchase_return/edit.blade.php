@extends('layouts.app')

@section('page_title')
  Purchase Order Return
@endsection

@section('page_header')
  <h1>
    Purchase Order Return
    <small>Edit Purchase Order Return</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('purchase-order') }}"><i class="fa fa-dashboard"></i> Purchase Order</a></li>
    <li>{{ $purchase_order->code }}</li>
    <li><a href="{{ URL::to('purchase-return') }}"><i class="fa fa-dashboard"></i>Return</a></li>
    <li><i></i>{{ $purchase_return->id }}</li>
    <li class="active"><i></i>Edit</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Basic Information</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            <div class="table-responsive">
              {!! Form::model($purchase_return, ['route'=>['purchase-return.update', $purchase_return->id], 'id'=>'form-edit-purchase-return', 'class'=>'form-horizontal','method'=>'put', 'files'=>true]) !!}
                <table class="table table-striped table-hover">
                  <thead>
                    <tr style="background-color:#3c8dbc;color:white">
                        <th style="display:none"></th>
                        <th style="width:10%;">Family</th>
                        <th style="width:15%;">Code</th>
                        <th style="width:15%;">Description</th>
                        <th style="width:5%;">Unit</th>
                        <th style="width:5%;">Qty</th>
                        <th style="width:10%;">Category</th>
                        <th style="width:10%;display:none">Price/item</th>
                        <th style="width:10%;">Price</th>
                        <th style="width:10%;">Return Qty</th>
                        <th style="width:10%;">Notes</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="display:none">
                        <select name="inventory_account" id="inventory_account" class="col-md-12" style="">
                          @foreach(list_account_inventory('52') as $as)
                              @if($as->name == 'PERSEDIAAN'.' '.$purchase_return->product->main_product->family->name)
                              <option value="{{ $as->id}}">{{ $as->account_number }}&nbsp;&nbsp;{{ $as->name}}</option>
                              @endif
                          @endforeach
                        </select>
                      </td>
                      <td>{{ $purchase_return->product->main_product->family->name }}</td>
                      <td>{{ $purchase_return->product->name }}</td>
                      <td>{{ $purchase_return->product->description }}</td>
                      <td>{{ $purchase_return->product->main_product->unit->name }}</td>
                      <td class="purchased_qty">
                        {{ \DB::table('product_purchase_order')->select('quantity')->where('product_id',$purchase_return->product_id)->where('purchase_order_id', $purchase_return->purchase_order_id)->value('quantity') }}
                      </td>
                      <td>{{ $purchase_return->product->main_product->category->name }}</td>
                      <td style="display:none">
                          {{ \DB::table('product_purchase_order')->select('price')->where('product_id',$purchase_return->product_id)->where('purchase_order_id', $purchase_return->purchase_order_id)->value('price')/\DB::table('product_purchase_order')->select('quantity')->where('product_id',$purchase_return->product_id)->where('purchase_order_id', $purchase_return->purchase_order_id)->value('quantity') }}
                          <?php
                            $purchase_return_price_per_unit = \DB::table('product_purchase_order')->select('price')->where('product_id',$purchase_return->product_id)->where('purchase_order_id', $purchase_return->purchase_order_id)->value('price')/\DB::table('product_purchase_order')->select('quantity')->where('product_id',$purchase_return->product_id)->where('purchase_order_id', $purchase_return->purchase_order_id)->value('quantity');
                          ?>
                      </td>
                      <td>
                        {{ number_format(\DB::table('product_purchase_order')->select('price')->where('product_id',$purchase_return->product_id)->where('purchase_order_id', $purchase_return->purchase_order_id)->value('price')) }}
                      </td>
                      <td>
                        {{ Form::text('quantity',null, ['class'=>'returned_quantity form-control']) }}
                      </td>
                      <td>
                        {{ Form::text('notes',null, ['class'=>'notes form-control']) }}
                      </td>
                    </tr>
                  </tbody>
                </table>
            </div>
            <br/>
            <div class="row">
                <div class="col-md-3"><strong>PO Reference</strong></div>
                <div class="col-md-1">:</div>
                <div class="col-md-3">
                    <p>{{ $purchase_return->purchase_order->code }}</p>
                </div>
            </div><!-- /.row -->
            <div class="row">
              <div class="col-md-3"><strong>Status</strong></div>
              <div class="col-md-1">:</div>
              <div class="col-md-3">
                <p>{{ strtoupper($purchase_return->status) }}</p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3"><strong>Supplier Name</strong></div>
              <div class="col-md-1">:</div>
              <div class="col-md-3">
                <p>{{ $purchase_return->purchase_order->supplier->name }}</p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3"><strong>Created At</strong></div>
              <div class="col-md-1">:</div>
              <div class="col-md-3">
                <p>{{ $purchase_return->created_at }}</p>
              </div>
            </div>
        </div><!-- /.box-body -->
        <div class="box-footer clearfix">
          <div class="form-group">
              {!! Form::label('', '', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              <a href="{{ url('purchase-return') }}" class="btn btn-default">
                <i class="fa fa-repeat"></i>&nbsp;Cancel
              </a>
              <button type="submit" class="btn btn-info" id="btn-submit-purchase-return">
                <i class="fa fa-save"></i>&nbsp;Submit
              </button>
            </div>
          </div>
          {!! Form::hidden('purchase_return_id', $purchase_return->id) !!}
          {!! Form::hidden('purchase_order_invoice_id',$purchase_return->purchase_order->purchase_order_invoice->id) !!}
          {!! Form::hidden('purchase_return_price_per_unit',$purchase_return_price_per_unit) !!}
          {!! Form::hidden('quantity_first',$purchase_return->quantity) !!}
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
    var the_value = parseInt($(this).val());
    if(the_value > purchased_qty){
      alertify.error('Returned quantity can not be greater than purchased quantity');
     $('#btn-submit-purchase-return').prop('disabled', true);
    }
    else{
      $('#btn-submit-purchase-return').prop('disabled', false);
    }
    return false;
  });
</script>
<!--ENDBlock Compare Control returned quantity to purchased quantity-->



<script type="text/javascript">
  //Block handle form edit purchase return submission
    $('#form-edit-purchase-return').on('submit', function(){
      $('#btn-submit-purchase-return').prop('disabled', true);
    });
  //ENDBlock handle form edit purchase order submission
</script>

@endsection
