<?php
    session_start();
    include_once("../initialize.php");
    include_once("../database/UserDB.php");
    
    //$_POST["registerMail"] = "marc.coucke@gmail.com";
    //$_POST["registerPass"] = "marcpassword";
    //$_POST["registerUsername"] = "Marc Coucke";
    //initalize parameters
    $isValid = false;
    $isAvailable = true;
    $isInserted = false;
    //Check if the parameters are valid (email, username and password)
    $emailIsValid = (isset($_POST["registerMail"]) == TRUE && filter_var($_POST["registerMail"], FILTER_VALIDATE_EMAIL) == TRUE);
    $passIsValid = (isset($_POST["registerPass"]) == TRUE && checkLength($_POST["registerPass"], 8, 30) == TRUE);
    $nameIsValid = (isset($_POST["registerUsername"]) == TRUE && checkLength($_POST["registerUsername"], 1, 30) == TRUE);
    $isValid = ($emailIsValid && $passIsValid && $nameIsValid);
        
    //proceed
     if($isValid == TRUE)
     {
        //Get all users from the database
        $users = UserDB::getAll();
        //iterate over all users
        for($i = 0; $i < count($users); $i++)
        {
            //check if emails match, if the case set isAvailable on false
            if($users[$i]->Email == $_POST["registerMail"])
            {
                $isAvailable = false;
                break;
            }
        }
        //check if email is available and parameters were valid
        if($isAvailable == false || $isValid == false)
        {
            //if user is not found return false
            $data["loggedInUser"] = false;
            $encodeddata = json_encode($data);
            echo($encodeddata);
        }
        else
        {
            //If everything is in order create new user
            $newUser = new User(-1, $_POST["registerUsername"], encrypt($_POST["registerMail"], $_POST["registerPass"]), $_POST["registerMail"]);
            //insert user
            $isInserted = UserDB::insert($newUser);
            //if insert successful log user in
            if($isInserted)
            {
                //get last inser id
                $insertId = UserDB::lastInsertedId();
                //get new user from database
                $newUser = UserDB::getById($insertId);
                
                //Set session variable
                $_SESSION["loggedInUser"] = $newUser;
               //If user is found encode data and sent back
                $data["loggedInUser"] = $newUser;
                $encodeddata = json_encode($data);
                echo($encodeddata);
            }
            else //else return false
            {
                 //if user is not found return false
                $data["loggedInUser"] = false;
                $encodeddata = json_encode($data);
                echo($encodeddata);
            }  
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