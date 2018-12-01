<!DOCTYPE html>
<html lang="en">

<head>
    <meta http:equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <!-- Bootstrap Core CSS -->
    {!! Html::style('css/bootstrap/bootstrap.css') !!}
<style>
    *{
        padding: 0;
        margin: 0;
    }
    .container{
        padding: 30px;
    }
    th{
        text-align: center;
    }
    table td{
        font-size: 8pt;
        padding-left: 3px;
    }
    h1,h4{
      text-align: center;
    }
</style>
</head>

<body>
    <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="box">
              <div class="box-header with-border">
                <h1 class="box-title">CATRA<small>TEXTILE</small></h1>
                <h4>Product Stock</h4>
                <h4 style="line-height:1.7">
                  <?php
                    $date = date_create($tgl);
                  ?>
                  Tanggal&nbsp;{{date_format($date,'d-m-Y')}}
                </h4>
              </div>
              <div class="box-body">
                <br>
                <div class="table-responsive">
                  <table style="width:100%" border="1" id="data-sales-order">
                    <thead>
                        <tr>
                            <th>No</th>
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
                        <?php $no = 1; ?>
                        @foreach($products as $view)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $view->main_product->family->name}}</td>
                                <td>{{ $view->name }}</td>
                                <td>{{ $view->description }}</td>
                                <td>{{ $view->main_product->unit->name}}</td>
                                <td>{{ $view->main_product->category->name}}</td>
                                <td>{{ $view->stock }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>

                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
</body>
</html>
