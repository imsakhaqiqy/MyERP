@extends('layouts.app')

@section('page_title')
  Edit Purchase Order
@endsection

@section('page_header')
  <h1>
    Purchase Order
    <small>Edit Purchase Order</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('purchase-order') }}"><i class="fa fa-dashboard"></i> Purchase Order</a></li>
    <li class="active"><i></i>Edit</li>
  </ol>
@endsection

@section('content')
  <!-- Row Products-->
  {!! Form::model($purchase_order, ['route'=>['purchase-order.update', $purchase_order->id], 'id'=>'form-edit-purchase-order', 'class'=>'form-horizontal','method'=>'put', 'files'=>true]) !!}
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
                @if($purchase_order->products->count() > 0)
                  @foreach($purchase_order->products as $product)
                  <tr id="tr_product_{{$product->id}}">
                    <td>
                      {{ $product->main_product->family->name}}
                    </td>
                    <td>
                      <input type="hidden" name="product_id[]" value="{{ $product->id}} " />
                      {{ $product->name }}
                    </td>
                    <td>
                      {{ $product->description }}
                    </td>
                    <td>{{ $product->main_product->unit->name }}</td>
                    <td>
                      <input type="text" name="quantity[]" class="quantity form-control" style="" value="{{ $product->pivot->quantity }}" />
                    </td>
                    <td>{{ $product->main_product->category->name}}</td>
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
  <!-- Row Supplier and Notes-->
  <div class="row">
    <div class="col-md-8">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Supplier and Notes</h3>
        </div><!-- /.box-header -->
        <div class="box-body">

            <div class="form-group{{ $errors->has('supplier_id') ? ' has-error' : '' }}">
              {!! Form::label('supplier_id', 'Supplier', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-6">
                {{ Form::select('supplier_id', $supplier_options, null, ['class'=>'form-control', 'placeholder'=>'Select supplier', 'id'=>'supplier_id']) }}
                @if ($errors->has('supplier_id'))
                  <span class="help-block">
                    <strong>{{ $errors->first('supplier_id') }}</strong>
                  </span>
                @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
              {!! Form::label('notes', 'Notes', ['class'=>'col-sm-2 control-label']) !!}
              <div class="col-sm-6">
                {{ Form::textarea('notes', null,['class'=>'form-control', 'placeholder'=>'Notes of purchase order', 'id'=>'notes']) }}
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
                <a href="{{ url('purchase-order') }}" class="btn btn-default">
                  <i class="fa fa-repeat"></i>&nbsp;Cancel
                </a>&nbsp;
                <button type="submit" class="btn btn-info" id="btn-submit-product">
                  <i class="fa fa-save"></i>&nbsp;Submit
                </button>
                <input type="hidden" name="id" value="{{ $purchase_order->id }}" />
              </div>
            </div>

        </div><!-- /.box-body -->
        <div class="box-footer clearfix">

        </div>
      </div><!-- /.box -->

    </div>
  </div>
  <!-- ENDRow Supplier and Notes-->
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
    //initially push selected products to var selected
    @foreach($purchase_order->products as $product)
      selected.push({{$product->main_product_id}});
    @endforeach
    console.log(selected);
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


    tableProduct.on('click', 'tr', function(){
        //var id = this.id;
        var id = tableProduct.row(this).data().id;
        var index = $.inArray(id, selected);
        if ( index === -1 ) {
            selected.push(id);
            $('#table-selected-products').append(
              '<tr class="tr_product_'+id+'">'+
                '<td id="sub_tabel_product_family"><b>'+
                    tableProduct.row(this).data().family_id+
                '</b></td>'+
                '<td><b>'+
                    tableProduct.row(this).data().name+
                    tableProduct.row(this).data().image+
                '</b></td>'+
                '<td><b>'+
                    tableProduct.row(this).data().description+
                '</b></td>'+
                '<td><b>'+
                    tableProduct.row(this).data().unit_id+
                '</b></td>'+
                '<td>'+
                    '<input type="text" name="parent_quantity" class="quantity form-control" style="" value="" />'+
                '</td>'+
                '<td><b>'+
                    tableProduct.row(this).data().category_id+
                '</b></td>'+
              '</tr>'
            );
            var token = $("meta[name='csrf-token']").attr('content');
            //alert(token);
            //panggil controller tampilan sub product
            $.ajax({
                url: '{!!URL::to('callSubProduct')!!}',
                type : 'POST',
                data : 'id='+id+'&_token='+token,
                beforeSend: function(){

                } ,
                success: function(response){
                    $.each(response,function(index,value){
                        $('#table-selected-products').append(
                          '<tr class="tr_product_'+id+'">'+
                            '<td>'+
                                '<input type="hidden" name="product_id[]" value="'+value.id+'" />'+
                                value.family+
                            '</td>'+
                            '<td>'+
                                value.name+
                            '</td>'+
                            '<td>'+
                                value.description+
                            '</td>'+
                            '<td>'+
                                value.unit+
                            '</td>'+
                            '<td>'+
                                '<input type="text" name="quantity[]" class="quantity form-control" style="" value="" />'+
                            '</td>'+
                            '<td>'+
                                value.category+
                            '</td>'+
                          '</tr>'
                        );
                    });
                },
            })

        } else {
            selected.splice( index, 1 );
            $('.tr_product_'+id).remove();
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
      if ($(this).index() != 0 && $(this).index() != 5) {
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

    $('#form-edit-purchase-order').on('submit', function(event){
      event.preventDefault();
      var data = $(this).serialize();
      $.ajax({
          url: '{!!URL::to('UpdatePurchaseOrder')!!}',
          type : 'POST',
          data : $(this).serialize(),
          beforeSend : function(){
            $('#btn-submit-product').prop('disabled', true);
          },
          success : function(response){
              if(response.msg == 'updatePurchaseOrderOk'){
                  window.location.href= '{{ URL::to('purchase-order') }}/'+response.purchase_order_id;
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
@endSection
