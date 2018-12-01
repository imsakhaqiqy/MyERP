@extends('layouts.app')

@section('page_title')
    Forbidden
@endsection

@section('page_header')

@endsection

@section('breadcrumb')

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box" style="height:500px">
                <div class="box-header with-border">
                    <h3><span style="color:blue">403</span> Page Forbidden</h3>
                </div>
                <div class="box-body">
                    <h4>Try one of the following:</h4>
                    <ul>
                        <li>Check back permissions</li>
                        <li>Please remember the normal permissions</li>
                    </ul>
                </div>
                <div class="box-footer clearfix">
                    <a href="javascript:history.back()" class="btn btn-default">
                      <i class="fa fa-repeat"></i>&nbsp;Go Back
                    </a>&nbsp;
                    <a href="{{ url('home') }}" class="btn btn-default">
                      <i class="fa fa-repeat"></i>&nbsp;Dashboard
                    </a>&nbsp;
                </div>
            </div>
        </div>
    </div>
@endsection
