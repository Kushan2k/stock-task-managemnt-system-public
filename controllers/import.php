<?php
include '../config.php';
session_start();

header("Access-Control-Allow-Methods:POST");

if($_SERVER['REQUEST_METHOD']=='GET'){
  header("Location:../index.php");


}


if(isset($_POST['import'])){

  $filename=$_FILES["csv"]["name"];

  $ext=substr($filename,strrpos($filename,"."),(strlen($filename)-strrpos($filename,".")));
  $sql = "INSERT INTO servers(id,name,city,country,ipmiip,seriel,rackunit,facilityname,racknumber,cpu,memory,diskmodel,nic,serverip,serveruser,serverpass,serverport,ipmiport,ipmiuser,ipmipass) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
  $stm = $conn->prepare($sql);
  //we check,file must be have csv extention
  if($ext=="csv"|| $ext=='CSV')
  {
    $file = fopen($filename, "r");
    while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
    {
      $id=(int)time()/100;
      $stm->bind_param('isssssssssssssssssss',$id,$emapData[0],$emapData[1],$emapData[2], $emapData[3], $emapData[4], $emapData[5], $emapData[6], $emapData[7], $emapData[8], $emapData[9], $emapData[10], $emapData[11], $emapData[12], $emapData[13], $emapData[14], $emapData[15], $emapData[16], $emapData[17], $emapData[18]);
      $stm->execute();
        

    }
    fclose($file);
    $_SESSION['msg']= "CSV File has been successfully Imported.";
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }
  else {
    $_SESSION['error']= "error happend";
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }

  
}
