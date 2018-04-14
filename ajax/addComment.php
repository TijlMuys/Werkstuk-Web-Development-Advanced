<?php
    include_once("../data/User.php");
    //start session
    session_start(); 
    include_once("../initialize.php");
    include_once("../database/CommentDB.php");
    
    //$_POST["commentContent"] = "Test";
    //$_POST["blogpostId"] = 14;
    
       
    //check if user is logged in, if the case convert data to the loggedInUser variable, else redirect to loginpage
    if(isset($_SESSION["loggedInUser"]))
    {
        $loggedInUser = $_SESSION["loggedInUser"];
        
    }
    else
    {
        header('Location: ../loginpage.php');
        exit();
    }
  
    //Initialize variables
    $isInserted = false;
    //Check if the parameters exist and are valid (content and blogpostid)
    if(isset($_POST["commentContent"]) && !empty($_POST["commentContent"]) && isset($_POST["blogpostId"]) && !empty($_POST["blogpostId"]))
    {
         //create comment
         $newComment = new Comment(-1, $_POST["blogpostId"], $loggedInUser->Id, -1, $_POST["commentContent"]);
         //add Comment to database
         $isInserted = CommentDB::insert($newComment);
        file_put_contents('log.txt', "".$_POST["commentContent"]." - ". $_POST["blogpostId"]."");
         //reload page
         if(true)
         {
            $data["response"] = "success";
            $encodeddata = json_encode($data);
            echo($encodeddata);
         }
         else
         {
            //sent data back if something goes wrong
            $data["response"] = "error";
            $encodeddata = json_encode($data);
            echo($encodeddata);
         }
        
    }
    else
    {
        //sent data back if something goes wrong
        $data["response"] = "error";
        $encodeddata = json_encode($data);
        echo($encodeddata);
    }
    
    
?>