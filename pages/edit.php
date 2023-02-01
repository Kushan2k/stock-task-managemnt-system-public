<?php
session_start();
include_once '../config.php';

if(!isset($_COOKIE['login']) && !isset($_SESSION['username'])){
  header("Location:./login.php");
}

if(!isset($_GET['task_id'])){
  $_SESSION['error'] = 'no tasks found!';
  header("Location:{$_SERVER['HTTP_REFERER']}");
}

?>

<!doctype html>
<html lang="en">
  <head>
  	<title>Edit Task</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css'>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro-v6@44659d9/css/all.min.css" rel="stylesheet" type="text/css" />
		
    <link rel="stylesheet" href="../css/task.css">
		<link rel="stylesheet" href="../css/style.css">
  </head>
  <body>

  <style>

    .logout-btn:hover{
      cursor:pointer;
    }
  </style>
		
		<div class="wrapper d-flex align-items-stretch">
			<nav id="sidebar">
				<div class="p-4 pt-5">
		  		<a href="#" class="img logo rounded-circle mb-5" style="background-image: url('https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_1280.png');"></a>
          <p class="text-center"><?= isset($_SESSION['username'])?$_SESSION['username']:'' ?></p>
	        <ul class="list-unstyled components mb-5">
	          <li >
	            <a href="#">Home</a>
	          </li>
	          
						
            <li class="active"><a href="">Task</a></li>
            <li><a href="./servers.php">Servers</a></li>
            <li> 
              <a href="#homeSubmenu" data-toggle="collapse" 
              aria-expanded="false" class="dropdown-toggle">Settings</a>
              <ul class="collapse list-unstyled" id="homeSubmenu">
                <li>
                    <a class='logout-btn'  data-toggle='modal' data-target='#adduserModel'>Add User</a>
                </li>
                <li>
                    <a class='logout-btn'  data-toggle='modal' data-target='#passwordChangeModel'>Change Password</a>
                </li>
                <li>
                    <a class='logout-btn'  data-toggle='modal' data-target='#usernameChangeModel'>Change Username</a>
                </li>
                
              </ul>
            </li>
            <li><a class='logout-btn'  data-toggle='modal' data-target='#exampleModal'>Logout</a></li>
	        </ul>

	        <div class="footer">
            
	        </div>

	      </div>
    	</nav>

        <!-- Page Content  -->
      <div id="content" class="p-4 p-md-5">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="container-fluid">

            <button type="button" id="sidebarCollapse" class="btn btn-primary">
              <i class="fa fa-bars"></i>
              <span class="sr-only">Toggle Menu</span>
            </button>
            
          </div>
        </nav>
        <div class="container">
          <?php

            if(isset($_SESSION['error'])){?>
              <p class="alert alert-danger text-center"><?= ucfirst($_SESSION['error'])?></p>
            <?php $_SESSION['error'] = null;}else if(isset($_SESSION['msg'])){?>
              <p class="alert alert-success text-center"><?= ucfirst($_SESSION['msg'])?></p>
            <?php $_SESSION['msg'] = null;}
          
          
          ?>
        </div>
        


        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Are you sure want to logout?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <form action='../controllers/authController.php' method='POST' class='p-0 m-0' >
                  <button type="submit" name='logout' class="btn btn-primary">Logout</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        <style>
          .eye{
            position: absolute;
            right: 5px;
            top: 5px;
          }
        </style>

        <!-- main content  -->
        <div class="container">

          <div class="row mb-2">
            <a href='./task.php' style='font-weight:bolder' class='btn btn-sm btn-outline-dark'><<</a>
          </div>
        
          <div class="row">
            <div class="col-12">

              <?php
              $sql = "SELECT * FROM task WHERE id={$_GET['task_id']}";
              $res = $conn->query($sql);
              if($res!=TRUE){
                $_SESSION['error'] = 'error happend while data fetching!';
                header("Location:{$_SERVER['HTTP_REFERER']}");
              }
              $data = $res->fetch_assoc();

              ?>

              <form action='../controllers/taskController.php' method='POST' enctype='multipart/form-data' >

                <div class='form-group'>
                  <label class='form-label'>Title</label>
                  <input type='text' name='title' required value='<?= $data['title']?>' class='form-control'>
                </div>
                <div class='form-group'>
                  <label class='form-label'>Follow Up</label>
                  <textarea  name='followup' required class='form-control'>
                    <?=trim(preg_replace('/\t+/', '', $data['followup'])) ?>
                  </textarea>
                </div>
                <div class='form-group'>
                  <label class='form-label'>Current Due Date</label>
                  <input type='text' readonly name='duedate' value='<?= $data['due_date']?>' class='form-control'>
                  
                </div>
                <div class='form-group'>
                  <label class='form-label'>New Due Date</label>
                  <input type='date' name='due' class='form-control'>
                  <small class='text-muted'>only pick a new date if you want to update the current date</small>
                  
                </div>
                <div class='form-group'>
                  <label class='form-label'>Upload a new file ?</label>
                  <input type='checkbox' id='file-upload' name='has_new_file'/>
                </div>
                <div class='form-group d-none ' id='file-box'>
                  <input type='hidden' value='<?= $_GET['task_id']?>' name='taskid'>
                  <input type='hidden'  name='pfile' value='<?= $data['file_url']?>'>
                  <label class='form-label'>New File?</label>
                  <input type='file' name='newfile' class='form-control'>
                </div>

                <div class='form-group d-flex justify-content-end '>
                  <a href='./task.php' class='btn btn-info mr-1'>Not Now</a>
                  <input type='submit' value='Update' name='update-task' class='btn btn-success'>
                </div>
                



              </form>

            </div>
          </div>
        </div>
        

        <!--Add user Modal -->
        <div class="modal fade" id="adduserModel" tabindex="-1" aria-labelledby="adduserModelLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="adduserModelLabel">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form action='../controllers/authController.php' method='POST' class='p-0 m-0' >
                <div class="modal-body">
                  <!-- Email input -->
                  <div class="form-outline mb-4">
                    <input type="text" id="form2Example1" required  class="form-control" name="username" />
                    <label class="form-label" for="form2Example1">Username</label>
                  </div>

                  <!-- Password input -->
                  <div class="form-outline mb-4 position-relative">
                    <input type="password" required id="form2Example2" class="form-control" name="password"/>
                    <label class="form-label" for="form2Example2">Password</label>
                    <div class="eye">
                      <button type="button" class="fas fa-eye border-0 bg-transparent eyes" data-show="true"></button> 
                      <button type="button" class="fas fa-eye-slash border-0 bg-transparent eyes d-none" data-show='false'></button> 
                    </div>
                  </div>
                  <!-- conforma pass word  -->
                  <div class="form-outline mb-4 position-relative">
                    <input type="password" required id="form2Example3" class="form-control" name="cpassword"/>
                    <label class="form-label" for="form2Example3">Conform Password</label>
                    
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  
                    <button type="submit" name='add-user' class="btn btn-success">Create</button>
                  
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- password change model   -->

        <div class="modal fade" id="passwordChangeModel" tabindex="-1" aria-labelledby="passwordChangeModelLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="passwordChangeModelLabel">Password Change</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form action='../controllers/authController.php' method='POST' class='p-0 m-0' >
                <div class="modal-body">
                  
                  <!-- Password input -->
                  <div class="form-outline mb-4 position-relative">
                    <input type="password" required id="form2Example4" class="form-control" name="currentpassword"/>
                    <label class="form-label" for="form2Example4">Current Password</label>
                    
                  </div>

                  <!-- new pass word  -->
                  <div class="form-outline mb-4 position-relative">
                    <input type="password" required id="form2Example5" class="form-control" name="newpassword"/>
                    <label class="form-label" for="form2Example5">New Password </label>
                    
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  
                    <button type="submit" name='change-password' class="btn btn-success">Save</button>
                  
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- change username model  -->
        <div class="modal fade" id="usernameChangeModel" tabindex="-1" aria-labelledby="usernameChangeModelLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="usernameChangeModelLabel">Change Username</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form action='../controllers/authController.php' method='POST' class='p-0 m-0' >
                <div class="modal-body">
                  
                  <!-- Password input -->
                  <div class="form-outline mb-4 position-relative">
                    <input type="text" required id="form2Example6" class="form-control" name="cuname"/>
                    <label class="form-label" for="form2Example6">Current Username</label>
                    
                  </div>

                  <!-- new pass word  -->
                  <div class="form-outline mb-4 position-relative">
                    <input type="text" required id="form2Example7" class="form-control" name="newuname"/>
                    <label class="form-label" for="form2Example7">New Username</label>
                    
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  
                    <button type="submit" name='change-username' class="btn btn-success">Save</button>
                  
                </div>
              </form>
            </div>
          </div>
        </div>
        
       

        

      </div>
    </div>

    

    <script src='../js/remove.js'></script>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>
    <script src='../js/task,.js'></script>

    <script>
      document.addEventListener("DOMContentLoaded",()=>{
        let upload=document.getElementById('file-upload')
        upload.addEventListener("change",(e)=>{

          let box=document.getElementById('file-box')

          if(e.target.checked){
            box.classList.remove('d-none')
            box.querySelector('input[type=file]').setAttribute('required','')
          }else{
            box.classList.add('d-none')
            box.querySelector('input[type=file]').removeAttribute('required')
          }
        })
      })
    </script>

  </body>
</html>