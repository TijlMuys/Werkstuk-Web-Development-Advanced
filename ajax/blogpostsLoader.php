<?php
    include_once("../initialize.php");
    include_once("../database/BlogContextQueries.php");
    
    $blogposts = BlogContextQueries::getAll();
    //var_dump($blogposts);
    $data["blogposts"] = $blogposts;
    $encodeddata = json_encode($data);
    //var_dump($encodeddata);
    echo($encodeddata);
   
    
?>