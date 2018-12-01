@extends('layouts.app')

@section('page_title')
    Report
@endsection

@section('page_header')
    <h1>
        Report
        <small>Report Search</small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('report') }}"><i class="fa fa-dashboard"></i> Report</a></li>
        <li class="active"><i></i>Index</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        {!! Form::open(['url'=>'report/search','role'=>'form','class'=>'form-horizontal','id'=>'form-search-neraca']) !!}
        <div class="col-lg-12">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3 class="box-title">Search Report</h3>
                    <a data-toggle="collapse" href="#collapse-report" title="Click to search report"><i class="fa fa-arrow-down pull-right"></i></a>
                </div>
                <div class="box-body collapse in" id="collapse-report">
                    <div class="form-group">
                        {!! Form::label('type_report','Report Type',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-6">
                                {!! Form::select('type_report',['0'=>'Ringkasan Penjualan',
                                                                '1'=>'Detail Penjualan',
                                                                '2'=>'Ringkasan Return Penjualan',
                                                                '3'=>'Detail Return Penjualan',
                                                                '4'=>'Ringkasan Pembelian',
                                                                '5'=>'Detail Pembelian',
                                                                '6'=>'Ringkasan Return Pembelian',
                                                                '7'=>'Detail Return Pembelian',
                                                                '8'=>'Rincian Penjualan per Pelanggan',
                                                                '9'=>'Penjualan per Pelanggan'],
                                                                null,['class'=>'form-control','placeholder'=>'Report of Type','id'=>'type_report']) !!}
                            </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('date_start','Date',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <span class="input-group-addon">Start</span>
                                {!! Form::date('date_start',\Carbon\Carbon::now(),['class'=>'form-control','placeholder'=>'Report of Type','id'=>'date_start']) !!}
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <span class="input-group-addon">End</span>
                                {!! Form::date('date_end',\Carbon\Carbon::now()->format('Y-m-d'),['class'=>'form-control','placeholder'=>'Report of Type','id'=>'date_end']) !!}
                                </div>
                            </div>
                    </div>
                    <div class="form-group" id="supplier" style="display:none">
                        {!! Form::label('keyword','Keyword',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-6">
                             <input list='data-supplier' class="form-control" placeholder="Keyword of Supplier" id="text-supplier" autocomplete="off">
                             @if(isset($data_supplier))
                             <datalist id="data-supplier">
                                 @foreach($data_supplier as $key)
                                    <option value="{{ $key['name']}}">
                                 @endforeach
                             </datalist>
                             @endif
                        </div>
                    </div>
                    <div class="form-group" id="customer" style="display:none">
                        {!! Form::label('keyword','Keyword',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-6">
                             <input list='data-customer' class="form-control" placeholder="Keyword of Customer" id="text-customer" autocomplete="off">
                             @if(isset($data_customer))
                             <datalist id="data-customer">
                                 @foreach($data_customer as $key)
                                    <option value="{{ $key['name']}}">
                                 @endforeach
                             </datalist>
                             @endif
                        </div>
                    </div>
                    <div class="form-group" id="product" style="display:none">
                        {!! Form::label('product','Product',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-6">
                             {!! Form::text('product',null,['class'=>'form-control', 'placeholder'=>'Product of report', 'id'=>'product']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('', '', ['class'=>'col-sm-2 control-label']) !!}
                      <div class="col-sm-6">
                        <button type="submit" class="btn btn-info" id="btn-submit-driver">
                          <i class="fa fa-search"></i>&nbsp;Search
                        </button>
                      </div>
                    </div>
                </div>
                <div class="box-footer clearfix">
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    @if(isset($report_type))
    <div class="row">
        <div class="col-lg-12">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        @if($report_type == 0)
                            Ringkasan Penjualan
                        @elseif($report_type == 1)
                            Detail Penjualan
                        @elseif($report_type == 2)
                            Ringkasan Return Penjualan
                        @elseif($report_type == 3)
                            Detail Return Penjualan
                        @elseif($report_type == 4)
                            Ringkasan Pembelian
                        @elseif($report_type == 5)
                            Detail Pembelian
                        @elseif($report_type == 6)
                            Ringkasan Return Pembelian
                        @elseif($report_type == 7)
                            Detail Return Pembelian
                        @elseif($report_type == 8)
                            Rincian Penjualan per Pelanggan
                        @elseif($report_type == 9)
                            Penjualan per Pelanggan
                        @endif
                    </h3>
                </div>
                <div class="box-body">
                        @if($report_type == 0)
                        {!! Form::open(['url'=>'report.report_print','role'=>'form','class'=>'form-horizontal','id'=>'form-search-report','files'=>true]) !!}
                        <div class="form-group pull-right">
                            {!! Form::label('','',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-3">
                                <input type="hidden" name="sort_report_type" value="{{ $report_type }}">
                                <input type="hidden" name="sort_start_date" value="{{ $start_date }}">
                                <input type="hidden" name="sort_end_date" value="{{ $end_date }}">
                                <button type="submit" class="btn btn-default" id="btn-submit-neraca-print" title="click to print">
                                    <i class="fa fa-print"></i>&nbsp;
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="table responsive" style="min-height:500px">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr style="background-color:#3c8dbc;color:white">
                                    <th style="width:10%;">Invoice</th>
                                    <th style="width:10%;">Date</th>
                                    <th style="width:20%;">Customer</th>
                                    <th style="width:10%;">Sub Total</th>
                                    <th style="width:10%;">Disc(%)</th>
                                    <th style="width:10%;">Tax(%)</th>
                                    <th style="width:10%;">Nilai Faktur</th>
                                    <th style="width:10%;">Retur</th>
                                    <th style="width:10%;">Net</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sum_bill_price = 0; $sum_return_price = 0; $sum_netto_price = 0; ?>
                                @foreach($data_invoice as $d_i)
                                    <tr>
                                        <td>{{ $d_i['no_faktur'] }}</td>
                                        <td>
                                            <?php
                                                $datenya = date_create($d_i['tgl_faktur']);
                                            ?>
                                            {{ date_format($datenya,'d-m-Y') }}
                                        </td>
                                        <td>{{ $d_i['customer'] }}</td>
                                        <td>{{ number_format($d_i['sub_total']) }}</td>
                                        <td>{{ $d_i['disc'] }}</td>
                                        <td>{{ $d_i['tax'].'%' }}</td>
                                        <td>
                                            {{ number_format($d_i['bill_price']) }}
                                            <?php $sum_bill_price += $d_i['bill_price']; ?>
                                        </td>
                                            <?php $x = []; $sum = 0;?>
                                            @foreach($d_i['return'] as $kj)

                                                <?php array_push($x,[
                                                    'price_per_unit'=>\DB::table('product_sales_order')->select('price_per_unit')->where('sales_order_id',$kj->sales_order_id)->where('product_id',$kj->product_id)->get()[0]->price_per_unit,
                                                    'quantity'=>$kj->quantity
                                                ]);
                                                ?>
                                            @endforeach
                                            @foreach($x as $xx)
                                                <?php $sum += $xx['price_per_unit']*$xx['quantity']; ?>
                                            @endforeach
                                            <td>
                                                {{ number_format($sum) }}
                                                <?php $sum_return_price += $sum; ?>
                                            </td>

                                        <td>
                                            {{ number_format($d_i['net']-$sum) }}
                                            <?php $sum_netto_price += $d_i['net']-$sum; ?>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" align="right">Total</td>
                                    <td style="background-color:red;color:white">{{ number_format($sum_bill_price) }}</td>
                                    <td style="background-color:red;color:white">{{ number_format($sum_return_price) }}</td>
                                    <td style="background-color:red;color:white">{{ number_format($sum_netto_price) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                        </div>
                        @elseif($report_type == 1)
                        {!! Form::open(['url'=>'report.report_print','role'=>'form','class'=>'form-horizontal','id'=>'form-search-report','files'=>true]) !!}
                        <div class="form-group pull-right">
                            {!! Form::label('','',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-3">
                                <input type="hidden" name="sort_report_type" value="{{ $report_type }}">
                                <input type="hidden" name="sort_start_date" value="{{ $start_date }}">
                                <input type="hidden" name="sort_end_date" value="{{ $end_date }}">
                                <input type="hidden" name="sort_product" value="{{ $product }}">
                                <button type="submit" class="btn btn-default" id="btn-submit-neraca-print" title="click to print">
                                    <i class="fa fa-print"></i>&nbsp;
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="table responsive" style="min-height:500px">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr style="background-color:#3c8dbc;color:white">
                                    <th>No.Faktur</th>
                                    <th>Tgl Faktur</th>
                                    <th>Customer</th>
                                    <th>Item</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Disc(%)</th>
                                    <th>Disc(AMT)</th>
                                    <th>Line Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sum_bill_price = 0; ?>
                                @foreach($data_invoice as $d_i)
                                    <tr>
                                        <td>{{ $d_i['no_faktur'] }}</td>
                                        <td>
                                            <?php
                                                $datenya = date_create($d_i['tgl_faktur']);
                                            ?>
                                            {{ date_format($datenya,'d-m-Y') }}
                                        </td>
                                        <td>{{ $d_i['customer'] }}</td>
                                        <td>{{ $d_i['item'] }}</td>
                                        <td>{{ number_format($d_i['unit_price']) }}</td>
                                        <td>{{ $d_i['quantity'] }}</td>
                                        <td>{{ $d_i['disc'] }}</td>
                                        <td>{{ $d_i['disc_amt'] }}</td>
                                        <td>
                                            {{ number_format($d_i['line_total']) }}
                                            <?php $sum_bill_price += $d_i['line_total']; ?>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="8" align="right">Total</td>
                                    <td style="background-color:red;color:white">{{ number_format($sum_bill_price) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                        </div>
                        @elseif($report_type == 2)
                        {!! Form::open(['url'=>'report.report_print','role'=>'form','class'=>'form-horizontal','id'=>'form-search-report','files'=>true]) !!}
                        <div class="form-group pull-right">
                            {!! Form::label('','',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-3">
                                <input type="hidden" name="sort_report_type" value="{{ $report_type }}">
                                <input type="hidden" name="sort_start_date" value="{{ $start_date }}">
                                <input type="hidden" name="sort_end_date" value="{{ $end_date }}">
                                <button type="submit" class="btn btn-default" id="btn-submit-neraca-print" title="click to print">
                                    <i class="fa fa-print"></i>&nbsp;
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="table responsive" style="min-height:500px">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr style="background-color:#3c8dbc;color:white">
                                    <th style="width:10%;">Invoice</th>
                                    <th style="width:10%;">Date</th>
                                    <th style="width:20%;">Customer</th>
                                    <th style="width:10%;">Sub Total</th>
                                    <th style="width:10%;">Disc(%)</th>
                                    <th style="width:10%;">Tax(%)</th>
                                    <th style="width:10%;">Total</th>
                                    <th style="width:10%;">Retur</th>
                                    <th style="width:10%;">Net</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sum_bill_price = 0; ?>
                                @foreach($data_invoice as $d_i)
                                    <tr>
                                        <td>{{ $d_i['no_faktur'] }}</td>
                                        <td>
                                            <?php
                                                $datenya = date_create($d_i['tgl_faktur']);
                                            ?>
                                            {{ date_format($datenya,'d-m-Y') }}
                                        </td>
                                        <td>{{ $d_i['customer'] }}</td>
                                        <td>{{ $d_i['sub_total'] }}</td>
                                        <td>{{ $d_i['disc'] }}</td>
                                        <td>{{ $d_i['tax'] }}</td>
                                        <?php $x = []; $sum = 0;?>
                                        @foreach($d_i['total'] as $kj)

                                            <?php array_push($x,[
                                                'price_per_unit'=>\DB::table('product_sales_order')->select('price_per_unit')->where('sales_order_id',$kj->sales_order_id)->where('product_id',$kj->product_id)->get()[0]->price_per_unit,
                                                'quantity'=>$kj->quantity
                                            ]);
                                            ?>
                                        @endforeach
                                        @foreach($x as $xx)
                                            <?php $sum += $xx['price_per_unit']*$xx['quantity']; ?>
                                        @endforeach
                                        <td>- {{ number_format($sum) }}</td>
                                        <td>{{ $d_i['return'] }}</td>
                                        <td>
                                            - {{ number_format($sum) }}
                                            <?php $sum_bill_price += $sum-$d_i['return']; ?>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" align="right">Total</td>
                                    <td style="background-color:red;color:white">- {{ number_format($sum_bill_price) }}</td>
                                    <td align="right">Net</td>
                                    <td style="background-color:red;color:white">- {{ number_format($sum_bill_price) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                        </div>
                        @elseif($report_type == 3)
                        {!! Form::open(['url'=>'report.report_print','role'=>'form','class'=>'form-horizontal','id'=>'form-search-report','files'=>true]) !!}
                        <div class="form-group pull-right">
                            {!! Form::label('','',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-3">
                                <input type="hidden" name="sort_report_type" value="{{ $report_type }}">
                                <input type="hidden" name="sort_start_date" value="{{ $start_date }}">
                                <input type="hidden" name="sort_end_date" value="{{ $end_date }}">
                                <input type="hidden" name="sort_product" value="{{ $product }}">
                                <button type="submit" class="btn btn-default" id="btn-submit-neraca-print" title="click to print">
                                    <i class="fa fa-print"></i>&nbsp;
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="table responsive" style="min-height:500px">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr style="background-color:#3c8dbc;color:white">
                                    <th>No.Faktur</th>
                                    <th>Tgl Faktur</th>
                                    <th>Customer</th>
                                    <th>Item</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Disc(%)</th>
                                    <th>Disc(amt)</th>
                                    <th>Line Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sum_bill_price = 0; ?>
                                @foreach($data_invoice as $d_i)
                                    <tr>
                                        <td>{{ $d_i['no_faktur'] }}</td>
                                        <td>
                                            <?php
                                                $datenya = date_create($d_i['tgl_faktur']);
                                            ?>
                                            {{ date_format($datenya,'d-m-Y') }}
                                        </td>
                                        <td>{{ $d_i['customer'] }}</td>
                                        <td>{{ $d_i['item'] }}</td>
                                        <td>{{ number_format($d_i['unit_price']) }}</td>
                                        <td>{{ $d_i['quantity'] }}</td>
                                        <td>{{ $d_i['disc'] }}</td>
                                        <td>{{ $d_i['disc_amt'] }}</td>
                                        <td>
                                            {{ number_format($d_i['unit_price']*$d_i['quantity']) }}
                                            <?php $sum_bill_price += $d_i['unit_price']*$d_i['quantity']; ?>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="8" align="right">Total</td>
                                    <td style="background-color:red;color:white">{{ number_format($sum_bill_price) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                        </div>
                        @elseif($report_type == 4)
                        {!! Form::open(['url'=>'report.report_print','role'=>'form','class'=>'form-horizontal','id'=>'form-search-report','files'=>true]) !!}
                        <div class="form-group pull-right">
                            {!! Form::label('','',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-3">
                                <input type="hidden" name="sort_report_type" value="{{ $report_type }}">
                                <input type="hidden" name="sort_start_date" value="{{ $start_date }}">
                                <input type="hidden" name="sort_end_date" value="{{ $end_date }}">
                                <input type="hidden" name="sort_keyword" value="{{ $keyword }}">
                                <button type="submit" class="btn btn-default" id="btn-submit-neraca-print" title="click to print">
                                    <i class="fa fa-print"></i>&nbsp;
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="table responsive" style="min-height:500px">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr style="background-color:#3c8dbc;color:white">
                                    <th style="width:10%;">Invoice</th>
                                    <th style="width:10%;">Date</th>
                                    <th style="width:20%;">Customer</th>
                                    <th style="width:10%;">Sub Total</th>
                                    <th style="width:10%;">Disc(%)</th>
                                    <th style="width:10%;">Tax(%)</th>
                                    <th style="width:10%;">Nilai Faktur</th>
                                    <th style="width:10%;">Retur</th>
                                    <th style="width:10%;">Net</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sum_bill_price = 0; $sum_return_price = 0; $sum_netto_price = 0; ?>
                                @foreach($data_invoice as $d_i)
                                    <tr>
                                        <td>{{ $d_i['no_faktur'] }}</td>
                                        <td>
                                            <?php
                                                $datenya = date_create($d_i['tgl_faktur']);
                                            ?>
                                            {{ date_format($datenya,'d-m-Y') }}
                                        </td>
                                        <td>{{ $d_i['supplier'] }}</td>
                                        <td>{{ $d_i['sub_total'] }}</td>
                                        <td>{{ $d_i['disc'] }}</td>
                                        <td>{{ $d_i['tax'] }}</td>
                                        <td>
                                            {{ number_format($d_i['bill_price']) }}
                                            <?php $sum_bill_price += $d_i['bill_price']; ?>
                                        </td>
                                            <?php $x = []; $sum = 0;?>
                                            @foreach($d_i['return'] as $kj)

                                                <?php array_push($x,[
                                                    'quantity_purchase_order'=>\DB::table('product_purchase_order')->select('quantity')->where('purchase_order_id',$kj->purchase_order_id)->where('product_id',$kj->product_id)->get()[0]->quantity,
                                                    'price'=>\DB::table('product_purchase_order')->select('price')->where('purchase_order_id',$kj->purchase_order_id)->where('product_id',$kj->product_id)->get()[0]->price,
                                                    'quantity'=>$kj->quantity
                                                ]);
                                                ?>
                                            @endforeach
                                            @foreach($x as $xx)
                                                <?php $sum += (($xx['price']/$xx['quantity_purchase_order'])*$xx['quantity']); ?>
                                            @endforeach
                                            <td>
                                                {{ number_format($sum) }}
                                                <?php $sum_return_price += $sum; ?>
                                            </td>

                                        <td>
                                            {{ number_format($d_i['net']-$sum) }}
                                            <?php $sum_netto_price += $d_i['net']-$sum; ?>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" align="right">Total Net</td>
                                    <td style="background-color:red;color:white">{{ number_format($sum_bill_price) }}</td>
                                    <td style="background-color:red;color:white">{{ number_format($sum_return_price) }}</td>
                                    <td style="background-color:red;color:white">{{ number_format($sum_netto_price) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                        </div>
                        @elseif($report_type == 5)
                        {!! Form::open(['url'=>'report.report_print','role'=>'form','class'=>'form-horizontal','id'=>'form-search-report','files'=>true]) !!}
                        <div class="form-group pull-right">
                            {!! Form::label('','',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-3">
                                <input type="hidden" name="sort_report_type" value="{{ $report_type }}">
                                <input type="hidden" name="sort_start_date" value="{{ $start_date }}">
                                <input type="hidden" name="sort_end_date" value="{{ $end_date }}">
                                <input type="hidden" name="sort_product" value="{{ $product }}">
                                <input type="hidden" name="sort_keyword" value="{{ $keyword }}">
                                <button type="submit" class="btn btn-default" id="btn-submit-neraca-print" title="click to print">
                                    <i class="fa fa-print"></i>&nbsp;
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="table responsive" style="min-height:500px">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr style="background-color:#3c8dbc;color:white">
                                    <th>No.Faktur</th>
                                    <th>Tgl Faktur</th>
                                    <th>Supplier</th>
                                    <th>Item</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Disc(%)</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sum_bill_price = 0; ?>
                                @foreach($data_invoice as $d_i)
                                    <tr>
                                        <td>{{ $d_i['no_faktur'] }}</td>
                                        <td>
                                            <?php
                                                $datenya = date_create($d_i['tgl_faktur']);
                                            ?>
                                            {{ date_format($datenya,'d-m-Y') }}
                                        </td>
                                        <td>{{ $d_i['supplier'] }}</td>
                                        <td>{{ $d_i['item'] }}</td>
                                        <td>{{ number_format($d_i['unit_price']) }}</td>
                                        <td>{{ $d_i['quantity'] }}</td>
                                        <td>{{ $d_i['disc'] }}</td>
                                        <td>
                                            {{ number_format($d_i['price']) }}
                                            <?php $sum_bill_price += $d_i['price']; ?>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7" align="right">Total</td>
                                    <td style="background-color:red;color:white">{{ number_format($sum_bill_price) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                        </div>
                        @elseif($report_type == 6)
                        {!! Form::open(['url'=>'report.report_print','role'=>'form','class'=>'form-horizontal','id'=>'form-search-report','files'=>true]) !!}
                        <div class="form-group pull-right">
                            {!! Form::label('','',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-3">
                                <input type="hidden" name="sort_report_type" value="{{ $report_type }}">
                                <input type="hidden" name="sort_start_date" value="{{ $start_date }}">
                                <input type="hidden" name="sort_end_date" value="{{ $end_date }}">
                                <input type="hidden" name="sort_keyword" value="{{ $keyword }}">
                                <button type="submit" class="btn btn-default" id="btn-submit-neraca-print" title="click to print">
                                    <i class="fa fa-print"></i>&nbsp;
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="table responsive" style="min-height:500px">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr style="background-color:#3c8dbc;color:white">
                                    <th style="width:10%;">Invoice</th>
                                    <th style="width:10%;">Date</th>
                                    <th style="width:20%;">Supplier</th>
                                    <th style="width:10%;">Sub Total</th>
                                    <th style="width:10%;">Disc(%)</th>
                                    <th style="width:10%;">Tax(%)</th>
                                    <th style="width:10%;">Total</th>
                                    <th style="width:10%;">Retur</th>
                                    <th style="width:10%;">Net</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sum_bill_price = 0; ?>
                                @foreach($data_invoice as $d_i)
                                    <tr>
                                        <td>{{ $d_i['no_faktur'] }}</td>
                                        <td>
                                            <?php
                                                $datenya = date_create($d_i['tgl_faktur']);
                                            ?>
                                            {{ date_format($datenya,'d-m-Y') }}
                                        </td>
                                        <td>{{ $d_i['supplier'] }}</td>
                                        <td>{{ $d_i['sub_total'] }}</td>
                                        <td>{{ $d_i['disc'] }}</td>
                                        <td>{{ $d_i['tax'] }}</td>
                                        <?php $x = []; $sum = 0;?>
                                        @foreach($d_i['total'] as $kj)
                                            <?php array_push($x,[
                                                'quantity_purchase_order'=>\DB::table('product_purchase_order')->select('quantity')->where('purchase_order_id',$kj->purchase_order_id)->where('product_id',$kj->product_id)->get()[0]->quantity,
                                                'price'=>\DB::table('product_purchase_order')->select('price')->where('purchase_order_id',$kj->purchase_order_id)->where('product_id',$kj->product_id)->get()[0]->price,
                                                'quantity'=>$kj->quantity
                                            ]);
                                            ?>
                                        @endforeach
                                        @foreach($x as $xx)
                                            <?php $sum += (($xx['price']/$xx['quantity_purchase_order'])*$xx['quantity']); ?>
                                        @endforeach
                                        <td>- {{ number_format($sum) }}</td>
                                        <td>{{ $d_i['return'] }}</td>
                                        <td>
                                            - {{ number_format($sum) }}
                                            <?php $sum_bill_price += $sum-$d_i['return']; ?>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" align="right">Total</td>
                                    <td style="background-color:red;color:white">- {{ number_format($sum_bill_price) }}</td>
                                    <td align="right">Net</td>
                                    <td style="background-color:red;color:white">- {{ number_format($sum_bill_price) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                        </div>
                        @elseif($report_type == 7)
                        {!! Form::open(['url'=>'report.report_print','role'=>'form','class'=>'form-horizontal','id'=>'form-search-report','files'=>true]) !!}
                        <div class="form-group pull-right">
                            {!! Form::label('','',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-3">
                                <input type="hidden" name="sort_report_type" value="{{ $report_type }}">
                                <input type="hidden" name="sort_start_date" value="{{ $start_date }}">
                                <input type="hidden" name="sort_end_date" value="{{ $end_date }}">
                                <input type="hidden" name="sort_product" value="{{ $product }}">
                                <input type="hidden" name="sort_keyword" value="{{ $keyword }}">
                                <button type="submit" class="btn btn-default" id="btn-submit-neraca-print" title="click to print">
                                    <i class="fa fa-print"></i>&nbsp;
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="table responsive" style="min-height:500px">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr style="background-color:#3c8dbc;color:white">
                                    <th>No.Faktur</th>
                                    <th>Tgl Faktur</th>
                                    <th>Supplier</th>
                                    <th>Item</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Disc(%)</th>
                                    <th>Disc(amt)</th>
                                    <th>Line Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sum_bill_price = 0; ?>
                                @foreach($data_invoice as $d_i)
                                    <tr>
                                        <td>{{ $d_i['no_faktur'] }}</td>
                                        <td>
                                            <?php
                                                $datenya = date_create($d_i['tgl_faktur']);
                                            ?>
                                            {{ date_format($datenya,'d-m-Y') }}
                                        </td>
                                        <td>{{ $d_i['supplier'] }}</td>
                                        <td>{{ $d_i['item'] }}</td>
                                        <td>{{ number_format($d_i['unit_price']) }}</td>
                                        <td>{{ $d_i['quantity'] }}</td>
                                        <td>{{ $d_i['disc'] }}</td>
                                        <td>{{ $d_i['disc_amt'] }}</td>
                                        <td>
                                            {{ number_format($d_i['unit_price']*$d_i['quantity']) }}
                                            <?php $sum_bill_price += $d_i['unit_price']*$d_i['quantity']; ?>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="8" align="right">Total</td>
                                    <td style="background-color:red;color:white">{{ number_format($sum_bill_price) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                        </div>
                        @elseif($report_type == 8)
                        {!! Form::open(['url'=>'report.report_print','role'=>'form','class'=>'form-horizontal','id'=>'form-search-report','files'=>true]) !!}
                        <div class="form-group pull-right">
                            {!! Form::label('','',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-3">
                                <input type="hidden" name="sort_report_type" value="{{ $report_type }}">
                                <input type="hidden" name="sort_start_date" value="{{ $start_date }}">
                                <input type="hidden" name="sort_end_date" value="{{ $end_date }}">
                                <input type="hidden" name="sort_keyword" value="{{ $keyword }}">
                                <button type="submit" class="btn btn-default" id="btn-submit-neraca-print" title="click to print">
                                    <i class="fa fa-print"></i>&nbsp;
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="table responsive" style="min-height:500px">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr style="background-color:#3c8dbc;color:white">
                                    <th>No.Faktur</th>
                                    <th>Tgl Faktur</th>
                                    <th>Keterangan</th>
                                    <th>Nilai Faktur</th>
                                    <th>Retur</th>
                                    <th>Netto</th>
                                    <th>Customer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sum_bill_price = 0; $sum_return_price = 0; $sum_netto_price = 0; ?>
                                @foreach($data_invoice as $d_i)
                                    <tr>
                                        <td>{{ $d_i['no_faktur'] }}</td>
                                        <td>
                                            <?php
                                                $datenya = date_create($d_i['tgl_faktur']);
                                            ?>
                                            {{ date_format($datenya,'d-m-Y') }}
                                        </td>
                                        <td>{{ $d_i['keterangan'] }}</td>
                                        <td>
                                            {{ number_format($d_i['bill_price']) }}
                                            <?php $sum_bill_price += $d_i['bill_price']; ?>
                                        </td>
                                        <?php $re = []; $sum = 0; ?>
                                        @foreach($d_i['return'] as $r)
                                            <?php
                                                array_push($re,[
                                                    'quantity'=>$r->quantity,
                                                    'price_per_unit'=>\DB::table('product_sales_order')->select('price_per_unit')->where('sales_order_id',$r->sales_order_id)->where('product_id',$r->product_id)->get()[0]->price_per_unit,
                                                ]);
                                            ?>
                                        @endforeach
                                        @foreach($re as $xx)
                                            <?php $sum += $xx['price_per_unit']*$xx['quantity']; ?>
                                        @endforeach
                                        <td>
                                            {{ number_format($sum) }}
                                            <?php $sum_return_price += $sum; ?>
                                        </td>
                                        <td>
                                            {{ number_format($d_i['netto']-$sum) }}
                                            <?php $sum_netto_price += $d_i['netto']-$sum; ?>
                                        </td>
                                        <td>{{ $d_i['customer'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" align="right">Total</td>
                                    <td style="background-color:red;color:white">{{ number_format($sum_bill_price) }}</td>
                                    <td style="background-color:red;color:white">{{ number_format($sum_return_price) }}</td>
                                    <td style="background-color:red;color:white">{{ number_format($sum_netto_price) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                        </div>
                        @elseif($report_type == 9)
                        {!! Form::open(['url'=>'report.report_print','role'=>'form','class'=>'form-horizontal','id'=>'form-search-report','files'=>true]) !!}
                        <div class="form-group pull-right">
                            {!! Form::label('','',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-3">
                                <input type="hidden" name="sort_report_type" value="{{ $report_type }}">
                                <input type="hidden" name="sort_start_date" value="{{ $start_date }}">
                                <input type="hidden" name="sort_end_date" value="{{ $end_date }}">
                                <input type="hidden" name="sort_keyword" value="{{ $keyword }}">
                                <button type="submit" class="btn btn-default" id="btn-submit-neraca-print" title="click to print">
                                    <i class="fa fa-print"></i>&nbsp;
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="table responsive" style="min-height:500px">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr style="background-color:#3c8dbc;color:white">
                                    <th>Customer</th>
                                    <th>Nilai Faktur</th>
                                    <th>Return</th>
                                    <th>Net</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sum_bill_price = 0; $sum_return_price = 0; $sum_netto_price = 0; ?>
                                @foreach($data_invoice as $d_i)
                                    <tr>
                                        <td>{{ $d_i['customer'] }}</td>
                                        <td>
                                            {{ number_format($d_i['nilai_faktur']) }}
                                            <?php $sum_bill_price += $d_i['nilai_faktur']; ?>
                                        </td>
                                        <?php $re = []; $sum = 0; ?>
                                        <?php
                                        // print_r($d_i['return']);
                                        // exit();
                                        ?>
                                        @foreach($d_i['return'] as $r)
                                            <?php
                                                array_push($re,[
                                                    'quantity'=>$r->quantity,
                                                    'price_per_unit'=>\DB::table('product_sales_order')->select('price_per_unit')->where('sales_order_id',$r->sales_order_id)->where('product_id',$r->product_id)->get()[0]->price_per_unit,
                                                ]);
                                            ?>
                                        @endforeach
                                        @foreach($re as $xx)
                                            <?php $sum += $xx['price_per_unit']*$xx['quantity']; ?>
                                        @endforeach
                                        <td>
                                            {{ number_format($sum) }}
                                            <?php $sum_return_price += $sum; ?>
                                        </td>
                                        <td>
                                            {{ number_format($d_i['nilai_faktur']-$sum) }}
                                            <?php $sum_netto_price += $d_i['nilai_faktur']-$sum; ?>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td align="right">Total</td>
                                    <td style="background-color:red;color:white">{{ number_format($sum_bill_price) }}</td>
                                    <td style="background-color:red;color:white">{{ number_format($sum_return_price) }}</td>
                                    <td style="background-color:red;color:white">{{ number_format($sum_netto_price) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                        </div>
                        @endif
                </div>
                <div class="box-footer clearfix">

                </div>
            </div>
        </div>
    </div>
    @else

    @endif
@endsection

@section('additional_scripts')
    <script type="text/javascript">
        $('#type_report').on('click',function(){
            var reportType = $('#type_report').val();
            if(reportType == 8){
                $('#supplier').hide();
                $('#customer').show();
                $('#product').hide();
                $('#text-customer').attr('name','keyword');
                $('#text-supplier').attr('name','');
            }else if (reportType == 0) {
                $('#customer').hide();
                $('#supplier').hide();
                $('#product').hide();
                $('#text-supplier').attr('name','');
                $('#text-customer').attr('name','');
            }else if (reportType == 1) {
                $('#customer').hide();
                $('#supplier').hide();
                $('#product').hide();
                $('#text-supplier').attr('name','');
                $('#text-customer').attr('name','');
            }else if (reportType == 2) {
                $('#customer').hide();
                $('#supplier').hide();
                $('#product').hide();
                $('#text-supplier').attr('name','');
                $('#text-customer').attr('name','');
            }else if (reportType == 3) {
                $('#customer').hide();
                $('#supplier').hide();
                $('#product').hide();
                $('#text-supplier').attr('name','');
                $('#text-customer').attr('name','');
            }else if (reportType == 4) {
                $('#customer').hide();
                $('#supplier').hide();
                $('#product').hide();
                $('#text-supplier').attr('name','keyword');
                $('#text-customer').attr('name','');
            }else if (reportType == 5) {
                $('#customer').hide();
                $('#supplier').hide();
                $('#product').hide();
                $('#text-supplier').attr('name','keyword');
                $('#text-customer').attr('name','');
            }else if (reportType == 6) {
                $('#customer').hide();
                $('#supplier').hide();
                $('#product').hide();
                $('#text-supplier').attr('name','keyword');
                $('#text-customer').attr('name','');
            }else if (reportType == 7) {
                $('#customer').hide();
                $('#supplier').hide();
                $('#product').hide();
                $('#text-supplier').attr('name','keyword');
                $('#text-customer').attr('name','');
            }else if (reportType == 9) {
                $('#customer').hide();
                $('#supplier').hide();
                $('#product').hide();
                $('#text-supplier').attr('name','');
                $('#text-customer').attr('name','');
            }else{
                $('#customer').hide();
                $('#supplier').show();
                $('#product').hide();
                $('#text-supplier').attr('name','keyword');
                $('#text-customer').attr('name','');
            }
        });
    </script>
@endsection
