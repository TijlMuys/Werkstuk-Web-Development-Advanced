<?php
    session_start();
    include_once("../initialize.php");
    include_once("../database/UserDB.php");
    include_once("validation.php");
    include_once("encryption.php");
    
    //$_POST["registerMail"] = "marc.coucke@gmail.com";
    //$_POST["registerPass"] = "marcpassword";
    //$_POST["registerUsername"] = "Marc Coucke";

    //check if user the httprequest was a POST request, otherwise return to homepage
    if($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        header('Location: ../home.php');
        exit();
        
    }


    //initalize parameters
    $isValid = false;
    $isAvailable = true;
    $isInserted = false;
    //Check if the parameters are valid (email, username and password)
    $emailIsValid = (validPostVar("registerMail") && validateEmail($_POST["registerMail"]));
    $passIsValid = (validPostVar("registerPass") && checkStringLength($_POST["registerPass"], 8, 30));
    $nameIsValid = (validPostVar("registerUsername") && checkStringLength($_POST["registerUsername"], 1, 30));
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