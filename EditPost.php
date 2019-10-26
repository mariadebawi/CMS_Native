<!-- Connecting db -->
<?php

    require_once("includes/db.php");
    require_once("includes/functions.php");
    require_once("includes/sessions.php");

     // get the name of the page
     $_SESSION['TrakingUrl'] = $_SERVER['PHP_SELF'] ; 

    ConfirmLogin() ;


   $SearchQUeryParams = $_GET['id'] ;
//edit post
  if(isset($_POST["submit"])){
    $post_title = $_POST["Post_title"] ;
    $category = $_POST["category"] ;
    $image = $_FILES["image"]["name"] ;
    $target = "upload/".basename( $_FILES["image"]["name"])  ;
    $PostDescription = $_POST["PostDescription"] ;

    $Admin = $_SESSION['AdminName'] ;

    //https://www.php.net/manual/fr/function.date.php

    date_default_timezone_set("Africa/Tunis") ; //the time exact of tunis
    $CurrentTime = time() ;
    $DateTime = strftime("%B-%d-%Y %H:%M:%S" , $CurrentTime) ;

    if(empty($post_title)){
        $_SESSION['ErrorMessage'] = "All fields must be field out" ;
        Redirect_to("Post.php") ;
    }
    elseif(empty($PostDescription)){
        $_SESSION['ErrorMessage'] = "All fields must be field out" ;
        Redirect_to("Post.php") ;
    }

    elseif(strlen($post_title) < 3){ //control legth
        $_SESSION['ErrorMessage'] = "post title must be greater than 2 characters" ;
        Redirect_to("Post.php") ;
    }
    elseif(strlen($post_title) > 299){ //control legth
        $_SESSION['ErrorMessage'] = "post title must be less than 300 characters" ;
        Redirect_to("Post.php") ;
    }
    elseif(strlen($PostDescription) < 10){ //control legth
        $_SESSION['ErrorMessage'] = "post title must be greater than 10 characters" ;
        Redirect_to("Post.php") ;
    }
    elseif(strlen($PostDescription) > 4999){ //control legth
        $_SESSION['ErrorMessage'] = "Category title must be less than 5000 characters" ;
        Redirect_to("Post.php") ;
    }
    else{

        if(!empty($_FILES["image"]["name"])) {
            // update post
            $sql = "UPDATE post SET title='$post_title', category='$category',image='$image' , dateTime='$DateTime',author='$Admin',post='$PostDescription' WHERE id='$SearchQUeryParams';";
        }
        else{
            $sql = "UPDATE post SET title='$post_title', category='$category', dateTime='$DateTime',author='$Admin',post='$PostDescription' WHERE id='$SearchQUeryParams';";
        }

        $stm = $ConnectingDb->query($sql) ;
        $excute = $stm->execute();

        // upload image to folder upload
        move_uploaded_file($_FILES["image"]["tmp_name"] , $target) ;

        if($excute){
            $_SESSION['SuccessMessage'] = "Post updated with successfully" ;
            Redirect_to("Post.php") ;

        }
        if($excute){
            $_SESSION['ErrorMessage'] = "You have a problem , Try Again " ;
            Redirect_to("Post.php") ;
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
    <title>Edit Post</title>
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
                    <h1><i class="fas fa-edit"></i> Edit Post</h1>
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
                  echo ErrorMessage() ;
                  echo SuccessMessage() ;
                  ?>

                 <form action="EditPost.php?id=<?php echo $SearchQUeryParams ;?>" method="post" enctype="multipart/form-data">
                     <?php
                       global $ConnectingDb ;

                        $sql = "SELECT * from post WHERE  id='$SearchQUeryParams'" ;
                        $stmt = $ConnectingDb->query($sql);

                         while($DataRows = $stmt->fetch()){
                         $PostId = $DataRows['id'] ;
                         $PostName = $DataRows['title'] ;
                         $Category = $DataRows['category'] ;
                         $author = $DataRows['author'] ;
                         $image = $DataRows['image'] ;
                         $PostText = $DataRows['post'] ;
                         $dateTime = $DataRows['dateTime'] ;
                     ?>

                     <div class="card bg-secondary text-light mb-3">
                        <div class="card-header">
                            <h1>Edit  Post</h1>
                        </div>
                        <div class="card-body bg-dark">
                            <div class="form-group">
                                <label for="title"><span class="fieldInfo">Post title : <?php echo $PostName  ?></span></label>
                                <input type="text" class="form-control" name="Post_title" placeholder="type title here" value="<?php echo $PostName  ?>">
                            </div>
                            <div class="form-group">
                                <label for="Categorytitle"><span class="fieldInfo">Chose category : <?php echo $Category ; ?></span></label>
                                 <select name="category" id="category" class="form-control">
                                     <option value="<?php echo $Category ?>"><?php echo $Category ?></option>
                                     <!-- fetch categories-->
                                    <?php  
                                     global $ConnectingDb ;
                                     $sql = "SELECT title from category"  ;
                                     $stmt = $ConnectingDb->query($sql) ;
                                     while($DataRows = $stmt->fetch()){
                                         $categoryName = $DataRows['title'] ;
                                    ?>
                                    <option value="<?php echo $categoryName ?>"><?php echo $categoryName ?> </option>
                                    <?php  } ?>
                                 </select>
                                    <!-- End fetch categories-->
                            </div>
                            <div class="form-group mb-1">
                                <label for="image"><span class="fieldInfo">Select Image :</span></label>
                                <div class="custom-file ">
                                <input type="File" class="custom-file-input" name="image" value="" id="image" >
                                <label for="imageSelect" class="custom-file-label ">Select Image</label>
                                <img class="mt-3" src="upload/<?php echo $image ?>" height="80px" width="80px" style="margin-bottom: 90px">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="post"><span class="fieldInfo">Post :</span></label>
                                <textarea class="form-control" name="PostDescription" id="PostDescription" cols="80" rows="8">/<?php echo $PostText ?></textarea>
                            </div>

                            <div class="row">
                              <div class="col-md-6 mb-2">
                                  <a href="dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i> Back to dashboard</a>
                              </div>
                              <div class="col-md-6 mb-2">
                                  <button class="btn btn-success btn-block" name="submit" type="submit"><i class="fas fa-check"></i> Edit</button>
                              </div>
                            </div>
                        </div>
                     </div>
                     <?php } ?>
                 </form>
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