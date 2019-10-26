<!-- Connecting db -->
<?php

require_once("includes/db.php");
require_once("includes/functions.php");
require_once("includes/sessions.php");

// get the name of the page
$_SESSION['TrakingUrl'] = $_SERVER['PHP_SELF'] ; 

ConfirmLogin() ; //if username dosen't exist , must login 

$Admin = $_SESSION['AdminName'] ;

//global $ConnectingDb ;

if (isset($_POST['delete'])) {

    $id = $_POST['deleteID'];


    $sqlDelete = "DELETE  from comments WHERE id = '$id'";
    $excute = $ConnectingDb->query($sqlDelete);


    if ($excute) {
        
        $_SESSION['SuccessMessage'] = "Comment deleted with successfully";
        Redirect_to("comment.php");
    }
     else { 
        $_SESSION['ErrorMessage'] = "You have a problem , Try Again ";
        Redirect_to("comment.php");
    }
}


?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="FontAwsome/all.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Comments</title>
</head>

<body>
    <div class="navdivider"></div>
    <!-- Navbar-->
    <nav class="navbar navbar-expand-lg  navbar-dark  bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">CMS</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavCmS">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavCmS">

                <ul class="navbar-nav mr-auto ">
                    <li class="nav-item ">
                        <a class="nav-link" href="myProfile.php">
                            <i class="fas fa-user text-success"></i> My Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashbaord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Post.php">Posts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_admins.php">Manage Admins</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="comment.php">Comments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="blog.php?page=1">Live Blog</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto ">
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">
                            <i class="fas fa-user-times "> </i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="navdivider"></div>
    <!-- end Navbar-->

    <!-- Header-->
    <header class="bg-dark text-white py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1><i class="fas fa-comments"></i> Manage Comment </h1>
                </div>
            </div>
        </div>
    </header>
    <!-- End Header-->

    <!-- Main Area -->

    <section style="min-height: 600px">

        <div class="container py-3 mb-4 ">
            <?php
            echo ErrorMessage();
            echo SuccessMessage();
            ?>
           
            <div class="row  mb-4">
                <div class="col-lg-12 ">
                    
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr class="text-center">
                                <th>No.</th>
                                <th>Date&Time</th>
                                <th>Name</th>
                                <th>Comments</th>
                                <th>Aprove</th>
                                <th>Action</th>
                                <th>Live Preview</th>
                            </tr>
                        </thead>
                        <!-- fetch Posts-->
                        <?php
                        global $ConnectingDb;
                        $sql = "SELECT * from comments ";
                        $stmt = $ConnectingDb->query($sql);
                        $Sr = 0;
                        while ($DataRows = $stmt->fetch()) {
                            $CommetId = $DataRows['id'];
                            $CommeterName = $DataRows['CommeterName'];
                            $CommentThoughts = $DataRows['CommentThoughts'];
                            $DateTime = $DataRows['DateTime'];
                            $PostId = $DataRows['Post_id'];
                            $satus = $DataRows['status'];
                            $Sr++;
                            ?>
                            <tbody>
                                <tr>
                                    <td><?php echo $Sr ?></td>

                                    <td>
                                        <?php
                                            //limit the content
                                            if (strlen($DateTime) > 11) {
                                                $DateTime = substr($DateTime, 0, 11) . "...";
                                            }
                                            echo $DateTime
                                            ?>
                                    </td>

                                    <td>
                                        <?php
                                            //limit the content
                                            if (strlen($CommeterName) > 20) {
                                                $CommeterName = substr($CommeterName, 0, 15) . "...";
                                            }
                                            echo $CommeterName
                                            ?>
                                    </td>


                                    <td>
                                        <?php
                                            //limit the content
                                            if (strlen($CommentThoughts) > 100) {
                                                $CommentThoughts = substr($CommentThoughts, 0, 99) . "...";
                                            }
                                            ?>
                                            <?php echo $CommentThoughts ?>


                                            
                                    </td>
                                    <td>
                                        <?php if($satus == "ON"){ ?>
                                          <a href="settingsComment.php?idd='<?php echo $CommetId ; ?>'"><span class="btn btn-warning">Disapprove</span></a>
                                        <?php }
                                         else { ?>
                                          <a href="settingsComment.php?id='<?php echo $CommetId ; ?>'"><span class="btn btn-success">Approve</span></a>
                                         <?php } ?>
                                        <!-- upadate -->
                                        
                                    </td>

                                    <td>
                                        <button class="btn btn-danger" data-toggle="modal" data-target="#DeleteModal" id="<?php echo $CommetId ?>">Delete</button>
                                    </td>
                                    <td>
                                        <a href="FullPostAll.php?id=<?php echo $PostId  ?>"  target='_blank' ><span class="btn btn-primary">Live Preview</span></a>
                                    </td>
                                </tr>


                                <!-- Modal -->
                                <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: #27aae1;">
                                                <h5 class="modal-title" id="exampleModalLabel">Delete Comment</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="comment.php" method="post">
                                                <div class="modal-body">
                                                    <input name="deleteID" class="d-none"  value="<?php echo $CommetId ?>">
                                                    Are you sure ?
                                                </div>
                                                <div class="modal-footer ">
                                                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>

                            <?php  } ?>
                            <!-- End fetch Posts-->
                            </tbody>

                    </table>
                </div>
            </div>
          
        </div>
    </section>

    <!--  End Main-->



    <!-- Footer-->
    <footer class="bg-dark text-white  pt-2">
        <div class="container ">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="load ">Theme By | Me | <span id="year"></span> &copy; All rights Reserved </p>
                    <p class="small"> You can get the code from my Github <a href="https://github.com/mariadebawi" target="_blank"><i class="fab fa-github text-white"></i></a></p>
                </div>
            </div>
        </div>
    </footer>
    <div class="navdivider"></div>

    <!-- End Footer-->








    <script src="js/jquery.js"></script>
    <script src="js/script.js"></script>
    <script src="FontAwsome/all.js"></script>
    <script src="bootstrap/bootstrap.min.js"></script>
</body>

</html>