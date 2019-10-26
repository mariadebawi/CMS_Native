<!-- Connecting db -->
<?php

require_once("includes/db.php");
require_once("includes/functions.php");
require_once("includes/sessions.php");

    // get the name of the page
    $_SESSION['TrakingUrl'] = $_SERVER['PHP_SELF'] ; 


ConfirmLogin() ;

//add categories 
if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $nameAdmin = $_POST["nameAdmin"];
    $passwordAdmin = $_POST["passwordAdmin"];
    $ConfirmPasswordAdmin = $_POST["ConfirmPasswordAdmin"];

    $Admin = $_SESSION['AdminName'] ;

    //https://www.php.net/manual/fr/function.date.php

    date_default_timezone_set("Africa/Tunis"); //the time exact of tunis
    $CurrentTime = time();
    $DateTime = strftime("%B-%d-%Y %H:%M:%S", $CurrentTime);


    if (empty($username) || empty($passwordAdmin) || empty($nameAdmin) ) {
        $_SESSION['ErrorMessage'] = "All fields must be field out";
        Redirect_to("Admins.php");
    } elseif (strlen($username) < 3 || strlen($nameAdmin) < 3 ) { //control legth
        $_SESSION['ErrorMessage'] = "username or AdminName must be greater than 2 characters";
        Redirect_to("Admins.php");
    } elseif (strlen($username) > 49) { //control legth
        $_SESSION['ErrorMessage'] = "username title must be less than 50 characters";
        Redirect_to("Admins.php");
    } elseif (existUser($username)) {
        $_SESSION['ErrorMessage'] = "username exist please chose again";
        Redirect_to("Admins.php");
    } elseif (strlen($passwordAdmin) < 3) { //control legth
        $_SESSION['ErrorMessage'] = "Password must be greater than 2 characters";
        Redirect_to("Admins.php");
    } elseif (strlen($passwordAdmin) > 49) { //control legth
        $_SESSION['ErrorMessage'] = "Password must be less than 50 characters";
        Redirect_to("Admins.php");
    } elseif ($passwordAdmin !== $ConfirmPasswordAdmin) {
        $_SESSION['ErrorMessage'] = "Password and Confirm Possword should be mutched";
        Redirect_to("Admins.php");
    } else {
        // add categorie
        global $ConnectingDb;

        
       
        $sql = "INSERT INTO admins(username,password,addby,name,dateTime)";
        $sql .= "VALUES(:username , :password ,:addby, :name,:dateTime)";
        $stm = $ConnectingDb->prepare($sql);
        $stm->bindValue(':username', $username);
        $stm->bindValue(':password', $passwordAdmin);
        $stm->bindValue(':addby', $Admin);
        $stm->bindValue(':name', $nameAdmin);
        $stm->bindValue(':dateTime', $DateTime);
        $excute = $stm->execute();

        if ($excute) {
            $_SESSION['SuccessMessage'] = "Admin with name ".$nameAdmin."added by ".$Admin." with successfully";
            Redirect_to("Admins.php");
        }
        if ($excute) {
            $_SESSION['ErrorMessage'] = "You have a problem on added";
            Redirect_to("Admins.php");
        }
    }
}



