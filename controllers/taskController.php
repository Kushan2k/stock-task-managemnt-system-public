<?php

session_start();
include_once '../config.php';

if($_SERVER['REQUEST_METHOD']=='GET'){
  header("Access-Control-Allow-Method:POST");
  header("Location:../index.php");
}
$UPLOAD_DIR = '../controllers/uploads/';


if(isset($_POST['add-task'])){



  $title = htmlspecialchars($_POST['title']);
  $followup = htmlspecialchars($_POST['followup']);
  $duedate = $_POST['date'];
  $file = $_FILES['file'];

  // print_r($_FILES);
  $filepath=$UPLOAD_DIR.$file['name'];


  $sql = "INSERT INTO task(title,followup,due_date,file_url,user_id) VALUES(?,?,?,?,?)";
  $stm = $conn->prepare($sql);


  if(move_uploaded_file($file['tmp_name'],$filepath)){
    $userid = $_COOKIE['token'] - 678;
    $stm->bind_param("ssssi", $title, $followup, $duedate, $filepath,$userid);
    if($stm->execute()){
      $_SESSION['msg'] = 'task added';
      header("Location:{$_SERVER['HTTP_REFERER']}");
    }else{
      unlink($filepath);
      $_SESSION['error'] = 'task added';
      header("Location:{$_SERVER['HTTP_REFERER']}");
    }
  }else{
    $_SESSION['error'] = 'file upload failed!';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }


}

if(isset($_POST['add-comment'])){
  $msg = htmlspecialchars($_POST['msg']);
  $data=date("j, n, Y g:i a",time());
  $task = $_POST['taskid'];
  $uid = $_COOKIE['token'] - 678;

  $sql = "INSERT INTO comments(msg,post_date,task_id,user_id) VALUES(?,?,?,?)";
  $stm = $conn->prepare($sql);
  $stm->bind_param('ssii', $msg, $data, $task,$uid);
  if($stm->execute()){
    $_SESSION['msg'] = 'comment added';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }else{
    $_SESSION['error'] = 'error adding comment';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }

}


if(isset($_POST['delete-task'])){
  $taskid = $_POST['taskid'];

  if(!filter_var($taskid,FILTER_VALIDATE_INT) || empty($taskid)){
    $_SESSION['error'] = 'task delete failed!';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }

  $sql = "DELETE FROM task WHERE id={$taskid}";
  if($conn->query($sql)==TRUE){
    $_SESSION['msg'] = 'task deleted!';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }else{
    $_SESSION['error'] = $conn->error;
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }
}

if(isset($_POST['update-task'])){

  $title = htmlspecialchars($_POST['title']);
  $msg = htmlspecialchars($_POST['followup']);
  $taskid = $_POST['taskid'];

  

  $due = $_POST['due']!='' ? $_POST['due'] : $_POST['duedate'];

  // echo isset($_POST['has_new_file']);

  if(isset($_POST['has_new_file'])){
    $file = $_FILES['newfile'];
    $filepath=$UPLOAD_DIR.$file['name'];
    $sql = "UPDATE task SET title=? , followup=? , file_url=? ,due_date=? WHERE id=?";
    $stm = $conn->prepare($sql);
    if(move_uploaded_file($file['tmp_name'],$filepath)){
      $stm->bind_param('ssssi', $title, $msg, $filepath, $due, $taskid);
      if($stm->execute()){
        unlink($_POST['pfile']);
        $_SESSION['msg'] = 'task updated!';
        header("Location:{$_SERVER['HTTP_REFERER']}");
      }else{
        $_SESSION['error'] = 'task update filed';
        header("Location:{$_SERVER['HTTP_REFERER']}");
      }
    }else{
      $_SESSION['error'] = 'file upload failed!';
      header("Location:{$_SERVER['HTTP_REFERER']}");
    }
  }else{
    $sql = "UPDATE task SET title=? , followup=? ,due_date=? WHERE id=?";
    $stm = $conn -> prepare($sql);
    $stm->bind_param('sssi', $title, $msg, $due, $taskid);

    if($stm->execute()){
      $_SESSION['msg'] = 'task updated';
      header("Location:../pages/task.php");
    }else{
      $_SESSION['error'] = 'task update filed';
      header("Location:{$_SERVER['HTTP_REFERER']}");
    }
  }

  
  
  
}

if(isset($_POST['update-comment'])){
  $msg = htmlspecialchars($_POST['newcomment']);
  $commentid = $_POST['commentid'];
  $taskid = $_POST['taskid'];

  if($conn->query("UPDATE comments SET msg='{$msg}' WHERE id={$commentid}")){
    $_SESSION['msg'] = 'comment updated!';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }else{
    $_SESSION['error'] = 'comment update failed!';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  } 
}

if(isset($_POST['delete-comment'])){
  $id = $_POST['commentid'];

  if($conn->query("DELETE FROM comments wHERE id={$id}")){
    $_SESSION['msg'] = 'comment deleted!';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }else{
    $_SESSION['error'] = 'comment delete failed!';
    header("Location:{$_SERVER['HTTP_REFERER']}");
  }
}
