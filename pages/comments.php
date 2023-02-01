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
  	<title>Comments</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css'>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
		
    <link rel="stylesheet" href="../css/task.css">
		<link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro-v6@44659d9/css/all.min.css" rel="stylesheet" type="text/css" />
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
	            <a href="../index.php">Home</a>
	          </li>
	          
						
            <li class="active"><a href="./task.php">Task</a></li>
            <li><a href="./servers.php">Servers</a></li>
            
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

          <div class="row mb-3">
            <button class='btn btn-info'  data-toggle='modal' data-target='#addTaskModel'>Add New Comment</button>
          </div>


          <div class="row">
            <div class="col-12">
              <div class="form-group pull-right">
                  <input type="text" class="search form-control" placeholder="What you looking for?">
              </div>
              <span class="counter pull-right"></span>
              <table class="table table-hover table-bordered results">
                <thead>
                  <tr>
                    <th class="col-7 ">Comments</th>
                    <th class='col-3'>Post Date</th>
                    <th></th>
                    
                  </tr>
                  <tr class="warning no-result">
                    <td colspan="4"><i class="fa fa-warning"></i> No result</td>
                  </tr>
                </thead>
                <tbody>
                  <?php

                  
                  $taskid = isset($_GET['task_id']) ? $_GET['task_id'] : '';
                  $sql = "SELECT * FROM comments WHERE task_id={$taskid} ORDER BY id DESC";
                  $res = $conn->query($sql);
                  if($res==TRUE){
                    if($res->num_rows>0){
                      while ($row = $res->fetch_assoc()) {
                          ?>
                          <tr>
                            <td><?= ucfirst($row['msg']) ?></td>
                            <td><?= $row['post_date']?></td>  
                            
                          <?php 
                          
                          if($row['user_id']==$_COOKIE['token']-678){?>
                            <td>
                              <button data-target='#EditCommentModel<?= $row['id']?>' class='border-0 bg-transparent fa-solid fa-pen text-success' data-toggle='modal' ></button>
                              <button data-comment='<?= $row['id'] ?>' class='fa-solid  fa-trash text-danger border-0 bg-transparent'  data-toggle='modal' data-target='#deleteCommentModel'></button>
                                
                            </td>
                         <?php }
                          
                          ?>

                            
                          </tr>

                          <!-- edit comment model  -->
                          <div class="modal fade" id="EditCommentModel<?= $row['id']?>" tabindex="6" aria-labelledby="EditCommentModel<?= $row['id']?>Label" aria-hidden="true">

                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="EditCommentModel<?= $row['id']?>Label">Add a task</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  
                                  <form action="../controllers/taskController.php" method='POST' >
                                    
                                    <input type='text' class='form-control' value='<?= $row['msg']?>' required name='newcomment' >
                                    <input type='hidden' value='<?= $taskid?>' name='taskid'>
                                    <input type='hidden' value='<?= $row['id']?>' name='commentid'>
                                    
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                      
                                        <button type="submit" name='update-comment' class="btn btn-success">Save</button>
                                      
                                    </div>
                                  </form>
                                  
                                </div>
                              </div>
                            </div>
                          </div>

                          


                      <?php }//end of while loop

                    }else{?>
                      <tr class="warning no-result">
                        <td colspan="2"><i class="fa fa-warning"></i> No result</td>
                      </tr>
                    <?php }
                  }else{?>
                    <tr class="warning no-result">
                      <td colspan='2'><i class="fa fa-warning"></i> server error</td>
                    </tr>
                  <?php }



                  ?>
                  
                  
                  
                </tbody>
              </table>
            </div>
          </div>
        </div>

        
        

        <!-- add new Comment model  -->
        <div class="modal fade" id="addTaskModel" tabindex="6" aria-labelledby="addTaskModelLabel" aria-hidden="true">

          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addTaskModelLabel">Comment Now</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                
                <form action="../controllers/taskController.php" method='POST' enctype='multipart/form-data'>

                  <div class="form-group">
                    <label class=" form-label">Message</label>
                    <input class="form-control" name='msg' type="text"/>
                    <input class="form-control" name='taskid' value='<?= $_GET['task_id']?>' type="hidden"/>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    
                      <button type="submit" name='add-comment' class="btn btn-success">Save</button>
                    
                  </div>
                </form>
                  
                
              </div>
            </div>
          </div>
        </div>

        <!-- delete comment model  -->
        <div class="modal fade" id="deleteCommentModel" tabindex="6" aria-labelledby="deleteCommentModelLabel" aria-hidden="true">

          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteCommentModelLabel">Add a task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p class='text-danger' >this comment will be deleted and this is a permenent action and can't be undone later.</p>
                <form action="../controllers/taskController.php" method='POST' >
                  
                <input type='hidden' name='commentid' id='comment_id' value='id'/>
                  
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    
                      <button type="submit" name='delete-comment' class="btn btn-danger">Delete</button>
                    
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
        let delBtns=document.querySelectorAll('.fa-trash');
        delBtns.forEach(btn=>{
          btn.addEventListener('click',(e)=>{
            document.getElementById("comment_id").value=e.target.dataset.comment
          })
        })

        let editbtns=document.querySelectorAll('.fa-pen')
        editbtns.forEach(btn=>{
          btn.addEventListener('click',(e)=>{

          })
        })
      })

    </script>


  </body>
</html>