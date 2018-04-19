<?php
    include_once("../initialize.php");
    include_once("../database/BlogContextQueries.php");
    
    //check if user the httprequest was a POST request, otherwise return to homepage
    if($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        header('Location: ../home.php');
        exit();
        
    }

    $blogposts = BlogContextQueries::getAll();
    //var_dump($blogposts);
    $data["blogposts"] = $blogposts;
    $encodeddata = json_encode($data);
    //var_dump($encodeddata);
    echo($encodeddata);
   
    
?>