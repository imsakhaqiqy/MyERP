@extends('layouts.app')

@section('page_title')
  Adjustment
@endsection

@section('page_header')
  <h1>
    Adjustment
    <small>Add New Adjustment Product</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('product-adjustment') }}"><i class="fa fa-dashboard"></i> Adjustment Product</a></li>
    <li class="active"><i></i>Create</li>
  </ol>
@endsection

@section('content')
  {!! Form::open(['route'=>'product-adjustment.store','role'=>'form','class'=>'form-horizontal','id'=>'form-product-adjustment']) !!}
  <div class="row">
    <div class="col-lg-12">
      <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
        <div class="box-header with-border">
          <h3 class="box-title">Basic Information</h3>
          <a href="#" id="btn-display-product-datatables" class="btn btn-primary pull-right" title="Select products to be added">
            <i class="fa fa-list"></i>&nbsp;Select Products
          </a>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="form-group{{ $errors->has('adjust_no') ? ' has-error' : '' }}">
            {!! Form::label('adjust_no', 'Adjust No', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-4">
              {!! Form::text('adjust_no',$code_adj,['class'=>'form-control', 'placeholder'=>'Adjust no of the products', 'id'=>'adjust_no', 'readonly']) !!}
              @if ($errors->has('adjust_no'))
                <span class="help-block">
                  <strong>{{ $errors->first('adjust_no') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('in_out') ? ' has-error' : '' }}">
            {!! Form::label('in_out', 'IN/OUT', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-4">
              {!! Form::select('in_out',['in'=>'IN','out'=>'OUT'],null,['class'=>'form-control', 'placeholder'=>'IN/OUT of the products', 'id'=>'in-out']) !!}
              @if ($errors->has('in_out'))
                <span class="help-block">
                  <strong>{{ $errors->first('in_out') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('adjust_account') ? ' has-error' : '' }}">
            {!! Form::label('adjust_account', 'Adjust Account', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-4">
              <select name="adjust_account" id="adjust-account" class="form-control">
                  <option value="">Select Adjust Account</option>
                  @foreach($adjust_account as $ac)
                    <option value="{{$ac->id}}">{{ $ac->account_number.' '.$ac->name }}</option>
                  @endforeach
                    <option value="initial_setup">INITIAL SETUP(NO POSTING)</option>
              </select>
              @if ($errors->has('adjust_account'))
                <span class="help-block">
                  <strong>{{ $errors->first('adjust_account') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
            {!! Form::label('notes', 'Notes', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-4">
              {!! Form::textarea('notes',null,['class'=>'form-control', 'placeholder'=>'Initial notes of the bank', 'id'=>'notes' , 'style'=>'height:100px']) !!}
              @if ($errors->has('notes'))
                <span class="help-block">
                  <strong>{{ $errors->first('notes') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <!-- <div class="table-responsive">
              <table class="table table-hover table-striped">
                  <thead>
                      <tr style="background-color:#3c8dbc;color:white">
                          <th style="width:20%">Code</th>
                          <th style="width:20%">Description</th>
                          <th style="width:10%">Unit</th>
                          <th>Unit Cost</th>
                          <th>Qty</th>
                          <th>Total</th>
                      </tr>
                  </thead>
                  <tbody id="select-table">
                      <tr>
                          <td>
                              <select name="select_product" class="form-control" id="select-product">
                                  <option>Select Product</option>
                                  @foreach($product as $p)
                                    <option value="{{$p->id}}">{{$p->name}}</option>
                                  @endforeach
                              </select>
                          </td>
                          <td>
                              <input type="text" name="select_description[]" class="form-control" id="select-description">
                          </td>
                          <td>
                              <input type="text" name="select_unit[]" class="form-control" id="select-unit">
                          </td>
                          <td>
                              <input type="text" name="select_unit_cost[]" class="form-control" id="select-unit-cost">
                          </td>
                          <td>
                              <input type="text" name="select_qty[]" class="form-control" id="select-qty">
                          </td>
                          <td>
                              <input type="text" name="select_total[]" class="form-control" id="select-total">
                          </td>
                      </tr>
                  </tbody>
              </table>
              <button class="btn btn-default" type="button" id="add-line">+</button>
          </div> -->

          <div class="table-responsive" style="max-height:500px">
            <table class="table table-striped table-hover" id="table-selected-products">
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
                <tr id="tr-no-product-selected">
                  <td colspan="6">No product selected</td>
                </tr>
            </table>
          </div>
          <div class="form-group">
              {!! Form::label('', '', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
              <a href="{{ url('product-adjustment') }}" class="btn btn-default">
                <i class="fa fa-repeat"></i>&nbsp;Cancel
              </a>&nbsp;
              <button type="submit" class="btn btn-info" id="btn-submit-product">
                <i class="fa fa-save"></i>&nbsp;Submit
              </button>
            </div>
          </div>
        </div><!-- /.box-body -->
        <div class="box-footer clearfix">

        </div>
      </div><!-- /.box -->

    </div>

  </div>
  {!! Form::close() !!}
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
                      <th style="width:15%;display:none">Stock</th>
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
              <tfoot>

              </tfoot>
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
{!! Html::script('js/autoNumeric.js') !!}
    <script type="text/javascript">
        $('#value').autoNumeric('init',{
            aSep:',',
            aDec:'.'
        });
    </script>
    <script type="text/javascript">
      $('#btn-display-product-datatables').on('click', function(event){
        event.preventDefault();
        $('#modal-display-products').modal('show');
      });
    </script>
    <script type="text/javascript">

      var selected = [];

      var tableProduct =  $('#table-product').DataTable({
        processing :true,
        serverSide : true,
        pageLength:10,
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
        }

      });

      tableProduct.on('click', 'tr', function(){
          //var id = this.id;
          var id = tableProduct.row(this).data().id;
          var name = tableProduct.row(this).data().name;
          var stock = tableProduct.row(this).data().stock;
          var index = $.inArray(id, selected);
          if ( index === -1 ) {
              selected.push(id);
              $('#table-selected-products').append(
                '<tr id="tr_product_'+id+'">'+
                  '<td>'+
                      '<input type="hidden" name="product_id[]" value="'+id+'" />'+
                      '<input type="hidden" name="family_name[]" value="'+tableProduct.row(this).data().family_id+'" />'+
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
                      tableProduct.row(this).data().category_id+
                  '</td>'+
                  '<td>'+
                      '<input type="text" name="unit_cost[]" class="unit_cost form-control" style=""/>'+
                  '</td>'+
                  '<td>'+
                      '<input type="text" name="quantity[]" class="quantity form-control" style=""/>'+
                  '</td>'+
                  '<td>'+
                      '<input type="text" name="total[]" class="total form-control" style=""/ readonly>'+
                  '</td>'+
                '</tr>'
              );
              $('.unit_cost').autoNumeric('init',{
                  aSep:',',
                  aDec:'.'
              });
              $('.quantity').autoNumeric('init',{
                  aSep:'',
                  aDec:'.'
              });
              $('.total').autoNumeric('init',{
                  aSep:',',
                  aDec:'.'
              });
          } else {
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
        if ($(this).index() != 0 && $(this).index() != 8) {
            $(this).html('<input class="form-control" type="text" placeholder="Search" data-id="' + $(this).index() + '" />');
        }

      });
      //Block search input and select
      $('#searchid input').keyup(function() {
        tableProduct.columns($(this).data('id')).search(this.value).draw();
      });
      // $('#searchid select').change(function () {
      //   if($(this).val() == ""){
      //     tableProduct.columns($(this).data('id')).search('').draw();
      //   }
      //   else{
      //     tableProduct.columns($(this).data('id')).search(this.value).draw();
      //   }
      // });
      //ENDBlock search input and select

    </script>
    {!! Html::script('js/autoNumeric.js') !!}
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
        // tableSelectedProducts.on('keyup','.quantity',function(){
        //   var quantity = parseInt($(this).val());
        //   var stock = parseInt($(this).parent().parent().find('.stock_product').html());
        //   if(quantity > stock){
        //     alertify.error('Sales quantity can not be greater than stock product');
        //     $('#btn-submit-product').prop('disabled', true);
        //   }else if (quantity < stock) {
        //     $('#btn-submit-product').prop('disabled', false);
        //   }
        // });
    </script>

    <script>
        //Block handle price value on price per unit keyup event
        tableSelectedProducts.on('keyup','.quantity',function(){
          var unitCost = parseFloat($(this).parent().parent().find('.unit_cost').val().replace(/,/g,''));
          var the_price = 0;
          if($(this).val() == ''){
            the_price = 0;
          }
          else{
            the_price = parseFloat($(this).val().replace(/,/g, ''))*unitCost;
          }

          $(this).parent().parent().find('.total').val(the_price).autoNumeric('update',{
              aSep:',',
              aDec:'.'
          });
          fill_the_bill_price();
        });

        function fill_the_bill_price(){

          var sum = 0;
          $(".total").each(function(){
              sum += +$(this).val().replace(/,/g, '');
          });
        //   $("#bill_price").val(sum);
        //   $('#bill_price').autoNumeric('update',{
        //       aSep:',',
        //       aDec:'.'
        //   });
        }
    </script>

    <!-- <script type="text/javascript">
        $('#add-line').on('click', function(){
            $('#select-table').append(
                '<tr>'+
                    '<td>'+
                    '<select name="select_product" class="form-control" id="select-product">'+
                        '<option>'+'Select Product'+'</option>'+
                        @foreach($product as $p)
                          '<option value="{{$p->id}}">{{$p->name}}</option>'+
                        @endforeach
                    '</select>'+
                    '</td>'+
                    '<td>'+
                    '<input type="text" class="form-control" name="select_description[]" id="select-description">'+
                    '</td>'+
                    '<td>'+
                    '<input type="text" class="form-control" name="select_unit[]">'+
                    '</td>'+
                    '<td>'+
                    '<input type="text" class="form-control" name="select_unit_cost[]">'+
                    '</td>'+
                    '<td>'+
                    '<input type="text" class="form-control" name="select_qty[]">'+
                    '</td>'+
                    '<td>'+
                    '<input type="text" class="form-control" name="select_total[]">'+
                    '</td>'+
                '</tr>'
            );
        })
        $(document).ready(function(){
            $('#select-product').change(function(){
                var product = $('#select-product').val();
                var token = $("meta[name='csrf-token']").attr('content');
                $.ajax({
                    url:"{!!URL::to('callFieldProduct')!!}",
                    type:'POST',
                    data:"product="+product+'&_token='+token,
                    beforeSend: function(){

                    } ,
                    success:function(response){
                        console.log(response);
                        $.each(response,function(index,value){
                            $('#select-description').val(value.description);
                        });
                    }
                });
            });
        });
    </script> -->
@endsection
