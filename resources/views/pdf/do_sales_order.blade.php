<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ $sales_order->code }}</title>
    <!-- Bootstrap Core CSS -->
    {!! Html::style('css/bootstrap/bootstrap.css') !!}
    <style>
        p{
            font-size:8pt;
        }
        hr{
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
        #data-sales-order td{
            padding-left: 3px;
        }
        th{
            text-align: center;
        }
        td{
            font-size:8pt;
        }
        hr{
          margin-top:5px;
          border:1px solid black;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
          <center>
            <h2>PT.CATRA TEXTILE RAYA</h2>
            <h5>Green Sedayu Bizpark DM 5 No.12 Jl.Daan Mogot KM 18 Kalideres - Jakarta Barat</h5>
            <h5>Telp. 021-22522283, 021-22522334</h5>
          </center>
          <hr>
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
                        <h2>SURAT JALAN</h2>
                    </td>
                </tr>
            </table> -->
        </div>
        <br/>
        <div class="row">
            <table style="width:100%" border="0">
                <tr>
                    <td rowspan="4" style="width:10%;vertical-align:top">Ship To</td>
                    <td rowspan="4" style="width:2%;vertical-align:top">:</td>
                    <td rowspan="4" style="width:46%;vertical-align:top">
                        <div class="box-attention" style="height:70px;padding:2px">
                            <p>{{ $sales_order->customer->name }}</p>
                            <p>{{ $sales_order->customer->address }}</p>
                        </div>
                    </td>
                    <td style="width:15%;padding-left:30px">Delivery No</td>
                    <td style="width:2%">:</td>
                    <td style="width:25%">{{ $sales_order->code }}</td>
                </tr>
                <tr>
                    <td style="width:15%;padding-left:30px">Delivery Date</td>
                    <td>:</td>
                    <td>{{ $sales_order->ship_date }}</td>
                </tr>
                <tr>
                    <td style="width:15%;padding-left:30px">Terms</td>
                    <td>:</td>
                    <td style="display:none">
                        <?php
                            $date = date_create($sales_order->created_at);
                            date_add($date,date_interval_create_from_date_string($invoice_term->day_many.' days'));
                        ?>
                        {{ date_format($date,"Y-m-d h:i:s") }}&nbsp;&nbsp;(&nbsp;{{ $invoice_term->name }}&nbsp;)
                    </td>
                    <td>CASH</td>
                </tr>
                <tr>
                    <td style="width:15%;padding-left:30px">Ship Via</td>
                    <td>:</td>
                    <td>{{ $sales_order->driver->name }}&nbsp;&nbsp;{{ $sales_order->vehicle->number_of_vehicle }}</td>
                </tr>
            </table>
        </div>
        <br/>
        <div class="row">
            <table style="width:100%" border="1" id="data-sales-order">
                <thead>
                    <tr>
                        <th style="width:5%">No</th>
                        <th style="width:25%">Kode Barang</th>
                        <th style="width:25%">Keterangan Barang</th>
                        <th style="width:10%">Jumlah</th>
                        <th style="width:10%">Satuan</th>
                        <th style="width:25%">Keterangan</th>
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
                        <td>{{ $sales_order->note }}</td>
                    </tr>
                    @if($product->main_product->unit->name)
                        <?php $mtr += $product->pivot->quantity; ?>
                    @endif
                    @endforeach
                    <tr>
                        <td colspan="5" style="text-align:right">
                            Total Roll : {{ $no-1 }}
                        </td>
                        <td style="text-align:center">Total Meter : {{ $mtr }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br/>
        <div class="row">
            <table style="width:100%">
                <tbody>
                    <tr>
                        <td style="height:40px;width:15%;vertical-align:top"><p>Pembuat</p></td>
                        <td style="height:40px;width:15%;vertical-align:top"><p>Disetujui</p></td>
                        <td style="height:40px;width:15%;vertical-align:top"><p>Pengirim</p></td>
                        <td style="height:40px;width:15%;vertical-align:top"><p>Penerima</p></td>
                        <td rowspan="2" style="width:40%;height:40px">
                            <div class="box-attention">
                            <p>PERHATIAN!!!</p>
                            <p>*Barang yang sudah dibeli tidak dapat ditukar atau</p>
                            <p>dikembalikan</p>
                            <p>*Pesanan barang jika diambil dalam tempo 2</p>
                            <p>bulan bukan menjadi tanggung jawab kami dan uang</p>
                            <p>muka dianggap hangus</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><hr><p>Date :</p></td>
                        <td><hr><p>Date :</p></td>
                        <td><hr><p>Date :</p></td>
                        <td><hr><p>Date :</p></td>
                    </tr>
                </tbody>
            </table>
            <p>Surat Jalan</p>
        </div>
    </div>
</body>
</html>
