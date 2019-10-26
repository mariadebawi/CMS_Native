
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
                }
                //Limit Post , Pagination 
                elseif ( isset($_GET['page']) ) {
                    $Page = $_GET['page'];
                    if ($Page == 0 || $Page < 1) {
                        $ShowPostFrom = 0;
                    } else {
                        $ShowPostFrom = ($Page * 5) - 5;
                    }
                    $sql = "SELECT * from post ORDER BY id DESC LIMIT $ShowPostFrom,4";
                    $stmt = $ConnectingDb->query($sql);
                } else {
                    $sql = "SELECT * from post ORDER BY id DESC LIMIT 0,3 ";
                    $stmt = $ConnectingDb->query($sql);
                }
                $Result = $stmt->rowCount();
                if ($Result >= 1) {
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
                                <small class="text-muted">Category : <span class="text-dark text-uppercase"><?php echo $Category ?></span> , Written By <span class="text-info text-uppercase "> <?php echo $author ?> </span> , On : <span class="text-muted"> <?php echo $dateTime ?></span></small>

                                <span class="badge badge-dark text-light float-right">Comments <?php echo CountCommentApprove($PostId); ?></span>
                                <hr>
                                <?php
                                        //limit the content
                                        if (strlen($PostText) > 150) {
                                            $PostText = substr($PostText, 0, 120) . "...";
                                        }
                                        echo "<p class='card-text'>" . $PostText . "</p>";
                                        ?>
                                <a href="FullPost.php?id=<?php echo $PostId  ?> ">
                                    <span class="btn btn-info float-right"> Read more >> </span>
                                </a>
                            </div>
                        </div>
                    <?php }
                    } else { ?>
                    <div class="card my-3">
                        <div class="card-body">
                            <h4 class="card-title text-warning"> No Posts with this search </h4>
                        </div>
                    </div>
                <?php } ?>

                <!-- Pagination -->
                <nav>
                    <ul class="pagination pagination-lg ">
                        <?php
                         if (isset($_GET['page'])) {
                            $Page = $_GET['page'];
                            if($Page>1){
                                ?>
                                <li class="page-item ">
                                    <a class="page-link" href="blog.php?page=<?php echo $Page-1 ?>">&laquo;</a>
                                </li>
                        <?php }
                        $TotalPost = CountPosts();
                        //echo $TotalPost.'<br>';
                        $NbrPagination = $TotalPost / 5;
                        $NbrPagination = ceil($NbrPagination);
                        if ($NbrPagination > 1) {
                           
                                for ($i = 1; $i <= $NbrPagination; $i++) { ?>

                                    <?php if ($i == $Page) {  ?>

                                        <li class="page-item active">
                                            <a class="page-link" href="blog.php?page=<?php echo $i ?>"><?php echo $i ?></a>
                                        </li>
                                    <?php } else { ?>
                                        <li class="page-item ">
                                            <a class="page-link" href="blog.php?page=<?php echo $i ?>"><?php echo $i ?></a>
                                        </li>
                                <?php
                                            }
                                        }
                                        if(!empty($Page)){
                                         if($Page+1 <= $NbrPagination){
                                        ?>
                                        <li class="page-item ">
                                            <a class="page-link" href="blog.php?page=<?php echo $Page+1 ?>">&raquo;</a>
                                        </li>
                        <?php }}}}
                      ?>
                    </ul>
                </nav>
                <!-- End Pagination -->

            </div>
            <div class="col-md-4">
               <div class="card mb-4">
                   <div class="card-body">
                       <img src="images/startblog.PNG" class="d-block img-fluid mb-3">
                       <div class="text-center">Lorem ipsum dolor sit amet consectetur adipisicing elit. Officiis earum iusto quod in consectetur inventore? Veniam in maiores voluptatibus, at eos inventore laudantium amet mollitia atque nobis eum reiciendis libero?</div>
                   </div>
               </div>
               <div class="card mb-4">
                   <div class="card-header bg-dark text-light">
                       <h2 class="lead">Sign Up !</h2>                     
                   </div>
                   <div class="card-body">
                     <button class="btn btn-success btn-block text-center text-white mb-4">Join the Forum</button>
                     <button class="btn btn-danger btn-block text-center text-white mb-4">Login</button>
                   </div>
               </div>
            </div>
        </div>
    </div>
    <!-- End Header-->

    <!-- Main Area -->

    <section style="min-height: 600px">

    </section>
    <!--  End Main-->

    <?php require_once("includes/footer.php"); ?>
