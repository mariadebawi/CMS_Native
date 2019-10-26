<?php


require_once("includes/db.php");
require_once("includes/functions.php");
require_once("includes/sessions.php");

global $ConnectingDb ;
$Admin = $_SESSION['AdminName'] ;


if (isset($_GET['id'])) {

    $idComment = $_GET['id'];
     //echo $idComment ;

     $sql = "UPDATE comments SET status='ON',aprovedby = '$Admin' WHERE id = $idComment;";
      $stm = $ConnectingDb->query($sql);
         $excute = $stm->execute();

          if ($excute) {
            $_SESSION['SuccessMessage'] = "Comment approved with successfully";
             Redirect_to("comment.php");
              }
                else{
                 $_SESSION['ErrorMessage'] = "You have a problem , Try Again ";
                 Redirect_to("comment.php") ;
               }
              
            }


            if (isset($_GET['idd'])) {

                $iddComment = $_GET['idd'];
                 //echo $idComment ;
            
                 $sql = "UPDATE comments SET status='OFF',aprovedby = '$Admin' WHERE id = $iddComment;";
                  $stm = $ConnectingDb->query($sql);
                     $excute = $stm->execute();
            
                      if ($excute) {
                        $_SESSION['SuccessMessage'] = "Comment disapproved with successfully";
                         Redirect_to("comment.php");
                          }
                            else{
                             $_SESSION['ErrorMessage'] = "You have a problem , Try Again ";
                             Redirect_to("comment.php") ;
                           }
                          
                        }
            ?>
