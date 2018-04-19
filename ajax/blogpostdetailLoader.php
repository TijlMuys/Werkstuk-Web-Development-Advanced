<?php
    include_once("../initialize.php");
    include_once("../database/BlogContextQueries.php");
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
        $blogpostdetail = BlogContextQueries::getById($_POST["Id"]);
        //$blogpostdetail = BlogContextQueries::getById(1);
        //var_dump($blogposts);
        $data["blogpostdetail"] = $blogpostdetail;
        $encodeddata = json_encode($data);
        //var_dump($encodeddata);
        echo($encodeddata);
        
    }
?>