<?php
session_start();

if(isset($_COOKIE['login']) && isset($_SESSION['login'])){
  header("Location:../index.php");
}

?>

<!doctype html>
<html lang="en">
  <head>
  	<title>Sidebar 01</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css'>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
		
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="../css/style.css">
  </head>
  <body>
		
		<div class="wrapper d-flex align-items-stretch">
			<nav id="sidebar">
				<div class="p-4 pt-5">
		  		<a href="#" class="img logo rounded-circle mb-5" style="background-image: url('https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_1280.png');"></a>
	        <ul class="list-unstyled components mb-5">
	          <li>
	            <a href="../index.php">Home</a>
	          </li>
	          <li class="active">
	              <a href="#">Login</a>
	          </li>
	          <li>
              
	          </li>
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
          <div class="row">
            <div class="container">
              <?php

                if(isset($_SESSION['error'])){?>
                  <p class="alert alert-danger text-center"><?= ucfirst($_SESSION['error'])?></p>
                <?php $_SESSION['error'] = null;}else if(isset($_SESSION['msg'])){?>
                  <p class="alert alert-success text-center"><?= ucfirst($_SESSION['msg'])?></p>
                <?php $_SESSION['msg'] = null;}
              
              
              ?>
            </div>
            <div class="col-12 col-md-6 mx-auto">
              <form action="../controllers/authController.php" method="POST">
                <!-- Email input -->
                <div class="form-outline mb-4">
                  <input type="text" id="form2Example1"  class="form-control" name="username" />
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

                <style>
                  .eye{
                    position: absolute;
                    right: 5px;
                    top: 5px;
                  }
                </style>

                

                <!-- Submit button -->
                <button type="submit" name="login" class="btn btn-primary btn-block mb-4">Sign in</button>

                
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
  </body>
</html>