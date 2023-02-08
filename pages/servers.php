<?php
session_start();
include_once '../config.php';

if(!isset($_COOKIE['login']) && !isset($_SESSION['username'])){
  header("Location:./login.php");
}

?>

<!doctype html>
<html lang="en">
  <head>
  	<title>Servers</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro-v6@44659d9/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
		
    <link rel="stylesheet" href="../css/task.css">
		<link rel="stylesheet" href="../css/style.css">
  </head>
  <body >

  <style>

    .logout-btn:hover{
      cursor:pointer;
    }
  </style>
		
		<div class="wrapper d-flex align-items-stretch ">
			<nav id="sidebar">
				<div class="p-4 pt-5">
		  		<a href="#" class="img logo rounded-circle mb-5" style="background-image: url('https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_1280.png');"></a>
          <p class="text-center"><?= isset($_SESSION['username'])?$_SESSION['username']:'' ?></p>
	        <ul class="list-unstyled components mb-5">
	          <li >
	            <a href="../index.php">Home</a>
	          </li>
	          
						
            <li ><a href="./task.php">Task</a></li>
            <li class="active"><a href="">Servers</a></li>
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
          <div class="row mb-3">
            <button class='btn btn-info'  data-toggle='modal' data-target='#addTaskModel'>Add New Server</button>
          </div>


          <div class="row">
            <div class="col-12">
              <div class="form-group pull-right">
                  
                  <input type="text" class="search form-control" placeholder="What you looking for?">
              </div>
              <p class="text-center display-4">Servers</p>

              <div class="container">
                <div class="row">
                  <div class="col-12 d-flex flex-row justify-content-end ">
                    <!-- <input type='button' data-toggle='modal' data-target='#importModal' class="mr-2  btn btn-info" value='Import From CSV' > -->
                    
                    <form action="../controllers/export.php" method="POST" class="my-2">
                      <input type="submit" name='export' value="Export to Excel" class="btn btn-sm btn-dark">
                    </form>
                  </div>
                </div>
              </div>

              <table class="table table-hover table-bordered results mx-auto">
                <thead>
                  <tr>
                    <th class="col ">ServerName</th>
                    <th class="col ">ServerIP</th>
                    <th class="col ">ServerUser</th>
                    <th class="col d-none d-md-flex'">ServerPort</th>
                    <th class="col"></th>
                    <th class="col "></th>
                  </tr>
                  <tr class="warning no-result">
                    <td colspan="4"><i class="fa fa-warning"></i> No result</td>
                  </tr>
                </thead>
                <tbody>
                  <?php

                  

                  $sql = "SELECT * FROM servers ORDER BY id DESC";
                  $res = $conn->query($sql);
                  if($res==TRUE){
                    if($res->num_rows>0){

                      while($row=$res->fetch_assoc()){?>
                        <tr  >
                         <td><?= ucfirst($row['name'])?></td>
                         <td><?= ucfirst($row['serverip'])?></td>
                         <td><?= ucfirst($row['serveruser'])?></td>
                         <td class='d-none d-md-flex'><?= ucfirst($row['serverport'])?></td>
                         <td>
                            <button class="text-black-50 w-100 fa-solid fa-chevrons-down btn btn-sm btn-info  border-0" data-toggle="collapse" href="#collapseExample<?= $row['id']?>" role="button" aria-expanded="false" aria-controls="collapseExample<?= $row['id']?>"></button>
                         </td>
                         <td class='d-flex'>
                          <a href='./editserver.php?server_id=<?= $row['id']?>'class="btn mr-1 btn-sm btn-success">Edit</a>
                          <button data-toggle='modal' data-target='#deleteServerModel' data-server='<?= $row['id']?>' class="btn btn-sm btn-danger delete-btn">Delete</button>
                         </td>
                          
                        </tr>
                        <tr class='bg-light'>
                           <td colspan='6'>
                            <div class="collapse" id="collapseExample<?= $row['id']?>">
                              <div class="card card-body">

                              <table class="table table-striped">
                                <thead>
                                  <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Value</th>
                                    
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php

                                    foreach($row as $key => $val) {
                                      ?>
                                    <tr>
                                      <td><?= ucfirst($key) ?></td>
                                      <td><?= $val ?></td>


                                    
                                      
                                      </tr>
                                    <?php }
                                    
                                  
                                  
                                  ?>
                                </tbody>
                              </table>

                              </div>
                            </div>
                          </td>
                        </tr>
                      <?php }

                    }else{?>
                      <td colspan="4"><i class="fa fa-warning"></i> No result</td>
                    <?php }

                  }else{?>
                      <td colspan="4"><i class="fa fa-warning"></i> error</td>
                  <?php }
                  
                  ?>
                  
                  
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- import Modal -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <form action='../controllers/import.php' method='POST' class='p-0 m-0' >
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="importModalLabel">Import From the Excel</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class='model-body p-3'>
                  <label class='form-label'>CSV File</lable>
                  <input type='file' required name='csv' accept='.csv.' class='form-control' />
                </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  
                    <button type="submit" name='import' class="btn btn-primary">Import</button>
                  
                </div>
              </div>
            </form>
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
        


        <!-- add new server model  -->
        <div class="modal fade" id="addTaskModel" tabindex="6" aria-labelledby="addTaskModelLabel" aria-hidden="true">

          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addTaskModelLabel">Add a server</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form action="../controllers/serverController.php" method='POST' >
                <div class="modal-body">

                  <div class="form-row my-1">
                    <div class="col">
                      <input type="text" required  class="form-control" name='name' placeholder="Name">
                    </div>
                    <div class="col">
                      <input type="text" required  class="form-control" name='city' placeholder="City">
                    </div>
                  </div>

                  <div class="form-row my-1">
                    <div class="col">
                      <input type="text"  class="form-control" name='country' placeholder="Country">
                    </div>
                    <div class="col">
                      <input type="text"  class="form-control" name='ipmiip' placeholder="ipmi ip">
                    </div>
                    <div class="col">
                      <input type="text"  class="form-control" name='seriel' placeholder="Seriel No">
                    </div>
                  </div>
                  
                  <div class="form-row my-1">
                    <div class="col">
                      <input type="text"  class="form-control" name='ru' placeholder="Rack Unit">
                    </div>
                    <div class="col">
                      <input type="text"  class="form-control" name='fn' placeholder="Fasility Name">
                    </div>
                    <div class="col">
                      <input type="text"  class="form-control" name='rn' placeholder="Rack No">
                    </div>
                  </div>
                   
                  <div class="form-row my-1">
                    <div class="col">
                      <input type="text"  class="form-control" name='cpu' placeholder="Cpu">
                    </div>
                    <div class="col">
                      <input type="text"  class="form-control" name='mem' placeholder="Memory">
                    </div>
                    <div class="col">
                      <input type="text"  class="form-control" name='dm' placeholder="Disk Model">
                    </div>
                    <div class="col">
                      <input type="text"  class="form-control" name='nic' placeholder="NIC">
                    </div>
                  </div>

                  <div class="form-row my-1">
                    <div class="col">
                      <input type="text"  class="form-control" name='serverip' placeholder="server ip">
                    </div>
                    <div class="col">
                      <input type="text"  class="form-control" name='serveruser' placeholder="server user">
                    </div>
                    <div class="col">
                      <input type="text"  class="form-control" name='serverpass' placeholder="server pass">
                    </div>
                    <div class="col">
                      <input type="text"  class="form-control" name='serverport' placeholder="server port">
                    </div>
                  </div>
                    
                  <div class="form-row my-1">
                    <div class="col">
                      <input type="text"  class="form-control" name='ipmiport' placeholder="ipmi port">
                    </div>
                    <div class="col">
                      <input type="text"  class="form-control" name='ipmiuser' placeholder="ipmi user">
                    </div>
                    <div class="col">
                      <input type="text"  class="form-control" name='ipmipass' placeholder="ipmi pass">
                    </div>
                    
                  </div>

                  <div class="form-row my-1 flex-column">
                    <div class="form-group mt-1">
                      <label  class="form-label">Mac Addresses <span> <button type="button" class="fa-solid fa-plus bg-transparent border-0 " id='addmac'></button></span> </label>
                    </div>
                    <div class="col mac-box">
                      <!-- <input type="text" required class="form-control" name='macaddress[]' placeholder="mac address"> -->
                    </div>
                  </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      
                    <button type="submit" name='add-servers' class="btn btn-success">Save</button>
                      
                </div>
              </form>
            </div>
          </div>
        </div>
        

        <!-- delete server model  -->
        <div class="modal fade" id="deleteServerModel" tabindex="6" aria-labelledby="deleteServerModelLabel" aria-hidden="true">

          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteServerModelLabel">Delete Server</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p class='text-danger' >server will be deleted and this is a permenent action and can't be undone later.</p>
                <form action="../controllers/serverController.php" method='POST' >
                  
                <input type='hidden' name='serverid' id='server_id' value='id'/>
                  
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    
                      <button type="submit" name='delete-server' class="btn btn-danger">Delete</button>
                    
                  </div>
                </form>
                  
                
              </div>
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
        let addmac=document.querySelector('#addmac')
        let macbox=document.querySelector('.mac-box')

        let delBtns=document.querySelectorAll('.delete-btn');
        delBtns.forEach(btn=>{
          btn.addEventListener('click',(e)=>{
            document.getElementById("server_id").value=e.target.dataset.server
          })
        })

        addmac.addEventListener("click",(e)=>{

          // <input type="text" required class="form-control" name='macaddress[]' placeholder="mac address">

          let input=document.createElement('input')
          input.setAttribute('type','text')
          input.setAttribute('required','')
          input.setAttribute('name','macaddress[]')
          input.setAttribute('placeholder','mac address')
          input.classList.add('form-control')
          macbox.appendChild(input)

        })
      })
    </script>


  </body>
</html>