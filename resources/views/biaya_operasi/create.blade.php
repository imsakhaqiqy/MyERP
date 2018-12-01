@extends('layouts.app')

@section('page_title')
    Jurnal Umum
@endsection

@section('page_header')
    <h1>
        Jurnal Umum
        <small>Add New Jurnal Umum</small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('biaya-operasi') }}"><i class="fa fa-dashboard"></i> Jurnal Umum</a></li>
        <li class="active"><i></i> Create</li>
    </ol>
@endsection

@section('content')
    {!! Form::open(['route'=>'biaya-operasi.store','role'=>'form','class'=>'form-horizontal','id'=>'form-create-biaya-operasi']) !!}
        <div class="row">
            <div class="col-lg-7">
                <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                    <div class="box-header with-border">
                        <h3 class="box-title">Basic Information</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group{{ $errors->has('pay_method') ? ' has-error' : '' }}">
                          {!! Form::label('pay_method','Payment Method', ['class'=>'col-sm-3 control-label']) !!}
                          <div class="col-sm-6">
                            {!! Form::select('pay_method',['2'=>'Cash','1'=>'Transfer'], null, ['placeholder'=>'Select payment method', 'class'=>'form-control', 'id'=>'pay_method']) !!}
                          </div>
                        </div>
                        <div class="form-group{{ $errors->has('cash_or_bank') ? ' has-error' : '' }}" style="display:none" id="cash_form">
                          {!! Form::label('cash_id', 'Cash', ['class'=>'col-sm-3 control-label']) !!}
                          <div class="col-sm-6">
                              {!! Form::select('',$cashs,null,['class'=>'form-control','placeholder'=>'Select Cash','id'=>'cash_id']) !!}
                              @if($errors->has('cash_or_bank'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('cash_or_bank') }}</strong>
                                  </span>
                              @endif
                          </div>
                        </div>
                        <div class="form-group{{ $errors->has('cash_or_bank') ? ' has-error' : '' }}" style="display:none" id="bank_form">
                          {!! Form::label('bank_id', 'Bank', ['class'=>'col-sm-3 control-label']) !!}
                          <div class="col-sm-6">
                              {!! Form::select('',$banks,null,['class'=>'form-control','placeholder'=>'Select Bank','id'=>'bank_id']) !!}
                              @if($errors->has('cash_or_bank'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('cash_or_bank') }}</strong>
                                  </span>
                              @endif
                          </div>
                        </div>
                        <div class="form-group{{ $errors->has('memo') ? ' has-error' : '' }}">
                          {!! Form::label('memo', 'Memo', ['class'=>'col-sm-3 control-label']) !!}
                          <div class="col-sm-6">
                            {!! Form::textarea('memo',null,['class'=>'form-control', 'placeholder'=>'Memo of the jurnal umum', 'id'=>'memo', 'style'=>'height:200px']) !!}
                            @if ($errors->has('memo'))
                              <span class="help-block">
                                <strong>{{ $errors->first('memo') }}</strong>
                              </span>
                            @endif
                          </div>
                        </div>
                    </div>
                    <div class="box-footer clearfix">
                        <div class="form-group">
                            {!! Form::label('', '', ['class'=>'col-sm-3 control-label']) !!}
                          <div class="col-sm-9">
                            <a href="{{ url('biaya-operasi') }}" class="btn btn-default">
                              <i class="fa fa-repeat"></i>&nbsp;Cancel
                            </a>&nbsp;
                            <button type="submit" class="btn btn-info" id="btn-submit-biaya-operasi">
                              <i class="fa fa-save"></i>&nbsp;Submit
                            </button>
                          </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                    <div class="box-header with-border">
                        <h3 class="box-title">Account</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group{{ $errors->has('beban_operasi_account') ? ' has-error' : '' }}">
                            {!! Form::label('expenses_account', 'Expenses Account', ['class'=>'col-sm-4 control-label']) !!}
                            <div class="col-sm-6">
                                <select name="beban_operasi_account" class="form-control">
                                  @foreach($sub_account as $sub)
                                    <option value="{{ $sub->id}}">{{ $sub->account_number}}&nbsp;&nbsp;{{ $sub->name}}</option>
                                  @endforeach
                                </select>
                                @if ($errors->has('beban_operasi_account'))
                                  <span class="help-block">
                                    <strong>{{ $errors->first('beban_operasi_account') }}</strong>
                                  </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('cash_bank_account') ? ' has-error' : '' }}">
                            {!! Form::label('cash_bank_account', 'Cash/Bank Account', ['class'=>'col-sm-4 control-label']) !!}
                            <div class="col-sm-6">
                                <select name="cash_bank_account" id="cash_bank_account" class="form-control">
                                  <option value="">Cash/Bank Account</option>
                                  @foreach(list_account_inventory('51') as $as)
                                    @if($as->level ==1)
                                    <optgroup label="{{ $as->name}}">
                                    @endif
                                    @foreach(list_sub_inventory('2',$as->id) as $sub)
                                      <option value="{{ $sub->id}}">{{ $sub->account_number }}&nbsp;&nbsp;{{ $sub->name}}</option>
                                    @endforeach
                                  @endforeach
                                </select>
                                @if ($errors->has('cash_bank_account'))
                                  <span class="help-block">
                                    <strong>{{ $errors->first('cash_bank_account') }}</strong>
                                  </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">

                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                    <div class="box-header with-border">
                        <h3 class="box-title">Amount</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group{{ $errors->has('debit') ? ' has-error' : '' }}">
                          {!! Form::label('debit', 'Debit', ['class'=>'col-sm-4 control-label']) !!}
                          <div class="col-sm-6">
                            {!! Form::text('debit',null,['class'=>'form-control', 'placeholder'=>'Debit of the jurnal umum', 'id'=>'debit']) !!}
                            @if ($errors->has('debit'))
                              <span class="help-block">
                                <strong>{{ $errors->first('debit') }}</strong>
                              </span>
                            @endif
                          </div>
                        </div>
                        <div class="form-group{{ $errors->has('credit') ? ' has-error' : '' }}">
                          {!! Form::label('credit', 'Credit', ['class'=>'col-sm-4 control-label']) !!}
                          <div class="col-sm-6">
                            {!! Form::text('credit',null,['class'=>'form-control', 'placeholder'=>'Credit of the jurnal umum', 'id'=>'credit']) !!}
                            @if ($errors->has('credit'))
                              <span class="help-block">
                                <strong>{{ $errors->first('credit') }}</strong>
                              </span>
                            @endif
                          </div>
                        </div>
                    </div>
                    <div class="box-footer clearfix">

                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>

