@extends('layouts.app')

@section('page_title')
  Adjustment Detail
@endsection

@section('page_header')
  <h1>
    Adjustment
    <small>Detail Adjustment Product</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('product-adjustment') }}"><i class="fa fa-dashboard"></i> Adjustment Product</a></li>
    <li><a href="{{ URL::to('product-adjustment/'.$adjustment->id) }}"><i class="fa fa-dashboard"></i> {{ $adjustment->code }}</a></li>
  </ol>
@endsection

@section('content')

  <div class="row">
    <div class="col-lg-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">General Information</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table class="table">
            <tr>
              <td class="col-lg-2"><b>Adjustment No</b></td>
              <td class="col-lg-4">{{ $adjustment->code }}</td>
            </tr>
            <tr>
              <td class="col-lg-2"><b>IN/OUT</b></td>
              <td class="col-lg-4">{{ $adjustment->in_out }}</td>
            </tr>
            <tr>
              <td class="col-lg-2"><b>Notes</b></td>
              <td class="col-lg-4">{{ $adjustment->notes }}</td>
            </tr>
            <tr>
              <td class="col-lg-2"><b>Created At</b></td>
              <td class="col-lg-4">{{ $adjustment->created_at }}</td>
            </tr>

          </table>
        </div><!-- /.box-body -->
        <div class="box-footer clearfix">
            <div class="table-responsive" style="max-height:500px">
              <table class="table table-striped table-hover" id="table-selected-products">
                  <thead>
                      <tr style="background-color:#3c8dbc;color:white">
                        <th style="width:10%;">Family</th>
                        <th style="width:15%;">Name</th>
                        <th style="width:20%;">Description</th>
                        <th style="width:10%;">Unit</th>
                        <th style="width:15%;">Category</th>
                        <th style="width:10%">Unit Cost</th>
                        <th style="width:10%;">Qty</th>
                        <th style="width:10%">Total</th>
                      </tr>
                 </thead>
                 <tbody>
                     @foreach($adjustment->product_adjustment as $pa)
                        <tr>
                            <td>{{$pa->product->main_product->family->name}}</td>
                            <td>{{$pa->product->name}}</td>
                            <td>{{$pa->product->description}}</td>
                            <td>{{$pa->product->main_product->unit->name}}</td>
                            <td>{{$pa->product->main_product->category->name}}</td>
                            <td>{{number_format($pa->unit_cost)}}</td>
                            <td>{{$pa->qty}}</td>
                            <td>{{number_format($pa->total)}}</td>
                        </tr>
                     @endforeach
                 </tbody>
              </table>
            </div>
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
