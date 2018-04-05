<?php
    include_once("../initialize.php");
    include_once("../database/BlogContextQueries.php");
    //if(TRUE)
    if(isset($_POST["Id"]) == TRUE && empty($_POST["Id"]) == FALSE)
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