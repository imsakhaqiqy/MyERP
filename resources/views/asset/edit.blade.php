@extends('layouts.app')

@section('page_title')
  Asset
@endsection

@section('page_header')
  <h1>
    Asset
    <small>Edit Asset</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('asset') }}"><i class="fa fa-dashboard"></i> Asset</a></li>
    <li> {{ $asset->code }}</li>
    <li class="active"><i></i>Edit</li>
  </ol>
@endsection

@section('content')
  {!! Form::model($asset,['route'=>['asset.update', $asset], 'class'=>'form-horizontal','id'=>'form-edit-asset', 'method'=>'put']) !!}
  <div class="row">
    <div class="col-lg-7">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Basic Information</h3>

        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
            {!! Form::label('code', 'Code', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              {!! Form::text('code',null,['class'=>'form-control', 'placeholder'=>'Code of the asset', 'id'=>'code', 'readonly']) !!}
              @if ($errors->has('code'))
                <span class="help-block">
                  <strong>{{ $errors->first('code') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            {!! Form::label('name', 'Name', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              {!! Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Name of the asset', 'id'=>'name']) !!}
              @if ($errors->has('name'))
                <span class="help-block">
                  <strong>{{ $errors->first('name') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('date_purchase') ? ' has-error' : '' }}">
            {!! Form::label('date_purchase', 'Date Purchase', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              {!! Form::date('date_purchase',null,['class'=>'form-control', 'placeholder'=>'Date purchase of asset', 'id'=>'date_purchase']) !!}
              @if ($errors->has('date_purchase'))
                <span class="help-block">
                  <strong>{{ $errors->first('date_purchase') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
            {!! Form::label('amount', 'Amount', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              {!! Form::text('amount',null,['class'=>'form-control', 'placeholder'=>'Amount of asset', 'id'=>'amount']) !!}
              @if ($errors->has('amount'))
                <span class="help-block">
                  <strong>{{ $errors->first('amount') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('residual_value') ? ' has-error' : '' }}">
            {!! Form::label('residual_value', 'Residual Value', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              {!! Form::text('residual_value',null,['class'=>'form-control', 'placeholder'=>'Residual value of asset', 'id'=>'residual_value']) !!}
              @if ($errors->has('residual_value'))
                <span class="help-block">
                  <strong>{{ $errors->first('residual_value') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('periode') ? ' has-error' : '' }}">
            {!! Form::label('periode', 'Periode', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              <div class="input-group{{ $errors->has('periode') ? ' has-error' : '' }}">
                  {!! Form::text('periode',null,['class'=>'form-control', 'placeholder'=>'Periode of asset', 'id'=>'periode']) !!}
                  @if ($errors->has('periode'))
                    <span class="help-block">
                      <strong>{{ $errors->first('periode') }}</strong>
                    </span>
                  @endif
                  <span class="input-group-addon">Bulan</span>
              </div>
            </div>
          </div>
          <div class="form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
            {!! Form::label('notes', 'Notes', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              {!! Form::textarea('notes',null,['class'=>'form-control', 'placeholder'=>'Initial notes of the asset', 'id'=>'notes']) !!}
              @if ($errors->has('notes'))
                <span class="help-block">
                  <strong>{{ $errors->first('notes') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <input type="hidden" name="notes_old" value="{{ $asset->name}}">
          <input type="hidden" name="created_at_old" value="{{ $asset->created_at }}">
          <div class="form-group">
              {!! Form::label('', '', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              <a href="{{ url('asset') }}" class="btn btn-default">
                <i class="fa fa-repeat"></i>&nbsp;Cancel
              </a>&nbsp;
              <button type="submit" class="btn btn-info" id="btn-submit-asset">
                <i class="fa fa-save"></i>&nbsp;Submit
              </button>
            </div>
          </div>
        </div><!-- /.box-body -->
        <div class="box-footer clearfix">

        </div>
      </div><!-- /.box -->

    </div>
    <div class="col-lg-5">
        <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
            <div class="box-header with-border">
                <h3 class="box-title">Select Account</h3>
            </div>
            <div class="box-body">
                <div class="form-group{{ $errors->has('asset_account') ? ' has-error' : '' }}">
                    {!! Form::label('asset_account', 'Asset', ['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-9">
                        <select name="asset_account" id="asset_account" class="form-control">
                          <option value="">Asset Account</option>
                          @foreach(list_parent('68') as $as)
                            @if($as->level ==1)
                            <optgroup label="{{ $as->name}}">
                            @endif
                            @foreach(list_child('2',$as->id) as $sub)
                              <option value="{{ $sub->id}}">{{ $sub->account_number }}&nbsp;&nbsp;{{ $sub->name}}</option>
                            @endforeach
                          @endforeach
                        </select>
                        @if ($errors->has('beban_operasi_account'))
                          <span class="help-block">
                            <strong>{{ $errors->first('beban_operasi_account') }}</strong>
                          </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('biaya_penyusutan_account') ? ' has-error' : '' }}">
                    {!! Form::label('biaya_penyusutan_account', 'Biaya Penyusutan', ['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-9">
                        <select name="biaya_penyusutan_account" id="biaya_penyusutan_account" class="form-control">
                          <option value="">Asset Account</option>
                          @foreach(list_parent('64') as $as)
                            @if($as->level ==1)
                            <optgroup label="{{ $as->name}}">
                            @endif
                            @foreach(list_child('2',$as->id) as $sub)
                              <option value="{{ $sub->id}}">{{ $sub->account_number }}&nbsp;&nbsp;{{ $sub->name}}</option>
                            @endforeach
                          @endforeach
                        </select>
                        @if ($errors->has('beban_operasi_account'))
                          <span class="help-block">
                            <strong>{{ $errors->first('beban_operasi_account') }}</strong>
                          </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('akumulasi_penyusutan_account') ? ' has-error' : '' }}">
                    {!! Form::label('akumulasi_penyusutan_account', 'Akumulasi Penyusutan', ['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-9">
                        <select name="akumulasi_penyusutan_account" id="akumulasi_penyusutan_account" class="form-control">
                          <option value="">Asset Account</option>
                          @foreach(list_parent('55') as $as)
                            @if($as->level ==1)
                            <optgroup label="{{ $as->name}}">
                            @endif
                            @foreach(list_child('2',$as->id) as $sub)
                              <option value="{{ $sub->id}}">{{ $sub->account_number }}&nbsp;&nbsp;{{ $sub->name}}</option>
                            @endforeach
                          @endforeach
                        </select>
                        @if ($errors->has('beban_operasi_account'))
                          <span class="help-block">
                            <strong>{{ $errors->first('beban_operasi_account') }}</strong>
                          </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="box-footer clearfix">

            </div>
        </div>
    </div>
  </div>
  {!! Form::close() !!}
@endsection

@section('additional_scripts')
{!! Html::script('js/autoNumeric.js') !!}
<script type="text/javascript">
    $('#amount').autoNumeric('init',{
        aSep:',',
        aDec:'.'
    });
    $('#residual_value').autoNumeric('init',{
        aSep:',',
        aDec:'.'
    });
</script>
@endsection
