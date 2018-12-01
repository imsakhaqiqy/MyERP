@extends('layouts.app')

@section('page_title')
  Jurnal Umum
@endsection

@section('page_header')
  <h1>
      Jurnal Umum
    <small>Detail Jurnal Umum</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('biaya-operasi') }}"><i class="fa fa-dashboard"></i> Jurnal Umum</a></li>
    <li class="active"><i></i>TS{{ $trans_chart_account->id }}</li>
  </ol>
@endsection

@section('content')

  <div class="row">
    <div class="col-lg-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-bars"></i>&nbsp;General Information</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table class="table">
            <tr>
              <td><b>Payment Method</b></td>
              <td>
                <?php $pay_method = \DB::table('transaction_chart_accounts')->select('description')->where('reference',$trans_chart_account->id)->value('description'); ?>
                <?php $source = \DB::table('transaction_chart_accounts')->select('source')->where('reference',$trans_chart_account->id)->value('source'); ?>
                @if($pay_method == 2)
                  <?php $cash = \DB::table('cashs')->select('name')->where('id',$source)->value('name'); ?>
                  Cash
                @else
                  <?php $bank = \DB::table('banks')->select('name')->where('id',$source)->value('name'); ?>
                  Transfer
                @endif
              </td>
            </tr>
            @if($pay_method == 2)
            <tr>
              <td><b>Cash</b></td>
              <td>{{ $cash }}</td>
            </tr>
            @else
            <tr>
              <td><b>Bank</b></td>
              <td>{{ $bank }}</td>
            </tr>
            @endif
            <tr>
              <td><b>Memo</b></td>
              <td>{{ $trans_chart_account->description }}</td>
            </tr>
            <tr>
              <td><b>Expenses Account</b></td>
              <td>{{ $trans_chart_account->sub_chart_account->name.' '.'('.$trans_chart_account->sub_chart_account->account_number.')' }}</td>
            </tr>
            <tr>
              <td><b>Cash/Bank Account</b></td>
              <td>
                <?php $sub_chart_account_id = \DB::table('transaction_chart_accounts')->select('sub_chart_account_id')->where('reference',$trans_chart_account->id)->value('sub_chart_account_id'); ?>
                {{\DB::table('sub_chart_accounts')->select('name')->where('id',$sub_chart_account_id)->value('name').' '.'('.\DB::table('sub_chart_accounts')->select('account_number')->where('id',$sub_chart_account_id)->value('account_number').')' }}
              </td>
            </tr>
            <tr>
              <td><b>Debit</b></td>
              <td>{{ number_format($trans_chart_account->amount) }}</td>
            </tr>
            <tr>
              <td><b>Credit</b></td>
              <td>{{ number_format($trans_chart_account->amount) }}</td>
            </tr>
            <tr>
              <td><b>Created At</b></td>
              <td>{{ $trans_chart_account->created_at }}</td>
            </tr>

          </table>
        </div><!-- /.box-body -->
        <div class="box-footer clearfix">

        </div>
      </div><!-- /.box -->
    </div>
  </div>
@endsection

@section('additional_scripts')
  {!! Html::script('js/autoNumeric.js') !!}
  <script type="text/javascript">
    $('#value').autoNumeric('init',{
        aSep:',',
        aDec:'.'
    });
  </script>
@endsection
