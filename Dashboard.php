
<!-- Connecting db -->
<?php

require_once("includes/db.php");
require_once("includes/functions.php");
require_once("includes/sessions.php");

// get the name of the page
$_SESSION['TrakingUrl'] = $_SERVER['PHP_SELF'];


ConfirmLogin();

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
                        <a class="nav-link" href="Admins.php">Manage Admins</a>
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
                    <h1><i class="fas fa-cogs"></i> Dashboard</h1>
                </div>
            </div>
        </div>
    </header>
    <!-- End Header-->

    <!-- Main -->
    <div class="container">
        <div class="row">
            <div class=" col-md-2 col-lg-2 py-2 mb-4 d-none d-md-block">
                <div class="card text-center bg-dark text-white mb-3">
                    <div class="card-body">
                        <h1 class="lead">Posts</h1>
                        <h4 class="display-5">
                            <i class="fab fa-readme"></i>
                               <?php echo CountPosts() ?>
                        </h4>
                    </div>
                </div>
                <div class="card text-center bg-dark text-white mb-3">
                    <div class="card-body">
                        <h1 class="lead">Categories</h1>
                        <h4 class="display-5">
                            <i class="fas fa-folder"></i>
                            <?php echo CountCategories() ?>

                        </h4>
                    </div>
                </div>
                <div class="card text-center bg-dark text-white mb-3">
                    <div class="card-body">
                        <h1 class="lead">Admins</h1>
                        <h4 class="display-5">
                            <i class="fas fa-users"></i>
                            <?php echo CountAdmins() ?>

                        </h4>
                    </div>
                </div>
                <div class="card text-center bg-dark text-white mb-3">
                    <div class="card-body">
                        <h1 class="lead">Comments</h1>
                        <h4 class="display-5">
                            <i class="fas fa-comments"></i>
                            <?php echo CountComment() ?>

                        </h4>
                    </div>
                </div>
            </div>
            <div class=" col-md-10 col-lg-10 py-2 ">
             <h1 class="fieldInfo my-3 font-weight-bold">Top Posts :</h1>
            <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr >
                                <th>#</th>
                                <th>Title</th>
                                <th>Date&Time</th>
                                <th>Author</th>
                                <th>Comments</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <!-- fetch Posts-->
                        <?php
                        global $ConnectingDb;
                        $sql = "SELECT * from post ORDER BY id DESC LIMIT 0,5";
                        $stmt = $ConnectingDb->query($sql);
                        $Sr = 0;
                        while ($DataRows = $stmt->fetch()) {
                            $PostId = $DataRows['id'];
                            $PostName = $DataRows['title'];
                            $Category = $DataRows['category'];
                            $author = $DataRows['author'];
                            $image = $DataRows['image'];
                            $PostText = $DataRows['post'];
                            $dateTime = $DataRows['dateTime'];
                            $Sr++;
                            ?>
                            <tbody>
                                <tr>
                                    <td><?php echo $Sr ?></td>
                                    <td>
                                        <?php
                                            //limit the content
                                            if (strlen($PostName) > 20) {
                                                $PostName = substr($PostName, 0, 15) . "...";
                                            }
                                            echo $PostName
                                            ?>
                                    </td>
                                    
                                    <td>
                                        <?php
                                            //limit the content
                                            // if (strlen($dateTime) > 11) {
                                            //     $dateTime = substr($dateTime, 0, 11) . "...";
                                            // }
                                            echo $dateTime
                                            ?>
                                    </td>
                                    <td>
                                        <?php
                                            //limit the content
                                            if (strlen($author) > 20) {
                                                $author = substr($author, 0, 7) . "...";
                                            }
                                            echo $author
                                            ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-success"><?php echo CountCommentApprove($PostId) ?></span>
                                        <span class="badge badge-danger"><?php echo CountCommentDiasapprove($PostId) ?></span>

                                    </td>
                                  
                                    <td>
                                        <a href="FullPost.php?id=<?php echo $PostId  ?> "><span class="btn btn-info">Preview</span></a>
                                    </td>
                                </tr>

                            <?php  } ?>
                            <!-- End fetch Posts-->
                            </tbody>

                    </table>
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