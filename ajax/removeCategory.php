<?php
    include_once("../initialize.php");
    include_once("../database/CategoryDB.php");
    include_once("validation.php");

    //check if user the httprequest was a POST request, otherwise return to homepage
    if($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        header('Location: ../home.php');
        exit();
        
    }
    //check if id was set and not null
    if(validPostVar("Id"))
    {
        $isSuccess = CategoryDB::deleteById($_POST["Id"]);
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