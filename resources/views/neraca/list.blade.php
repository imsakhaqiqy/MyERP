@extends('layouts.app')

@section('page_title')
    Neraca
@endsection

@section('page_header')
    <h1>
        Neraca
        <small>Neraca Search</small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('neraca') }}"><i class="fa fa-dashboard"></i> Neraca</a></li>
        <li class="active"><i></i>Index</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        {!! Form::open(['url'=>'neraca/submit','role'=>'form','class'=>'form-horizontal','id'=>'form-search-neraca']) !!}
        <div class="col-lg-12">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3 class="box-title">Neraca Search</h3>
                    <a data-toggle="collapse" href="#collapse-neraca" title="Click to search neraca"><i class="fa fa-arrow-down pull-right"></i></a>
                </div>
                <div class="box-body collapse" id="collapse-neraca">
                    <div class="form-group">
                        <div class="col-sm-offset-1 col-sm-11">
                            <div class="radio">
                                <label><input type="radio" id="sort_by_year" name="sort_by_year" value="y"> Sort by year</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('years','Years',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-2">
                            {!! Form::number('years','2017',['class'=>'form-control','id'=>'years']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-1 col-sm-11">
                            <div class="radio">
                                <label><input type="radio" id="sort_by_month_start" name="sort_by_year" value="m"> Sort by month</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('months','Start',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-2">
                            <select class="form-control" name="list_months_start" id="list_months_start">
                                <option>Select Month</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            {!! Form::number('list_years_start','2017',['class'=>'form-control','id'=>'list_years_start']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('months','End',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-2">
                            <select class="form-control" name="list_months_end" id="list_months_end">
                                <option>Select Month</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            {!! Form::number('list_years_end','2017',['class'=>'form-control','id'=>'list_years_end']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('','',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            <button type="submit" href="#" class="btn btn-info" id="btn-submit-neraca">
                                <i class="fa fa-search"></i>&nbsp;Search
                            </button>
                        </div>
                    </div>
                </div>
                <div class="box-footer">

                </div>
            </box>
        </div>
        {!! Form::close() !!}
    </div>

    <div class="col-lg-12">
        <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
            <div class="box-header with-border">
                {!! Form::open(['url'=>'neraca.neraca_print','role'=>'form','class'=>'form-horizontal','id'=>'form-search-neraca','files'=>true]) !!}
                <center>
                    <h3 class="box-title">CATRA<small>TEXTILE</small></h3>
                    <h4>NERACA</h4>
                    <h4 id="sort_target">
                        @if(isset($year_in))
                            Tahun&nbsp;{{ $year }}
                        @elseif(isset($month_in))
                            Bulan&nbsp;{{ $conv_month_start }}&nbsp;Tahun&nbsp;{{ $year_start }}&nbsp;sampai&nbsp;Bulan&nbsp;{{ $conv_month_end }}&nbsp;Tahun&nbsp;{{ $year_end}}
                        @else
                            Tahun&nbsp;{{ date('Y') }}
                        @endif
                    </h4>
                </center>
                @if(isset($year_in))
                <div class="form-group pull-right">
                    {!! Form::label('','',['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-3">
                        <input type="hidden" name="sort_target_year" id="sort_target_year" value="{{ $year }}">
                        <input type="hidden" name="sort_target" id="sort_target" value="y">
                        <input type="hidden" name="sort_target_akum_kendaraan" id="sort_target_akum_kendaraan">
                        <input type="hidden" name="sort_target_akum_gedung" id="sort_target_akum_gedung">
                        <input type="hidden" name="sort_target_akum_inventaris" id="sort_target_akum_inventaris">
                        <button type="submit" class="btn btn-default" id="btn-submit-neraca-print" title="click to print">
                            <i class="fa fa-print"></i>&nbsp;
                        </button>
                    </div>
                </div>
                @elseif(isset($month_in))
                <div class="form-group pull-right">
                    {!! Form::label('','',['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-3">
                        <input type="hidden" name="sort_target" id="sort_target" value="m">
                        <input type="hidden" name="sort_target_months_start" id="sort_by_month_year_start" value="{{ $month_start }}">
                        <input type="hidden" name="sort_target_years_start" id="sort_by_month_end" value="{{ $year_start }}">
                        <input type="hidden" name="sort_target_months_end" id="sort_by_month_year_end" value="{{ $month_end }}">
                        <input type="hidden" name="sort_target_years_end" id="sort_by_month_year_end" value="{{ $year_end }}">
                        <input type="hidden" name="sort_target_akum_kendaraan" id="sort_target_akum_kendaraan">
                        <input type="hidden" name="sort_target_akum_gedung" id="sort_target_akum_gedung">
                        <input type="hidden" name="sort_target_akum_inventaris" id="sort_target_akum_inventaris">
                        <button type="submit" class="btn btn-default" id="btn-submit-neraca-print" title="click to print">
                            <i class="fa fa-print"></i>&nbsp;
                        </button>
                    </div>
                </div>
                @endif
                {!! Form::close() !!}
            </div>
            <div class="box-body table-responsive">
                <center>
                    <table class="table table-striped table-hover" id="table-neraca" style="width:80%">
                        <thead>
                            <tr>
                                <th style="width:30%">No.AkunA</th>
                                <th style="width:50%">Deskripsi</th>
                                <th style="width:20%;">Saldo</th>
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
                                        @if(isset($year_in))
                                            @if(list_transaction_cash_bank($sub->id,$year,'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_cash_bank($sub->id,$year,'y','')) }}.00
                                                <?php $sum += list_transaction_cash_bank($sub->id,$year,'y','');?>
                                                <?php $sum_cash_bank_saldo_awal += list_transaction_modal($sub->id,'','y','','SALDO AWAL');?>
                                            </td>
                                            @endif
                                        @elseif(isset($month_in))
                                            @if(list_transaction_cash_bank($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59') == '')
                                            <td align="right" style="padding-right:3px">
                                              0.00
                                              <?php $sum_cash_bank_saldo_awal += list_transaction_modal($sub->id,'','m','','SALDO AWAL');?>
                                            </td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_cash_bank($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59')) }}.00
                                                <?php $sum += list_transaction_cash_bank($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59');?>
                                                <?php $sum_cash_bank_saldo_awal += list_transaction_modal($sub->id,'','m','','SALDO AWAL');?>
                                            </td>
                                            @endif
                                        @else
                                            @if(list_transaction_cash_bank($sub->id,date('Y'),'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_cash_bank($sub->id,date('Y'),'y','')) }}.00
                                                <?php $sum += list_transaction_cash_bank($sub->id,date('Y'),'y','');?>
                                                <?php $sum_cash_bank_saldo_awal += list_transaction_modal($sub->id,'','y','','SALDO AWAL');?>
                                            </td>
                                            @endif
                                        @endif
                                    </tr>
                                    @endforeach
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td style="border-top:1px solid black">Total {{ $cash_bank->name }}</td>
                                    <td align="right" style="border-top:1px solid black;padding-right:3px">
                                      {{ number_format($sum) }}.00<?php $sum_cash_bank = $sum; $sum_cash_bank_awal = $sum_cash_bank_saldo_awal;?>
                                    </td>
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
                                        <td style="padding-left:20px;">{{ $as->account_number}}</td>
                                        <td style="padding-left:20px;">{{ $as->name}}</td>
                                        <td></td>
                                    </tr>
                                    @endif
                                    @foreach(list_child('2',$as->id) as $sub)
                                    <tr>
                                        <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                        <td style="padding-left:40px;">{{ $sub->name}}</td>
                                        @if(isset($year_in))
                                            @if(list_transaction_piutang($sub->id,$year,'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_piutang($sub->id,$year,'y','')) }}.00
                                                <?php $sum += list_transaction_piutang($sub->id,$year,'y','');?>
                                            </td>
                                            @endif
                                        @elseif(isset($month_in))
                                            @if(list_transaction_piutang($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_piutang($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59')) }}.00
                                                <?php $sum += list_transaction_piutang($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59'); ?>
                                            </td>
                                            @endif
                                        @else
                                            @if(list_transaction_piutang($sub->id,date('Y'),'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_piutang($sub->id,date('Y'),'y','')) }}.00
                                                <?php $sum += list_transaction_piutang($sub->id,date('Y'),'y',''); ?>
                                            </td>
                                            @endif
                                        @endif
                                    </tr>
                                    @endforeach
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td style="border-top:1px solid black">Total {{ $piutang->name }}</td>
                                    <td align="right" style="border-top:1px solid black;padding-right:3px">
                                      @if($sum == 0)
                                        0.00
                                      @else
                                        {{ number_format($sum) }}.00<?php $sum_piutang = $sum; ?>
                                      @endif
                                    </td>
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
                                        <td style="padding-left:20px;">{{ $as->account_number}}</td>
                                        <td style="padding-left:20px;">{{ $as->name}}</td>
                                        <td></td>
                                    </tr>
                                    @endif
                                    @foreach(list_child('2',$as->id) as $sub)
                                    <tr>
                                        <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                        <td style="padding-left:40px;">{{ $sub->name}}</td>
                                        @if(isset($year_in))
                                            @if(list_transaction_inventory($sub->id,$year,'y','') == '')
                                            <td align="right" style="padding-right:3px">
                                              0.00
                                              <?php $sum_inventory_saldo_awal+= list_transaction_modal($sub->id,'','y','','SALDO AWAL'); ?>
                                            </td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_inventory($sub->id,$year,'y','')) }}.00
                                                <?php $sum+= list_transaction_inventory($sub->id,$year,'y',''); ?>
                                                <?php $sum_inventory_saldo_awal+= list_transaction_modal($sub->id,'','y','','SALDO AWAL'); ?>
                                            </td>
                                            @endif
                                        @elseif(isset($month_in))
                                            @if(list_transaction_inventory($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59') == '')
                                            <td align="right" style="padding-right:3px">
                                              0.00
                                              <?php $sum_inventory_saldo_awal+= list_transaction_modal($sub->id,'','m','','SALDO AWAL'); ?>
                                            </td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_inventory($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59')) }}.00
                                                <?php $sum += list_transaction_inventory($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59'); ?>
                                                <?php $sum_inventory_saldo_awal+= list_transaction_modal($sub->id,'','m','','SALDO AWAL'); ?>
                                            </td>
                                            @endif
                                        @else
                                            @if(list_transaction_inventory($sub->id,date('Y'),'y','') == '')
                                            <td align="right" style="padding-right:3px">
                                              0.00
                                              <?php $sum_inventory_saldo_awal+= list_transaction_modal($sub->id,'','y','','SALDO AWAL'); ?>
                                            </td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_inventory($sub->id,date('Y'),'y','')) }}.00
                                                <?php $sum += list_transaction_inventory($sub->id,date('Y'),'y',''); ?>
                                                <?php $sum_inventory_saldo_awal+= list_transaction_modal($sub->id,'','y','','SALDO AWAL'); ?>
                                            </td>
                                            @endif
                                        @endif
                                    </tr>
                                    @endforeach
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td style="border-top:1px solid black">Total {{ $persediaan->name }}</td>
                                    <td align="right" style="border-top:1px solid black;padding-right:3px">
                                      @if($sum == 0)
                                        0.00
                                      @else
                                        {{ number_format($sum) }}.00 <?php $sum_inventory = $sum; $sum_inventory_awal = $sum_inventory_saldo_awal;?>
                                      @endif
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            @foreach($chart_account as $aktiva_lancar_lainnya)
                                <?php $sum = 0; ?>
                                @if($aktiva_lancar_lainnya->id == 69)
                                <tr>
                                    <td></td>
                                    <td><b>{{ $aktiva_lancar_lainnya->name}}</b></td>
                                    <td></td>
                                </tr>
                                @foreach(list_parent('69') as $as)
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
                                        @if(isset($year_in))
                                            @if(list_transaction_aktiva_lancar_lainnya($sub->id,$year,'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_aktiva_lancar_lainnya($sub->id,$year,'y','')) }}.00
                                                <?php $sum+= list_transaction_aktiva_lancar_lainnya($sub->id,$year,'y',''); ?>
                                            </td>
                                            @endif
                                        @elseif(isset($month_in))
                                            @if(list_transaction_aktiva_lancar_lainnya($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_aktiva_lancar_lainnya($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59')) }}.00
                                                <?php $sum += list_transaction_aktiva_lancar_lainnya($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59'); ?>
                                            </td>
                                            @endif
                                        @else
                                            @if(list_transaction_aktiva_lancar_lainnya($sub->id,date('Y'),'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_aktiva_lancar_lainnya($sub->id,date('Y'),'y','')) }}.00
                                                <?php $sum += list_transaction_aktiva_lancar_lainnya($sub->id,date('Y'),'y',''); ?>
                                            </td>
                                            @endif
                                        @endif
                                    </tr>
                                    @endforeach
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td style="border-top:1px solid black">Total {{ $aktiva_lancar_lainnya->name }}<?php $sum_aktiva_lancar_lainnya = $sum; ?></td>
                                    <td align="right" style="border-top:1px solid black;padding-right:3px">0.00</td>
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
                                        @if(isset($year_in))
                                            @if(list_transaction_nilai_history($sub->id,$year,'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_nilai_history($sub->id,$year,'y','')) }}.00
                                                <?php $sum+= list_transaction_nilai_history($sub->id,$year,'y',''); ?>
                                            </td>
                                            @endif
                                        @elseif(isset($month_in))
                                            @if(list_transaction_nilai_history($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_nilai_history($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59')) }}.00
                                                <?php $sum += list_transaction_nilai_history($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59'); ?>
                                            </td>
                                            @endif
                                        @else
                                            @if(list_transaction_nilai_history($sub->id,date('Y'),'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_nilai_history($sub->id,date('Y'),'y','')) }}.00
                                                <?php $sum += list_transaction_nilai_history($sub->id,date('Y'),'y',''); ?>
                                            </td>
                                            @endif
                                        @endif
                                    </tr>
                                    @endforeach
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td style="border-top:1px solid black">Total {{ $nilai_history->name }}</td>
                                    <td align="right" style="border-top:1px solid black;padding-right:3px">
                                      @if($sum == 0)
                                        0.00
                                      @else
                                        {{ number_format($sum) }}.00 <?php $sum_nilai_history = $sum; ?>
                                      @endif
                                    </td>
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
                                        <td style="padding-left:20px;">{{ $as->account_number}}</td>
                                        <td style="padding-left:20px;">{{ $as->name}}</td>
                                        <td></td>
                                    </tr>
                                    @endif
                                    @foreach(list_child('2',$as->id) as $sub)
                                    <tr>
                                        <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                        <td style="padding-left:40px;">{{ $sub->name}}</td>
                                        @if(isset($year_in))
                                            @if(list_transaction_akumulasi_penyusutan_diff($sub->id,$year,'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td id="{{$sub->id}}" align="right" style="padding-right:3px">
                                                <?php $sum_akum = 0; ?>
                                                @foreach(list_transaction_akumulasi_penyusutan_diff($sub->id,$year) as $x)
                                                    <tr style="display:none">
                                                        <td>{{ $x['amount'] }}</td>
                                                        <td>{{ $x['source'] }}</td>
                                                        <td>{{ $x['tahun'] }}</td>
                                                        <td>{{ $x['bulan'] }}</td>
                                                        <td>{{ $x['date'] }}</td>
                                                        <td>
                                                            <?php
                                                                $cdiff = $x['date']-$x['tahun']+1;
                                                                if($cdiff == 0){
                                                                    $cdiff = $cdiff;
                                                                }
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
                                                            @if($sum_akum == 0)
                                                              {{ number_format($sum_akum)}}.00
                                                              <?php $sum += $sum_akum; ?>
                                                            @else
                                                              {{ number_format($sum_akum)}}.00
                                                              <?php $sum += $sum_akum; ?>
                                                            @endif

                                                        </td>
                                                    </tr>
                                            </td>
                                            @endif
                                        @elseif(isset($month_in))
                                            @if(list_transaction_akumulasi_penyusutan($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td id="{{$sub->id}}" align="right" style="padding-right:3px">
                                                <?php $sum_akum = 0; ?>
                                                @foreach(list_transaction_akumulasi_penyusutan_diff($sub->id,$year_end) as $x)
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
                                                          @if($sum_akum == 0)
                                                            {{ number_format($sum_akum)}}.00
                                                            <?php $sum += $sum_akum; ?>
                                                          @else
                                                            {{ number_format($sum_akum)}}.00
                                                            <?php $sum += $sum_akum; ?>
                                                          @endif
                                                        </td>
                                                    </tr>
                                            </td>
                                            @endif
                                        @else
                                            @if(list_transaction_akumulasi_penyusutan($sub->id,date('Y'),'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td id="{{$sub->id}}" align="right" style="padding-right:3px">
                                                <?php $sum_akum = 0; ?>
                                                @foreach(list_transaction_akumulasi_penyusutan_diff($sub->id,date('Y')) as $x)
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
                                                          @if($sum_akum == 0)
                                                            {{ number_format($sum_akum)}}.00
                                                            <?php $sum += $sum_akum; ?>
                                                          @else
                                                            {{ number_format($sum_akum)}}.00
                                                            <?php $sum += $sum_akum; ?>
                                                          @endif
                                                        </td>
                                                    </tr>
                                            </td>
                                            @endif
                                        @endif
                                    </tr>
                                    @endforeach
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td style="border-top:1px solid black">Total {{ $akumulasi_penyusutan->name }}</td>
                                    <td align="right" style="border-top:1px solid black;padding-right:3px">
                                      @if($sum == 0)
                                        0.00
                                      @else
                                        {{ number_format($sum) }}.00<?php $sum_akumulasi_penyusutan = $sum; ?>
                                      @endif
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            <tr>
                                <td></td>
                                <td style="border-top:1px solid black">Total Aktiva-Aktiva</td>
                                <td align="right" style="border-top:1px solid black;padding-right:3px">{{ number_format($sum_cash_bank+$sum_piutang+$sum_inventory+$sum_aktiva_lancar_lainnya+($sum_nilai_history-$sum_akumulasi_penyusutan)) }}.00</td>
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
                                        <td style="padding-left:20px;">{{ $as->account_number}}</td>
                                        <td style="padding-left:20px;">{{ $as->name}}</td>
                                        <td></td>
                                    </tr>
                                    @endif
                                    @foreach(list_child('2',$as->id) as $sub)
                                    <tr>
                                        <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                        <td style="padding-left:40px;">{{ $sub->name}}</td>
                                        @if(isset($year_in))
                                            @if(list_transaction_hutang($sub->id,$year,'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_hutang($sub->id,$year,'y','')) }}.00
                                                <?php $sum += list_transaction_hutang($sub->id,$year,'y',''); ?>
                                            </td>
                                            @endif
                                        @elseif(isset($month_in))
                                            @if(list_transaction_hutang($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_hutang($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59')) }}.00
                                                <?php $sum += list_transaction_hutang($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59'); ?>
                                            </td>
                                            @endif
                                        @else
                                            @if(list_transaction_hutang($sub->id,date('Y'),'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_hutang($sub->id,date('Y'),'y','')) }}.00
                                                <?php $sum += list_transaction_hutang($sub->id,date('Y'),'y',''); ?>
                                            </td>
                                            @endif
                                        @endif
                                    </tr>
                                    @endforeach
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td style="border-top:1px solid black">Total {{ $kewajiban->name }}</td>
                                    <td align="right" style="border-top:1px solid black;padding-right:3px">
                                      @if($sum == 0)
                                        0.00
                                      @else
                                        {{ number_format($sum) }}.00<?php $sum_kewajiban = $sum; ?>
                                      @endif
                                    </td>
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
                                        <td style="padding-left:20px;">{{ $as->account_number}}</td>
                                        <td style="padding-left:20px;">{{ $as->name}}</td>
                                        <td></td>
                                    </tr>
                                    @endif
                                    @foreach(list_child('2',$as->id) as $sub)
                                    <tr>
                                        <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                        <td style="padding-left:40px;">{{ $sub->name}}</td>
                                        @if(isset($year_in))
                                            @if(list_transaction_kewajiban_lancar_lainnya($sub->id,$year,'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_kewajiban_lancar_lainnya($sub->id,$year,'y','')) }}.00
                                                <?php $sum += list_transaction_kewajiban_lancar_lainnya($sub->id,$year,'y',''); ?>
                                            </td>
                                            @endif
                                        @elseif(isset($month_in))
                                            @if(list_transaction_kewajiban_lancar_lainnya($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_kewajiban_lancar_lainnya($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59')) }}.00
                                                <?php $sum += list_transaction_kewajiban_lancar_lainnya($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59'); ?>
                                            </td>
                                            @endif
                                        @else
                                            @if(list_transaction_kewajiban_lancar_lainnya($sub->id,date('Y'),'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_kewajiban_lancar_lainnya($sub->id,date('Y'),'y','')) }}.00
                                                <?php $sum += list_transaction_kewajiban_lancar_lainnya($sub->id,date('Y'),'y',''); ?>
                                            </td>
                                            @endif
                                        @endif
                                    </tr>
                                    @endforeach
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td style="border-top:1px solid black">Total {{ $kewajiban_lancar_lainnya->name }}</td>
                                    <td align="right" style="border-top:1px solid black;padding-right:3px">
                                      @if($sum == 0)
                                        0,00
                                      @else
                                        {{ number_format($sum) }}.00<?php $sum_kewajiban_lancar_lainnya = $sum; ?>
                                      @endif
                                    </td>
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
                                        @if(isset($year_in))
                                            @if(list_transaction_kewajiban_jangka_panjang($as->id,$year,'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_kewajiban_jangka_panjang($as->id,$year,'y','')) }}.00
                                                <?php $sum += list_transaction_kewajiban_jangka_panjang($as->id,$year,'y',''); ?>
                                            </td>
                                            @endif
                                        @elseif(isset($month_in))
                                            @if(list_transaction_kewajiban_jangka_panjang($as->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_kewajiban_jangka_panjang($as->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59')) }}.00
                                                <?php $sum += list_transaction_kewajiban_jangka_panjang($as->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59'); ?>
                                            </td>
                                            @endif
                                        @else
                                            @if(list_transaction_kewajiban_jangka_panjang($as->id,date('Y'),'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_kewajiban_jangka_panjang($as->id,date('Y'),'y','')) }}.00
                                                <?php $sum += list_transaction_kewajiban_jangka_panjang($as->id,date('Y'),'y',''); ?>
                                            </td>
                                            @endif
                                        @endif
                                    </tr>
                                    @endif
                                    @foreach(list_child('2',$as->id) as $sub)
                                    <tr>
                                        <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                        <td style="padding-left:40px;">{{ $sub->name}}</td>
                                        @if(isset($year_in))
                                            @if(list_transaction_kewajiban_jangka_panjang($sub->id,$year,'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_kewajiban_jangka_panjang($sub->id,$year,'y','')) }}.00
                                                <?php $sum += list_transaction_kewajiban_jangka_panjang($sub->id,$year,'y',''); ?>
                                            </td>
                                            @endif
                                        @elseif(isset($month_in))
                                            @if(list_transaction_kewajiban_jangka_panjang($sub->id,date('Y'),'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_kewajiban_jangka_panjang($sub->id,date('Y'),'y','')) }}.00
                                                <?php $sum += list_transaction_kewajiban_jangka_panjang($sub->id,date('Y'),'y',''); ?>
                                            </td>
                                            @endif
                                        @else
                                            @if(list_transaction_kewajiban_jangka_panjang($sub->id,date('Y'),'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_kewajiban_jangka_panjang($sub->id,date('Y'),'y','')) }}.00
                                                <?php $sum += list_transaction_kewajiban_jangka_panjang($sub->id,date('Y'),'y',''); ?>
                                            </td>
                                            @endif
                                        @endif
                                    </tr>
                                    @endforeach
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td style="border-top:1px solid black">Total {{ $kewajiban_jangka_panjang->name }}</td>
                                    <td align="right" style="border-top:1px solid black;padding-right:3px">
                                      @if($sum == 0)
                                        0.00
                                      @else
                                        {{ number_format($sum) }}.00<?php $sum_kewajiban_jangka_panjang = $sum; ?>
                                      @endif
                                    </td>
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
                                        @if(isset($year_in))
                                            @if(list_transaction_equitas($as->id,$year,'y','') == '')
                                              @if($as->name == 'MODAL')
                                                <td align="right" style="padding-right:3px">
                                                    {{ number_format($sum_cash_bank_awal+$sum_inventory_awal) }}.00
                                                    <?php $sum += $sum_cash_bank_awal+$sum_inventory_awal; ?>
                                                </td>
                                              @else
                                                <td align="right" style="padding-right:3px">
                                                    {{ number_format(list_transaction_equitas($as->id,$year,'y','')) }}.00
                                                    <?php $sum += list_transaction_equitas($as->id,$year,'y',''); ?>
                                                </td>
                                              @endif
                                            @else
                                              @if($as->name == 'MODAL')
                                                <td align="right" style="padding-right:3px">
                                                    {{ number_format($sum_cash_bank_awal+$sum_inventory_awal) }}.00
                                                    <?php $sum += $sum_cash_bank_awal+$sum_inventory_awal; ?>
                                                </td>
                                              @else
                                                <td align="right" style="padding-right:3px">
                                                    {{ number_format(list_transaction_equitas($as->id,$year,'y','')) }}.00
                                                    <?php $sum += list_transaction_equitas($as->id,$year,'y',''); ?>
                                                </td>
                                              @endif
                                            @endif
                                        @elseif(isset($month_in))
                                            @if(list_transaction_equitas($as->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59') == '')
                                              @if($as->name == 'MODAL')
                                                <td align="right" style="padding-right:3px">
                                                    {{ number_format($sum_cash_bank_awal+$sum_inventory_awal) }}.00
                                                    <?php $sum += $sum_cash_bank_awal+$sum_inventory_awal; ?>
                                                </td>
                                              @else
                                                <td align="right" style="padding-right:3px">
                                                    {{ number_format(list_transaction_equitas($as->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59')) }}.00
                                                    <?php $sum += list_transaction_equitas($as->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59'); ?>
                                                </td>
                                              @endif
                                            @else
                                              @if($as->name == 'MODAL')
                                                <td align="right" style="padding-right:3px">
                                                    {{ number_format($sum_cash_bank_awal+$sum_inventory_awal) }}.00
                                                    <?php $sum += $sum_cash_bank_awal+$sum_inventory_awal; ?>
                                                </td>
                                              @else
                                                <td align="right" style="padding-right:3px">
                                                    {{ number_format(list_transaction_equitas($as->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59')) }}.00
                                                    <?php $sum += list_transaction_equitas($as->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59'); ?>
                                                </td>
                                              @endif
                                            @endif
                                        @else
                                            @if(list_transaction_equitas($as->id,date('Y'),'y','') == '')
                                                @if($as->name == 'MODAL')
                                                  <td align="right" style="padding-right:3px">
                                                      {{ number_format($sum_cash_bank_awal+$sum_inventory_awal) }}.00
                                                      <?php $sum += $sum_cash_bank_awal+$sum_inventory_awal; ?>
                                                  </td>
                                                @else
                                                  <td align="right" style="padding-right:3px">
                                                      {{ number_format(list_transaction_equitas($as->id,date('Y'),'y','')) }}.00
                                                      <?php $sum += list_transaction_equitas($as->id,date('Y'),'y',''); ?>
                                                  </td>
                                                @endif
                                            @else
                                                @if($as->name == 'MODAL')
                                                  <td align="right" style="padding-right:3px">
                                                      {{ number_format($sum_cash_bank_awal+$sum_inventory_awal) }}.00
                                                      <?php $sum += $sum_cash_bank_awal+$sum_inventory_awal; ?>
                                                  </td>
                                                @else
                                                  <td align="right" style="padding-right:3px">
                                                      {{ number_format(list_transaction_equitas($as->id,date('Y'),'y','')) }}.00
                                                      <?php $sum += list_transaction_equitas($as->id,date('Y'),'y',''); ?>
                                                  </td>
                                                @endif
                                            @endif
                                        @endif
                                    </tr>
                                    @endif
                                    @foreach(list_child('2',$as->id) as $sub)
                                    <tr>
                                        <td style="padding-left:40px;">{{ $sub->account_number}}</td>
                                        <td style="padding-left:40px;">{{ $sub->name}}</td>
                                        @if(isset($year_in))
                                            @if(list_transaction_equitas($sub->id,$year,'y','') == '')
                                              @if($sub->name == 'MODAL')
                                                <td align="right" style="padding-right:3px">
                                                    {{ number_format($sum_cash_bank_awal+$sum_inventory_awal) }}.00
                                                    <?php $sum += list_transaction_equitas($sub->id,$year,'y',''); ?>
                                                </td>
                                              @else
                                                <td align="right" style="padding-right:3px">
                                                    {{ number_format(list_transaction_equitas($as->id,$year,'y','')) }}.00
                                                    <?php $sum += list_transaction_equitas($as->id,$year,'y',''); ?>
                                                </td>
                                              @endif
                                            @else
                                              @if($sub->name == 'MODAL')
                                                <td align="right" style="padding-right:3px">
                                                    {{ number_format($sum_cash_bank_awal+$sum_inventory_awal) }}.00
                                                    <?php $sum += list_transaction_equitas($sub->id,$year,'y',''); ?>
                                                </td>
                                              @else
                                                <td align="right" style="padding-right:3px">
                                                    {{ number_format(list_transaction_equitas($as->id,$year,'y','')) }}.00
                                                    <?php $sum += list_transaction_equitas($as->id,$year,'y',''); ?>
                                                </td>
                                              @endif
                                            @endif
                                        @elseif(isset($month_in))
                                            @if(list_transaction_equitas($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_equitas($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59')) }}.00
                                                <?php $sum += list_transaction_equitas($sub->id,$year_start.'-'.$month_start.'-01 00:00:00','m',$year_end.'-'.$month_end.'-31 23:59:59'); ?>
                                            </td>
                                            @endif
                                        @else
                                            @if(list_transaction_equitas($sub->id,date('Y'),'y','') == '')
                                            <td align="right" style="padding-right:3px">0.00</td>
                                            @else
                                            <td align="right" style="padding-right:3px">
                                                {{ number_format(list_transaction_equitas($sub->id,date('Y'),'y','')) }}.00
                                                <?php $sum += list_transaction_equitas($sub->id,date('Y'),'y',''); ?>
                                            </td>
                                            @endif
                                        @endif
                                    </tr>
                                    @endforeach
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td style="border-top:1px solid black">Total {{ $equitas->name }}</td>
                                    <td align="right" style="border-top:1px solid black;padding-right:3px">{{ number_format($sum) }}.00<?php $sum_equitas = $sum; ?></td>
                                </tr>
                                @endif
                            @endforeach
                            <tr>
                                <td></td>
                                <td style="border-top:1px solid black">Total Kewajiban dan Equitas</td>
                                <td align="right" style="border-top:1px solid black;padding-right:3px">{{ number_format($sum_equitas+($sum_kewajiban+$sum_kewajiban_lancar_lainnya+$sum_kewajiban_jangka_panjang)) }}.00</td>
                            </tr>
                        </tbody>
                    </table>
                </center>
            </div>
        </div>
    </div>
@endsection

@section('additional_scripts')
    <script type="text/javascript">
        // $('#btn-submit-neraca').on('click',function(){
        //     var sortYear = document.getElementById('sort_by_year');
        //     if(sortYear.checked){
        //         var year = $('#years').val();
        //         $('#sort_target').text(year);
        //     }
        // });

        $('#btn-submit-neraca-print').on('click',function(){
            var sort_target = $('#sort_target').text();
            $('#cont_sort_target').val(sort_target);
        });

        // $(document).ready(function(){
        //   $('#table-neraca').DataTable({
        //
        //   });
        // });
        $(document).ready(function(){
            $('#73').text($('#sum_akum73').text());
            $('#74').text($('#sum_akum74').text());
            $('#75').text($('#sum_akum75').text());
            $('#sort_target_akum_kendaraan').val($('#sum_akum73').text());
            $('#sort_target_akum_gedung').val($('#sum_akum74').text());
            $('#sort_target_akum_inventaris').val($('#sum_akum75').text());
        });
    </script>
@endsection
