<?php

if($_SERVER['REQUEST_METHOD']=='GET'){
  // header("Location:./index.php");
}

$username = 'root';
$password = '';
$host = 'localhost';
$db = 'stocks';

$conn = new mysqli($host, $username, $password, $db);

if($conn->error){
  die;
  
}