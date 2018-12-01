<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ $sales_order_invoice->code  }}</title>
    <!-- Bootstrap Core CSS -->
    {!! Html::style('css/bootstrap/bootstrap.css') !!}
</head>
<style>
    /* TODO CSS Style */
    p{
        font-size:8pt;
    }
    .footer-text{
        width:90%;
        border:1px solid black;
        margin-left: 0px
    }
    .box-attention{
        border:1px solid black;
        padding:5px;
    }
    *{
        padding: 0;
        margin: 0;
    }
    .container{
        padding: 30px;
    }
    #data-invoice td{
        padding-right: 3px;
        padding-left: 3px;
    }
    th{
        text-align: center;
    }
    td{
        font-size:8pt;
    }
    #line-header{
      margin-top:5px;
      border:1px solid black;
    }
</style>

<body>
    <div class="container">
        <div class="row">
            <center>
              <h2>PT.CATRA TEXTILE RAYA</h2>
              <h5>Green Sedayu Bizpark DM 5 No.12 Jl.Daan Mogot KM 18 Kalideres - Jakarta Barat</h5>
              <h5>Telp. 021-22522283, 021-22522334</h5>
            </center>
            <hr id="line-header">
            <!-- <table style="width:100%">
                <tr>
                    <td style="width:30%;vertical-align:top;text-align:left">
                        <h1>CATRA<small>TEXTILE</small></h1>
                    </td>
                    <td style="width:40%;text-align:left">
                        <p>Green Sedayu Bizpark</p>
                        <p>DMS No. 12 Jl. Daan Mogot KM 18</p>
                        <p>Kalideres- Jakarta Barat</p>
                        <p>Telp. 021-22522283, 021-22522334</p>
                    </td>
                    <td style="width:30%;vertical-align:top;text-align:left">
                        <h2>FAKTUR</h2>
                    </td>
                </tr>
            </table> -->
        </div>
        <br/>
        <div class="row">
            <table style="width:100%" border="0">
                <tr>
                    <td rowspan="7" style="width:10%;vertical-align:top">Bill To</td>
                    <td rowspan="7" style="width:2%;vertical-align:top">:</td>
                    <td rowspan="7" style="width:46%;vertical-align:top">
                        <div class="box-attention" style="height:70px;padding:2px">
                            <p>{{ $sales_order->customer->name }}</p>
                            <p>{{ $sales_order->customer->address }}</p>
                        </div>
                    </td>
                    <td style="width:15%;padding-left:30px">Invoice No</td>
                    <td style="width:2%">:</td>
                    <td style="width:25%">{{ $sales_order_invoice->code }}</td>
                </tr>
                <tr>
                    <td style="width:15%;padding-left:30px">Invoice Date</td>
                    <td>:</td>
                    <td>{{ $sales_order_invoice->created_at }}</td>
                </tr>
                <tr>
                    <td style="width:15%;padding-left:30px">Terms</td>
                    <td>:</td>
                    <td>
                        <?php
                            $date = date_create($sales_order->created_at);
                            date_add($date,date_interval_create_from_date_string($invoice_term->day_many.' days'));
                        ?>
                        {{ date_format($date,"Y-m-d h:i:s") }}&nbsp;&nbsp;(&nbsp;{{ $invoice_term->name }}&nbsp;)
                    </td>
                </tr>
                <tr>
                    <td style="width:15%;padding-left:30px">Ship Via</td>
                    <td>:</td>
                    <td>{{ $sales_order->driver->name }}&nbsp;&nbsp;{{ $sales_order->vehicle->number_of_vehicle }}</td>
                </tr>
                <tr>
                    <td style="width:15%;padding-left:30px">Ship Date</td>
                    <td>:</td>
                    <td>{{ $sales_order->ship_date }}</td>
                </tr>
                <tr>
                    <td style="width:15%;padding-left:30px">D.O. No.</td>
                    <td>:</td>
                    <td>{{ $sales_order->code}}</td>
                </tr>
                @if($sales_order_invoice->ppn_hidden == 'yes')

                @else
                  <tr>
                      <td style="width:15%;padding-left:30px">PPN</td>
                      <td>:</td>
                      <td>({{ $sales_order_invoice->persen_ppn}}&nbsp;%)&nbsp;{{ number_format($sales_order_invoice->price_ppn)}}</td>
                  </tr>
                @endif
            </table>
        </div>
        <br/>
        <div class="row">
            <table style="width:100%" border="1" id="data-invoice">
                <thead>
                    <tr>
                        <th style="width:5%">No</th>
                        <th style="width:20%">Kode Barang</th>
                        <th style="width:25%">Keterangan Barang</th>
                        <th style="width:10%">Jumlah</th>
                        <th style="width:10%">Satuan</th>
                        <th style="width:15%">Harga Satuan</th>
                        <th style="width:15%">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; $mtr = 0;?>
                    @foreach($sales_order->products as $product )
                    <tr>
                        <td>{{ $no++.'.' }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td style="text-align:center">{{ $product->pivot->quantity }}</td>
                        <td style="text-align:center">{{ $product->main_product->unit->name }}</td>
                        <td style="text-align:right">{{ number_format($product->pivot->price_per_unit) }}.00</td>
                        <td style="text-align:right">{{ number_format($product->pivot->price) }}.00</td>
                    </tr>
                    @if($product->pivot->price)
                        <?php $mtr += $product->pivot->price; ?>
                    @endif
                    @endforeach
                    <tr>
                        @if($sales_order_invoice->ppn_hidden == 'yes')
                          <td colspan="6" style="text-align:right">
                              Total Invoice
                          </td>
                          <td style="text-align:right"> {{ number_format($mtr) }}.00</td>
                        @else
                          <td colspan="6" style="text-align:right">
                              Total Invoice + PPN
                          </td>
                          <td style="text-align:right"> {{ number_format($mtr+$sales_order_invoice->price_ppn) }}.00</td>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>
        <br/>
        <div class="row">
            <table style="width:100%">
                <tbody>
                    <tr>
                        <td style="height:40px;width:15%;vertical-align:top"><p>Hormat Kami</p></td>
                        <td style="height:40px;width:15%;vertical-align:top"><p>Penerima</p></td>
                        <td style="height:40px;width:15%;vertical-align:top"></td>
                        <td rowspan="2" style="width:55%;height:40px">
                            <div class="box-attention">
                            <p>PERHATIAN!!!</p>
                            <p>*Pembayaran dengan Cek/Giro akan dianggap lunas,</p>
                            <p>jika sudah cair dalam rekening kami</p>
                            <p>BCA KCP Taman Palem Lestari</p>
                            <p>A/C: 757 054 5455</p>
                            <p>A/N: CATUR PUTRA NG</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><hr class="footer-text"><p>Date :</p></td>
                        <td><hr class="footer-text"><p>Date :</p></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <p>Invoice Sales Order</p>
        </div>
    </div>
</body>
</html>
