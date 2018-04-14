<?php
    session_start();
    include_once("../initialize.php");
    include_once("../database/UserDB.php");


    //$_POST["loginMail"] = "admin@gmail.com";
    //$_POST["loginPass"] = "adminpassword";
    //Check if the post variable contains both an email and a password and are valid using the filter_var function of php and out own checkLength function
     if(isset($_POST["loginMail"]) == TRUE && filter_var($_POST["loginMail"], FILTER_VALIDATE_EMAIL) == TRUE &&  isset($_POST["loginPass"]) == TRUE && checkLength($_POST["loginPass"], 8, 30) == TRUE)
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

    //encryption and decryption functions 
    function encrypt($plain, $key) 
    { 
         return openssl_encrypt($crypted,"AES-128-ECB", $key); 
    } 

    function decrypt($crypted, $key) 
    { 
        return openssl_decrypt($crypted,"AES-128-ECB", $key);
    }

    //Function to check password length
    function checkLength($string, $lowerbound, $upperbound) 
    {
        if(strlen($string) >= $lowerbound && strlen($string) <= $upperbound) 
        {
          return true;
        } 
        else 
        {
          return false;
        }
    }
?>