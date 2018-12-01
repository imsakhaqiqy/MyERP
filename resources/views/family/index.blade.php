@extends('layouts.app')

@section('page_title')
    Family Product
@endsection

@section('page_header')
    <h1>
        Family Product
        <small>Family Product List</small>
    </h1>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ url('family') }}"><i class="fa fa-dashboard"></i> Family Product</a></li>
        <li class="active"><i></i>Index</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box" style="box-shadow:0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-top:none">
                <div class="box-header with-border">
                    <h3 class="box-title">Family Product</h3>
                    <a href="{{ URL::to('family/create') }}" class="btn btn-primary pull-right" title="Create new family">
                        <i class="fa fa-plus"></i>&nbsp;Add New
                    </a>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-striped table-hover" id="table-family">
                        <thead>
                            <tr style="background-color:#3c8dbc;color:white">
                                <th style="width:5%;">#</th>
                                <th style="width:10%;">Code</th>
                                <th style="width:55%;">Family Name</th>
                                <th style="width:20%;">Created At</th>
                                <th style="width:10%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($family->count() > 0)
                            <?php $no = 1; ?>
                                @foreach($family as $fam)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $fam->code }}</td>
                                        <td>{{ $fam->name }}</td>
                                        <td>{{ $fam->created_at }}</td>
                                        <td>
                                            <a href="{{ url('family/'.$fam->id) }}" class="btn btn-success btn-xs" title="Click for view this family product">
                                                <i class="fa fa-external-link-square"></i>
                                            </a>
                                            <a href="{{ url('family/'.$fam->id.'/edit') }}" class="btn btn-info btn-xs" title="Click to edit this family product">
                                              <i class="fa fa-edit"></i>
                                            </a>
                                            @if(\Auth::user()->can('delete-family-module'))
                                            <button type="button" class="btn btn-danger btn-xs btn-delete-family" data-id="{{ $fam->id }}" data-text="{{$fam->name}}" title="Click to delete this family product">
                                              <i class="fa fa-trash"></i>
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">Tidak ada family terdaftar</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix">

                </div>
            </div>
        </div>
    </div>

    <!--Modal Delete Family-->
    <div class="modal fade" id="modal-delete-family" tabindex="-1" role="dialog" aria-labelledby="modal-delete-familyLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
        {!! Form::open(['url'=>'deleteFamily', 'method'=>'post']) !!}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modal-delete-familyLabel">Confirmation</h4>
          </div>
          <div class="modal-body">
            Anda akan menghapus family <b id="family-name-to-delete"></b>
            <br/>
            <p class="text text-danger">
              <i class="fa fa-info-circle"></i>&nbsp;Proses menghapus tidak bisa dibatalkan
            </p>
            <input type="hidden" id="family_id" name="family_id">
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
    var tableFamily = $('#table-family').DataTable({

    });

    tableFamily.on('click','.btn-delete-family', function(){
      var id = $(this).attr('data-id');
      var name = $(this).attr('data-text');
      $('#family_id').val(id);
      $('#family-name-to-delete').text(name);
      $('#modal-delete-family').modal('show');
    });

  </script>
@endsection
