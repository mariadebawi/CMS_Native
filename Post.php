
<!-- Connecting db -->
<?php

require_once("includes/db.php");
require_once("includes/functions.php");
require_once("includes/sessions.php");

// get the name of the page
$_SESSION['TrakingUrl'] = $_SERVER['PHP_SELF'];


ConfirmLogin();


if (isset($_POST['delete'])) {

    $id = $_POST['deleteID'];


    $sql = "DELETE  from post WHERE id = '$id'";
    $excute = $ConnectingDb->query($sql);


    if ($excute) {

        $_SESSION['SuccessMessage'] = "post deleted with successfully";
        Redirect_to("post.php");
    }
    if ($excute) {
        $_SESSION['ErrorMessage'] = "You have a problem , Try Again ";
        Redirect_to("post.php");
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
    <title>New Post</title>
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
                <div class="col-md-12 mb-2">
                    <h1><i class="fas fa-blog"></i> Blog Posts</h1>

                </div>
                <div class="col-md-3 col-lg-3 col-sm-6 col-xs-6 mb-2">
                    <a href="NewPost.php" class="btn btn-primary btn-block"><i class="fas fa-edit text-white"></i> Add New Post</a>
                </div>
                <div class="col-md-3 col-lg-3 col-sm-6 col-xs-6 mb-2">
                    <a href="categories.php" class="btn btn-info btn-block"><i class="fas fa-folder-plus text-white"></i> Add New Category</a>
                </div>
                <div class="col-md-3 col-lg-3 col-sm-6 col-xs-6 mb-2">
                    <a href="Admins.php" class="btn btn-warning btn-block"><i class="fas fa-user-plus text-white"></i> Add New Admin</a>
                </div>
                <div class="col-md-3 col-lg-3 col-sm-6 col-xs-6 mb-2">
                    <a href=">Comments.php" class="btn btn-success btn-block"><i class="fas fa-check text-white"></i>Approve Comments</a>
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
            <div class="row">
                <div class="col-lg-12 ">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Date&Time</th>
                                <th>Author</th>
                                <th>Banner</th>
                                <th>Comments</th>
                                <th>Action</th>
                                <th>Live Preview</th>
                            </tr>
                        </thead>
                        <!-- fetch Posts-->
                        <?php
                        global $ConnectingDb;
                        $sql = "SELECT * from post ORDER BY id DESC";
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
                                            if (strlen($Category) > 8) {
                                                $Category = substr($Category, 0, 8) . "...";
                                            }
                                            echo $Category
                                            ?>
                                    </td>
                                    <td>
                                        <?php
                                            //limit the content
                                            if (strlen($dateTime) > 11) {
                                                $dateTime = substr($dateTime, 0, 11) . "...";
                                            }
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
                                    <td><img src="upload/<?php echo $image ?>" height="90px" width="175px"></td>
                                    <td>
                                        <?php if (CountCommentApprove($PostId)) { ?>
                                            <a href="comment.php" target="_blank">
                                                <span class="badge badge-success"><?php echo CountCommentApprove($PostId) ?></span>
                                            </a>
                                        <?php } else { ?>
                                            <a href="comment.php" target="_blank">
                                                <span class="badge badge-danger"><?php echo CountCommentDiasapprove($PostId) ?></span>
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger" data-toggle="modal" data-target="#DeleteModal" id="<?php echo $PostId ?>">Delete</button>
                                        <a href="EditPost.php?id=<?php echo $PostId ?>" target="_blank"><span class="btn btn-warning">Edit</span></a>
                                    </td>
                                    <td>
                                        <a href="FullPost.php?id=<?php echo $PostId  ?> "><span class="btn btn-primary">Live Preview</span></a>
                                    </td>
                                </tr>


                                <!-- Modal -->
                                <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: #27aae1;">
                                                <h5 class="modal-title" id="exampleModalLabel">Delete Post</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="Post.php" method="post">
                                                <div class="modal-body">
                                                    <input name="deleteID" class="d-none" value="<?php echo $PostId ?>">
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

    <!-- End Main Area -->





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