@extends('layouts.app')

@section('page_title')
  Edit Sales Order
@endsection

@section('page_header')
  <h1>
    Sales Order
    <small>Edit Sales Order</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('sales-order') }}"><i class="fa fa-dashboard"></i> Sales Order</a></li>
    <li><a href="{{ URL::to('sales-order/'.$sales_order->id.'/edit') }}"><i class="fa fa-dashboard"></i> {{ $sales_order->code }}</a></li>
    <li class="active"><i></i>Edit</li>
  </ol>
@endsection

@section('content')
  <!-- Row Products-->
  {!! Form::model($sales_order, ['route'=>['sales-order.update', $sales_order->id], 'id'=>'form-edit-sales-order', 'class'=>'form-horizontal','method'=>'put', 'files'=>true]) !!}
  <div class="row">
    <div class="col-lg-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Products Added</h3>
          <a href="#" id="btn-display-product-datatables" class="btn btn-primary pull-right" title="Select products to be added">
            <i class="fa fa-list"></i>&nbsp;Select Products
          </a>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-striped table-hover" id="table-selected-products">
              <thead>
                  <tr style="background-color:#3c8dbc;color:white">
                      <th style="width:15%;">Family</th>
                      <th style="width:15%;">Name</th>
                      <th style="width:15%;display:none">Stock</th>
                      <th style="width:20%;">Description</th>
                      <th style="width:15%;">Unit</th>
                      <th style="width:15%;">Quantity</th>
                      <th style="width:20%;">Category</th>
                  </tr>
              </thead>
              <tbody>
                  @if($sales_order->products->count() > 0)
                    @foreach($sales_order->products as $product)
                    <tr id="tr_product_{{$product->id}}">
                      <td>{{ $product->main_product->family->name }}</td>
                      <td>
                        <input type="hidden" name="product_id[]" value="{{ $product->id}} " />
                        {{ $product->name }}
                        &nbsp;<button type="button" class="btn btn-info btn-xs btn-view-products" data-id="{{ $product->id }}" data-text="{{ $product->name }}" data-stock="{{ $product->stock }}" title="View Stock">
                            <i class="fa fa-external-link-square"></i>
                        </button>
                      </td>
                      <td style="display:none" class="stock_product">{{ $product->stock }}</td>
                      <td>{{ $product->description }}</td>
                      <td>{{ $product->main_product->unit->name }}</td>
                      <td>
                        <input type="text" name="quantity[]" class="quantity form-control" style="" value="{{ $product->pivot->quantity }}" />
                      </td>
                      <td>{{ $product->main_product->category->name }}</td>
                    </tr>
                    @endforeach
                  @else
                  <tr id="tr-no-product-selected">
                    <td>There are no product</td>
                  </tr>
                  @endif
              </tbody>
              <tfoot>

              </tfoot>
            </table>
          </div>

        </div><!-- /.box-body -->
        <div class="box-footer clearfix">

        </div>
      </div><!-- /.box -->
    </div>
  </div>
  <!-- ENDRow Products-->
  <!-- Row Customer and Notes-->
  <div class="row">
    <div class="col-md-6">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Customer and Notes</h3>
        </div><!-- /.box-header -->
        <div class="box-body">

            <div class="form-group{{ $errors->has('do_number') ? ' has-error' : '' }}">
              {!! Form::label('do_number', 'D.O Number', ['class'=>'col-sm-3 control-label']) !!}
              <div class="col-sm-6">
                  <input type="text" class="form-control" placeholder="D.O Number" id="do_number" autocomplete="off" value="{{$sales_order->code}}" name="do_number">
                  @if($errors->has('do_number'))
                      <span class="help-block">
                          <strong>{{ $errors->first('do_number') }}</strong>
                      </span>
                  @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
              {!! Form::label('customer_id', 'Customer Name', ['class'=>'col-sm-3 control-label']) !!}
              <div class="col-sm-6">
                {{ Form::select('customer_id', $customer_options, null, ['class'=>'form-control', 'placeholder'=>'Select customer', 'id'=>'customer_id']) }}
                @if ($errors->has('customer_id'))
                  <span class="help-block">
                    <strong>{{ $errors->first('customer_id') }}</strong>
                  </span>
                @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
              {!! Form::label('notes', 'Notes', ['class'=>'col-sm-3 control-label']) !!}
              <div class="col-sm-6">
                {{ Form::textarea('notes', null,['class'=>'form-control', 'placeholder'=>'Notes of Sales Order', 'id'=>'notes']) }}
                @if ($errors->has('notes'))
                  <span class="help-block">
                    <strong>{{ $errors->first('notes') }}</strong>
                  </span>
                @endif
              </div>
            </div>

            <div class="form-group">
                {!! Form::label('', '', ['class'=>'col-sm-3 control-label']) !!}
              <div class="col-sm-9">
                <a href="{{ url('sales-order') }}" class="btn btn-default">
                  <i class="fa fa-repeat"></i>&nbsp;Cancel
                </a>&nbsp;
                <button type="submit" class="btn btn-info" id="btn-submit-product">
                  <i class="fa fa-save"></i>&nbsp;Submit
                </button>
                <input type="hidden" name="id" value="{{ $sales_order->id }}" />
              </div>
            </div>

        </div><!-- /.box-body -->
        <div class="box-footer clearfix">

        </div>
      </div><!-- /.box -->

    </div>
    <div class="col-md-6">
        <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
            <div class="box-header with-border">
                <h3 class="box-title">Drivers and Transport</h3>
            </div>
            <div class="box-body">
                <div class="form-group{{ $errors->has('driver_id') ? 'has-error' : '' }}">
                    {!! Form::label('driver_id','Driver Name',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {{ Form::select('driver_id',$driver_options,null,['class'=>'form-control','placeholder'=>'Select driver','id'=>'driver_id']) }}
                        @if($errors->has('driver_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('driver_id') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('vehicle_id') ? 'has-error' : '' }}">
                    {!! Form::label('vehicle_id','Vehicle Number',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {{ Form::select('vehicle_id',$vehicle_options,null,['class'=>'form-control','placeholder'=>'Select vehicle','id'=>'vehicle_id']) }}
                        @if($errors->has('vehicle_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('vehicle_id') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('ship_date') ? ' has-error' : '' }}">
                  {!! Form::label('ship_date', 'Ship Date', ['class'=>'col-sm-3 control-label']) !!}
                  <div class="col-sm-6">
                      {{ Form::text('ship_date',null,['class'=>'form-control','placeholder'=>'Ship Date','id'=>'ship_date','autocomplete'=>'off']) }}
                      @if($errors->has('ship_date'))
                          <span class="help-block">
                              <strong>{{ $errors->first('ship_date') }}</strong>
                          </span>
                      @endif
                  </div>
                </div>
            </div>
        </div>
    </div>
  </div>
  <!-- ENDRow customer and Notes-->
  {!! Form::close() !!}

  <!--Modal Display product datatables-->
  <div class="modal fade" id="modal-display-products" tabindex="-1" role="dialog" aria-labelledby="modal-display-productsLabel">
    <div class="modal-dialog modal-lg" role="document" style="width:80%">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-display-productsLabel">Products list</h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-striped table-hover" id="table-product" style="width:100%">
              <thead>
                  <tr style="background-color:#3c8dbc;color:white">
                      <th style="width:5%;">#</th>
                      <th style="width:10%;">Family</th>
                      <th style="width:20%;">Name</th>
                      <th style="width:15%;;display:none">Stock</th>
                      <th style="width:20%;">Description</th>
                      <th style="width:15%;">Unit</th>
                      <th style="width:15%;">Category</th>
                  </tr>
                </thead>
                <thead id="searchid">
                  <tr>
                      <th style="width:5%;"></th>
                      <th style="width:10%;">Family</th>
                      <th style="width:20%;">Name</th>
                      <th style="width:15%;display:none">Stock</th>
                      <th style="width:20%;">Description</th>
                      <th style="width:15%;">Unit</th>
                      <th style="width:15%;">Category</th>
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
  </div>
<!--ENDModal Display product datatables-->

<!-- Modal view stock product -->
<div class="modal fade" id="modal-view-product" tabindex="-1" role="dialog" aria-labelledby="modal-view-productLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-view-productLabel">View Stock Product</h4>
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
                      <td id="view_name_product"></td>
                    </tr>
                    <tr>
                      <td style="width:30%;">Stock</td>
                      <td>:</td>
                      <td id="view_stock_product"></td>
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
@endsection


@section('additional_scripts')
  <!--Auto numeric plugin-->
  {!! Html::script('js/autoNumeric.js') !!}
  {!! Html::style('css/datepicker/jquery-ui.css') !!}
  {!! Html::script('js/jquery-ui.js') !!}
  <script>
      $( function() {
        $( "#ship_date" ).datepicker({
            dateFormat: 'yy-mm-dd'
        });
      } );
  </script>
  <script type="text/javascript">
    $('#btn-display-product-datatables').on('click', function(event){
      event.preventDefault();
      $('#modal-display-products').modal('show');
    });
    $('.quantity').autoNumeric('init',{
      aSep:'',
      aDec:'.'
    });
  </script>

  <script type="text/javascript">

    var selected = [];
    //initially push selected products to variable selected
    @foreach($sales_order->products as $product)
      selected.push({{$product->id}});
    @endforeach
    //ENDinitially push selected products to var selected
    var tableProduct =  $('#table-product').DataTable({
      processing :true,
      serverSide : true,
      pageLength : 10,
      ajax : '{!! route('datatables.getProducts') !!}',
      columns :[
          {data: 'rownum', name: 'rownum', searchable:false},
          { data: 'family_id', name: 'family_id', searchable:false},
          { data: 'name', name: 'name'},
          { data: 'stock', name: 'stock', visible: false},
          { data: 'description', name: 'description', searchable:false},
          { data: 'unit_id', name: 'unit_id' , searchable:false, searchable:false},
          { data: 'category_id', name: 'category_id' , searchable:false},
      ],
      rowCallback: function(row, data){
        if($.inArray(data.id, selected) !== -1){
          $(row).addClass('selected');
        }
      },
      initComplete:function(){
        //console.log(selected);
      },

    });

    tableProduct.on('click', 'tr', function () {
        //var id = this.id;
        var id = tableProduct.row(this).data().id;
        var name = tableProduct.row(this).data().name;
        var stock = tableProduct.row(this).data().stock;
        var index = $.inArray(id, selected);
        if( index === -1 ){
          selected.push(id);
          $('#table-selected-products').append(
            '<tr id="tr_product_'+id+'">'+
              '<td>'+
                  '<input type="hidden" name="product_id[]" value="'+id+'" />'+
                  tableProduct.row(this).data().family_id+
              '</td>'+
              '<td>'+
                  tableProduct.row(this).data().name+
                  '&nbsp;<button type="button" class="btn btn-info btn-xs btn-view-products" data-id="'+id+'" data-text="'+name+'" data-stock="'+stock+'" title="View Stock">'+
                      '<i class="fa fa-external-link-square"></i>'+
                  '</button>'+
              '</td>'+
              '<td class="stock_product" style="display:none">'+
                  tableProduct.row(this).data().stock+
              '</td>'+
              '<td>'+
                  tableProduct.row(this).data().description+
              '</td>'+
              '<td>'+
                  tableProduct.row(this).data().unit_id+
              '</td>'+
              '<td>'+
                  '<input type="text" name="quantity[]" class="quantity form-control" style="" value="" />'+
              '</td>'+
              '<td>'+
                  tableProduct.row(this).data().category_id+
              '</td>'+
            '</tr>'
          );
          $('.quantity').autoNumeric('init',{
            aSep:'',
            aDec:'.'
          });
        }else{
            selected.splice( index, 1 );
            $('#tr_product_'+id).remove();
        }
        $(this).toggleClass('selected');

    } );

    $('#btn-set-product').on('click', function(){
      if(selected.length !== 0){
        $('#tr-no-product-selected').hide();
      }
      else{
        $('#tr-no-product-selected').show();
      }
      $('#modal-display-products').modal('hide');
    });

      // Setup - add a text input to each header cell
    $('#searchid th').each(function() {
      if ($(this).index() != 0 && $(this).index() != 7) {
          $(this).html('<input class="form-control" type="text" placeholder="Search" data-id="' + $(this).index() + '" />');
      }
    });
    //Block search input and select
    $('#searchid input').keyup(function() {
      tableProduct.columns($(this).data('id')).search(this.value).draw();
    });
    $('#searchid select').change(function () {
      if($(this).val() == ""){
        tableProduct.columns($(this).data('id')).search('').draw();
      }
      else{
        tableProduct.columns($(this).data('id')).search(this.value).draw();
      }
    });
    //ENDBlock search input and select

  </script>

  <script type="text/javascript">

    $('#form-edit-sales-order').on('submit', function(event){
      event.preventDefault();
      var data = $(this).serialize();
      $.ajax({
          url: '{!!URL::to('UpdateSalesOrder')!!}',
          type : 'POST',
          data : $(this).serialize(),
          beforeSend : function(){
            $('#btn-submit-product').prop('disabled', true);
          },
          success : function(response){
              if(response.msg == 'updateSalesOrderOk'){
                  window.location.href= '{{ URL::to('sales-order') }}/'+response.sales_order_id;
              }
              else{
                $('#btn-submit-product').prop('disabled', false);
                  console.log(response);
              }
          },
          error:function(data){
            var htmlErrors = '<p>Error : </p>';
            errors = data.responseJSON;
            $.each(errors, function(index, value){
              htmlErrors+= '<p>'+value+'</p>';
            });
            $('#btn-submit-product').prop('disabled', false);
            alertify.set('notifier', 'delay',0);
            alertify.error(htmlErrors);
        }
      });
    });
  </script>

  <script type="text/javascript">
      var sum = document.getElementsByClassName('sum_qty');
      for(var a = 0; a < sum.length; a++){
          document.getElementsByClassName('target_qty')[a].value = document.getElementsByClassName('sum_qty')[a].innerHTML;
      }

  </script>

  <script type="text/javascript">
      var tableSelectedProducts = $('#table-selected-products');
      tableSelectedProducts.on('click','.btn-view-products',function(event){
        event.preventDefault();
        var name = $(this).attr('data-text');
        var stock = $(this).attr('data-stock');
        $('#view_name_product').text(name);
        $('#view_stock_product').text(stock);
        $('#modal-view-product').modal('show');
      });
      tableSelectedProducts.on('keyup','.quantity',function(){
        var quantity = parseInt($(this).val());
        var stock = parseInt($(this).parent().parent().find('.stock_product').html());
        if(quantity > stock){
          alertify.error('Sales quantity can not be greater than stock product');
          $('#btn-submit-product').prop('disabled', true);
        }else if (quantity < stock) {
          $('#btn-submit-product').prop('disabled', false);
        }
      });
  </script>
@endSection
