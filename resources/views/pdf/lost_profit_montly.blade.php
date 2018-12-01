<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Lost &amp; Profit</title>
    <!-- Bootstrap Core CSS -->
    {!! Html::style('css/bootstrap/bootstrap.css') !!}
    <style>
        *{
            padding: 0;
            margin: 0;
        }
        .container{
            padding: 50px;
        }
        h1,h4{
            text-align: center;
        }
        td{
            font-size: 10pt;
        }
        th{
            text-align: center;
        }
        table tr .parent{
            color: navy;
            padding-left: 20px;
        }
        table tr .sub_child{
            padding-left: 40px;
        }
        table tr .total_child{
            color: navy;
            padding-left: 40px;
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
                        <h4>LABA RUGI</h4>
                        <h4 style="line-height:1.7">
                            @if(isset($sort_target_y))
                                Tahun&nbsp;{{ $sort_target_year }}
                            @elseif(isset($sort_target_m))
                              <?php $month = ''; ?>
                              <?php $months = ''; ?>
                              @if($month_start == 01)
                                <?php $month = 'January'; ?>
                              @elseif($month_start == 02)
                                <?php $month = 'February'; ?>
                              @elseif($month_start == 03)
                                <?php $month = 'March'; ?>
                              @elseif($month_start == 04)
                                <?php $month = 'April'; ?>
                              @elseif($month_start == 05)
                                <?php $month = 'May'; ?>
                              @elseif($month_start == 06)
                                <?php $month = 'June'; ?>
                              @elseif($month_start == 07)
                                <?php $month = 'July'; ?>
                              @elseif($month_start == 08)
                                <?php $month = 'August'; ?>
                              @elseif($month_start == 09)
                                <?php $month = 'September'; ?>
                              @elseif($month_start == 10)
                                <?php $month = 'October'; ?>
                              @elseif($month_start == 11)
                                <?php $month = 'November'; ?>
                              @elseif($month_start == 12)
                                <?php $month = 'December'; ?>
                              @endif

                              @if($month_end == 01)
                                <?php $months = 'January'; ?>
                              @elseif($month_end == 02)
                                <?php $months = 'February'; ?>
                              @elseif($month_end == 03)
                                <?php $months = 'March'; ?>
                              @elseif($month_end == 04)
                                <?php $months = 'April'; ?>
                              @elseif($month_end == 05)
                                <?php $months = 'May'; ?>
                              @elseif($month_end == 06)
                                <?php $months = 'June'; ?>
                              @elseif($month_end == 07)
                                <?php $months = 'July'; ?>
                              @elseif($month_end == 08)
                                <?php $months = 'August'; ?>
                              @elseif($month_end == 09)
                                <?php $months = 'September'; ?>
                              @elseif($month_end == 10)
                                <?php $months = 'October'; ?>
                              @elseif($month_end == 11)
                                <?php $months = 'November'; ?>
                              @elseif($month_end == 12)
                                <?php $months = 'December'; ?>
                              @endif
                                Bulan&nbsp;{{ $month }}&nbsp;Tahun&nbsp;{{ $year_start}}&nbsp;sampai&nbsp;Bulan&nbsp;{{ $months}}&nbsp;Tahun&nbsp;{{ $year_end}}
                            @endif
                        </h4>
                    </div>
                    <br/>
                    <div class="box-body">
                        <table class="table-responsive table">
                            <thead>
                                <tr>
                                    <th>No. Akun</th>
                                    <th>Deskripsi</th>
                                    <th>Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sum_pendapatan_operasional = 0;
                                    $sum_harga_pokok_penjualan = 0;
                                    $sum_beban_operasi = 0;
                                ?>
                                @foreach($chart_account as $pendapatan)
                                    <?php $sum_return = 0; $sum_penjualan = 0; ?>
                                    @if($pendapatan->id == 61)
                                    <tr>
                                        <td></td>
                                        <td><b>{{ $pendapatan->name}}</b></td>
                                        <td></td>
                                    </tr>
                                    @foreach(list_parent('61') as $as)
                                        @if($as->level == 1)
                                        <tr>
                                            <td style="padding-left:20px;">{{ $as->account_number }}</td>
                                            <td style="padding-left:20px;">{{ $as->name}}</td>
                                            <td></td>
                                        </tr>
                                        @endif
                                        @foreach(list_child('2',$as->id) as $sub)
                                        <tr>
                                            <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                            <td style="padding-left:40px;">{{ $sub->name}}</td>
                                            @if(isset($sort_target_y))
                                                <td align="right">
                                                @if(list_transaction_pendapatan($sub->id,$sort_target_year,$sort_target_y,'') == '')
                                                  0.00
                                                @else
                                                  {{number_format(list_transaction_pendapatan($sub->id,$sort_target_year,$sort_target_y,''))}}.00
                                                  <?php $sum_penjualan += sum_penjualan($sub->id,$sort_target_year,$sort_target_y,'','PENJUALAN'); ?>
                                                  <?php $sum_return += sum_penjualan($sub->id,$sort_target_year,$sort_target_y,'','RETURN PENJUALAN'); ?>
                                                @endif
                                                </td>
                                            @elseif(isset($sort_target_m))
                                                <td align="right">
                                                @if(list_transaction_pendapatan($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59') == '')
                                                  0.00
                                                @else
                                                  {{ number_format(list_transaction_pendapatan($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59')) }}.00
                                                  <?php $sum_penjualan += sum_penjualan($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59','PENJUALAN'); ?>
                                                  <?php $sum_return += sum_penjualan($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59','RETURN PENJUALAN'); ?>
                                                @endif
                                                </td>
                                            @else
                                                <td align="right">
                                                @if(list_transaction_pendapatan($sub->id,date('Y'),'y','') == '')
                                                  0.00
                                                @else
                                                  {{ number_format(list_transaction_pendapatan($sub->id,date('Y'),'y','')) }}.00
                                                  <?php $sum_penjualan += sum_penjualan($sub->id,date('Y'),'y','','PENJUALAN'); ?>
                                                  <?php $sum_return += sum_penjualan($sub->id,date('Y'),'y','','RETURN PENJUALAN'); ?>
                                                @endif
                                                </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td style="border-top:1px solid black">Total {{ $pendapatan->name }}</td>
                                        <td align="right" style="border-top:1px solid black;">
                                          <?php $sum_pendapatan_operasional = $sum_penjualan-$sum_return; ?>
                                          @if($sum_pendapatan_operasional == 0 )
                                            0.00
                                          @else
                                            {{ number_format($sum_penjualan-$sum_return)}}.00
                                          @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                @foreach($chart_account as $harga_pokok_penjualan)
                                    <?php $sum = 0; ?>
                                    @if($harga_pokok_penjualan->id == 63)
                                    <tr>
                                        <td></td>
                                        <td><b>{{ $harga_pokok_penjualan->name}}</b></td>
                                        <td></td>
                                    </tr>
                                    @foreach(list_parent('63') as $as)
                                        @if($as->level == 1)
                                        <tr>
                                            <td style="padding-left:20px;">{{ $as->account_number }}</td>
                                            <td style="padding-left:20px;">{{ $as->name}}</td>
                                            <td></td>
                                        </tr>
                                        @endif
                                        @foreach(list_child('2',$as->id) as $sub)
                                        <tr>
                                            <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                            <td style="padding-left:40px;">{{ $sub->name}}</td>
                                            @if(isset($sort_target_y))
                                                <td align="right">
                                                @if(list_transaction_harga_pokok_penjualan($sub->id,$sort_target_year,$sort_target_y,'') == '')
                                                  0.00
                                                @else
                                                  {{ number_format(list_transaction_harga_pokok_penjualan($sub->id,$sort_target_year,$sort_target_y,'')) }}.00
                                                  <?php $sum += sum_penjualan($sub->id,$sort_target_year,$sort_target_y,'','HARGA POKOK PENJUALAN'); ?>
                                                @endif
                                                </td>
                                            @elseif(isset($sort_target_m))
                                                <td align="right">
                                                @if(list_transaction_harga_pokok_penjualan($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59') == '')
                                                  0.00
                                                @else
                                                  {{ number_format(list_transaction_harga_pokok_penjualan($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59')) }}.00
                                                  <?php $sum += sum_penjualan($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59','HARGA POKOK PENJUALAN'); ?>
                                                @endif
                                                </td>
                                            @else
                                                <td align="right">
                                                @if(list_transaction_harga_pokok_penjualan($sub->id,date('Y'),'y','') == '')
                                                  0.00
                                                @else
                                                  {{ number_format(list_transaction_harga_pokok_penjualan($sub->id,date('Y'),'y','')) }}.00
                                                  <?php $sum += sum_penjualan($sub->id,date('Y'),'y','','HARGA POKOK PENJUALAN'); ?>
                                                @endif
                                                </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td style="border-top:1px solid black">Total {{ $harga_pokok_penjualan->name }}</td>
                                        <td align="right" style="border-top:1px solid black;">
                                          <?php $sum_harga_pokok_penjualan = $sum; ?>
                                          @if($sum == 0)
                                            0.00
                                          @else
                                            {{ number_format($sum) }}.00
                                          @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                @foreach($chart_account as $beban_operasi)
                                    <?php $sum_biaya_operasi = 0; $sum_biaya_penyusutan = 0; ?>
                                    @if($beban_operasi->id == 64)
                                    <tr>
                                        <td></td>
                                        <td><b>{{ $beban_operasi->name}}</b></td>
                                        <td></td>
                                    </tr>
                                    @foreach(list_parent('64') as $as)
                                        @if($as->level == 1)
                                        <tr>
                                            <td style="padding-left:20px;">{{ $as->account_number }}</td>
                                            <td style="padding-left:20px;">{{ $as->name}}</td>
                                            <td></td>
                                        </tr>
                                        @endif
                                        @foreach(list_child('2',$as->id) as $sub)
                                        <tr>
                                            <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                            <td style="padding-left:40px;">{{ $sub->name}}</td>
                                            @if(isset($sort_target_y))
                                                <td align="right">
                                                @if(list_transaction_beban_operasi($sub->id,$sort_target_year,$sort_target_y,'') == '')
                                                  0.00
                                                @else
                                                  {{ number_format(list_transaction_beban_operasi($sub->id,$sort_target_year,$sort_target_y,'')) }}.00
                                                  <?php $sum_biaya_operasi += sum_penjualan($sub->id,$sort_target_year,$sort_target_y,'','BIAYA OPERASIONAL'); ?>
                                                  <?php $sum_biaya_penyusutan += sum_penjualan($sub->id,$sort_target_year,$sort_target_y,'','BIAYA PENYUSUTAN'); ?>
                                                @endif
                                                </td>
                                            @elseif(isset($sort_target_m))
                                                <td align="right">
                                                @if(list_transaction_beban_operasi($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59') == '')
                                                  0.00</td>
                                                @else
                                                  {{ number_format(list_transaction_beban_operasi($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59')) }}.00
                                                  <?php $sum_biaya_operasi += sum_penjualan($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59','BIAYA OPERASIONAL'); ?>
                                                  <?php $sum_biaya_penyusutan += sum_penjualan($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59','BIAYA PENYUSUTAN'); ?>
                                                @endif
                                                </td>
                                            @else
                                                <td align="right">
                                                @if(list_transaction_beban_operasi($sub->id,date('Y'),'y','') == '')
                                                  0.00
                                                @else
                                                  {{ number_format(list_transaction_beban_operasi($sub->id,date('Y'),'y','')) }}.00
                                                  <?php $sum_biaya_operasi += sum_penjualan($sub->id,date('Y'),'y','','BIAYA OPERASIONAL'); ?>
                                                  <?php $sum_biaya_penyusutan += sum_penjualan($sub->id,date('Y'),'y','','BIAYA PENYUSUTAN'); ?>
                                                @endif
                                                </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td style="border-top:1px solid black">Total {{ $beban_operasi->name }}</td>
                                        <td align="right" style="border-top:1px solid black;">
                                          <?php $sum_beban_operasi = $sum_biaya_operasi+$sum_biaya_penyusutan; ?>
                                          @if($sum_beban_operasi == 0)
                                            0.00
                                          @else
                                            {{ number_format($sum_biaya_operasi+$sum_biaya_penyusutan) }}.00
                                          @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                    <tr>
                                        <td></td>
                                        <td style="border-top:1px solid black">Total Pendapatan</td>
                                        <td align="right" style="border-top:1px solid black;border-bottom:1px solid black;">
                                            <?php $sum_all_pendapatan = ($sum_pendapatan_operasional-$sum_harga_pokok_penjualan)-$sum_beban_operasi; ?>
                                            @if($sum_all_pendapatan == 0)
                                              0.00
                                            @else
                                              {{ number_format(($sum_pendapatan_operasional-$sum_harga_pokok_penjualan)-$sum_beban_operasi) }}.00
                                            @endif
                                        </td>
                                    </tr>
                                <?php
                                    $sum_pendapatan_lainnya = 0;
                                    $sum_beban_lainnya = 0;
                                ?>
                                @foreach($chart_account as $pendapatan_lainnya)
                                    <?php $sum_pend_lainnya = 0; ?>
                                    @if($pendapatan_lainnya->id == 62)
                                    <tr>
                                        <td></td>
                                        <td><b>{{ $pendapatan_lainnya->name}}</b></td>
                                        <td></td>
                                    </tr>
                                    @foreach(list_parent('62') as $as)
                                        @if($as->level == 1)
                                        <tr>
                                            <td style="padding-left:20px;">{{ $as->account_number }}</td>
                                            <td style="padding-left:20px;">{{ $as->name}}</td>
                                            <td></td>
                                        </tr>
                                        @endif
                                        @foreach(list_child('2',$as->id) as $sub)
                                        <tr>
                                            <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                            <td style="padding-left:40px;">{{ $sub->name}}</td>
                                            @if(isset($sort_target_y))
                                                <td align="right">
                                                @if(list_transaction_pendapatan_lainnya($sub->id,$sort_target_year,$sort_target_y,'') == '')
                                                  0.00
                                                @else
                                                  {{ number_format(list_transaction_pendapatan_lainnya($sub->id,$sort_target_year,$sort_target_y,'')) }}.00
                                                  <?php $sum_pend_lainnya += sum_penjualan($sub->id,$sort_target_year,$sort_target_y,'','BIAYA OPERASIONAL'); ?>
                                                @endif
                                                </td>
                                            @elseif(isset($sort_target_m))
                                                <td align="right">
                                                @if(list_transaction_pendapatan_lainnya($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59') == '')
                                                  0.00
                                                @else
                                                  {{ number_format(list_transaction_pendapatan_lainnya($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59')) }}.00
                                                  <?php $sum_pend_lainnya += sum_penjualan($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59','BIAYA OPERASIONAL'); ?>
                                                @endif
                                                </td>
                                            @else
                                                <td align="right">
                                                @if(list_transaction_pendapatan_lainnya($sub->id,date('Y'),'y','') == '')
                                                  0.00
                                                @else
                                                  {{ number_format(list_transaction_pendapatan_lainnya($sub->id,date('Y'),'y','')) }}.00
                                                  <?php $sum_pend_lainnya += list_transaction_pendapatan_lainnya($sub->id,date('Y'),'y','','BIAYA OPERASIONAL'); ?>
                                                @endif
                                                </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td style="border-top:1px solid black">Total {{ $pendapatan_lainnya->name }}</td>
                                        <td align="right" style="border-top:1px solid black;">
                                          <?php $sum_pendapatan_lainnya = $sum_pend_lainnya; ?>
                                          @if($sum_pendapatan_lainnya == 0)
                                            0.00
                                          @else
                                            {{ number_format($sum_pend_lainnya) }}.00
                                          @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                @foreach($chart_account as $beban_lainnya)
                                    <?php $sum_beb_lainnya = 0; ?>
                                    @if($beban_lainnya->id == 65)
                                    <tr>
                                        <td></td>
                                        <td><b>{{ $beban_lainnya->name}}</b></td>
                                        <td></td>
                                    </tr>
                                    @foreach(list_parent('65') as $as)
                                        @if($as->level == 1)
                                        <tr>
                                            <td style="padding-left:20px;">{{ $as->account_number }}</td>
                                            <td style="padding-left:20px;">{{ $as->name}}</td>
                                            <td></td>
                                        </tr>
                                        @endif
                                        @foreach(list_child('2',$as->id) as $sub)
                                        <tr>
                                            <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                            <td style="padding-left:40px;">{{ $sub->name}}</td>
                                            @if(isset($sort_target_y))
                                                <td align="right">
                                                @if(list_transaction_beban_lainnya($sub->id,$sort_target_year,$sort_target_y,'') == '')
                                                  0.00
                                                @else
                                                  {{ number_format(list_transaction_beban_lainnya($sub->id,$sort_target_year,$sort_target_y,'')) }}.00
                                                  <?php $sum_beb_lainnya += sum_penjualan($sub->id,$sort_target_year,$sort_target_y,'','BIAYA OPERASIONAL'); ?>
                                                @endif
                                                </td>
                                            @elseif(isset($sort_target_m))
                                                <td align="right">
                                                @if(list_transaction_beban_lainnya($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59') == '')
                                                  0.00
                                                @else
                                                  {{ number_format(list_transaction_beban_lainnya($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59')) }}.00
                                                  <?php $sum_beb_lainnya += sum_penjualan($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59','BIAYA OPERASIONAL'); ?>
                                                @endif
                                                </td>
                                            @else
                                                <td align="right">
                                                @if(list_transaction_beban_lainnya($sub->id,date('Y'),'y','') == '')
                                                  0.00
                                                @else
                                                  {{ number_format(list_transaction_beban_lainnya($sub->id,date('Y'),'y','')) }}.00
                                                  <?php $sum_beb_lainnya += sum_penjualan($sub->id,date('Y'),'y','','BIAYA OPERASIONAL'); ?>
                                                @endif
                                                </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td style="border-top:1px solid black">Total {{ $beban_lainnya->name }}</td>
                                        <td align="right" style="border-top:1px solid black;">
                                          <?php $sum_beban_lainnya = $sum_beb_lainnya; ?>
                                          @if($sum_beban_lainnya == 0)
                                            0.00
                                          @else
                                            {{ number_format($sum_beb_lainnya) }}.00
                                          @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td style="border-top:1px solid black">Total Pendapatan Lainnya dan Beban Lainnya</td>
                                    <td align="right" style="border-top:1px solid black;border-bottom:1px solid black;">
                                        <?php $sum_all_pendapatan_lainnya = $sum_pendapatan_lainnya-$sum_beban_lainnya; ?>
                                        @if($sum_all_pendapatan_lainnya == 0)
                                          0.00
                                        @else
                                          {{ number_format($sum_pendapatan_lainnya-$sum_beban_lainnya) }}.00
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="border-top:1px solid black">Laba</td>
                                    <td align="right" style="border-top:1px solid black;border-bottom:1px solid black;">
                                        @if($sum_all_pendapatan+$sum_all_pendapatan_lainnya == 0)
                                          0.00
                                        @else
                                          {{ number_format($sum_all_pendapatan+$sum_all_pendapatan_lainnya) }}.00
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
