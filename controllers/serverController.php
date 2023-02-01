<?php

session_start();
include_once '../config.php';

if($_SERVER['REQUEST_METHOD']=='GET'){
  header("Access-Control-Allow-Method:POST");
  header("Location:../index.php");
}


if(isset($_POST['add-servers'])){
  $name = htmlspecialchars($_POST['name']);
  $city = htmlspecialchars($_POST['city']);

  if(empty($name) || empty($city)){
    $_SESSION['error'] = 'name and city is required!';
    header("Location:{$_SERVER['HTTP_REFERER']}");
    return;
  }
  $country=empty($_POST['country'])?'-':$_POST['country'];
  $ipmiip= empty($_POST['ipmiip'])?'-':$_POST['ipmiip'];
  $seriel= empty($_POST['seriel'])?'-':$_POST['seriel'];
  $rackunit= empty($_POST['ru'])?'-':$_POST['ru'];
  $facilityname= empty($_POST['fn'])?'-':$_POST['fn'];
  $racknumber= empty($_POST['rn'])?'-':$_POST['rn'];
  $cpu= empty($_POST['cpu'])?'-':$_POST['cpu'];
  $memory= empty($_POST['mem'])?'-':$_POST['mem'];
  $diskmodel= empty($_POST['dm'])?'':$_POST['dm'];
  $nic= empty($_POST['nic'])?'-':$_POST['nic'];
  $serverip= empty($_POST['serverip'])?'-':$_POST['serverip'];
  $serveruser= empty($_POST['serveruser'])?'-':$_POST['serveruser'];
  $serverpass= empty($_POST['serverpass'])?'-':$_POST['serverpass'];
  $serverport= empty($_POST['serverport'])?'-':$_POST['serverport'];
  $ipmiport= empty($_POST['ipmiport'])?'-':$_POST['ipmiport'];
  $ipmiuser= empty($_POST['ipmiuser'])?'-':$_POST['ipmiuser'];
  $ipmipass= empty($_POST['ipmipass'])?'-':$_POST['ipmipass'];

  $id=(int)time()/100;


  $sql = "INSERT INTO servers(id,name,city,country,ipmiip,seriel,rackunit,facilityname,racknumber,cpu,memory,diskmodel,nic,serverip,serveruser,serverpass,serverport,ipmiport,ipmiuser,ipmipass) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

  $sqlmac = "INSERT INTO macs(server_id,macaddress) VALUES(?,?)";

  $stm = $conn->prepare($sql);
  $stm2 = $conn->prepare($sqlmac);

  $stm->bind_param('isssssssssssssssssss',$id,$name,$city,$country, $ipmiip, $seriel, $rackunit, $facilityname, $racknumber, $cpu, $memory, $diskmodel, $nic, $serverip, $serveruser, $serverpass, $serverport, $ipmiport, $ipmiuser, $ipmipass);

  if(!$stm->execute()){
    $_SESSION['error'] = 'server adding failed!';
    // echo 'erro server';
    header("Location:{$_SERVER['HTTP_REFERER']}");
    return;
    
  }

  if(isset($_POST['macaddress']) && !empty($_POST['macaddress']) && count($_POST['macaddress'])>0){

    for($i=0;$i<count($_POST['macaddress']);$i++){
      $stm2->bind_param('is', $id, $_POST['macaddress'][$i]);
      $stm2->execute();
    }
    // echo 'mac added';
    $_SESSION['msg'] = 'saved complete!';
    header("Location:{$_SERVER['HTTP_REFERER']}");
    return;
  }

  $_SESSION['error'] = 'mac address saving failed!';
  header("Location:{$_SERVER['HTTP_REFERER']}");
  return;

  // echo $conn->error;

 

  

  // if(empty($address)|| empty($name) || empty($email) || empty($country) || empty($city)){
  //    $_SESSION['error'] = 'all the fileds are required!';
  //     header("Location:{$_SERVER['HTTP_REFERER']}");
  //     return;
  // }

  // if(!$email){
  //   $_SESSION['error'] = 'email is not valid';
  //   header("Location:{$_SERVER['HTTP_REFERER']}");
  // }

  // $sql = "INSERT INTO servers(name,address,email,city,country) VALUES(?,?,?,?,?)";
  // $stm=$conn->prepare($sql);
  // $stm->bind_param("sssss",$name,$address,$email,$city,$country);

  // if($stm->execute()){
  //   $_SESSION['msg'] = 'server added!';
  //   header("Location:{$_SERVER['HTTP_REFERER']}");
  // }else{
  //    $_SESSION['error'] = 'server adding failed!';
  //   header("Location:{$_SERVER['HTTP_REFERER']}");
  // }


}