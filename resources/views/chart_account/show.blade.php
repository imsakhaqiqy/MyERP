@extends('layouts.app')

@section('page_title')
    Chart Account Detail
@endsection

@section('page_header')
    <h1>
        Chart Account
        <small>Detail Chart Account</small>
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
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
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
                                <td style="width:25%">Account Number</td>
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
            <H3>Add New Sub Chart Account</H3>
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Basic Information
                    </h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    {!! Form::open(['url'=>'sub-chart-account.store_sub','role'=>'form','class'=>'form-horizontal','id'=>'form-create-sub-chart-account']) !!}
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
                        {!! Form::label('name','Name',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Name of the sub chart account','id'=>'name']) !!}
                            @if($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('account_number') ? ' has-error' : ''}}">
                        {!! Form::label('account_number','Account Number',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('account_number',null,['class'=>'form-control','placeholder'=>'Account Number of the sub chart account','id'=>'number_account']) !!}
                            @if($errors->has('account_number'))
                            <span class="help-block">
                                <strong>{{ $errors->first('account_number') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('level') ? ' has-error' : '' }}">
                        {!! Form::label('level','Parent/Child',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::select('level',['1'=>'Parent','2'=>'Child'],null,['placeholder'=>'Parent/Child','class'=>'form-control','id'=>'level']) !!}
                            @if($errors->has('level'))
                            <span class="help-block">
                                <strong>{{ $errors->first('level') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('select_parent') ? 'has-error' : '' }}" style="display:none" id="display_parent">
                        {!! Form::label('select_parent','Select Parent',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            <select class="form-control" name="parent_id">
                                <option value="0">Select Parent</option>
                                @foreach($sub_chart_account as $sub)
                                    @if($sub->level == 1)
                                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                    @endif
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('saldo_awal') ? ' has-error' : ''}}" id="saldo_awal_create" style="display:none">
                        {!! Form::label('saldo_awal','Saldo Awal',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('saldo_awal',null,['class'=>'form-control','placeholder'=>'Saldo awal of the sub chart account','id'=>'saldo_awal']) !!}
                            @if($errors->has('saldo_awal'))
                            <span class="help-block">
                                <strong>{{ $errors->first('saldo_awal') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="chart_account_id" value="{{ $chart_account->id }}">

                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
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
                </div><!-- /.box-footer -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3 class="box-title">Sub Chart Account
                        <small>Sub Chart Account List</small>
                    </h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive" style="max-height:500px">
                        <table class="table table-striped table-hover" id="table-sub-chart-account">
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
                                  @if($key->level == 1)
                                  <tr>
                                    <td> {{ $no++ }}</td>
                                    <td> {{ $key->name }}</td>
                                    <td> {{ $key->account_number }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-xs btn-view-sub-chart-account" data-id="{{ $key->id }}" data-text="{{ $key->name }}" data-account-number="{{ $key->account_number }}" data-saldo="{{ number_format($key->saldo_awal) }}" data-created-at="{{ $key->created_at }}" data-level="{{ $key->level }}" title="Click to this detail">
                                            <i class="fa fa-external-link-square"></i>
                                        </button>&nbsp;
                                        <button type="button" class="btn btn-success btn-xs btn-edit-sub-chart-account" data-id="{{ $key->id }}" data-text="{{ $key->name }}" data-account-number="{{ $key->account_number }}" data-saldo="{{ number_format($key->saldo_awal) }}" data-level="{{ $key->level }}" title="Click to edit this sub chart account">
                                            <i class="fa fa-edit"></i>
                                        </button>&nbsp;
                                        @if(\Auth::user()->can('delete-chart-account-module'))
                                          <button type="button" class="btn btn-danger btn-xs btn-delete-sub-chart-account" data-id="{{ $key->id }}" data-text="{{ $key->name }}" title="Click to delete this sub chart account">
                                              <i class="fa fa-trash"></i>
                                          </button>
                                        @endif
                                    </td>
                                  </tr>
                                  @foreach(child_chart_account($key->id) as $child)
                                    <tr>
                                        <td></td>
                                        <td style="padding-left:20px">{{ $child->name }}</td>
                                        <td style="padding-left:20px">{{ $child->account_number }}</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-xs btn-view-sub-chart-account" data-id="{{ $child->id }}" data-text="{{ $child->name }}" data-account-number="{{ $child->account_number }}" data-saldo="{{ number_format($child->saldo_awal) }}" data-created-at="{{ $child->created_at }}" data-level="{{ $child->level }}" data-parent="{{ \DB::table('sub_chart_accounts')->where('id',$child->parent_id)->value('name') }}" title="Click to this detail">
                                                <i class="fa fa-external-link-square"></i>
                                            </button>&nbsp;
                                            <button type="button" class="btn btn-success btn-xs btn-edit-sub-chart-account" data-id="{{ $child->id }}" data-text="{{ $child->name }}" data-account-number="{{ $child->account_number }}" data-saldo="{{ number_format($child->saldo_awal) }}" data-level="{{ $child->level }}" data-parent="{{ $child->parent_id }}" title="Click to edit this sub chart account">
                                                <i class="fa fa-edit"></i>
                                            </button>&nbsp;
                                            @if(\Auth::user()->can('delete-chart-account-module'))
                                              <button type="button" class="btn btn-danger btn-xs btn-delete-sub-chart-account" data-id="{{ $child->id }}" data-text="{{ $child->name }}" title="Click to delete this sub chart account">
                                                  <i class="fa fa-trash"></i>
                                              </button>
                                            @endif
                                        </td>
                                    </tr>
                                  @endforeach
                                  @endif
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
                          <td style="width:30%;">Name</td>
                          <td>:</td>
                          <td id="view_sub_chart_account_name"></td>
                        </tr>
                        <tr>
                          <td style="width:30%;">Account Number</td>
                          <td>:</td>
                          <td id="view_sub_chart_account_account_number"></td>
                        </tr>
                        <tr>
                          <td style="width:30%;">Parent/Child</td>
                          <td>:</td>
                          <td id="view_sub_chart_account_level"></td>
                        </tr>
                        <tr style="display:none" id="parent_tr">
                          <td style="width:30%;">Parent Name</td>
                          <td>:</td>
                          <td id="view_sub_chart_account_parent_name"></td>
                        </tr>
                        <tr>
                          <td style="width:30%;">Created At</td>
                          <td>:</td>
                          <td id="view_sub_chart_account_created_at"></td>
                        </tr>
                        <tr style="display:none" id="saldo_awal_tr">
                          <td style="width:30%;">Saldo Awal</td>
                          <td>:</td>
                          <td id="view_sub_chart_account_saldo_awal"></td>
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
                      <div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
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
                      <div class="form-group{{ $errors->has('account_number') ? ' has-error' : ''}}">
                          {!! Form::label('account_number','Account Number',['class'=>'col-sm-3 control-label']) !!}
                          <div class="col-sm-9">
                              {!! Form::text('account_number',null,['class'=>'form-control','placeholder'=>'Account Number of the sub chart account','id'=>'number_account_view']) !!}
                              @if($errors->has('account_number'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('account_number') }}</strong>
                              </span>
                              @endif
                              {!! Form::hidden('sub_chart_account_id',null,['id'=>'sub_chart_account_id_view']) !!}
                              <input type="hidden" name="chart_account_id" value="{{ $chart_account->id }}">
                          </div>
                      </div>
                      <div class="form-group{{ $errors->has('level') ? ' has-error' : '' }}">
                          {!! Form::label('level','Parent/Child',['class'=>'col-sm-3 control-label']) !!}
                          <div class="col-sm-9">
                              {!! Form::select('level',['1'=>'Parent','2'=>'Child'],null,['placeholder'=>'Parent/Child','class'=>'form-control','id'=>'level_update']) !!}
                              @if($errors->has('level'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('level') }}</strong>
                              </span>
                              @endif
                          </div>
                      </div>
                      <div class="form-group{{ $errors->has('select_parent') ? 'has-error' : '' }}" style="display:none" id="display_parent_update">
                          {!! Form::label('select_parent','Select Parent',['class'=>'col-sm-3 control-label']) !!}
                          <div class="col-sm-9">
                              <select class="form-control" name="parent_id" id="parent_name_id" value="">
                                  @foreach($sub_chart_account as $sub)
                                      @if($sub->level == 1)
                                      <option value="{{ $sub->id }}" id="{{ $sub->id}}">{{ $sub->name }}</option>
                                      @endif
                                  @endforeach
                              </select>
                          </div>
                      </div>
                      <div class="form-group{{ $errors->has('saldo_awal_edit') ? ' has-error' : ''}}" id="display_saldo_awal_edit">
                          {!! Form::label('saldo_awal_edit-','Saldo Awal',['class'=>'col-sm-3 control-label']) !!}
                          <div class="col-sm-9">
                              {!! Form::text('saldo_awal_edit',null,['class'=>'form-control','placeholder'=>'Saldo awal of the sub chart account','id'=>'saldo_awal_edit']) !!}
                              @if($errors->has('saldo_awal_edit'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('saldo_awal_edit') }}</strong>
                              </span>
                              @endif
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
    {!! Html::script('js/autoNumeric.js') !!}
    <script type="text/javascript">
        $('#saldo_awal_edit').autoNumeric('init',{
          aSep:',',
          aDec:'.'
        });

        $('#saldo_awal').autoNumeric('init',{
          aSep:',',
          aDec:'.'
        });

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
            var name = $(this).attr('data-text');
            var account_number = $(this).attr('data-account-number');
            var created_at = $(this).attr('data-created-at');
            var id = $(this).attr('data-id');
            var saldoAwal = $(this).attr('data-saldo');
            var level = $(this).attr('data-level');
            var parent_child = '';
            var parentName = $(this).attr('data-parent');
            if(level == 1)
            {
                parent_child = 'Parent';
                $('#parent_tr').hide();
                $('#saldo_awal_tr').hide();
            }else{
                parent_child = 'Child';
                $('#parent_tr').show();
                $('#saldo_awal_tr').show();
                $('#view_sub_chart_account_parent_name').text(parentName);
            }
            $('#view_sub_chart_account_name').text(name);
            $('#view_sub_chart_account_account_number').text(account_number);
            $('#view_sub_chart_account_created_at').text(created_at);
            $('#view_sub_chart_account_saldo_awal').text(saldoAwal);
            $('#view_sub_chart_account_level').text(parent_child);
            $('#sub_chart_account_id_view').val(id);
            $('#modal-view-sub-chart-account').modal('show');
        });

        //Edit button handler
        tableSubChartAccount.on('click','.btn-edit-sub-chart-account',function(e){
            var name = $(this).attr('data-text');
            var account_number = $(this).attr('data-account-number');
            var id = $(this).attr('data-id');
            var saldoAwal = $(this).attr('data-saldo');
            var level = $(this).attr('data-level');
            var parentName = $(this).attr('data-parent');
            if(level == 1)
            {
                $('#display_parent_update').hide();
                $('#display_saldo_awal_edit').hide();
            }else{
                $('#display_parent_update').show();
                document.getElementById(parentName).selected = true;
                $('#display_saldo_awal_edit').show();
            }
            $('#name_view').val(name);
            $('#number_account_view').val(account_number);
            $('#saldo_awal_edit').val(saldoAwal);
            $('#sub_chart_account_id_view').val(id);
            $('#level_update').val(level);
            $('#modal-edit-sub-chart-account').modal('show');
        });

        //onclick detail
        $('#level').on('change',function(){
            var level = $('#level').val();
            if(level == 2){
                $('#display_parent').show();
                $('#saldo_awal_create').show();
            }else{
                $('#display_parent').hide();
                $('#saldo_awal_create').hide();
            }
        });

        //onclick detail update
        $('#level_update').on('change',function(){
            var level = $('#level_update').val();
            if(level == 2){
                $('#display_parent_update').show();
                $('#display_saldo_awal_edit').show();
            }else{
                $('#display_parent_update').hide();
                $('#display_saldo_awal_edit').hide();
            }
        });
        //TODO search handler
    </script>
@endsection
