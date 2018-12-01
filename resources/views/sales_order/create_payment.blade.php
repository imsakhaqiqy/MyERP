@extends('layouts.app')

@section('page_title')
    Sales Invoice Payment
@endsection

@section('page_header')
    <h1>
        Sales Invoice Payment
        <small>Add New Sales Invoice Payment</small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('sales-order') }}"><i class="fa fa-dashboard"></i> Sales Order</a></li>
        <li><a href="{{ URL::to('sales-order/'.$invoice->sales_order->id.'') }}"><i class="fa fa-dashboard"></i> {{ $invoice->sales_order->code }}</a></li>
        <li><a href="{{ URL::to('sales-order-invoice/'.$invoice->id.'' )}}"></a><i class="fa fa-dashboard"></i> {{ $invoice->code }}</a></li>
        <li class="active">Create Payment</li>
    </ol>
@endsection

@section('content')
    <ul class="nav nav-tabs">
        <li class="active">
            <a data-toggle="tab" href="#section-payment-method-cash" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none"><i class="fa fa-desktop"></i>&nbsp;Cash</a>
        </li>
        <li>
            <a data-toggle="tab" href="#section-payment-method-bank" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none"><i class="fa fa-desktop"></i>&nbsp;Bank Transfer</a>
        </li>
        <li>
            <a data-toggle="tab" href="#section-payment-method-giro" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none"><i class="fa fa-desktop"></i>&nbsp;Giro</a>
        </li>
    </ul>
    <div class="tab-content">
        <div id="section-payment-method-cash" class="tab-pane fade in active">
            <br>
            <div class="row">
                <div class="col-lg-7">
                    <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                        <div class="box-header with-border">
                            <h3 class="box-title">Basic Information</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            {!! Form::open(['url'=>'storeSalesPaymentCash','role'=>'form','class'=>'form-horizontal','id'=>'form-store-invoice-payment']) !!}
                                <div class="form-group{{ $errors->has('cash_id') ? ' has-error' : '' }}">
                                    {!! Form::label('cash_id','Cash',['class'=>'col-sm-3 control-label']) !!}
                                    <div class="col-sm-6">
                                        {{ Form::select('cash_id',$cashs,null,['class'=>'form-control','placeholder'=>'Select Cash','id'=>'cash_id']) }}
                                        @if($errors->has('cash_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('cash_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                                    {!! Form::label('amount','Amount',['class'=>'col-sm-3 control-label']) !!}
                                    <div class="col-sm-6">
                                    {{ Form::text('amount',null,['class'=>'form-control','placeholder'=>'Payment amount','id'=>'amount-cash','autocomplete'=>'off']) }}
                                    @if($errors->has('amount'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('amount') }}</strong>
                                        </span>
                                    @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('select_account') ? ' has-error' : '' }}">
                                    {!! Form::label('select_account','Cash/Bank Account',['class'=>'col-sm-3 control-label']) !!}
                                    <div class="col-sm-6">
                                    <select name="select_account" class="form-control">
                                        <option value="">Select Account</option>
                                    @foreach(list_account_cash_bank('51') as $as)
                                        @if($as->level == 1)
                                        <optgroup label="{{ $as->name }}">
                                        @endif
                                        @if($as->level == 2)
                                        <option value="{{ $as->id }}">{{ $as->account_number }}&nbsp;&nbsp;{{ $as->name }}</option>
                                        @endif
                                    @endforeach
                                    </select>
                                    @if($errors->has('select_account'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('select_account') }}</strong>
                                        </span>
                                    @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('','',['class'=>'col-sm-2 control-label']) !!}
                                    <div class="col-sm-10">
                                        <a href="{{ url('sales-order-invoice/'.$invoice->id.'') }}" class="btn btn-default">
                                            <i class="fa fa-repeat"></i>&nbsp;Cancel
                                        </a>&nbsp;
                                        <input type="hidden" name="sales_order_invoice_id" value="{{ $invoice->id }}">
                                        <input type="hidden" name="sales_order_invoice_code" value="{{ $invoice->code }}">
                                        <input type="hidden" name="payment_method_id" value="2">
                                        <button type="submit" class="btn btn-info" id="btn-submit-payment">
                                            <i class="fa fa-save"></i>&nbsp;Submit
                                        </button>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div><!-- /.box-body -->
                    </div>
                </div><!-- /.payment-method -->
                <div class="col-lg-5">
                    <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                        <div class="box-header with-border">
                            <h3 class="box-title">Invoice Information</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Invoice Code</strong>
                                </div>
                                <div class="col-md-3">
                                    {{ $invoice->code }}
                                </div>
                            </div>
                            </br>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Bill Price</strong>
                                </div>
                                <div class="col-md-3">
                                    {{ number_format($invoice->bill_price) }}
                                </div>
                            </div>
                            </br>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Paid Price</strong>
                                </div>
                                <div class="col-md-3">
                                    {{ number_format($invoice->paid_price) }}
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.invoice-information -->
            </div>
        </div>
        <div id="section-payment-method-bank" class="tab-pane fade">
            <br>
            <div class="row">
                <div class="col-lg-7">
                    <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                        <div class="box-header with-border">
                            <h3 class="box-title">Basic Information</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            {!! Form::open(['url'=>'storeSalesPaymentTransfer','role'=>'form','class'=>'form-horizontal','id'=>'form-store-invoice-payment']) !!}
                                <div class="form-group{{ $errors->has('bank_id') ? ' has-error' : '' }}">
                                    {!! Form::label('bank_id','Bank',['class'=>'col-sm-3 control-label']) !!}
                                    <div class="col-sm-6">
                                        {{ Form::select('bank_id',$banks,null,['class'=>'form-control','placeholder'=>'Select Bank','id'=>'bank_id']) }}
                                        @if($errors->has('bank_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('bank_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                                  {!! Form::label('amount', 'Amount', ['class'=>'col-sm-3 control-label']) !!}
                                  <div class="col-sm-6">
                                    {{ Form::text('amount', null,['class'=>'form-control', 'placeholder'=>'Payment amount', 'id'=>'amount-bank','autocomplete'=>'off']) }}
                                    @if ($errors->has('amount'))
                                      <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                      </span>
                                    @endif
                                  </div>
                                </div>
                                <div class="form-group{{ $errors->has('select_account') ? ' has-error' : '' }}">
                                    {!! Form::label('select_account','Cash/Bank Account',['class'=>'col-sm-3 control-label']) !!}
                                    <div class="col-sm-6">
                                    <select name="select_account" class="form-control">
                                        <option value="">Select Account</option>
                                    @foreach(list_account_cash_bank('51') as $as)
                                        @if($as->level == 1)
                                        <optgroup label="{{ $as->name }}">
                                        @endif
                                        @if($as->level == 2)
                                        <option value="{{ $as->id }}">{{ $as->account_number }}&nbsp;&nbsp;{{ $as->name }}</option>
                                        @endif
                                    @endforeach
                                    </select>
                                    @if($errors->has('select_account'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('select_account') }}</strong>
                                        </span>
                                    @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                  {!! Form::label('', '', ['class'=>'col-sm-2 control-label']) !!}
                                  <div class="col-sm-10">
                                    <a href="{{ url('sales-order-invoice/'.$invoice->id.'') }}" class="btn btn-default">
                                      <i class="fa fa-repeat"></i>&nbsp;Cancel
                                    </a>&nbsp;
                                    <input type="hidden" name="sales_order_invoice_id" value="{{ $invoice->id }}">
                                    <input type="hidden" name="sales_order_invoice_code" value="{{ $invoice->code }}">
                                    <input type="hidden" name="payment_method_id" value="1">
                                    <button type="submit" class="btn btn-info" id="btn-submit-payment">
                                      <i class="fa fa-save"></i>&nbsp;Submit
                                    </button>
                                    <!-- <button type="button" id="tes">Tes</button> -->
                                  </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                      <div class="box-header with-border">
                        <h3 class="box-title">Invoice Information</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <div class="row">
                          <div class="col-md-6"><strong>Invoice Code</strong></div>
                          <div class="col-md-3">{{ $invoice->code }} </div>
                        </div>
                        <br/>
                        <div class="row">
                          <div class="col-md-6"><strong>Bill Price</strong></div>
                          <div class="col-md-3">{{ number_format($invoice->bill_price) }} </div>
                        </div>
                        <br/>
                        <div class="row">
                          <div class="col-md-6"><strong>Paid Price</strong></div>
                          <div class="col-md-3">{{ number_format($invoice->paid_price) }} </div>
                        </div>


                      </div><!-- /.box-body -->

                    </div><!-- /.box -->
                </div>
            </div>
        </div>
        <div id="section-payment-method-giro" class="tab-pane fade">
            <br>
            <div class="row">
                <div class="col-lg-7">
                    <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                        <div class="box-header with-border">
                            <h3 class="box-title">Basic Information</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            {!! Form::open(['url'=>'storeSalesPaymentGiro','role'=>'form','class'=>'form-horizontal','id'=>'form-store-invoice-payment']) !!}
                                <div class="form-group{{ $errors->has('no_giro') ? ' has-error' : '' }}">
                                    {!! Form::label('no_giro','No.Giro',['class'=>'col-sm-3 control-label']) !!}
                                    <div class="col-sm-6">
                                        {{ Form::text('no_giro',null,['class'=>'form-control','placeholder'=>'Input No.Giro','id'=>'no_giro']) }}
                                        @if($errors->has('no_giro'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('no_giro') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('nama_bank') ? ' has-error' : '' }}">
                                  {!! Form::label('nama_bank', 'Bank', ['class'=>'col-sm-3 control-label']) !!}
                                  <div class="col-sm-6">
                                    {{ Form::text('nama_bank', null,['class'=>'form-control', 'placeholder'=>'Bank of name', 'id'=>'nama_bank','autocomplete'=>'off']) }}
                                    @if ($errors->has('nama_bank'))
                                      <span class="help-block">
                                        <strong>{{ $errors->first('nama_bank') }}</strong>
                                      </span>
                                    @endif
                                  </div>
                                </div>
                                <div class="form-group{{ $errors->has('tanggal_cair') ? ' has-error' : '' }}">
                                  {!! Form::label('tanggal_cair', 'Tanggal Cair', ['class'=>'col-sm-3 control-label']) !!}
                                  <div class="col-sm-6">
                                    {{ Form::date('tanggal_cair', null,['class'=>'form-control', 'placeholder'=>'Tanggal cair of giro', 'id'=>'tanggal_cair','autocomplete'=>'off']) }}
                                    @if ($errors->has('tanggal_cair'))
                                      <span class="help-block">
                                        <strong>{{ $errors->first('tanggal_cair') }}</strong>
                                      </span>
                                    @endif
                                  </div>
                                </div>
                                <div class="form-group{{ $errors->has('amount_giro') ? ' has-error' : '' }}">
                                  {!! Form::label('amount_giro', 'Amount Giro', ['class'=>'col-sm-3 control-label']) !!}
                                  <div class="col-sm-6">
                                    {!! Form::text('amount_giro', null,['class'=>'form-control', 'placeholder'=>'Amount of giro', 'id'=>'amount_giro']) !!}
                                    @if ($errors->has('amount_giro'))
                                      <span class="help-block">
                                        <strong>{{ $errors->first('amount_giro') }}</strong>
                                      </span>
                                    @endif
                                  </div>
                                </div>
                                <div class="form-group{{ $errors->has('gir_account') ? ' has-error' : '' }}">
                                    {!! Form::label('gir_account','Cash/Bank Account',['class'=>'col-sm-3 control-label']) !!}
                                    <div class="col-sm-6">
                                    <select name="gir_account" class="form-control">
                                        <option value="">Select Account</option>
                                    @foreach(list_account_cash_bank('51') as $as)
                                        @if($as->level == 1)
                                        <optgroup label="{{ $as->name }}">
                                        @endif
                                        @if($as->level == 2)
                                        <option value="{{ $as->id }}">{{ $as->account_number }}&nbsp;&nbsp;{{ $as->name }}</option>
                                        @endif
                                    @endforeach
                                    </select>
                                    @if($errors->has('gir_account'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('gir_account') }}</strong>
                                        </span>
                                    @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                  {!! Form::label('', '', ['class'=>'col-sm-2 control-label']) !!}
                                  <div class="col-sm-10">
                                    <a href="{{ url('sales-order-invoice/'.$invoice->id.'') }}" class="btn btn-default">
                                      <i class="fa fa-repeat"></i>&nbsp;Cancel
                                    </a>&nbsp;
                                    <input type="hidden" name="sales_order_invoice_id" value="{{ $invoice->id }}">
                                    <input type="hidden" name="sales_order_invoice_code" value="{{ $invoice->code }}">
                                    <input type="hidden" name="payment_method_id" value="3">
                                    <button type="submit" class="btn btn-info" id="btn-submit-payment">
                                      <i class="fa fa-save"></i>&nbsp;Submit
                                    </button>
                                    <!-- <button type="button" id="tes">Tes</button> -->
                                  </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                      <div class="box-header with-border">
                        <h3 class="box-title">Invoice Information</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <div class="row">
                          <div class="col-md-6"><strong>Invoice Code</strong></div>
                          <div class="col-md-3">{{ $invoice->code }} </div>
                        </div>
                        <br/>
                        <div class="row">
                          <div class="col-md-6"><strong>Bill Price</strong></div>
                          <div class="col-md-3">{{ number_format($invoice->bill_price) }} </div>
                        </div>
                        <br/>
                        <div class="row">
                          <div class="col-md-6"><strong>Paid Price</strong></div>
                          <div class="col-md-3">{{ number_format($invoice->paid_price) }} </div>
                        </div>


                      </div><!-- /.box-body -->

                    </div><!-- /.box -->
                </div>
            </div>
        </div>
    </div>

    <!--Modal Display product datatables-->
    <!-- <div class="modal fade" id="modal-select-chart-account" tabindex="-1" role="dialog" aria-labelledby="modal-select-chartAccountLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modal-display-productsLabel">Chart Account list</h4>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="table-chart-account">
                <thead>
                  <tr>
                    <th style="width:5%;">#</th>
                    <th>Sub Chart Account</th>
                    <th>Account Number</th>
                </tr>
                </thead>
                <thead id="searchid">
                  <tr>
                    <th style="width:5%;">#</th>
                    <th>Sub Chart Account</th>
                    <th>Account Number</th>
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-info" id="btn-set-product">Set selected products</button>
          </div>

        </div>
      </div>
    </div> -->
  <!--ENDModal Display product datatables-->
@endsection

@section('additional_scripts')
    {!! Html::script('js/autoNumeric.js') !!}
    <script type="text/javascript">
        $('#amount-cash').autoNumeric('init',{
            aSep:',',
            aDec:'.'
        });

        $('#amount-bank').autoNumeric('init',{
            aSep:',',
            aDec:'.'
        });

        $('#amount_giro').autoNumeric('init',{
            aSep:',',
            aDec:'.'
        });

        $('#tes').on('click', function(event){
          event.preventDefault();
          $('#modal-select-chart-account').modal('show');
        });

        $('#searchid th').each(function() {
          if ($(this).index() != 0 && $(this).index() != 3) {
              $(this).html('<input class="form-control" type="text" placeholder="Search" data-id="' + $(this).index() + '" />');
          }

        });
    </script>
@endsection
