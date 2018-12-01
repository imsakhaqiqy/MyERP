<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class RestoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(\Auth::user()->can('restore-module'))
      {
          return view('restore.index');
      }else{
          return view('403');
      }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        error_reporting(0);
        $connect = mysql_connect('192.168.1.10','psiuser','psiuser14');
        mysql_select_db('db_catra',$connect);
        $nama_file = $_FILES['restore']['name'];
        $ukuran_file = $_FILES['restore']['size'];
        if($nama_file == '')
        {
          echo "Fatal Error";
        }else{
          $uploaddir = 'C://Restore/';
          $alamatfile = $uploaddir.$nama_file;
          $templine = array();
          if(move_uploaded_file($_FILES['restore']['tmp_name'],$alamatfile)){
            $filename = 'C://Restore/'.$nama_file;
            $templine = '';
            $lines = file($filename);
            foreach ($lines as $line) {
              if(substr($line,0,2) == '--' || $line == '')
                continue;
                $templine .= $line;
              if(substr(trim($line),-1,1) == ';')
              {
                mysql_query($templine);
                $templine = '';
              }
            }
            return redirect('restore')
              ->with('successMessage','Restore data has been success');
          }else{
            return redirect('restore.show')
              ->with('errorMessage','Restore data has been failed');
          }
        }

    }

    // protected function restore_data($file)
    // {
    //   $nama_file = $_FILES['restore']['name'];
    //   $ukuran_file = $_FILES['restore']['size'];
    //
    // }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
