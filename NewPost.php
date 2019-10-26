<!-- Connecting db -->
<?php

require_once("includes/db.php");
require_once("includes/functions.php");
require_once("includes/sessions.php");

 // get the name of the page
 $_SESSION['TrakingUrl'] = $_SERVER['PHP_SELF'] ; 

ConfirmLogin() ;


//add post 
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
        Redirect_to("NewPost.php") ;
    }
    elseif(empty($PostDescription)){
        $_SESSION['ErrorMessage'] = "All fields must be field out" ;
         Redirect_to("NewPost.php") ;
     } 
     
    elseif(strlen($post_title) < 3){ //control legth
        $_SESSION['ErrorMessage'] = "post title must be greater than 2 characters" ;
        Redirect_to("NewPost.php") ;
    } 
    elseif(strlen($post_title) > 299){ //control legth
        $_SESSION['ErrorMessage'] = "post title must be less than 300 characters" ;
        Redirect_to("NewPost.php") ;
    } 
    elseif(strlen($PostDescription) < 10){ //control legth
        $_SESSION['ErrorMessage'] = "post title must be greater than 10 characters" ;
        Redirect_to("NewPost.php") ;
    } 
     elseif(strlen($PostDescription) > 4999){ //control legth
         $_SESSION['ErrorMessage'] = "Category title must be less than 5000 characters" ;
         Redirect_to("NewPost.php") ;
     }
   else{ 
        // add post
        global $ConnectingDb ;

        $sql = "INSERT INTO post(dateTime,title,category,author,image,post)" ;
        $sql .= "VALUES(:dateTime,:PostTitle,:categoryName ,:adminName,:image,:PostText)" ;

        $stm = $ConnectingDb->prepare($sql) ;

        $stm->bindValue(':dateTime' , $DateTime) ;
        $stm->bindValue(':PostTitle' , $post_title) ;
        $stm->bindValue(':categoryName' , $category) ;
        $stm->bindValue(':adminName' , $Admin) ;
        $stm->bindValue(':image' , $image) ;
        $stm->bindValue(':PostText' , $PostDescription) ;
       
        $excute = $stm->execute();

         // upload image to folder upload
        move_uploaded_file($_FILES["image"]["tmp_name"] , $target) ;

         if($excute){
            $_SESSION['SuccessMessage'] = "Post added with successfully" ;
            Redirect_to("NewPost.php") ;
         }
         if($excute){
            $_SESSION['ErrorMessage'] = "You have a problem " ;
            Redirect_to("NewPost.php") ;
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
                    <h1><i class="fas fa-edit"></i> Manage Posts</h1>
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
                 <form action="NewPost.php" method="post" enctype="multipart/form-data">
                     <div class="card bg-secondary text-light mb-3">
                        <div class="card-header">
                            <h1>Add new Post</h1>
                        </div>
                        <div class="card-body bg-dark">
                            <div class="form-group">
                                <label for="title"><span class="fieldInfo">Post title :</span></label>
                                <input type="text" class="form-control" name="Post_title" placeholder="type title here" >
                            </div>
                            <div class="form-group">
                                <label for="Categorytitle"><span class="fieldInfo">Chose category :</span></label>
                                 <select name="category" id="category" class="form-control">
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
                                <div class="custom-file">
                                <input type="File" class="custom-file-input" name="image" value="" id="image" >
                                <label for="imageSelect" class="custom-file-label">Select Image</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="post"><span class="fieldInfo">Post :</span></label>
                                <textarea class="form-control" name="PostDescription" id="PostDescription" cols="80" rows="8"></textarea>            
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
              </div>
          </div>
          
      </div>
    <!-- End main -->



    <section>
    </section>

    <?php require_once("includes/footer.php"); ?>
