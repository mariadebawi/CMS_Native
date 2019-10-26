<!-- Connecting db -->
<?php

require_once("includes/db.php");
require_once("includes/functions.php");
require_once("includes/sessions.php");

global $ConnectingDb;

// get the name of the page
$_SESSION['TrakingUrl'] = $_SERVER['PHP_SELF'];

ConfirmLogin();

$Admin = $_SESSION['AdminName'];
$user =  $_SESSION['Username'];


//edit post
if (isset($_POST["submit"])) {

    $nameAdmin = $_POST["name"];
    $headline = $_POST["headline"];
    $description = $_POST["description"];
    $image = $_POST["image"];

    //https://www.php.net/manual/fr/function.date.php

    date_default_timezone_set("Africa/Tunis"); //the time exact of tunis
    $CurrentTime = time();
    $DateTime = strftime("%B-%d-%Y %H:%M:%S", $CurrentTime);


    if (empty($nameAdmin) || empty($headline) || empty($description)) {
        $_SESSION['ErrorMessage'] = "All fields must be field out";
        Redirect_to("myProfile.php");
    } elseif (strlen($headline) < 3 || strlen($nameAdmin) < 3) { //control legth
        $_SESSION['ErrorMessage'] = "headline or AdminName must be greater than 2 characters";
        Redirect_to("myProfile.php");
    } elseif (strlen($headline) > 13) {
        $_SESSION['ErrorMessage'] = "headline or AdminName must be less than 12 characters";
        Redirect_to("myProfile.php");
    } else {

        if (!empty($_FILES["image"]["name"])) {
            // update post
            $sql = "UPDATE admins SET name='$nameAdmin',description='$description',image='$image' , dateTime='$DateTime',headline='$headline' WHERE username='$user';";
        } else {
            $sql = "UPDATE admins SET name='$nameAdmin',description='$description', dateTime='$DateTime',headline='$headline' WHERE username ='$SearchQUeryParams';";
        }

        $stm = $ConnectingDb->query($sql);
        $excute = $stm->execute();

        // upload image to folder upload
        move_uploaded_file($_FILES["image"]["tmp_name"], $target);

        if ($excute) {
            $_SESSION['SuccessMessage'] = "Admin updated with successfully";
            Redirect_to("myProfile.php");
        }
        if ($excute) {
            $_SESSION['ErrorMessage'] = "You have a problem , Try Again ";
            Redirect_to("myProfile.php");
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
    <title>My Profile</title>
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
                        <a class="nav-link" href="admins.php">Manage Admins</a>
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
                    <h1><i class="fas fa-user"></i> My Profile</h1>
                </div>
            </div>
        </div>
    </header>
    <!-- End Header-->

    <!-- Main -->
    <div class="container mt-3">
        <?php
        $sql = "SELECT * from admins where username = '$user'";
        $stmt = $ConnectingDb->query($sql);

        while ($DataRows = $stmt->fetch()) {
            $name = $DataRows['name'];
            $description = $DataRows['description'];
            $image = $DataRows['image'];
            $headline = $DataRows['headline'];
            ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="card my-2">
                        <div class="card-header bg-dark text-light px-3">
                            <h2><?php echo $name; ?></h2>
                        </div>
                        <div class="card-body px-3">
                            <img src="upload/david.png" class="img-fluid block mb-3" />
                            <div>
                                <p><?php echo $headline; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 py-2 mb-4">
                    <!-- Session msg-->
                    <?php
                        echo ErrorMessage();
                        echo SuccessMessage();
                        ?>
                    <form action="myProfile.php?username=<?php echo $user; ?>" method="post" enctype="multipart/form-data">
                        <div class="card bg-secondary text-light mb-4">
                            <div class="card-header">
                                <h1>Edit Profile</h1>
                            </div>
                            <div class="card-body bg-dark">
                                <div class="form-group">

                                    <input type="text" class="form-control" value="<?php echo $name ?>" name="name" placeholder="your name">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" value="<?php echo $headline ?>" name="headline" placeholder="headline">
                                    <small class="text-muted">Add a professional headline like <span class="font-weight-bold">student </span> , <span class="text-danger font-weight-bold ">No more than 12 caracteres</span></small>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="description" value="<?php echo $description ?>" name="description" id="description" cols="80" rows="8"></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <div class="custom-file">
                                        <input type="File" class="custom-file-input" name="image" value="<?php echo $image ?>" id="image">
                                        <label for="imageSelect" class="custom-file-label">Select Image</label>
                                    </div>
                                </div>


                                <div class="row my-3">
                                    <div class="col-md-6 mb-2">
                                        <a href="dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i> Back to dashboard</a>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <button class="btn btn-success btn-block" name="submit" type="submit"><i class="fas fa-check"></i> Publish</button>
                                    </div>
                                </div>
                            <?php } ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

    </div>
    <!-- End main -->



    <section>
    </section>

    <?php require_once("includes/footer.php"); ?>