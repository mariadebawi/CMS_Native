<!-- Connecting db -->
<?php

require_once("includes/db.php");
require_once("includes/functions.php");
require_once("includes/sessions.php");

$PostUrl = $_GET['id'] ;

//add comment 
if (isset($_POST["submit"])) {
    
    $comment_user = $_POST["commenterName"];
    $comment_Email = $_POST["commenterEmail"];
    $comment_thoughts = $_POST["CommentThoughts"];

    $Admin = $_SESSION['AdminName'] ;

    //https://www.php.net/manual/fr/function.date.php

    date_default_timezone_set("Africa/Tunis"); //the time exact of tunis
    $CurrentTime = time();
    $DateTime = strftime("%B-%d-%Y %H:%M:%S", $CurrentTime);

    if (empty($comment_user)) {
        $_SESSION['ErrorMessage'] = "The commentr name is required";
        Redirect_to("FullPost.php?id={$PostUrl}");
    } 
    elseif (empty($comment_Email)) {
        $_SESSION['ErrorMessage'] = "The commentr Email is required";
        Redirect_to("FullPost.php?id={$PostUrl}");
    }
     elseif (empty($comment_thoughts)) {
        $_SESSION['ErrorMessage'] = "The comment Thought is required";
        Redirect_to("FullPost.php?id={$PostUrl}");
    } 
    else {
        // add post
       

        global $ConnectingDb;

        $sql = "INSERT INTO Comments(dateTime,CommeterName,commenterEmail,CommentThoughts,aprovedby,status,post_id)";
        $sql .= "VALUES(:DateTime,:CommeterName,:commenterEmail ,:CommentThoughts,'Pending','OFF',:PostId)";

        $stm = $ConnectingDb->prepare($sql);

        $stm->bindValue(':DateTime', $DateTime);
        $stm->bindValue(':CommeterName', $comment_user);
        $stm->bindValue(':commenterEmail', $comment_Email);
        $stm->bindValue(':CommentThoughts', $comment_thoughts);
        $stm->bindValue(':PostId', $PostUrl);

        $excute = $stm->execute();

        if ($excute) {
            $_SESSION['SuccessMessage'] = "Comment added with successfully";
            Redirect_to("FullPost.php?id={$PostUrl}");
        }
        if ($excute) {
            $_SESSION['ErrorMessage'] = "You have a problem ";
            Redirect_to("FullPost.php?id={$PostUrl}");
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
    <title>Blog</title>
</head>

<body>
    <div class="navdivider"></div>
    <!-- Navbar-->
    <nav class="navbar navbar-expand-lg  navbar-dark  bg-dark">
        <div class="container">
            <a class="navbar-brand" href="Blog.php">CMS</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavCmS">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavCmS">

                <ul class="navbar-nav mr-auto ">
                    <li class="nav-item ">
                        <a class="nav-link" href="Blog.php">
                            <i class="fas fa-user text-success"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="post.php">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Features</a>
                    </li>

                </ul>
                <ul class="navbar-nav ml-auto ">
                    <form action="Blog.php" class="form-inline d-none d-sm-block">
                        <div class="form-group">
                            <input class="form-control mr-2" type="text" placeholder="Searchb here" name="search">
                            <button class="btn btn-primary" name="searchButton">Go</button>
                        </div>
                    </form>
                </ul>
            </div>
        </div>
    </nav>
    <div class="navdivider"></div>
    <!-- end Navbar-->

    <!-- Header-->
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-8">
                <h1>The Complete CMS Blog</h1>
                <h1 class="lead"> the Complete Blog by using Php by me</h1>
                <?php echo ErrorMessage(); ?>
                <!-- fetch Posts-->
                <?php
                global $ConnectingDb; //search
                /* search with f=date time or catgory or titlePost*/
                if (isset($_GET['searchButton'])) {
                    $Search = ($_GET['search']);
                    $sql = "SELECT * from post WHERE 
                             dateTime Like :search OR
                             category Like :search OR
                             author Like :search OR
                             title Like :search OR
                             post Like :search  ";
                    $stmt = $ConnectingDb->prepare($sql);
                    $stmt->bindValue(':search', '%' . $Search . '%');
                    $stmt->execute();
                } else {
                    $PostUrl = $_GET['id'] ;
                    if (!isset($_GET['id'])) {
                        $_SESSION['ErrorMessage'] = "Bad Request !";
                        Redirect_to("Blog.php");
                    }
                   
                    $sql = "SELECT * from post WHERE  id ='$PostUrl'";
                    $stmt = $ConnectingDb->query($sql);
                }
                while ($DataRows = $stmt->fetch()) {
                    $PostId = $DataRows['id'];
                    $PostName = $DataRows['title'];
                    $Category = $DataRows['category'];
                    $author = $DataRows['author'];
                    $image = $DataRows['image'];
                    $PostText = $DataRows['post'];
                    $dateTime = $DataRows['dateTime'];

                    ?>
                    <div class="card my-3">
                        <img src="upload/<?php echo $image ?> " style="max-height: 450px" class="img-fluid card-img-top" />
                        <div class="card-body">
                            <h4 class="card-title">
                                <?php echo $PostName; ?>
                            </h4>
                            <small class="text-muted">Written By <?php echo $author ?> On <?php echo $dateTime ?></small>
                            <span class="badge badge-dark text-light float-right">Comments <?php echo CountCommentApprove($PostId) ; ?></span>
                            <hr>
                            <?php echo "<p class='card-text'>" . $PostText . "</p>"; ?>
                        </div>
                    </div>
                <?php  } ?>

                <!--  Fetching Comment -->
                <span class="fieldInfo">Comments</span> <br><br>

                <?php
                global $ConnectingDb ;
                //status must be ON
                    $sql = "SELECT * from comments WHERE post_id ='$PostUrl' AND status='ON'";
                    $stmt = $ConnectingDb->query($sql);
            
                    while ($DataRows = $stmt->fetch()) {
                        $CommeterName = $DataRows['CommeterName'];
                        $commenterEmail = $DataRows['commenterEmail'];
                        $CommentThoughts = $DataRows['CommentThoughts'];
                        $CommentDate = $DataRows['DateTime'];
                        $aprovedby = $DataRows['aprovedby']; 
                        $status = $DataRows['status'];
                        ?>

                        <div>
                            <div class="media CommentBlock">
                                <img src="images/comment.png" alt="avatar" class="img-fluid align-self-center d-block">
                                <div class="media-body ml-2 py-3">
                                    <h6 class="lead"> <?php echo $CommeterName ;?> </h6>
                                    <p class="small"><?php echo $CommentDate ;?></p>
                                    <p><?php echo $CommentThoughts ;?></p>
                                </div>
                            </div>
                        </div>
                        <hr>
                    <?php }?>
                
                <!-- end Fetching Comment   -->

                <!-- Comments Area -->
                <?php
                    echo ErrorMessage();
                    echo SuccessMessage();
                ?>
                <div class="comment">
                    <form action="FullPost.php?id=<?php echo $PostUrl ?>" method="post">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="fieldInfo">Share your thoughts about this post</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" value="" class="form-control" name="commenterName" placeholder="Commenter">
                                    </div>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="email" value="" class="form-control" name="commenterEmail" placeholder="Email">
                                    </div>
                                </div>
                                <div class="input-group mb-2">
                                    <textarea placeholder="Type Your thoughts here" name="CommentThoughts" id="" cols="80" rows="8" class="form-control"></textarea>
                                </div>
                                <div>
                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- End Comments Area -->

            </div>
            <div class="col-md-4 bg-dark ">

            </div>
        </div>
    </div>
    <!-- End Header-->

    <!-- Main Area -->

    <section style="min-height: 600px">

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