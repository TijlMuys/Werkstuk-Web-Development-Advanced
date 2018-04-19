<?php
    include_once("../initialize.php");
    include_once("../database/CategoryDB.php");

    //check if user the httprequest was a POST request, otherwise return to homepage
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        //Get all cetegories from DB
        $categories = CategoryDB::getAll();
        //encode data
        $encodeddata = json_encode($categories);
        //return data
        echo($encodeddata);
    }
    else
    {
        header('Location: ../home.php');
        exit();
    }
?>