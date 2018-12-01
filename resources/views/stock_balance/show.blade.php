@extends('layouts.app')

@section('page_title')
    Stock Balance Detail
@endsection

@section('page_header')
    <h1>
        Stock Balance
        <small>Detail Stock Balance</small>
    </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('stock_balance') }}"><i class="fa fa-dashboard"></i> Stock Balance</a></li>
    <li class="active"><i></i> {{ $stock_balance->code }}</li>
  </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3">

        </div>
        <div class="col-md-6">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-bars"></i>&nbsp;General Information</h3>
                </div><!-- /.box header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tr>
                                <td style="width:20%">Code</td>
                                <td>:</td>
                                <td>{{ $stock_balance->code }}</td>
                            </tr>
                            <tr>
                                <td style="width:20%">Created By</td>
                                <td>:</td>
                                <td>{{ $stock_balance->creator->name }}</td>
                            </tr>
                            <tr>
                                <td style="width:20%">Created At</td>
                                <td>:</td>
                                <td>{{ $stock_balance->created_at }}</td>
                            </tr>
                            <tr>
                                <td style="width:20%">Updated At</td>
                                <td>:</td>
                                <td>{{ $stock_balance->updated_at }}</td>
                            </tr>
                        </table>
                    </div>
                </div><!-- /.box body -->
                <div class="box-footer clearfix">

                </div>
            </div>
        </div>
        <div class="col-md-3">

        </div>

        <div class="col-md-12">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-bookmark-o"></i>&nbsp;Product Information</h3>
                    <div class="pull-right">
                      <!--Show button create payment only when invoice status is NOT completed yet-->
                      <a href="{{ url('stock_balance/'.$stock_balance->id.'/printPdf') }}" class="btn btn-default btn-xs">
                        <i class='fa fa-print'></i>&nbsp;Print
                      </a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive" style="max-height:500px">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:5%">#</th>
                                    <th>Family</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Unit</th>
                                    <th>Category</th>
                                    <th>System Stock</th>
                                    <th>Real Stock</th>
                                    <th>Information</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $x =1; ?>
                                @foreach($dataList as $view)
                                    <tr>
                                        <td>{{ $x++ }}</td>
                                        <td>{{ \DB::table('families')->select('name')->where('id',$view->family_id)->value('name') }}</td>
                                        <td>{{ $view->name }}</td>
                                        <td>{{ $view->description }}</td>
                                        <td>{{ \DB::table('units')->select('name')->where('id',$view->unit_id)->value('name') }}</td>
                                        <td>{{ \DB::table('categories')->select('name')->where('id',$view->category_id)->value('name') }}</td>
                                        <td>{{ $view->system_stock }}</td>
                                        <td>@if($view->real_stock<$view->system_stock)
                                                <span style="color:red;font-weight:bold">
                                            @else
                                                <span style="color:green;font-weight:bold">
                                            @endif
                                            {{ $view->real_stock }}</span>
                                        </td>
                                        <td>{{ $view->information }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
