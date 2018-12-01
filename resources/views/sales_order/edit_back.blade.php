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
    <li class="active"><i></i>Edit</li>
  </ol>
@endsection

@section('content')
  <!-- Row Products-->
  {!! Form::model($sales_order, ['route'=>['sales-order.update', $sales_order->id], 'id'=>'form-edit-sales-order', 'class'=>'form-horizontal','method'=>'put', 'files'=>true]) !!}
  <div class="row">
    <div class="col-lg-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Products</h3>
          <a href="#" id="btn-display-product-datatables" class="btn btn-primary pull-right" title="Select products to be added">
            <i class="fa fa-list"></i>&nbsp;Select Products
          </a>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="table-selected-products">
              <thead>
                  <tr>
                    <th style="width:15%;background-color:#3c8dbc;color:white">Family</th>
                    <th style="width:15%;background-color:#3c8dbc;color:white">Code</th>
                    <th style="width:20%;background-color:#3c8dbc;color:white">Description</th>
                    <th style="width:15%;background-color:#3c8dbc;color:white">Unit</th>
                    <th style="width:15%;background-color:#3c8dbc;color:white">Quantity</th>
                    <th style="width:20%;background-color:#3c8dbc;color:white">Category</th>
                  </tr>
              </thead>
              <tbody>
                  @if(count($row_display))
                      @foreach($row_display as $row)
                        <?php $sum_qty = 0; ?>
                          <tr class="tr_product_{{ $row['main_product_id'] }}">
                            <td><strong>{{ $row['family'] }}</strong></td>
                            <td>
                                <strong>
                                    {{ $row['main_product'] }}
                                </strong>
                                @if($row['image'] != NULL)
                                <a href="#" class="thumbnail">
                                    {!! Html::image('img/products/thumb_'.$row['image'].'', $row['image']) !!}
                                </a>
                                @else
                                <a href="#" class="thumbnail">
                                    {!! Html::image('files/default/noimageavailable.jpeg', 'No Image') !!}
                                </a>
                                @endif
                            </td>
                            <td><strong>{{ $row['description'] }}</strong></td>
                            <td><strong>{{ $row['unit'] }}</strong></td>
                            <td>
                                <input type="text" name="parent_stock" value="" class="target_qty">
                            </td>
                            <td><strong>{{ $row['category'] }}</strong></td>
                          </tr>
                          @foreach($row['ordered_products'] as $or)
                          <tr class="tr_product_{{ $row['main_product_id'] }}">
                            <td>
                              <input type="hidden" name="product_id[]" value="{{ $or['product_id'] }} " />
                              {{ $or['family'] }}
                            </td>
                            <td>{{ $or['code'] }} </td>
                            <td>{{ $or['description'] }} </td>
                            <td>{{ $or['unit'] }} </td>
                            <td>
                                <input type="text" name="quantity[]" value="{{ $or['quantity'] }}">
                                <?php $sum_qty += $or['quantity']; ?>
                            </td>
                            <td>{{ $or['category'] }}</td>
                          </tr>
                          @endforeach
                          <tr style="display:none">
                              <td colspan="6" class="sum_qty">{{ $sum_qty }}</td>
                          </tr>
                      @endforeach
                @else
                <tr id="tr-no-product-selected">
                  <td>There are no product</td>
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
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Customer and Notes</h3>
        </div><!-- /.box-header -->
        <div class="box-body">

            <div class="form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
              {!! Form::label('customer_id', 'Customer', ['class'=>'col-sm-2 control-label']) !!}
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
              {!! Form::label('notes', 'Notes', ['class'=>'col-sm-2 control-label']) !!}
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
                {!! Form::label('', '', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
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
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Drivers and Transport</h3>
            </div>
            <div class="box-body">
                <div class="form-group{{ $errors->has('driver_id') ? 'has-error' : '' }}">
                    {!! Form::label('driver_id','Driver',['class'=>'col-sm-2 control-label']) !!}
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
                    {!! Form::label('vehicle_id','Vehicle',['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-6">
                        {{ Form::select('vehicle_id',$vehicle_options,null,['class'=>'form-control','placeholder'=>'Select vehicle','id'=>'vehicle_id']) }}
                        @if($errors->has('vehicle_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('vehicle_id') }}</strong>
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
            <table class="table table-bordered" id="table-product">
              <thead>
                  <tr>
                      <th style="width:5%;">#</th>
                      <th>Family</th>
                      <th>Code</th>
                      <th>Image</th>
                      <th>Description</th>
                      <th>Unit</th>
                      <th>Category</th>
                  </tr>
                </thead>
                <thead id="searchid">
                  <tr>
                      <th style="width:5%;">#</th>
                      <th>Family</th>
                      <th>Code</th>
                      <th>Image</th>
                      <th>Description</th>
                      <th>Unit</th>
                      <th>Category</th>
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
@endsection


@section('additional_scripts')
  <!--Auto numeric plugin-->
  {!! Html::script('js/autoNumeric.js') !!}

  <script type="text/javascript">
    $('#btn-display-product-datatables').on('click', function(event){
      event.preventDefault();
      $('#modal-display-products').modal('show');
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
      ajax : '{!! route('datatables.getMainProducts') !!}',
      columns :[
          {data: 'rownum', name: 'rownum', searchable:false},
          { data: 'family_id', name: 'family_id'},
          { data: 'name', name: 'name'},
          { data: 'image', name: 'image'},
          { data: 'description', name: 'description'},
          { data: 'unit_id', name: 'unit_id' },
          { data: 'category_id', name: 'category_id' },
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
        var index = $.inArray(id, selected);
        if( index === -1 ){
          selected.push(id);
          $('#table-selected-products').append(
            '<tr id="tr_product_'+id+'">'+
              '<td>'+
                '<input type="hidden" name="product_id[]" value="'+id+'" />'+
                tableProduct.row(this).data().name+
              '</td>'+
              '<td>'+
                '<input type="text" name="quantity[]" class="quantity form-control" style="" value="" />'+
              '</td>'+
              '<td>'+
                tableProduct.row(this).data().unit_id+
              '</td>'+
            '</tr>'
          );
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
@endSection
