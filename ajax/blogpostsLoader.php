<?php
    include_once("../initialize.php");
    include_once("../database/BlogContextQueries.php");
    if(isset($_POST["filterinput"]) == TRUE && empty($_POST["filterinput"]) == FALSE)
    {
        $blogposts = BlogContextQueries::getAll();
        //var_dump($blogposts);
        $data["blogposts"] = $blogposts;
        $encodeddata = json_encode($data);
        //var_dump($encodeddata);
        echo($encodeddata);
        
    }
    else
    {
        $blogposts = BlogContextQueries::getAll();
        //var_dump($blogposts);
        $data["blogposts"] = $blogposts;
        $encodeddata = json_encode($data);
        //var_dump($encodeddata);
        echo($encodeddata);
    }
    
?>