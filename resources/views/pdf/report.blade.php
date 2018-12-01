<!DOCTYPE html>
<html lang="en">

<head>
    <meta http:equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <!-- Bootstrap Core CSS -->
    {!! Html::style('css/bootstrap/bootstrap.css') !!}
<style>
    *{
        padding: 0;
        margin: 0;
    }
    .container{
        padding: 20px;
    }
    h1,h4{
        text-align: center;
    }
    th{
        text-align:center;
        font-size:10pt;
    }
    table td{
        font-size:9pt;
        padding-left:3px;
    }
</style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h1 class="box-title">CATRA<small>TEXTILE</small></h1>
                        <h4>{{ $sort_report_type }}</h4>
                        <h4 style="line-height:1.7">
                            <?php
                            $start_datenya = date_create($sort_start_date);
                            $end_datenya = date_create($sort_end_date);
                            ?>
                            Tanggal&nbsp;{{  date_format($start_datenya,'d-m-Y') }}&nbsp;s/d&nbsp;{{ date_format($end_datenya,'d-m-Y') }}
                        </h4>
                    </div>
                    <div class="box-body">
                        @if($sort_report_type == 'Ringkasan Penjualan')
                        <div class="table-responsive">
                            <br>
                            <table border="1" style="width:100%">
                                <thead>
                                    <tr style="">
                                        <th style="">Invoice</th>
                                        <th style="">Date</th>
                                        <th style="">Customer</th>
                                        <th style="">Sub Total</th>
                                        <th style="">Disc(%)</th>
                                        <th style="">Tax(%)</th>
                                        <th style="">Nilai</th>
                                        <th style="">Retur</th>
                                        <th style="">Net</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sum_bill_price = 0; $sum_return_price = 0; $sum_netto_price = 0; ?>
                                    @foreach($data_report as $d_i)
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
                                            <td>{{ $d_i['tax'] }}</td>
                                            <td align="right" style="padding-right:3px">
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
                                                <td align="right" style="padding-right:3px">
                                                    {{ number_format($sum) }}
                                                    <?php $sum_return_price += $sum; ?>
                                                </td>

                                            <td align="right" style="padding-right:3px">
                                                {{ number_format($d_i['net']-$sum) }}
                                                <?php $sum_netto_price += $d_i['net']-$sum; ?>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" align="right" style="padding-right:3px">Total</td>
                                        <td style="padding-right:3px" align="right">{{ number_format($sum_bill_price) }}</td>
                                        <td style="padding-right:3px" align="right">{{ number_format($sum_return_price) }}</td>
                                        <td style="padding-right:3px" align="right">{{ number_format($sum_netto_price) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @elseif($sort_report_type == 'Detail Penjualan')
                        <div class="table-responsive">
                            <br>
                            <table border="1" style="width:100%">
                                <thead>
                                    <tr style="">
                                        <th>No.Faktur</th>
                                        <th>Tgl Faktur</th>
                                        <th>Customer</th>
                                        <th>Item</th>
                                        <th>Unit Price</th>
                                        <th>Qty</th>
                                        <th>Disc(%)</th>
                                        <th>Disc(AMT)</th>
                                        <th>Line Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sum_bill_price = 0; ?>
                                    @foreach($data_report as $d_i)
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
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format($d_i['line_total']) }}
                                                <?php $sum_bill_price += $d_i['line_total']; ?>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="8" align="right" style="padding-right:3px">Total</td>
                                        <td align="right" style="padding-right:3px">{{ number_format($sum_bill_price) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @elseif($sort_report_type == 'Ringkasan Return Penjualan')
                        <div class="table-responsive">
                            <br>
                            <table border="1" style="width:100%">
                                <thead>
                                    <tr style="">
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
                                    @foreach($data_report as $d_i)
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
                                            <td align="right" style="padding-right:3px">- {{ number_format($sum) }}</td>
                                            <td>{{ $d_i['return'] }}</td>
                                            <td align="right" style="padding-right:3px">
                                                - {{ number_format($sum) }}
                                                <?php $sum_bill_price += $sum-$d_i['return']; ?>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" align="right" style="padding-right:3px">Total</td>
                                        <td align="right" style="padding-right:3px">- {{ number_format($sum_bill_price) }}</td>
                                        <td align="right" style="padding-right:3px">Net</td>
                                        <td align="right" style="padding-right:3px">- {{ number_format($sum_bill_price) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @elseif($sort_report_type == 'Detail Return Penjualan')
                        <div class="table-responsive">
                            <br>
                            <table border="1" style="width:100%">
                                <thead>
                                    <tr style="">
                                        <th>No.Faktur</th>
                                        <th>Tgl Faktur</th>
                                        <th>Customer</th>
                                        <th>Item</th>
                                        <th>Unit Price</th>
                                        <th>Qty</th>
                                        <th>Disc(%)</th>
                                        <th>Disc(amt)</th>
                                        <th>Line Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sum_bill_price = 0; ?>
                                    @foreach($data_report as $d_i)
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
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format($d_i['unit_price']*$d_i['quantity']) }}
                                                <?php $sum_bill_price += $d_i['unit_price']*$d_i['quantity']; ?>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="8" align="right" style="padding-right:3px">Total</td>
                                        <td align="right" style="padding-right:3px">{{ number_format($sum_bill_price) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @elseif($sort_report_type == 'Ringkasan Pembelian')
                        <div class="table-responsive">
                            <br>
                            <table border="1" style="width:100%">
                                <thead>
                                    <tr style="">
                                        <th>Invoice</th>
                                        <th style="width:10%">Date</th>
                                        <th>Customer</th>
                                        <th>Sub Total</th>
                                        <th>Disc(%)</th>
                                        <th>Tax(%)</th>
                                        <th>Nilai Faktur</th>
                                        <th>Retur</th>
                                        <th>Net</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sum_bill_price = 0; $sum_return_price = 0; $sum_netto_price = 0; ?>
                                    @foreach($data_report as $d_i)
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
                                            <td align="right" style="padding-right:3px">
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
                                                <td align="right" style="padding-right:3px">
                                                    {{ number_format($sum) }}
                                                    <?php $sum_return_price += $sum; ?>
                                                </td>
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format($d_i['net']-$sum) }}
                                                <?php $sum_netto_price += $d_i['net']-$sum; ?>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" align="right" style="padding-right:3px">Total</td>
                                        <td align="right" style="padding-right:3px">{{ number_format($sum_bill_price) }}</td>
                                        <td align="right" style="padding-right:3px">{{ number_format($sum_return_price) }}</td>
                                        <td align="right" style="padding-right:3px">{{ number_format($sum_netto_price) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @elseif($sort_report_type == 'Detail Pembelian')
                        <div class="table-responsive">
                            <br>
                            <table border="1" style="width:100%">
                                <thead>
                                    <tr style="">
                                        <th>No.Faktur</th>
                                        <th>Tgl Faktur</th>
                                        <th>Supplier</th>
                                        <th>Item</th>
                                        <th>Unit Price</th>
                                        <th>Qty</th>
                                        <th>Disc(%)</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sum_bill_price = 0; ?>
                                    @foreach($data_report as $d_i)
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
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format($d_i['price']) }}
                                                <?php $sum_bill_price += $d_i['price']; ?>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7" align="right" style="padding-right:3px">Total</td>
                                        <td align="right" style="padding-right:3px">{{ number_format($sum_bill_price) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @elseif($sort_report_type == 'Ringkasan Return Pembelian')
                        <div class="table-responsive">
                            <br>
                            <table border="1" style="width:100%">
                                <thead>
                                    <tr style="">
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
                                    @foreach($data_report as $d_i)
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
                                            <td align="right" style="padding-right:3px">- {{ number_format($sum) }}</td>
                                            <td align="right" style="padding-right:3px">{{ $d_i['return'] }}</td>
                                            <td align="right" style="padding-right:3px">
                                                - {{ number_format($sum) }}
                                                <?php $sum_bill_price += $sum-$d_i['return']; ?>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" align="right" style="padding-right:3px">Total</td>
                                        <td align="right" style="padding-right:3px">- {{ number_format($sum_bill_price) }}</td>
                                        <td align="right" style="padding-right:3px">Net</td>
                                        <td align="right" style="padding-right:3px">- {{ number_format($sum_bill_price) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @elseif($sort_report_type == 'Detail Return Pembelian')
                        <div class="table-responsive">
                            <br>
                            <table border="1" style="width:100%">
                                <thead>
                                    <tr style="">
                                        <th>No.Faktur</th>
                                        <th>Tgl Faktur</th>
                                        <th>Supplier</th>
                                        <th>Item</th>
                                        <th>Unit Price</th>
                                        <th>Qty</th>
                                        <th style="width:10%">Disc(%)</th>
                                        <th style="width:10%">Disc(amt)</th>
                                        <th>Line Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sum_bill_price = 0; ?>
                                    @foreach($data_report as $d_i)
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
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format($d_i['unit_price']*$d_i['quantity']) }}
                                                <?php $sum_bill_price += $d_i['unit_price']*$d_i['quantity']; ?>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="8" align="right" style="padding-right:3px">Total</td>
                                        <td align="right" style="padding-right:3px">{{ number_format($sum_bill_price) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @elseif($sort_report_type == 'Rincian Penjualan per Pelanggan')
                        <div class="table-responsive">
                            <br>
                            <table border="1" style="width:100%">
                                <thead>
                                    <tr style="">
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
                                    <?php $sum_bill_price = 0; $sum_netto_price = 0; $sum_return = 0; ?>
                                    @foreach($data_report as $d_i)
                                        <tr>
                                            <td>{{ $d_i['no_faktur'] }}</td>
                                            <td>
                                                <?php
                                                    $datenya = date_create($d_i['tgl_faktur']);
                                                ?>
                                                {{ date_format($datenya,'d-m-Y') }}
                                            </td>
                                            <td>{{ $d_i['keterangan'] }}</td>
                                            <td align="right" style="padding-right:3px">
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
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format($sum) }}
                                                <?php $sum_return += $sum; ?>
                                            </td>
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format($d_i['netto']-$sum) }}
                                                <?php $sum_netto_price += $d_i['netto']-$sum; ?>
                                            </td>
                                            <td>{{ $d_i['customer'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" align="right" style="padding-right:3px">Total</td>
                                        <td align="right" style="padding-right:3px">{{ number_format($sum_bill_price) }}</td>
                                        <td align="right" style="padding-right:3px">{{ number_format($sum_return) }}</td>
                                        <td align="right" style="padding-right:3px">{{ number_format($sum_netto_price) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @elseif($sort_report_type == 'Penjualan per Pelanggan')
                        <div class="table-responsive">
                            <br>
                            <table border="1" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Nilai Faktur</th>
                                        <th>Return</th>
                                        <th>Net</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sum_bill_price = 0; $sum_return_price = 0; $sum_netto_price = 0; ?>
                                    @foreach($data_report as $d_i)
                                        <tr>
                                            <td>{{ $d_i['customer'] }}</td>
                                            <td align="right" style="padding-right:3px">
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
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format($sum) }}
                                                <?php $sum_return_price += $sum; ?>
                                            </td>
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format($d_i['nilai_faktur']-$sum) }}
                                                <?php $sum_netto_price += $d_i['nilai_faktur']-$sum; ?>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td align="right" style="padding-right:3px">Total</td>
                                        <td align="right" style="padding-right:3px">{{ number_format($sum_bill_price) }}</td>
                                        <td align="right" style="padding-right:3px">{{ number_format($sum_return_price) }}</td>
                                        <td align="right" style="padding-right:3px">{{ number_format($sum_netto_price) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