if (isset($_POST['delete'])) {

    $id = $_POST['deleteID'];


    $sql = "DELETE  from admins WHERE id = '$id'";
    $excute = $ConnectingDb->query($sql);


    if ($excute) {

        $_SESSION['SuccessMessage'] = "Admin deleted with successfully";
        Redirect_to("Admins.php");
    }
    if ($excute) {
        $_SESSION['ErrorMessage'] = "You have a problem , Try Again ";
        Redirect_to("Admins.php");
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
    <title>Admin Page</title>
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
                        <a class="nav-link" href="dashbaord.php">Dashbaord</a>
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
                    <h1><i class="fas fa-user"></i> Manage User</h1>
                </div>
            </div>
        </div>
    </header>
    <!-- End Header-->

    <!-- Main -->
    <div class="container">
        <div class="row">
            <div class="col-offset-lg-1 col-md-10 py-2 mb-4">
                <!-- Session msg-->
                <?php
                echo ErrorMessage();
                echo SuccessMessage();
                ?>
                <form action="Admins.php" method="post">
                    <div class="card bg-secondary text-light mb-3">
                        <div class="card-header">
                            <h1>Add new admin</h1>
                        </div>
                        <div class="card-body bg-dark">
                            <div class="form-group">
                                <label for="title"><span class="fieldInfo">Username :</span></label>
                                <input type="email" class="form-control" name="username" placeholder="type username here">
                            </div>
                            <div class="form-group">
                                <label for="title"><span class="fieldInfo">Name :</span></label>
                                <input type="text" class="form-control" name="nameAdmin" placeholder="type name here">
                                <!-- <small class="text-warning text-muted"> Optional</small> -->
                            </div>
                            <div class="form-group" style="position:relative ">
                                <label for="title"><span class="fieldInfo">Password :</span></label>
                                <input type="password" class="form-control " id="Password" name="passwordAdmin" placeholder="type password here" >
                                
                                <span id="show" class="fas fa-eye-slash" > </span>
                               
                            </div>
                            <div class="form-group">
                                <label for="title"><span class="fieldInfo">Confirm Password :</span></label>
                                <input type="password" class="form-control" name="ConfirmPasswordAdmin" placeholder="Confirm password here">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <a href="dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i> Back to dashboard</a>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <button class="btn btn-success btn-block" name="submit" type="submit"><i class="fas fa-check"></i> Publish</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-lg-12 ">
                        <h1 class="fieldInfo mb-3 font-weight-bold">Admins :</h1>
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>

                                    <th>Date&Time</th>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Added By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <!-- fetch Posts-->
                            <?php
                            global $ConnectingDb;
                            $sql = "SELECT * from admins ORDER BY id DESC";
                            $stmt = $ConnectingDb->query($sql);
                            $Sr = 0;
                            while ($DataRows = $stmt->fetch()) {
                                $adminId = $DataRows['id'];
                                $username = $DataRows['username'];
                                $name = $DataRows['name'];
                                $datetime	 = $DataRows['datetime'];
                                $addBy	 = $DataRows['addby'];
                                $Sr++;
                                ?>
                                <tbody>
                                    <tr>
                                        <td><?php echo $Sr ?></td>

                                        <td>
                                            <?php
                                                //limit the content
                                                // if (strlen($dateTime) > 11) {
                                                //     $dateTime = substr($dateTime, 0, 11) . "...";
                                                // }
                                                echo $datetime
                                                ?>
                                        </td>


                                        <td>
                                            <?php
                                                //limit the content
                                                if (strlen($username) > 20) {
                                                    $username = substr($username, 0, 15) . "...";
                                                }
                                                echo $username
                                                ?>
                                        </td>

                                        <td>
                                            <?php
                                                //limit the content
                                                if (strlen($name) > 20) {
                                                    $name = substr($name, 0, 7) . "...";
                                                }
                                                echo $name
                                                ?>
                                        </td>

                                        <td>
                                            <?php
                                                //limit the content
                                                if (strlen($addBy) > 20) {
                                                    $addBy = substr($addBy, 0, 7) . "...";
                                                }
                                                echo $addBy
                                                ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger" data-toggle="modal" data-target="#DeleteModal" id="<?php echo $adminId ?>">Delete</button>
                                        </td>

                                    </tr>


                                    <!-- Modal -->
                                    <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background-color: #27aae1;">
                                                    <h5 class="modal-title" id="exampleModalLabel">Delete Admin</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="Admins.php" method="post">
                                                    <div class="modal-body">
                                                        <input name="deleteID" class="d-none" value="<?php echo $adminId ?>">
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
        </div>

    </div>
    <!-- End main -->



    <section>
    </section>






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