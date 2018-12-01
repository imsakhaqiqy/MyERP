@extends('layouts.app')

@section('page_title')
    Chart Account
@endsection

@section('page_header')
    <h1>
        Chart Account Detail
        <small>{{ $chart_account->name}} </small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('chart-account') }}"><i class="fa fa-dashboard"></i> Chart Account</a></li>
        <li class="active"><i></i> {{ $chart_account->name }}</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bars"></i>&nbsp;General Information</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table  class="table table-hover">
                            <tr>
                                <td style="width:25%">Name</td>
                                <td>:</td>
                                <td>{{ $chart_account->name }}</td>
                            </tr>
                            <tr>
                                <td style="width:25%">Accoount Number</td>
                                <td>:</td>
                                <td>{{ $chart_account->account_number }}</td>
                            </tr>
                            <tr>
                                <td style="width:25%">Description</td>
                                <td>:</td>
                                <td>{{ $chart_account->description }}</td>
                            </tr>
                        </table>
                    </div>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">

                </div><!-- /.box-footer -->
            </div>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Add New Sub Chart Account
                    </h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    {!! Form::open(['url'=>'sub-chart-account.store_sub','role'=>'form','class'=>'form-horizontal','id'=>'form-create-sub-chart-account']) !!}
                    <div class="form-group{{ $errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name','Name',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::select('reference',$cash,null,['class'=>'form-control','placeholder'=>'Name of the sub chart account','id'=>'name']) !!}
                            @if($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('account_number') ? 'has-error' : ''}}">
                        {!! Form::label('account_number','Account Number',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('account_number',null,['class'=>'form-control','placeholder'=>'Account Number of the sub chart account','id'=>'number_account']) !!}
                            @if($errors->has('number_account'))
                            <span class="help-block">
                                <strong>{{ $errors->first('number_account') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="chart_account_id" value="{{ $chart_account->id }}">
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">

                </div><!-- /.box-footer -->
            </div>
            <div class="box">
                <div class="box-body">
                    <div class="form-group">
                        {!! Form::label('','',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            <a href="{{ url('chart-account') }}" class="btn btn-primary">
                                <i class="fa fa-repeat"></i>&nbsp;Cancel
                            </a>
                            <button type="submit" class="btn btn-info" id="btn-submit-sub-chart-account">
                                <i class="fa fa-save"></i>&nbsp;Submit
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Sub Chart Account
                        <small>Sub Chart Account List</small>
                    </h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-bordered" id="table-sub-chart-account">
                        <thead>
                            <tr>
                                <th style="width:5%">#</th>
                                <th style="width:40%">Name</th>
                                <th style="width:30%">Account Number</th>
                                <th style="width:25%;text-align:center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($sub_chart_account->count() > 0)
                            <?php $no = 1; ?>
                              @foreach($sub_chart_account as $key)
                              <tr>
                                <td> {{ $no++ }}</td>
                                <td> {{ $key->cashs->name }}</td>
                                <td> {{ $key->account_number }}</td>
                                <td>
                                    <button type="button" class="btn btn-info btn-xs btn-view-sub-chart-account" data-id="{{ $key->id }}" data-text="{{ $key->cashs->name }}">
                                        <i class="fa fa-external-link-square"></i>
                                    </button>&nbsp;
                                    <button type="button" class="btn btn-success btn-xs btn-edit-sub-chart-account" data-id="{{ $key->id }}" data-text="{{ $key->cashs->name }}" data-account-number="{{ $key->account_number }}">
                                        <i class="fa fa-edit"></i>
                                    </button>&nbsp;
                                    <button type="button" class="btn btn-danger btn-xs btn-delete-sub-chart-account" data-id="{{ $key->id }}" data-text="{{ $key->cashs->name }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                              </tr>
                              @endforeach
                            @else
                            <tr>
                              <td colspan="4">
                                <p class="alert alert-info"><i class="fa fa-info-circle"></i>&nbsp;There is no related sub chart account to this chart account</p>
                              </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">

                </div>
            </div>
        </div>
    </div>

    <!-- Modal view sub chart account -->
    <div class="modal fade" id="modal-view-sub-chart-account" tabindex="-1" role="dialog" aria-labelledby="modal-view-subChartAccountLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="modal-delete-subChartAccountLabel">View Sub Chart Account</h4>
            </div>
            <div class="modal-body">
                <div class="box">
                  <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bars"></i>&nbsp;General Informations</h3>
                  </div><!-- /.box-header -->
                  <div class="box-body">
                    <div class="table-responsive">
                      <table class="table table-hover">
                        <tr>
                          <td style="width:15%;">Code</td>
                          <td>:</td>
                          <td id="view_sub_chart_account_id"></td>
                        </tr>
                        <tr>
                          <td style="width:15%;">Name</td>
                          <td>:</td>
                          <td id="view_sub_chart_account_name"></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                  <div class="box-footer clearfix">

                  </div>
              </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal END -->

    <!-- Modal edit sub chart account -->
    <div class="modal fade" id="modal-edit-sub-chart-account" tabindex="-1" role="dialog" aria-labelledby="modal-edit-subChartAccountLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="modal-edit-subChartAccountLabel">Edit Sub Chart Account</h4>
            </div>
            <div class="modal-body">
                <div class="box">
                  <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bars"></i>&nbsp;General Informations</h3>
                  </div><!-- /.box-header -->
                  <div class="box-body">
                      {!! Form::open(['url'=>'sub-chart-account.update_sub','role'=>'form','class'=>'form-horizontal','id'=>'form-create-sub-chart-account','method'=>'put']) !!}
                      <div class="form-group{{ $errors->has('name') ? 'has-error' : ''}}">
                          {!! Form::label('name','Name',['class'=>'col-sm-3 control-label']) !!}
                          <div class="col-sm-9">
                              {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Name of the sub chart account','id'=>'name_view']) !!}
                              @if($errors->has('name'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('name') }}</strong>
                              </span>
                              @endif
                          </div>
                      </div>
                      <div class="form-group{{ $errors->has('account_number') ? 'has-error' : ''}}">
                          {!! Form::label('account_number','Account Number',['class'=>'col-sm-3 control-label']) !!}
                          <div class="col-sm-9">
                              {!! Form::text('account_number',null,['class'=>'form-control','placeholder'=>'Account Number of the sub chart account','id'=>'number_account_view']) !!}
                              @if($errors->has('number_account'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('number_account') }}</strong>
                              </span>
                              @endif
                              {!! Form::hidden('sub_chart_account_id',null,['id'=>'sub_chart_account_id_view']) !!}
                              <input type="hidden" name="chart_account_id" value="{{ $chart_account->id }}">
                          </div>
                      </div>
                      <input type="hidden" name="chart_account_id" value="{{ $chart_account->id }}">
                      <div class="form-group">
                          {!! Form::label('','',['class'=>'col-sm-3 control-label']) !!}
                          <div class="col-sm-9">
                              <button type="submit" class="btn btn-primary" data-dismiss="modal">
                                  <i class="fa fa-repeat"></i>&nbsp;Cancel
                              </button>
                              <button type="submit" class="btn btn-info" id="btn-submit-sub-chart-account">
                                  <i class="fa fa-save"></i>&nbsp;Submit
                              </button>
                          </div>
                      </div>
                     {!! Form::close() !!}
                  </div>
                  <div class="box-footer clearfix">

                  </div>
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
      </div>
    </div>
    <!-- Modal END -->

    <!-- Modal delete sub chart account -->
    <div class="modal fade" id="modal-delete-sub-chart-account" tabindex="-1" role="dialog" aria-labelledby="modal-delete-subChartAccountLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
        {!! Form::open(['url'=>'deleteSubChartAccount', 'method'=>'post']) !!}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modal-delete-subChartAccountLabel">Confirmation</h4>
          </div>
          <div class="modal-body">
            You are going to remove sub chart account&nbsp;<b id="sub-chart-account-name-to-delete"></b>
            <br/>
            <p class="text text-danger">
              <i class="fa fa-info-circle"></i>&nbsp;This process can not be reverted
            </p>
            <input type="hidden" id="sub_chart_account_id" name="sub_chart_account_id">
            <input type="hidden" name="chart_account_id" value="{{ $chart_account->id }}">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete</button>
          </div>
        {!! Form::close() !!}
        </div>
      </div>
    </div>
    <!-- END Modal -->
@endsection

@section('additional_scripts')
    <script type="text/javascript">
        var tableSubChartAccount = $('#table-sub-chart-account');

        //Delete button handler
        tableSubChartAccount.on('click', '.btn-delete-sub-chart-account', function (e) {
          var id = $(this).attr('data-id');
          var name = $(this).attr('data-text');
          $('#sub_chart_account_id').val(id);
          $('#sub-chart-account-name-to-delete').text(name);
          $('#modal-delete-sub-chart-account').modal('show');
        });

        //View button handler
        tableSubChartAccount.on('click','.btn-view-sub-chart-account',function(e){
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-text');
            $('#view_sub_chart_account_id').text(id);
            $('#view_sub_chart_account_name').text(name);
            $('#modal-view-sub-chart-account').modal('show');
        });

        //Edit button handler
        tableSubChartAccount.on('click','.btn-edit-sub-chart-account',function(e){
            var name = $(this).attr('data-text');
            var account_number = $(this).attr('data-account-number');
            var id = $(this).attr('data-id');
            $('#name_view').val(name);
            $('#number_account_view').val(account_number);
            $('#sub_chart_account_id_view').val(id);
            $('#modal-edit-sub-chart-account').modal('show');
        });

        //TODO search handler
    </script>
@endsection
