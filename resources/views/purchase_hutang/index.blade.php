@extends('layouts.app')

@section('page_title')
  List Hutang
@endsection

@section('page_header')
  <h1>
    List Hutang
    <small>List Hutang Detail</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('purchase-hutang') }}"><i class="fa fa-cart-arrow-down"></i> Purchase Hutang</a></li>
    <li class="active">Index</li>
  </ol>
@endsection

@section('content')
    <ul class="nav nav-tabs">
      <li class="active">
        <a data-toggle="tab" href="#section-riwayat-hutang" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none"><i class="fa fa-desktop"></i>&nbsp;Riwayat Hutang</a>
      </li>
      <li>
        <a data-toggle="tab" href="#section-belum-lunas" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none"><i class="fa fa-bookmark"></i>&nbsp;Faktur Belum Lunas</a>
      </li>
    </ul>
    <div class="tab-content">
        <div id="section-riwayat-hutang" class="tab-pane fade in active">
            <br>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                        <div class="box-header with-border">
                            <h3 class="box-title">Supplier Summary</h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive" style="max-height:500px">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr style="background-color:#3c8dbc;color:white">
                                            <th style="width:15%;">Supplier Code</th>
                                            <th style="width:40%;" colspan="3">Supplier Name</th>
                                            <th style="width:30%;" colspan="2">Balance</th>
                                            <th style="width:15%;">Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sum_balance = 0; ?>
                                        @foreach($data_hutang as $dat)
                                        <?php $sum_bill_price = 0; $sum_paid_price = 0;?>
                                            <tr>
                                                <td>{{ $dat['code'] }}</td>
                                                <td colspan="3">{{ $dat['name'] }}</td>
                                                <td colspan="2" class="target_sum"></td>
                                                <td><a data-toggle="collapse" href=".demo{{$dat['id']}}">Invoices</a></td>
                                            </tr>
                                            <tr class="demo{{ $dat['id']}} collapse">
                                                <th></th>
                                                <th style="background-color:#3c8dbc;color:white">Code Invoice</th>
                                                <th style="background-color:#3c8dbc;color:white">Created At</th>
                                                <th style="background-color:#3c8dbc;color:white">Due Date</th>
                                                <th style="background-color:#3c8dbc;color:white">Bill Price</th>
                                                <th style="background-color:#3c8dbc;color:white">Paid Price</th>
                                                <th style="background-color:#3c8dbc;color:white">Age</th>
                                            </tr>
                                            @foreach($dat['purchase'] as $pur)
                                                <tr class="demo{{$dat['id']}} collapse">
                                                    <td></td>
                                                    <td>{{ $pur['code'] }}</td>
                                                    <td>{{ $pur['created_at'] }}</td>
                                                    <td>{{ $pur['due_date']}}</td>
                                                    <td>
                                                        {{ number_format($pur['bill_price']) }}
                                                        <?php $sum_bill_price += $pur['bill_price']; ?>
                                                    </td>
                                                    <td>
                                                        {{ number_format($pur['paid_price']) }}
                                                        <?php $sum_paid_price += $pur['paid_price']; ?>
                                                    </td>
                                                    <td>
                                                      <?php
                                                          $date1 = date_create(date('Y-m-d'));
                                                          $date2 = date_create($pur['due_date']);
                                                          $diff = date_diff($date1,$date2);
                                                          echo $diff->format("%R%a days");
                                                      ?>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr style="display:none">
                                              <td colspan="3" class="sum">
                                                  {{ number_format($sum_bill_price-$sum_paid_price) }}
                                                  <?php $sum_balance += $sum_bill_price-$sum_paid_price; ?>
                                              </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" align="right">Total Hutang</td>
                                            <td style="background-color:red;color:white">{{ number_format($sum_balance) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="box-footer">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="section-belum-lunas" class="tab-pane fade">
            <br>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                        <div class="box-header with-border">
                            <h3 class="box-title">Supplier Summary</h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive" style="max-height:500px">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr style="background-color:#3c8dbc;color:white">
                                            <th style="width:20%;">No. Faktur</th>
                                            <th style="width:20%;">Tanggal Faktur</th>
                                            <th style="width:20%;">Jatuh Tempo</th>
                                            <th style="width:15%;">Nilai Faktur</th>
                                            <th style="width:15%;">Hutang</th>
                                            <th style="width:10%;">Umur</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data_hutang as $dat)
                                            <?php $sum_bill_price = 0; $sum_hutang = 0; ?>
                                            <tr>
                                                <td>{{ $dat['code']}}</td>
                                                <td>{{ $dat['name']}}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            @foreach($dat['purchase'] as $pur)
                                                <tr>
                                                    <td align="center">{{ $pur['code']}}</td>
                                                    <td align="center">{{ $pur['created_at']}}</td>
                                                    <td align="center">{{ $pur['due_date']}}</td>
                                                    <td align="center">
                                                        {{ number_format($pur['bill_price'])}}
                                                        <?php $sum_bill_price += $pur['bill_price']; ?>
                                                    </td>
                                                    <td align="center">
                                                        {{ number_format($pur['bill_price']-$pur['paid_price'])}}
                                                        <?php $sum_hutang += $pur['bill_price']-$pur['paid_price']; ?>
                                                    </td>
                                                    <td align="center">
                                                        <?php
                                                            $date1 = date_create(date('Y-m-d'));
                                                            $date2 = date_create($pur['due_date']);
                                                            $diff = date_diff($date1,$date2);
                                                            echo $diff->format("%R%a days");
                                                        ?>
                                                    </td>
                                                </tr>
                                            @endforeach
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td align="center" style="background-color:blue;color:white">{{ number_format($sum_bill_price) }}</td>
                                                    <td align="center" style="background-color:red;color:white">{{ number_format($sum_hutang)}}</td>
                                                </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>

                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="box-footer">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('additional_scripts')
<script type="text/javascript">
    var sum = document.getElementsByClassName('sum');
    for(var a = 0; a < sum.length; a++){
      document.getElementsByClassName('target_sum')[a].innerHTML = document.getElementsByClassName('sum')[a].innerHTML;
    }
</script>
@endsection
