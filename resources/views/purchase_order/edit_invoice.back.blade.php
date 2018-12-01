@extends('layouts.app')

@section('page_title')
  Purchase Order Invoice
@endsection

@section('page_header')
  <h1>
    Edit Purchase Order Invoice
    <small>Create purchase order invoice</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('purchase-order-invoice') }}"><i class="fa fa-dashboard"></i> Purchase Order Invoice</a></li>
    <li><a href="{{ URL::to('purchase-order/'.$purchase_order_invoice->id.'') }}"><i class="fa fa-dashboard"></i> {{ $purchase_order_invoice->code }}</a></li>
    <li class="active">Edit</li>
  </ol>
@endsection

@section('content')

  <!-- Row Invoice-->
  <div class="row">
    <div class="col-lg-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Form Edit Invoice</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          {!! Form::model($purchase_order_invoice, ['route'=>['purchase-order-invoice.update', $purchase_order_invoice->id], 'id'=>'form-edit-purchase-order-invoice', 'class'=>'form-horizontal','method'=>'put', 'files'=>true]) !!}
            <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
              {!! Form::label('code', 'Code', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-6">
                {!! Form::text('code',null,['class'=>'form-control', 'placeholder'=>'Code of the invoice', 'id'=>'code']) !!}
                @if ($errors->has('code'))
                  <span class="help-block">
                    <strong>{{ $errors->first('code') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group{{ $errors->has('bill_price') ? ' has-error' : '' }}">
              {!! Form::label('bill_price', 'Bill Price', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-6">
                {!! Form::text('bill_price',null,['class'=>'form-control', 'placeholder'=>'Bill price of the invoice', 'id'=>'bill_price']) !!}
                @if ($errors->has('bill_price'))
                  <span class="help-block">
                    <strong>{{ $errors->first('bill_price') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group{{ $errors->has('paid_price') ? ' has-error' : '' }}">
              {!! Form::label('paid_price', 'Paid Price', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-6">
                {!! Form::text('paid_price',null,['class'=>'form-control', 'placeholder'=>'Paid price for the invoice', 'id'=>'paid_price']) !!}
                @if ($errors->has('paid_price'))
                  <span class="help-block">
                    <strong>{{ $errors->first('paid_price') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
              {!! Form::label('status', 'Status', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-6">
                {!! Form::select('status',['paid'=>'Paid', 'unpaid'=>'Unpaid'], null, ['placeholder'=>'--Select Payment status--', 'class'=>'form-control', 'id'=>'status']) !!}
                @if ($errors->has('status'))
                  <span class="help-block">
                    <strong>{{ $errors->first('status') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
              {!! Form::label('notes', 'Notes', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-6">
                {!! Form::textarea('notes',null,['class'=>'form-control', 'placeholder'=>'Paid price for the invoice', 'id'=>'notes']) !!}
                @if ($errors->has('notes'))
                  <span class="help-block">
                    <strong>{{ $errors->first('notes') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group">
                {!! Form::label('', '', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-6">
                <a href="{{ url('purchase-order-invoice') }}" class="btn btn-default">
                  <i class="fa fa-repeat"></i>&nbsp;Cancel
                </a>&nbsp;
                <button type="submit" class="btn btn-info" id="btn-submit-purchase-order-invoice">
                  <i class="fa fa-save"></i>&nbsp;Submit
                </button>
              </div>
            </div>
            {!! Form::hidden('purchase_order_invoice_id', $purchase_order_invoice->id) !!}
          {!! Form::close() !!}
        </div><!-- /.box-body -->

      </div><!-- /.box -->
    </div>
  </div>
  <!-- ENDRow Invoice-->






@endsection


@section('additional_scripts')

  {!! Html::script('js/autoNumeric.js') !!}
  <script type="text/javascript">
    $('#bill_price').autoNumeric('init',{
        aSep:',',
        aDec:'.'
    });
    $('#paid_price').autoNumeric('init',{
        aSep:',',
        aDec:'.'
    });

  </script>

  <script type="text/javascript">
  //Block handle form create purchase order submission
    $('#form-edit-purchase-order-invoice').on('submit', function(event){
      $('#btn-submit-purchase-order-invoice').attr('disable','disabled');
    });
  //ENDBlock handle form create purchase order submission
  </script>

@endSection
