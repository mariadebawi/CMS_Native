<!-- Connecting db -->
<?php

require_once("includes/db.php");
require_once("includes/functions.php");
require_once("includes/sessions.php");

//stop to go to the page login if y'r connected

if(isset($_SESSION['AdminName'])){
    Redirect_to("Dashboard.php");
}


// login 
if (isset($_POST["submit"])) {


    $username = $_POST["username"];
    $password = $_POST["password"];

   
    if (empty($username) || empty($password)) {
        $_SESSION['ErrorMessage'] = "All fields must be field out";
        Redirect_to("login.php");
    }  
    elseif (!existUser($username)) {
        $_SESSION['ErrorMessage'] = "username dosen't  exist";
        Redirect_to("login.php");
    }
    else {
    $FoutAccount = Login($username , $password) ;
    if($FoutAccount){
        $_SESSION['AdminName'] = $FoutAccount['name'] ;
        $_SESSION['SuccessMessage'] = "welcome " .$_SESSION['AdminName'];
    
         if(isset($_SESSION['TrakingUrl'])){
            //if you logout and want a specific page
            Redirect_to($_SESSION['TrakingUrl']);
         }
         else {
            Redirect_to("Dashboard.php");
            echo  "hello". $_SESSION['TrakingUrl'] ;
         }
    }
     else {
            $_SESSION['ErrorMessage'] = "Incorrect Username/Password";
            Redirect_to("login.php");
    }
    
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
    <title>Login</title>
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
                </div>
            </div>
        </div>
    </header>
    <!-- End Header-->

    <!-- Main Area -->

    <section class="container py-2 mb-4">
        <div class="row"> 
            <div class="offset-sm-3 col-sm-6" style="min-height: 500px">
                <br><br>
                <?php 
                    echo ErrorMessage() ; 
                    echo SuccessMessage() ;
                   ?>
                <div class="card bg-secondary text-light">
                    <div class="card-header">
                        <h4>Welcome Back !!!</h4>
                    </div>
                    <div class="card-body bg-dark">
                        <form action="login.php" method="post">
                            <div class="form-group">
                            <label for="username"><span class=" fieldInfo">Username</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-info"><i class="text-white fas fa-user"></i></span>
                                    </div>
                                    <input type="email" value="" class="form-control" name="username" placeholder="username">
                                </div>
                                <label for="passowrd"><span class=" fieldInfo">Password</span></label>
                                <div class="input-group mb-3" >
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-info"><i class="text-white fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" value="" class="form-control" name="password" placeholder="password">

                                </div>
                            </div>
                               <button class="btn btn-info btn-block" type="submit" name="submit">Login</button>
                        </form>
                    </div>
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