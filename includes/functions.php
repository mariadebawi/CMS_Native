<?php 




 function Redirect_to($NewLocal){
   header("location:".$NewLocal);
   exit ;
 }

 

function existUser($username){
  global $ConnectingDb ;
  $sql = "SELECT * from admins WHERE  username =:username";
  $stmt = $ConnectingDb->prepare($sql);
  $stmt->bindValue(':username' , $username) ;
  $stmt->execute() ;
  $Result = $stmt->rowCount() ;
    if($Result == 1) {
       return true ;
      } 
    else {
       return false ;
    }

}


function existCateg($category){
  global $ConnectingDb ;
  $sql = "SELECT * from category WHERE  title =:titleCatg";
  $stmt = $ConnectingDb->prepare($sql);
  $stmt->bindValue(':titleCatg' , $category) ;
  $stmt->execute() ;
  $Result = $stmt->rowCount() ;
    if($Result == 1) {
       return true ;
      } 
    else {
       return false ;
    }

}

function ConfirmLogin(){
  if(isset($_SESSION['AdminName'])){
     return true ;
  }
  else {
    $_SESSION['ErrorMessage'] = "Login is required";
    Redirect_to("login.php");

  }
  
}


function Login($username , $password){
  global $ConnectingDb ;
 
  $sql = "SELECT * from admins WHERE  username =:username AND password =:password LIMIT 1";
  $stmt = $ConnectingDb->prepare($sql);
  $stmt->bindValue(':username' , $username) ;
  $stmt->bindValue(':password' , $password) ;

  $stmt->execute() ;
  $Result = $stmt->rowCount() ;
    if($Result == 1) {
      $FoutAccount = $stmt->fetch() ;
       return $FoutAccount ;
      } 

    else {
       return null ;
    }
  } 
 

  function CountCategories(){
    global $ConnectingDb ;
 
    $sql = "SELECT COUNT(*) from category ";
    $stmt = $ConnectingDb->query($sql) ;
    $Result = $stmt->fetch() ;
    $Total = array_shift($Result) ;
    return $Total ;
  }

  
  function CountAdmins(){
    global $ConnectingDb ;
 
    $sql = "SELECT COUNT(*) from admins ";
    $stmt = $ConnectingDb->query($sql) ;
    $Result = $stmt->fetch() ;
    $Total = array_shift($Result) ;
    return $Total ;
  }


  function CountComment(){
    global $ConnectingDb ;
 
    $sql = "SELECT COUNT(*) from comments ";
    $stmt = $ConnectingDb->query($sql) ;
    $Result = $stmt->fetch() ;
    $Total = array_shift($Result) ;
    return $Total ;
  }

  function CountCommentApprove($PostID){
    global $ConnectingDb ;
 
    $sql = "SELECT COUNT(*) from comments where Post_id = $PostID AND status = 'ON' ";
    $stmt = $ConnectingDb->query($sql) ;
    $Result = $stmt->fetch() ;
    $Total = array_shift($Result) ;
    return $Total ;
  }
  function CountCommentDiasapprove($PostID){
    global $ConnectingDb ;
 
    $sql = "SELECT COUNT(*) from comments where Post_id = $PostID AND status = 'OFF' ";
    $stmt = $ConnectingDb->query($sql) ;
    $Result = $stmt->fetch() ;
    $Total = array_shift($Result) ;
      return $Total ;
  }


  
  function CountPosts(){
    global $ConnectingDb ;
 
    $sql = "SELECT COUNT(*) from post ";
    $stmt = $ConnectingDb->query($sql) ;
    $Result = $stmt->fetch() ;
    $Total = array_shift($Result) ;
    return $Total ;
  }