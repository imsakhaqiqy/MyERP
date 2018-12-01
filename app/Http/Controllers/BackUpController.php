<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class BackUpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('backup-module')){
          return view('backup.index');
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
        $file = 'PT_CATRA_TEXTILE_RAYA_'.date('Ymd').'_backup'.'.sql';
        $this->backup_tables('192.168.1.10','psiuser','psiuser14','db_catra',$file);
        // $return_val = NULL;
        // $output = NULL;
        // $command = "/usr/bin/mysqldump.exe --opt -h localhost -u root -p db_catra > C://Backup/db_catra_backup1.sql";
        // exec($command,$output,$return_val);
        return redirect('backup')
          ->with('successMessage','Backup has been success');
    }

    protected function backup_tables($host,$username,$password,$database,$nama_file,$tables = '*')
    {
      $link = mysql_connect($host,$username,$password);
      mysql_select_db($database,$link);
      if($tables == '*')
      {
        $tables = array();
        $result = mysql_query('SHOW TABLES');
        while($row = mysql_fetch_row($result))
        {
          $tables[] = $row[0];
        }
      }else{
        $tables = is_array($tables) ? $tables : explode(',',$tables);
      }

      foreach ($tables as $table) {
        $result = mysql_query('SELECT * FROM '.$table);
        $num_fields = mysql_num_fields($result);
        $return.= 'DROP TABLE '.$table.';';
        $row2 =  mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
        $return.= "\n\n".$row2[1].";\n\n";
        for ($i=0; $i < $num_fields; $i++) {
          while($row = mysql_fetch_row($result)){
            $return .= 'INSERT INTO '.$table.' VALUES(';
              for($j = 0; $j < $num_fields; $j ++){
                $row[$j] = addslashes($row[$j]);
                $row[$j] = ereg_replace("\n","\\n",$row[$j]);
                // print_r($row);
                // exit();
                if(isset($row[$j])){
                  $return .= '"'.$row[$j].'"';
                }else{
                  $return .= '""';
                }
                if($j < ($num_fields-1)){
                  $return .= ',';
                }
              }
              $return .= ");\n";
          }
        }
        $return .= "\n\n\n";
      }
      $nama_file;
      $handle = fopen('C://Backup/'.$nama_file,'w+');
      fwrite($handle,$return);
      fclose($handle);
    }

    protected function backup_tables_dump()
    {
      return exec("mysqldump -h localhost -u root -p db_catra > C://Backup/db_catra_backup.sql");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
