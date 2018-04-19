<?php
    //start session
    session_start(); 
    include_once("../initialize.php");
    include_once("../database/CategoryDB.php");
    include_once("validation.php");
    
    //check if user the httprequest was a POST request, otherwise return to homepage
    if($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        header('Location: ../home.php');
        exit();
        
    }
       
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
    if(validPostVar("CategoryName"))
    {
         //create comment
         $newCategory = new Category($_POST["CategoryName"]);
         //add Comment to database
         $isInserted = CommentDB::insert($newCategory);
         //check if insert was successful
         if($isInserted)
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