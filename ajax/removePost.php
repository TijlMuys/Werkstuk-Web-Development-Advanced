<?php
    include_once("../data/User.php");
    //start session
    session_start();
    include_once("../initialize.php");
    include_once("../database/BlogpostDB.php");
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
        //check if user is admin, if not redirect to loginpage
        if($loggedInUser->IsAdmin != 1)
        {
            header('Location: ../loginpage.php');
            exit();
        }
        
    }
    else
    {
        header('Location: ../loginpage.php');
        exit();
    }
    
    //check if id was set and not null
    if(validPostVar("Id"))
    {
        $isSuccess = BlogpostDB::deleteById($_POST["Id"]);
        if($isSuccess)
        {
            $data["response"] = "success";
            $encodeddata = json_encode($data);
            echo($encodeddata);
        }
        else
        {
            $data["response"] = "error";
            $encodeddata = json_encode($data);
            echo($encodeddata);
        }
        
    }
    else
    {
        $data["response"] = "error";
        $encodeddata = json_encode($data);
        echo($encodeddata);
    }
   
?>