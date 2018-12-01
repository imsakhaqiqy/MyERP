@extends('layouts.app')

@section('page_title')
    Category Product
@endsection

@section('page_header')
  <h1>
    Category Product
    <small>Category Product List</small>
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ URL::to('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('category') }}"><i class="fa fa-dashboard"></i> Category Product</a></li>
    <li class="active"><i></i>Index</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-12">
        <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
            <div class="box-header with-border">
              <h3 class="box-title">Category Product</h3>
              <a href="{{ URL::to('category/create')}}" class="btn btn-primary pull-right" title="Create new category product">
                <i class="fa fa-plus"></i>&nbsp;Add New
              </a>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                  <table class="table table-striped table-hover" id="table-category">
                      <thead>
                        <tr style="background-color:#3c8dbc;color:white">
                          <th style="width:5%;">No</th>
                          <th style="width:10%;">Code</th>
                          <th style="width:35%;">Category Name</th>
                          <th style="width:20%;">Family</th>
                          <th style="width:20%;">Created At</th>
                          <th style="width:10%;text-align:center;">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($categories->count() >0 )
                            <?php $no = 1; ?>
                          @foreach($categories as $category)
                            <tr>
                              <td>{{ $no++ }}</td>
                              <td>{{ $category->code }}</td>
                              <td>{{ $category->name }}</td>
                              <td>{{ $category->family->name }}</td>
                              <td>{{ $category->created_at }}</td>
                              <td style="text-align:center;">
                                <a href="{{ url('category/'.$category->id) }}" class="btn btn-success btn-xs" title="Click for view this category product">
                                    <i class="fa fa-external-link-square"></i>
                                </a>&nbsp;
                                <a href="{{ url('category/'.$category->id.'/edit') }}" class="btn btn-info btn-xs" title="Click to edit this category product">
                                  <i class="fa fa-edit"></i>
                                </a>&nbsp;
                                @if(\Auth::user()->can('delete-category-module'))
                                <button type="button" class="btn btn-danger btn-xs btn-delete-category" data-id="{{ $category->id }}" data-text="{{$category->name}}" title="Click to delete this category product">
                                  <i class="fa fa-trash"></i>
                                </button>
                                @endif
                              </td>
                            </tr>
                          @endforeach
                        @else
                        <!-- <tr>
                          <td colspan="4">Tidak ada kategori terdaftar</td>
                        </tr> -->
                        @endif
                    </tbody>
                  </table>
              </div>
            </div><!-- /.box-body -->
            <div class="box-footer clearfix">

            </div>
        </div><!-- /.box -->

    </div>
  </div>

  <!--Modal Delete Category-->
  <div class="modal fade" id="modal-delete-category" tabindex="-1" role="dialog" aria-labelledby="modal-delete-categoryLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      {!! Form::open(['url'=>'deleteCategory', 'method'=>'post']) !!}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-delete-categoryLabel">Confirmation</h4>
        </div>
        <div class="modal-body">
          Anda akan menghapus kategori <b id="category-name-to-delete"></b>
          <br/>
          <p class="text text-danger">
            <i class="fa fa-info-circle"></i>&nbsp;Proses menghapus tidak bisa dibatalkan
          </p>
          <input type="hidden" id="category_id" name="category_id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
<!--ENDModal Delete Category-->
@endsection

@section('additional_scripts')
  <script type="text/javascript">
    $('.btn-delete-category').on('click', function(){
      var id = $(this).attr('data-id');
      var name = $(this).attr('data-text');
      $('#category_id').val(id);
      $('#category-name-to-delete').text(name);
      $('#modal-delete-category').modal('show');
    });

    $('#table-category').DataTable({

    });
  </script>
  <script type="text/javascript">
  </script>
@endsection
