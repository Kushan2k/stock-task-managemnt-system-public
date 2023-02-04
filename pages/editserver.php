<?php
session_start();
include_once '../config.php';

if(!isset($_COOKIE['login']) && !isset($_SESSION['username'])){
  header("Location:./login.php");
}

if(!isset($_GET['server_id'])){
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
	          
						
            <li ><a href="">Task</a></li>
            <li class="active"><a href="./servers.php">Servers</a></li>
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
            <a href='./servers.php' style='font-weight:bolder' class='btn btn-sm btn-outline-dark'><<</a>
          </div>
        
          <div class="row">
            <div class="col-12">

              <?php

              $sql = "SELECT * FROM servers WHERE id={$_GET['server_id']}";
              $res = $conn->query($sql);
              if($res->num_rows>0){
                $data = $res->fetch_assoc();?>

                <form action="../controllers/serverController.php" method='POST' >
                  <div class="modal-body">

                    <input type='hidden' value='<?= $_GET['server_id']?>' name='serverid'/>

                    <div class="form-row my-1 justify-content-around">
                      <div class="form-group">
                        <label class='form-label'>Server Name</label>
                        <input type="text" required  class="form-control" name='name' placeholder="Name" value='<?= $data['name']?>'>
                      </div>
                      <div class="form-group">
                        <label class='form-label'>City</label>
                        <input type="text" required  class="form-control" name='city' placeholder="City" value='<?= $data['city']?>'>
                      </div>
                    </div>

                    <div class="form-row my-1 justify-content-around">
                      <div class="form-group">
                        <label class='form-label'>Country</label>
                        <input type="text" value='<?= $data['country']?>'  class="form-control" name='country' placeholder="Country">
                      </div>
                      <div class="form-group">
                        <label class='form-label'>Ipmi ip</label>
                        <input type="text"  class="form-control" value='<?= $data['ipmiip']?>' name='ipmiip' placeholder="ipmi ip">
                      </div>
                      <div class="form-group">
                        <label class='form-label'>Server No</label>
                        <input type="text" value='<?= $data['seriel']?>' class="form-control" name='seriel' placeholder="Seriel No">
                      </div>
                    </div>
                    
                    <div class="form-row my-1 justify-content-around">
                      <div class="form-group">
                        <label class='form-label'>Rack Unit</label>
                        <input type="text" value='<?= $data['rackunit']?>'  class="form-control" name='ru' placeholder="Rack Unit">
                      </div>
                      <div class="form-group">
                        <label class='form-label'>Facility Name</label>
                        <input type="text" value='<?= $data['facilityname']?>' class="form-control" name='fn' placeholder="Fasility Name">
                      </div>
                      <div class="form-group">
                        <label class='form-label'>Rack No</label>
                        <input type="text" value='<?= $data['racknumber']?>' class="form-control" name='rn' placeholder="Rack No">
                      </div>
                    </div>
                    
                    <div class="form-row my-1 justify-content-around">
                      <div class="form-group">
                        <label class='form-label'>CPU</label>
                        <input type="text" value='<?= $data['cpu']?>'  class="form-control" name='cpu' placeholder="Cpu">
                      </div>
                      <div class="form-group">
                        <label class='form-label'>Memory</label>
                        <input type="text" value='<?= $data['memory']?>'  class="form-control" name='mem' placeholder="Memory">
                      </div>
                      <div class="form-group">
                        <label class='form-label'>Disk Model</label>
                        <input type="text" value='<?= $data['diskmodel']?>'  class="form-control" name='dm' placeholder="Disk Model">
                      </div>
                      <div class="form-group">
                        <label class='form-label'>NIC</label>
                        <input type="text" value='<?= $data['nic']?>'  class="form-control" name='nic' placeholder="NIC">
                      </div>
                    </div>

                    <div class="form-row my-1 justify-content-around">
                      <div class="form-group">
                        <label class='form-label'>Server IP</label>
                        <input type="text"  value='<?= $data['serverip']?>' class="form-control" name='serverip' placeholder="server ip">
                      </div>
                      <div class="form-group">
                        <label class='form-label'>Server User</label>
                        <input type="text" value='<?= $data['serveruser']?>' class="form-control" name='serveruser' placeholder="server user">
                      </div>
                      <div class="form-group">
                        <label class='form-label'>Server Password</label>
                        <input type="text" value='<?= $data['serverpass']?>' class="form-control" name='serverpass' placeholder="server pass">
                      </div>
                      <div class="from-group">
                        <label class='form-label'>Server Port</label>
                        <input type="text" value='<?= $data['serverport']?>' class="form-control" name='serverport' placeholder="server port">
                      </div>
                    </div>
                      
                    <div class="form-row my-1 justify-content-around">
                      <div class="form-group">
                        <label class='form-label'>ipmi port</label>
                        <input type="text" value='<?= $data['ipmiport']?>' class="form-control" name='ipmiport' placeholder="ipmi port">
                      </div>
                      <div class="form-group">
                        <label class='form-label'>ipmi user</label>
                        <input type="text" value='<?= $data['ipmiuser']?>' class="form-control" name='ipmiuser' placeholder="ipmi user">
                      </div>
                      <div class="from-group">
                        <label class='form-label'>ipmi password</label>
                        <input type="text" value='<?= $data['ipmipass']?>' class="form-control" name='ipmipass' placeholder="ipmi pass">
                      </div>
                      
                    </div>

                  </div>
                  <div class="modal-footer">
                      <a href='./servers.php' type="button" class="btn btn-secondary" >Close</a>
                        
                      <button type="submit" name='update-servers' class="btn btn-success">Update</button>
                        
                  </div>
                </form>

              <?php }else{
                $_SESSION['error'] = 'no servers found!';
                header("Location:{$_SERVER['HTTP_REFERER']}");
              }
              

              ?>

              

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

    

  </body>
</html>