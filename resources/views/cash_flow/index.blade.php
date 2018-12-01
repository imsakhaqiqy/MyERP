@extends('layouts.app')

@section('page_title')
    Cash Flow
@endsection

@section('page_header')
    <h1>
        Cash Flow
        <small>Cash Flow Search</small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('cash-flow') }}"><i class="fa fa-dashboard"></i> Cash Flow</a></li>
        <li class="active"><i></i>Index</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        {!! Form::open(['url'=>'cash-flow/search','role'=>'form','class'=>'form-horizontal','id'=>'form-search-cash-flow']) !!}
        <div class="col-lg-12">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3 class="box-title">Cash Flow Search</h3>
                    <a data-toggle="collapse" href="#collapse-cash-flow" title="Click to search cash flow"><i class="fa fa-arrow-down pull-right"></i></a>
                </div>
                <div class="box-body collapse" id="collapse-cash-flow">
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
                    <div class="form-group">
                        {!! Form::label('', '', ['class'=>'col-sm-2 control-label']) !!}
                      <div class="col-sm-6">
                        <a href="{{ url('home') }}" class="btn btn-default">
                          <i class="fa fa-repeat"></i>&nbsp;Cancel
                        </a>&nbsp;
                        <button type="submit" class="btn btn-info" id="btn-submit-ledger">
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

    @if(isset($date_start) AND isset($date_end))
    <div class="row">
        <div class="col-lg-12">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    {!! Form::open(['url'=>'cash-flow.cash_flow_print','role'=>'form','class'=>'form-horizontal','id'=>'form-search-neraca','files'=>true]) !!}
                    <center>
                        <h3 class="box-title">CATRA<small>TEXTILE</small></h3>
                        <h4>ARUS KAS</h4>
                        <h4>
                            <?php
                              $date_start_f = date_create($date_start);
                              $date_end_f = date_create($date_end);
                            ?>
                            DARI TANGGAL&nbsp;{{ date_format($date_start_f,'d-m-Y') }}&nbsp;SAMPAI&nbsp;TANGGAL&nbsp;{{ date_format($date_end_f,'d-m-Y') }}
                        </h4>
                    </center>
                    <div class="form-group pull-right">
                        {!! Form::label('','',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            <input type="hidden" name="sort_date_start" id="sort_date_start" value="{{ $date_start }}">
                            <input type="hidden" name="sort_date_end" id="sort_date_end" value="{{ $date_end }}">
                            <input type="hidden" name="sort_lost_profit" id="sort_lost_profit">
                            <input type="hidden" name="sort_akumulasi_penyusutan" id="sort_akumulasi_penyusutan">
                            <input type="hidden" name="sort_akun_hutang" id="sort_akun_hutang">
                            <input type="hidden" name="sort_kewajiban_lancar_lainnya" id="sort_kewajiban_lancar_lainnya">
                            <input type="hidden" name="sort_akun_piutang" id="sort_akun_piutang">
                            <input type="hidden" name="sort_aset_lancar_lainnya" id="sort_aset_lancar_lainnya">
                            <input type="hidden" name="sort_nilai_histori" id="sort_nilai_histori">
                            <input type="hidden" name="sort_kewajiban_jangka_panjang" id="sort_kewajiban_jangka_panjang">
                            <input type="hidden" name="sort_ekuitas" id="sort_ekuitas">
                            <button type="submit" class="btn btn-default" id="btn-submit-neraca-print" title="click to print">
                                <i class="fa fa-print"></i>&nbsp;
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <?php $net_laba = 0; ?>
                        <center>
                            <table class="table table-striped table-hover" id="table-lost-profit" style="width:80%;display:none">
                                <thead>
                                    <tr>
                                        <th style="width:30%">No.Akun</th>
                                        <th style="width:40%">Deskripsi</th>
                                        <th style="width:40%">Amount</th>
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
                                                    @if(list_transaction_pendapatan($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59') == '')
                                                    <td>0,00</td>
                                                    @else
                                                    <td>
                                                        {{ number_format(list_transaction_pendapatan($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59')) }}
                                                        <?php $sum_penjualan += sum_penjualan($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59','PENJUALAN'); ?>
                                                        <?php $sum_return += sum_penjualan($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59','RETURN PENJUALAN'); ?>
                                                    </td>
                                                    @endif
                                            </tr>
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td style="border-top:1px solid black">Total {{ $pendapatan->name }}</td>
                                            <td style="border-top:1px solid black">{{ number_format($sum_penjualan-$sum_return)}}<?php $sum_pendapatan_operasional = $sum_penjualan-$sum_return; ?></td>
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
                                                    @if(list_transaction_harga_pokok_penjualan($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59') == '')
                                                    <td>0,00</td>
                                                    @else
                                                    <td>
                                                        {{ number_format(list_transaction_harga_pokok_penjualan($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59')) }}
                                                        <?php $sum += sum_penjualan($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59','HARGA POKOK PENJUALAN'); ?>
                                                    </td>
                                                    @endif
                                            </tr>
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td style="border-top:1px solid black">Total {{ $harga_pokok_penjualan->name }}</td>
                                            <td style="border-top:1px solid black">{{ number_format($sum) }}<?php $sum_harga_pokok_penjualan = $sum; ?></td>
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
                                                    @if(list_transaction_beban_operasi($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59') == '')
                                                    <td>0,00</td>
                                                    @else
                                                    <td>
                                                        {{ number_format(list_transaction_beban_operasi($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59')) }}
                                                        <?php $sum_biaya_operasi += sum_penjualan($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59','BIAYA OPERASIONAL'); ?>
                                                        <?php $sum_biaya_penyusutan += sum_penjualan($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59','BIAYA PENYUSUTAN'); ?>
                                                    </td>
                                                    @endif
                                            </tr>
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td style="border-top:1px solid black">Total {{ $beban_operasi->name }}</td>
                                            <td style="border-top:1px solid black">{{ number_format($sum_biaya_operasi+$sum_biaya_penyusutan) }}<?php $sum_beban_operasi = $sum_biaya_operasi+$sum_biaya_penyusutan; ?></td>
                                        </tr>
                                        @endif
                                    @endforeach
                                        <tr>
                                            <td></td>
                                            <td style="border-top:1px solid black">Total Pendapatan</td>
                                            <td style="border-top:1px solid black;border-bottom:1px solid black">
                                                {{ number_format(($sum_pendapatan_operasional-$sum_harga_pokok_penjualan)-$sum_beban_operasi) }}
                                                <?php $sum_all_pendapatan = ($sum_pendapatan_operasional-$sum_harga_pokok_penjualan)-$sum_beban_operasi; ?>
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
                                                    @if(list_transaction_pendapatan_lainnya($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59') == '')
                                                    <td>0,00</td>
                                                    @else
                                                    <td>
                                                        {{ number_format(list_transaction_pendapatan_lainnya($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59')) }}
                                                        <?php $sum_pend_lainnya += sum_penjualan($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59','BIAYA OPERASIONAL'); ?>
                                                    </td>
                                                    @endif
                                            </tr>
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td style="border-top:1px solid black">Total {{ $pendapatan_lainnya->name }}</td>
                                            <td style="border-top:1px solid black">{{ number_format($sum_pend_lainnya) }}<?php $sum_pendapatan_lainnya = $sum_pend_lainnya; ?></td>
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
                                                    @if(list_transaction_beban_lainnya($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59') == '')
                                                    <td>0,00</td>
                                                    @else
                                                    <td>
                                                        {{ number_format(list_transaction_beban_lainnya($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59')) }}
                                                        <?php $sum_beb_lainnya += sum_penjualan($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59','BIAYA OPERASIONAL'); ?>
                                                    </td>
                                                    @endif
                                            </tr>
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td style="border-top:1px solid black">Total {{ $beban_lainnya->name }}</td>
                                            <td style="border-top:1px solid black">{{ number_format($sum_beb_lainnya) }}<?php $sum_beban_lainnya = $sum_beb_lainnya; ?></td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td style="border-top:1px solid black">Total Pendapatan Lainnya dan Beban Lainnya</td>
                                        <td style="border-top:1px solid black;border-bottom:1px solid black">
                                            {{ number_format($sum_pendapatan_lainnya-$sum_beban_lainnya) }}
                                            <?php $sum_all_pendapatan_lainnya = $sum_pendapatan_lainnya-$sum_beban_lainnya; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="border-top:1px solid black">Laba</td>
                                        <td style="border-top:1px solid black;border-bottom:1px solid black">
                                            {{ number_format($sum_all_pendapatan+$sum_all_pendapatan_lainnya) }}
                                            <?php $net_laba = $sum_all_pendapatan+$sum_all_pendapatan_lainnya; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </center>
                        <center>
                            <table class="table table-striped table-hover" id="table-neraca" style="width:80%;display:none">
                                <thead>
                                    <tr>
                                        <th style="width:30%">No.Akun</th>
                                        <th style="width:40%">Deskripsi</th>
                                        <th style="width:30%">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td><b>Aktiva-Aktiva</b></td>
                                        <td></td>
                                    </tr>
                                    <?php
                                    $sum_cash_bank = 0;
                                    $sum_cash_bank_awal = 0;
                                    $sum_piutang = 0;
                                    $sum_inventory = 0;
                                    $sum_inventory_awal = 0;
                                    $sum_aktiva_lancar_lainnya = 0;
                                    $sum_nilai_history = 0;
                                    $sum_akumulasi_penyusutan = 0;
                                    ?>
                                    @foreach($chart_account as $cash_bank)
                                        <?php $sum=0; $sum_cash_bank_saldo_awal = 0;?>
                                        @if($cash_bank->id == 51)
                                        <tr>
                                            <td></td>
                                            <td><b>{{ $cash_bank->name}}</b></td>
                                            <td></td>
                                        </tr>
                                        @foreach(list_parent('51') as $as)
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
                                                    @if(list_transaction_cash_bank($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59') == '')
                                                    <td>0,00</td>
                                                    @else
                                                    <td>
                                                        {{ number_format(list_transaction_cash_bank($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59')) }}
                                                        <?php $sum += list_transaction_cash_bank($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59');?>
                                                        <?php $sum_cash_bank_saldo_awal += list_transaction_modal($sub->id,'','m','','SALDO AWAL');?>
                                                    </td>
                                                    @endif
                                            </tr>
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td style="border-top:1px solid black">Total {{ $cash_bank->name }}</td>
                                            <td style="border-top:1px solid black">{{ number_format($sum) }}<?php $sum_cash_bank = $sum; $sum_cash_bank_awal = $sum_cash_bank_saldo_awal; ?></td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    @foreach($chart_account as $piutang)
                                        <?php $sum =0;?>
                                        @if($piutang->id == 49)
                                        <tr>
                                            <td></td>
                                            <td><b>{{ $piutang->name}}</b></td>
                                            <td></td>
                                        </tr>
                                        @foreach(list_parent('49') as $as)
                                            @if($as->level == 1)
                                            <tr>
                                                <td style="padding-left:20px;">{{ $sub->account_number}}</td>
                                                <td style="padding-left:20px;">{{ $as->name}}</td>
                                                <td></td>
                                            </tr>
                                            @endif
                                            @foreach(list_child('2',$as->id) as $sub)
                                            <tr>
                                                <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                                <td style="padding-left:40px;">{{ $sub->name}}</td>
                                                    @if(list_transaction_piutang($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59') == '')
                                                    <td>0,00</td>
                                                    @else
                                                    <td>
                                                        {{ number_format(list_transaction_piutang($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59')) }}
                                                        <?php $sum += list_transaction_piutang($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59'); ?>
                                                    </td>
                                                    @endif
                                            </tr>
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td style="border-top:1px solid black">Total {{ $piutang->name }}</td>
                                            <td style="border-top:1px solid black">{{ number_format($sum) }}<?php $sum_piutang = $sum; ?></td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    @foreach($chart_account as $persediaan)
                                        <?php $sum = 0; $sum_inventory_saldo_awal = 0;?>
                                        @if($persediaan->id == 52)
                                        <tr>
                                            <td></td>
                                            <td><b>{{ $persediaan->name}}</b></td>
                                            <td></td>
                                        </tr>
                                        @foreach(list_parent('52') as $as)
                                            @if($as->level == 1)
                                            <tr>
                                                <td style="padding-left:20px;">{{ $sub->account_number}}</td>
                                                <td style="padding-left:20px;">{{ $as->name}}</td>
                                                <td></td>
                                            </tr>
                                            @endif
                                            @foreach(list_child('2',$as->id) as $sub)
                                            <tr>
                                                <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                                <td style="padding-left:40px;">{{ $sub->name}}</td>
                                                    @if(list_transaction_inventory($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59') == '')
                                                    <td>0,00</td>
                                                    @else
                                                    <td>
                                                        {{ number_format(list_transaction_inventory($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59')) }}
                                                        <?php $sum += list_transaction_inventory($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59'); ?>
                                                        <?php $sum_inventory_saldo_awal+= list_transaction_modal($sub->id,'','m','','SALDO AWAL'); ?>
                                                    </td>
                                                    @endif
                                            </tr>
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td style="border-top:1px solid black">Total {{ $persediaan->name }}</td>
                                            <td style="border-top:1px solid black">{{ number_format($sum) }} <?php $sum_inventory = $sum; $sum_inventory_awal = $sum_inventory_saldo_awal;?></td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    @foreach($chart_account as $aktiva_lancar_lainnya)
                                        <?php $sum = 0; ?>
                                        @if($aktiva_lancar_lainnya->id == 50)
                                        <tr>
                                            <td></td>
                                            <td><b>{{ $aktiva_lancar_lainnya->name}}</b></td>
                                            <td></td>
                                        </tr>
                                        @foreach(list_parent('50') as $as)
                                            @if($as->level == 1)
                                            <tr>
                                                <td style="padding-left:20px;">{{ $sub->account_number}}</td>
                                                <td style="padding-left:20px;">{{ $as->name}}</td>
                                                <td></td>
                                            </tr>
                                            @endif
                                            @foreach(list_child('2',$as->id) as $sub)
                                            <tr>
                                                <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                                <td style="padding-left:40px;">{{ $sub->name}}</td>
                                                    @if(list_transaction_aktiva_lancar_lainnya($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59') == '')
                                                    <td>0,00</td>
                                                    @else
                                                    <td>
                                                        {{ number_format(list_transaction_aktiva_lancar_lainnya($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59')) }}
                                                        <?php $sum += list_transaction_aktiva_lancar_lainnya($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59'); ?>
                                                    </td>
                                                    @endif
                                            </tr>
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td style="border-top:1px solid black">Total {{ $aktiva_lancar_lainnya->name }}<?php $sum_aktiva_lancar_lainnya = $sum; ?></td>
                                            <td style="border-top:1px solid black">0,00</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    @foreach($chart_account as $nilai_history)
                                        <?php $sum = 0; ?>
                                        @if($nilai_history->id == 68)
                                        <tr>
                                            <td></td>
                                            <td><b>{{ $nilai_history->name}}</b></td>
                                            <td></td>
                                        </tr>
                                        @foreach(list_parent('68') as $as)
                                            @if($as->level == 1)
                                            <tr>
                                                <td style="padding-left:20px;">{{ $as->account_number}}</td>
                                                <td style="padding-left:20px;">{{ $as->name}}</td>
                                                <td></td>
                                            </tr>
                                            @endif
                                            @foreach(list_child('2',$as->id) as $sub)
                                            <tr>
                                                <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                                <td style="padding-left:40px;">{{ $sub->name}}</td>
                                                    @if(list_transaction_nilai_history($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59') == '')
                                                    <td>0,00</td>
                                                    @else
                                                    <td>
                                                        {{ number_format(list_transaction_nilai_history($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59')) }}
                                                        <?php $sum += list_transaction_nilai_history($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59'); ?>
                                                    </td>
                                                    @endif
                                            </tr>
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td style="border-top:1px solid black">Total {{ $nilai_history->name }}</td>
                                            <td style="border-top:1px solid black">{{ number_format($sum) }} <?php $sum_nilai_history = $sum; ?></td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    @foreach($chart_account as $akumulasi_penyusutan)
                                        <?php $sum = 0; ?>
                                        @if($akumulasi_penyusutan->id == 55)
                                        <tr>
                                            <td></td>
                                            <td><b>{{ $akumulasi_penyusutan->name}}</b></td>
                                            <td></td>
                                        </tr>
                                        @foreach(list_parent('55') as $as)
                                            @if($as->level == 1)
                                            <tr>
                                                <td style="padding-left:20px;">{{ $sub->account_number}}</td>
                                                <td style="padding-left:20px;">{{ $as->name}}</td>
                                                <td></td>
                                            </tr>
                                            @endif
                                            @foreach(list_child('2',$as->id) as $sub)
                                            <tr>
                                                <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                                <td style="padding-left:40px;">{{ $sub->name}}</td>
                                                    @if(list_transaction_akumulasi_penyusutan($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59') == '')
                                                    <td>0,00</td>
                                                    @else
                                                    <td>
                                                      <?php $sum_akum = 0; ?>
                                                      <?php $year_end = date_create($date_end); ?>
                                                      @foreach(list_transaction_akumulasi_penyusutan_diff($sub->id,date_format($year_end,'Y')) as $x)
                                                          <tr style="display:none">
                                                              <td>{{ $x['amount'] }}</td>
                                                              <td>{{ $x['source'] }}</td>
                                                              <td>{{ $x['tahun'] }}</td>
                                                              <td>{{ $x['bulan'] }}</td>
                                                              <td>{{ $x['date'] }}</td>
                                                              <td>
                                                                  <?php
                                                                      $cdiff = $x['date']-$x['tahun'];
                                                                      if($cdiff == 0){
                                                                          $cdiff = $cdiff+1;
                                                                      }else{
                                                                          $cdiff = $cdiff+1;
                                                                      }
                                                                      echo $cdiff;
                                                                  ?>
                                                              </td>
                                                              <td>
                                                                  <?php echo $x['amount']*$cdiff; ?>
                                                                  <?php $sum_akum += $x['amount']*$cdiff; ?>
                                                              </td>
                                                          </tr>
                                                      @endforeach
                                                          <tr style="display:none">
                                                              <td colspan="7" align="right" id="sum_akum{{$sub->id}}">
                                                                  {{ number_format($sum_akum)}}
                                                                  <?php $sum += $sum_akum; ?>
                                                              </td>
                                                          </tr>
                                                    </td>
                                                    @endif
                                            </tr>
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td style="border-top:1px solid black">Total {{ $akumulasi_penyusutan->name }}</td>
                                            <td style="border-top:1px solid black">{{ number_format($sum) }}<?php $sum_akumulasi_penyusutan = $sum; ?></td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td style="border-top:1px solid black">Total Aktiva-Aktiva</td>
                                        <td style="border-top:1px solid black">{{ number_format($sum_cash_bank+$sum_piutang+$sum_inventory+$sum_aktiva_lancar_lainnya+($sum_nilai_history-$sum_akumulasi_penyusutan)) }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><b>Kewajiban dan Ekuitas</b></td>
                                        <td></td>
                                    </tr>
                                    <?php
                                    $sum_kewajiban = 0;
                                    $sum_kewajiban_lancar_lainnya = 0;
                                    $sum_kewajiban_jangka_panjang = 0;
                                    $sum_equitas = 0;
                                    ?>
                                    @foreach($chart_account as $kewajiban)
                                        <?php $sum = 0; ?>
                                        @if($kewajiban->id == 56)
                                        <tr>
                                            <td></td>
                                            <td><b>{{ $kewajiban->name}}</b></td>
                                            <td></td>
                                        </tr>
                                        @foreach(list_parent('56') as $as)
                                            @if($as->level == 1)
                                            <tr>
                                                <td style="padding-left:20px;">{{ $sub->account_number}}</td>
                                                <td style="padding-left:20px;">{{ $as->name}}</td>
                                                <td></td>
                                            </tr>
                                            @endif
                                            @foreach(list_child('2',$as->id) as $sub)
                                            <tr>
                                                <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                                <td style="padding-left:40px;">{{ $sub->name}}</td>
                                                    @if(list_transaction_hutang($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59') == '')
                                                    <td>0,00</td>
                                                    @else
                                                    <td>
                                                        {{ number_format(list_transaction_hutang($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59')) }}
                                                        <?php $sum += list_transaction_hutang($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59'); ?>
                                                    </td>
                                                    @endif
                                            </tr>
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td style="border-top:1px solid black">Total {{ $kewajiban->name }}</td>
                                            <td style="border-top:1px solid black">{{ number_format($sum) }}<?php $sum_kewajiban = $sum; ?></td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    @foreach($chart_account as $kewajiban_lancar_lainnya)
                                        <?php $sum = 0; ?>
                                        @if($kewajiban_lancar_lainnya->id == 58)
                                        <tr>
                                            <td></td>
                                            <td><b>{{ $kewajiban_lancar_lainnya->name}}</b></td>
                                            <td></td>
                                        </tr>
                                        @foreach(list_parent('58') as $as)
                                            @if($as->level == 1)
                                            <tr>
                                                <td style="padding-left:20px;">{{ $sub->account_number}}</td>
                                                <td style="padding-left:20px;">{{ $as->name}}</td>
                                                <td></td>
                                            </tr>
                                            @endif
                                            @foreach(list_child('2',$as->id) as $sub)
                                            <tr>
                                                <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                                <td style="padding-left:40px;">{{ $sub->name}}</td>
                                                    @if(list_transaction_kewajiban_lancar_lainnya($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59') == '')
                                                    <td>0,00</td>
                                                    @else
                                                    <td>
                                                        {{ number_format(list_transaction_kewajiban_lancar_lainnya($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59')) }}
                                                        <?php $sum += list_transaction_kewajiban_lancar_lainnya($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59'); ?>
                                                    </td>
                                                    @endif
                                            </tr>
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td style="border-top:1px solid black">Total {{ $kewajiban_lancar_lainnya->name }}</td>
                                            <td style="border-top:1px solid black">{{ number_format($sum) }}<?php $sum_kewajiban_lancar_lainnya = $sum; ?></td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    @foreach($chart_account as $kewajiban_jangka_panjang)
                                        <?php $sum = 0; ?>
                                        @if($kewajiban_jangka_panjang->id == 59)
                                        <tr>
                                            <td></td>
                                            <td><b>{{ $kewajiban_jangka_panjang->name}}</b></td>
                                            <td></td>
                                        </tr>
                                        @foreach(list_parent('59') as $as)
                                            @if($as->level == 1)
                                            <tr>
                                                <td style="padding-left:20px;">{{ $as->account_number}}</td>
                                                <td style="padding-left:20px;">{{ $as->name}}</td>
                                                    @if(list_transaction_kewajiban_jangka_panjang($as->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59') == '')
                                                    <td>0,00</td>
                                                    @else
                                                    <td>
                                                        {{ number_format(list_transaction_kewajiban_jangka_panjang($as->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59')) }}
                                                        <?php $sum += list_transaction_kewajiban_jangka_panjang($as->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59'); ?>
                                                    </td>
                                                    @endif
                                            </tr>
                                            @endif
                                            @foreach(list_child('2',$as->id) as $sub)
                                            <tr>
                                                <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                                <td style="padding-left:40px;">{{ $sub->name}}</td>
                                                    @if(list_transaction_kewajiban_jangka_panjang($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59') == '')
                                                    <td>0,00</td>
                                                    @else
                                                    <td>
                                                        {{ number_format(list_transaction_kewajiban_jangka_panjang($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59')) }}
                                                        <?php $sum += list_transaction_kewajiban_jangka_panjang($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59'); ?>
                                                    </td>
                                                    @endif
                                            </tr>
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td style="border-top:1px solid black">Total {{ $kewajiban_jangka_panjang->name }}</td>
                                            <td style="border-top:1px solid black">{{ number_format($sum) }}<?php $sum_kewajiban_jangka_panjang = $sum; ?></td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    @foreach($chart_account as $equitas)
                                        <?php $sum = 0; ?>
                                        @if($equitas->id == 60)
                                        <tr>
                                            <td></td>
                                            <td><b>{{ $equitas->name}}</b></td>
                                            <td></td>
                                        </tr>
                                        @foreach(list_parent('60') as $as)
                                            @if($as->level == 1)
                                            <tr>
                                                <td style="padding-left:20px;">{{ $as->account_number}}</td>
                                                <td style="padding-left:20px;">{{ $as->name}}</td>
                                                    @if(list_transaction_equitas($as->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59') == '')
                                                      @if($as->name == 'MODAL')
                                                      <td>
                                                          {{ number_format($sum_cash_bank_awal+$sum_inventory_awal) }}
                                                          <?php $sum += $sum_cash_bank_awal+$sum_inventory_awal; ?>
                                                      </td>
                                                      @else
                                                      <td>
                                                          {{ number_format(list_transaction_equitas($as->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59')) }}.00
                                                          <?php $sum += list_transaction_equitas($as->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59'); ?>
                                                      </td>
                                                      @endif
                                                    @else
                                                      @if($as->name == 'MODAL')
                                                      <td>
                                                          {{ number_format($sum_cash_bank_awal+$sum_inventory_awal) }}
                                                          <?php $sum += $sum_cash_bank_awal+$sum_inventory_awal; ?>
                                                      </td>
                                                      @else
                                                      <td>
                                                          {{ number_format(list_transaction_equitas($as->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59')) }}.00
                                                          <?php $sum += list_transaction_equitas($as->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59'); ?>
                                                      </td>
                                                      @endif
                                                    @endif
                                            </tr>
                                            @endif
                                            @foreach(list_child('2',$as->id) as $sub)
                                            <tr>
                                                <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                                <td style="padding-left:40px;">{{ $sub->name}}</td>
                                                    @if(list_transaction_equitas($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59') == '')
                                                    <td>0,00</td>
                                                    @else
                                                    <td>
                                                        {{ number_format(list_transaction_equitas($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59')) }}
                                                        <?php $sum += list_transaction_equitas($sub->id,$date_start.' 00:00:00','m',$date_end.' 23:59:59'); ?>
                                                    </td>
                                                    @endif
                                            </tr>
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td style="border-top:1px solid black">Total {{ $equitas->name }}</td>
                                            <td style="border-top:1px solid black">{{ number_format($sum) }}<?php $sum_equitas = $sum; ?></td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td style="border-top:1px solid black">Total Kewajiban dan Equitas</td>
                                        <td style="border-top:1px solid black">{{ number_format($sum_equitas+($sum_kewajiban+$sum_kewajiban_lancar_lainnya+$sum_kewajiban_jangka_panjang)) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </center>
                        <center>
                            <table class="table table-striped table-hover" id="table-cash-flow" style="width:80%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Deskripsi</th>
                                        <th>Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="3">Cash Flow From : Operating Activities</td>
                                    </tr>
                                    <tr>
                                        <td>Net Income</td>
                                        <td>(From Profit&nbsp;&amp;&nbsp;Loss Statement)</td>
                                        @if($net_laba == 0)
                                            <td id="data-lost-profit" align="right">0.00</td>
                                        @else
                                            <td id="data-lost-profit" align="right">{{ number_format($net_laba) }}.00</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>Tambah</td>
                                        <td>Akumulasi Penyusutan</td>
                                        @if($sum_akumulasi_penyusutan == 0)
                                            <td id="data-akumulasi-penyusutan" align="right">0.00</td>
                                        @else
                                            <td id="data-akumulasi-penyusutan" align="right">{{ number_format($sum_akumulasi_penyusutan) }}.00</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="border-top:1px solid black;">Net Income sesudah akumulasi penyusutan</td>
                                        <td style="border-top:1px solid black;" align="right">
                                          @if($net_laba-$sum_akumulasi_penyusutan == 0)
                                            0.00
                                          @else
                                            {{ number_format($net_laba-$sum_akumulasi_penyusutan)}}.00
                                          @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Kurang</td>
                                        <td>Akun Hutang</td>
                                        @if($sum_kewajiban == 0)
                                            <td id="data-akun-hutang" align="right">0.00</td>
                                        @else
                                            <td id="data-akun-hutang" align="right">{{ number_format($sum_kewajiban) }}.00</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>Kurang</td>
                                        <td>Kewajiban Lancar Lainnya</td>
                                        @if($sum_kewajiban_lancar_lainnya == 0)
                                            <td id="data-kewajiban-lancar-lainnya" align="right">0.00</td>
                                        @else
                                            <td id="data-kewajiban-lancar-lainnya" align="right">{{ number_format($sum_kewajiban_lancar_lainnya) }}.00</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>Tambah</td>
                                        <td>Akun Piutang</td>
                                        @if($sum_piutang == 0)
                                            <td id="data-akun-piutang" align="right">0.00</td>
                                        @else
                                            <td id="data-akun-piutang" align="right">{{ number_format($sum_piutang) }}.00</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>Tambah</td>
                                        <td>Aset Lancar Lainnya</td>
                                        <td id="data-aset-lancar-lainnya" align="right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="border-top:1px solid black;">Net Income</td>
                                        <td style="border-top:1px solid black;" align="right">
                                            <?php $sum_net = (($net_laba-$sum_akumulasi_penyusutan)+($sum_piutang))-($sum_kewajiban+$sum_kewajiban_lancar_lainnya); ?>
                                            @if($sum_net == 0)
                                              0.00
                                            @else
                                              {{ number_format((($net_laba-$sum_akumulasi_penyusutan)+($sum_piutang))-($sum_kewajiban+$sum_kewajiban_lancar_lainnya))}}.00
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">Cash Flow From : Investing</td>
                                    </tr>
                                    <tr>
                                        <td>Tambah</td>
                                        <td>Nilai Histori</td>
                                        @if($sum_nilai_history == 0)
                                            <td id="data-nilai-histori" align="right">0.00</td>
                                        @else
                                            <td id="data-nilai-histori" align="right">{{ number_format($sum_nilai_history) }}.00</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="border-top:1px solid black;">Net Investing</td>
                                        <td style="border-top:1px solid black;" align="right">
                                          @if($sum_nilai_history == 0)
                                            0.00
                                          @else
                                            {{ number_format($sum_nilai_history)}}.00
                                          @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">Cash Flow From : Financing</td>
                                    </tr>
                                    <tr>
                                        <td>Kurang</td>
                                        <td>Kewajiban Jangka Panjang</td>
                                        @if($sum_kewajiban_jangka_panjang == 0)
                                            <td id="data-kewajiban-jangka-panjang" align="right">0.00</td>
                                        @else
                                            <td id="data-kewajiban-jangka-panjang" align="right">{{ number_format($sum_kewajiban_jangka_panjang) }}.00</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>Tambah</td>
                                        <td>Ekuitas</td>
                                        @if($sum_equitas == 0)
                                            <td id="data-ekuitas" align="right">0.00</td>
                                        @else
                                            <td id="data-ekuitas" align="right">{{ number_format($sum_equitas) }}.00</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="border-top:1px solid black;">Net Financing</td>
                                        <td style="border-top:1px solid black;" align="right">
                                          @if($sum_equitas-$sum_kewajiban_jangka_panjang == 0)
                                            0.00
                                          @else
                                            {{ number_format($sum_equitas-$sum_kewajiban_jangka_panjang)}}.00
                                          @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="border-top:1px solid black;">Net Cash Periode</td>
                                        <td style="border-top:1px solid black;" align="right">
                                          @if($sum_net+($sum_equitas+$sum_nilai_history)-$sum_kewajiban_jangka_panjang == 0)
                                            0.00
                                          @else
                                            {{ number_format($sum_net+($sum_equitas+$sum_nilai_history)-$sum_kewajiban_jangka_panjang)}}.00
                                          @endif
                                          </td>
                                    </tr>
                                </tbody>
                            </table>
                        </center>
                    </div>
                </div>
                <div class="box-footer clearfix">

                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('additional_scripts')
    <script type="text/javascript">
    $(document).ready(function(){
        $('#sort_lost_profit').val($('#data-lost-profit').text());
        $('#sort_akumulasi_penyusutan').val($('#data-akumulasi-penyusutan').text());
        $('#sort_akun_hutang').val($('#data-akun-hutang').text());
        $('#sort_kewajiban_lancar_lainnya').val($('#data-kewajiban-lancar-lainnya').text());
        $('#sort_akun_piutang').val($('#data-akun-piutang').text());
        $('#sort_aset_lancar_lainnya').val($('#data-aset-lancar-lainnya').text());
        $('#sort_nilai_histori').val($('#data-nilai-histori').text())
        $('#sort_kewajiban_jangka_panjang').val($('#data-kewajiban-jangka-panjang').text());
        $('#sort_ekuitas').val($('#data-ekuitas').text());
    });
    </script>
@endsection