@endsection

@section('additional_scripts')
    {!! Html::script('js/autoNumeric.js') !!}
    <script type="text/javascript">
        $('#amount').autoNumeric('init',{
            aSep:',',
            aDec:'.'
        });
        $('#debit').autoNumeric('init',{
            aSep:',',
            aDec:'.'
        });
        $('#credit').autoNumeric('init',{
            aSep:',',
            aDec:'.'
        });
    </script>

    <script type="text/javascript">
        $('#pay_method').on('click',function(){
            var payMethod = $('#pay_method').val();
            if(payMethod == 2)
            {
                $('#cash_form').show();
                $('#bank_form').hide();
                $('#cash_id').attr('name','cash_or_bank');
            }else if (payMethod == 1)
            {
                $('#bank_form').show();
                $('#bank_id').attr('name','cash_or_bank');
                $('#cash_form').hide();
            }else{
                $('#bank_form').hide();
                $('#bank_id').attr('name','');
                $('#cash_form').hide();
                $('#cash_id').attr('name','');
            }
        });
    </script>

    <script type="text/javascript">
        $('#credit').on('keyup', function(){
            var credit = parseInt($('#credit').val());
            var debit = parseInt($('#debit').val());
            if(credit != debit){
                $('#btn-submit-biaya-operasi').prop('disabled',true);
            }else{
                $('#btn-submit-biaya-operasi').prop('disabled',false);
            }
            return false;
        });

    </script>
    <!--ENDBlock Compare control returned quantity to sales quantity-->
@endsection
