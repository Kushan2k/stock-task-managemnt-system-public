
<?php

session_start();
include_once '../config.php';

if($_SERVER['REQUEST_METHOD']=='GET'){
  header("Access-Control-Allow-Methods:POST");
  header("Location:../index.php");
}



if(isset($_POST['login'])){

  if(empty($_POST['username'])){
    $_SESSION['error'] = 'please fill out the inputs!';
    header("Location:../pages/login.php");
  }
  if(empty($_POST['password'])){
    $_SESSION['error'] = 'please fill out the inputs!';
    header("Location:../pages/login.php");
  }

  $username = htmlspecialchars($_POST['username']);
  $password = htmlspecialchars($_POST['password']);


  $SQl = "SELECT user_id,password FROM users WHERE username=?";
  $stm = $conn->prepare($SQl);
  if(!$stm){
    $_SESSION['error'] = 'unecpected error happend!';
    header("Location:./login.php");
  }

  $stm->bind_param("s", $username);

  if($stm->execute()){
    $res = $stm->get_result();
    if($res->num_rows>0){
      $data = $res->fetch_assoc();
      $hash = $data['password'];
      $id = $data['user_id'];
      if(password_verify($password,$hash)){
        $_SESSION['login'] = true;
        $_SESSION['username']=$username;
        setcookie('login', 1, time() + 60 * 60 * 60, '/', '', $httponly = true);
        setcookie('token', $id+678, time() + 60 * 60 * 60, '/', '', $httponly = true);
        
        $_SESSION['msg'] = 'you are loged in';
        header("Location:../index.php");
      }else{
        $_SESSION['error'] = 'password is wrong for this username';
        header("Location:../pages/login.php");
      }
    }else{
      $_SESSION['error'] = 'no users found';
      header("Location:../pages/login.php");
    }
  }else{
    $_SESSION['error'] = 'unecpected error happend!';
    header("Location:../pages/login.php");
  }

  







}

if(isset($_POST['logout'])){

  $_SESSION['login'] = null;
  setcookie('login', '', time() + 60 * 60 * 60, '/', '', $httponly = true);
  $_SESSION['msg'] = 'sorry to see you go!';
  header("Location:../pages/login.php");
}


if(isset($_POST['add-user'])){

  $username = htmlspecialchars($_POST['username']);
  $password = $_POST['password'];
  $cpassword = $_POST['cpassword'];

  if(empty($username) || empty($password) || empty($cpassword)){
    $_SESSION['error'] = 'please fill out the fileds!';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }

  if($password!=$cpassword){
    $_SESSION['error'] = 'Passwords do not match!';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }

  $hash = password_hash($password, PASSWORD_BCRYPT);

  $sql = "INSERT INTO users(username,password) VALUES(?,?)";
  $stm = $conn->prepare($sql);
  if(!$stm){
    $_SESSION['error'] = 'unexpected error happend!';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }

  $stm->bind_param("ss", $username, $hash);

  if($stm->execute()){
    $_SESSION['msg'] = 'user addded';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }else{
    $_SESSION['error'] = 'user added failed!';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }



}
if(isset($_POST['change-password'])){

  if(!isset($_SESSION['username'])){
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }

  $currentpass = $_POST['currentpassword'];
  $newpass = $_POST['newpassword'];

  if(empty($currentpass) || empty($newpass)){
    $_SESSION['error'] = 'please fill out the inputs!';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }

  $sql = "SELECT password FROM users WHERE username=?";
  $sql2 = "UPDATE users SET password=? WHERE username=?";
  $stm = $conn->prepare($sql);
  $stm->bind_param("s", $_SESSION['username']);

  if($stm->execute()){
    $res = $stm->get_result();
    if($res->num_rows>0){
      $h = $res->fetch_assoc()['password'];

      if(password_verify($currentpass,$h)){

        $stm2 = $conn->prepare($sql2);
        $newhash=password_hash($newpass,PASSWORD_BCRYPT);
        $stm2->bind_param("ss", $newhash, $_SESSION['username']);
        if($stm2->execute()){

          $_SESSION['msg'] = 'Password changed';
          header("Location:{$_SERVER['HTTP_REFERER']}");
        }else{
          $_SESSION['error'] = 'Password change failed!';
          header("Location:{$_SERVER['HTTP_REFERER']}");
        }
        
      }else{
        $_SESSION['error'] = 'Passwords do not match!';
        header("Location:{$_SERVER['HTTP_REFERER']}");
      }
    }else{
      $_SESSION['error'] = 'No users found!';
      header("Location:{$_SERVER['HTTP_REFERER']}");
    }
  }else{
    $_SESSION['error'] = 'Unexpected error happend!';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }

}

if(isset($_POST['change-username'])){

  $currentName = htmlspecialchars($_POST['cuname']);
  $newName = htmlspecialchars($_POST['newuname']);

  if(empty($currentName) || empty($newName)){
    $_SESSION['error'] = 'please fill out the fileds!';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }

  if($currentName!=$_SESSION['username']){
    $_SESSION['error'] = 'Usernames do not match';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }

  $sql = "UPDATE users SET username=? WHERE username=?";
  $stm = $conn->prepare($sql);
  $stm->bind_param("ss", $newName,$_SESSION['username']);

  if($stm->execute()){
    $_SESSION['msg'] = 'username updated!';
    $_SESSION['username'] = $newName;
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }else{
    $_SESSION['error'] = 'username update failed!';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }


}