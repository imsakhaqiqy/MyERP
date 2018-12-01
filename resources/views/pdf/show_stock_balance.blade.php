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
      padding: 20px;
  }
  h1,h4{
      text-align: center;
  }
  th{
      text-align:center;
      font-size:10pt;
  }
  table td{
      font-size:9pt;
      padding-left:3px;
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
                  <h4>Stock Balance</h4>
                  <h4 style="line-height:1.7">
                    <?php
                      $date = date_create($stock_balance->created_at);
                    ?>
                    Tanggal&nbsp;{{date_format($date,'d-m-Y')}}
                  </h4>
                </div>
                <div class="box-body">
                  <table style="width:50%" border="0">
                      <tr>
                        <td style="width:30%;vertical-align:top">Code</td>
                        <td style="width:5%">:</td>
                        <td style="width:65%">{{ $stock_balance->code}}</td>
                      </tr>
                      <tr>
                          <td style="width:30%;vertical-align:top">Created By</td>
                          <td style="width:5%">:</td>
                          <td style="width:65%">{{ $stock_balance->creator->name}}</td>
                      </tr>
                      <tr>
                          <td style="width:30%;vertical-align:top">Created At</td>
                          <td style="width:5%">:</td>
                          <td style="width:65%">{{ $stock_balance->created_at}}</td>
                      </tr>
                  </table>
                  <br>
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
                                  <td>{{ \DB::table('families')->select('name')->where('id',$view->family_id)->value('name') }}</td>
                                  <td>{{ $view->name }}</td>
                                  <td>{{ $view->description }}</td>
                                  <td>{{ \DB::table('units')->select('name')->where('id',$view->unit_id)->value('name') }}</td>
                                  <td>{{ \DB::table('categories')->select('name')->where('id',$view->category_id)->value('name') }}</td>
                                  <td>{{ $view->system_stock }}</td>
                                  <td>{{ $view->real_stock }}</td>
                                  <td>{{ $view->information }}</td>
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

</body>
</html>
