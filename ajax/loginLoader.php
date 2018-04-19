<?php
    session_start();
    include_once("../initialize.php");
    include_once("../database/UserDB.php");
    include_once("validation.php");
    include_once("encryption.php");


    //$_POST["loginMail"] = "admin@gmail.com";
    //$_POST["loginPass"] = "adminpassword";

    //check if user the httprequest was a POST request, otherwise return to homepage
    if($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        header('Location: ../home.php');
        exit();
        
    }
    
    //Check if the post variable contains both an email and a password and are valid 
     if(validPostVar("loginMail") && validateEmail($_POST["loginMail"]) &&  validPostVar("loginPass") && checkStringLength($_POST["loginPass"], 8, 30))
     {
        //Get all users from the database
        $users = UserDB::getAll();
        //initiate variable that will hold the logged in user (when it exists)
        $loggedInUser = false;
        //iterate over all users
        for($i = 0; $i < count($users); $i++)
        {
            //check if username and password match, if the case set logginUser and break loop 
            if($users[$i]->Password == encrypt($_POST["loginMail"], $_POST["loginPass"]) && $users[$i]->Email == $_POST["loginMail"])
            {
                $loggedInUser =$users[$i];
                break;
            }
        }
        //check if loggedInUser is false
        if($loggedInUser == false)
        {
            //if user is not found return false
            $data["loggedInUser"] = false;
            $encodeddata = json_encode($data);
            echo($encodeddata);
        }
        else
        {
            //If user is logged in set session variable
            //Set session variable
            $_SESSION["loggedInUser"] = $loggedInUser;
           //If user is found encode data and sent back
            $data["loggedInUser"] = $loggedInUser;
            $encodeddata = json_encode($data);
            echo($encodeddata);
        }
     }
    else
    {
        $data["loggedInUser"] = "invalid";
        $encodeddata = json_encode($data);
        echo($encodeddata);
    }
?>