@extends('layouts.app')

@section('page_title')
    Sales Order Return
@endsection

@section('page_header')
    <h1>
        Sales Order Return
        <small>Edit Sales Order Return</small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('sales-order') }}"><i class="fa fa-dashboard"></i> Sales Order</a></li>
        <li>{{ $sales_order->code }}</li>
        <li><a href="{{ URL::to('sales-return')}}"><i class="fa fa-dashboard"></i> Return</a></li>
        <li><i></i>{{ $sales_return->id }}</li>
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
                    {!! Form::model($sales_return,['route'=>['sales-return.update',$sales_return->id],'id'=>'form-edit-sales-return','class'=>'form-horizontal','method'=>'put','files'=>true]) !!}
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
                                <th style="width:10%;">Price/item</th>
                                <th style="width:10%;">Price</th>
                                <th style="width:10%;">Return Qty</th>
                                <th style="width:10%;">Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="display:none">
                                  <select name="inventory_account" id="inventory_account" class="col-md-12">
                                    @foreach(list_account_inventory('52') as $as)
                                      @if($as->name == 'PERSEDIAAN'.' '.$sales_return->product->main_product->family->name)
                                      <option value="{{ $as->id}}">{{ $as->account_number }}&nbsp;&nbsp;{{ $as->name}}</option>
                                      @endif
                                    @endforeach
                                  </select><br/><br/><br/>
                                  <select name="return_account" id="return_account" class="col-md-12">
                                      @foreach(list_parent('61') as $return_account)
                                        @if($return_account->name == 'RETURN PENJUALAN'.' '.$sales_return->product->main_product->family->name)
                                        <option value="{{ $return_account->id}}">{{ $return_account->account_number }}&nbsp;&nbsp;{{ $return_account->name}}</option>
                                        @endif
                                      @endforeach
                                  </select><br/><br/>
                                  <select name="cost_goods_account" id="cost_goods_account" class="col-md-12">
                                      @foreach(list_parent('63') as $cost_goods_account)
                                        @if($cost_goods_account->name == 'HARGA POKOK PENJUALAN'.' '.$sales_return->product->main_product->family->name)
                                        <option value="{{ $cost_goods_account->id}}">{{ $cost_goods_account->account_number }}&nbsp;&nbsp;{{ $cost_goods_account->name}}</option>
                                        @endif
                                      @endforeach
                                  </select>
                                </td>
                                <td>{{ $sales_return->product->main_product->family->name }}</td>
                                <td>{{ $sales_return->product->name }}</td>
                                <td>{{ $sales_return->product->description }}</td>
                                <td>{{ $sales_return->product->main_product->unit->name }}</td>
                                <td class="salesed_qty">
                                    {{ \DB::table('product_sales_order')->select('quantity')->where('product_id',$sales_return->product_id)->where('sales_order_id',$sales_return->sales_order_id)->value('quantity') }}
                                </td>
                                <td>{{ $sales_return->product->main_product->category->name }}</td>
                                <td>
                                  {{ number_format(\DB::table('product_sales_order')->select('price_per_unit')->where('product_id',$sales_return->product_id)->where('sales_order_id',$sales_return->sales_order_id)->value('price_per_unit')) }}
                                </td>
                                <td>
                                  {{ number_format(\DB::table('product_sales_order')->select('price')->where('product_id',$sales_return->product_id)->where('sales_order_id',$sales_return->sales_order_id)->value('price')) }}
                                </td>
                                <td>
                                    {{ Form::text('quantity',null,['class'=>'returned_quantity form-control']) }}
                                </td>
                                <td>
                                    {{ Form::text('notes',null,['class'=>'notes form-control']) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-md-3"><strong>SO Reference</strong></div>
                        <div class="col-md-1">:</div>
                        <div class="col-md-3">
                            <p>{{ $sales_return->sales_order->code }}</p>
                        </div>
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-md-3"><strong>Status</strong></div>
                        <div class="col-md-1">:</div>
                        <div class="col-md-3">
                            <p>{{ strtoupper($sales_return->status) }}</p>
                        </div>
                    </div><!-- /.row -->
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
                    <div class="form-group">
                        {!! Form::label('','',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            <a href="{{ url('sales-return') }}" class="btn btn-default">
                                <i class="fa fa-repeat"></i>&nbsp;Cancel
                            </a>
                            <button type="submit" class="btn btn-info" id="btn-submit-sales-return">
                                <i class="fa fa-save"></i>&nbsp;Submit
                            </button>
                        </div>
                    </div>
                    {!! Form::hidden('sales_return_id',$sales_return->id) !!}
                    {!! Form::hidden('sales_order_invoice_id',$sales_return->sales_order->sales_order_invoice->id) !!}
                    {!! Form::hidden('sales_return_price_per_unit',\DB::table('product_sales_order')->select('price_per_unit')->where('product_id',$sales_return->product_id)->where('sales_order_id',$sales_return->sales_order_id)->value('price_per_unit')) !!}
                    {!! Form::hidden('quantity_first',$sales_return->quantity) !!}
                    {!! Form::close() !!}
                </div><!-- /.box-footer -->
            </div>
        </div>
    </div>
@endsection
