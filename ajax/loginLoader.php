<?php
    session_start();
    include_once("../initialize.php");
    include_once("../database/UserDB.php");
    include_once("validation.php");
    include_once("encryption.php");

    //check if user the httprequest was a POST request, otherwise return to homepage
    if($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        header('Location: ../home.php');
        exit();
        
    }

    //check if remember me is chosen, if the case set cookies
    if(validPostVar("stayLoggedIn"))
    {
        //set cookies for a duration of 60 days
        setcookie('usermail', $_POST['loginMail'], time()+60*60*24*60, '/');
        setcookie('password', encrypt($_POST["loginPass"], $_POST["loginMail"]), time()+60*60*24*60, '/');
    }
    else
    {
        //if not chosen unset cookie
        setcookie("usermail", "", time()-3600, '/');
        setcookie("password", "", time()-3600, '/');
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
            if($users[$i]->Password == encrypt($_POST["loginPass"], $_POST["loginMail"]) && $users[$i]->Email == $_POST["loginMail"])
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