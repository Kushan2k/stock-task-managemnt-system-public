<?php

header("Access-Control-Allow-Methods:POST");
session_start();

include_once '../config.php';

if($_SERVER['REQUEST_METHOD']=='GET'){
  header("Location:../index.php");
}

if(isset($_POST['export'])){

  $sql = "SELECT * FROM servers";
  $res = $conn->query($sql);
  $data = [];
  if($res==TRUE){
    if($res->num_rows>0){

      while($row=$res->fetch_assoc()){
        array_push($data, $row);
      }
      $filename = "exported_data_".date('Ymd') . ".xls";     
      header("Content-Type: application/vnd.ms-excel");
      header("Content-Disposition: attachment; filename=\"$filename\"");
      ExportFile($data);
      header("Location:{$_SERVER['HTTP_REFERER']}");


    }else{
      $_SESSION['error'] = 'no data to export';
      header("Location:{$_SERVER['HTTP_REFERER']}");
    }
  }else{
    $_SESSION['error'] = 'export error!';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }

}

function ExportFile($records) {
  $heading = false;
    if(!empty($records))
      foreach($records as $row) {
      if(!$heading) {
        // display field/column names as a first row
        echo implode("\t", array_keys($row)) . "\n";
        $heading = true;
      }
      echo implode("\t", array_values($row)) . "\n";
      }
    exit;
}







?>