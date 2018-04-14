<?php
    include_once("../data/User.php");
    //start session
    session_start(); 
    include_once("../initialize.php");
    include_once("../database/BlogpostDB.php");
    include_once("../database/CategoryDB.php");
    
    //$_POST["Title"] = "TestTitle";
    //$_POST["Category"] = "Programming";
    //$_POST["Content"] = "This is a testpost";
    //$_POST["ImageUrl"] = "images/blogpostImages/code1.jpg";
    
    //initialize isValid boolean
    $isValid = true;
    //initialise imageurl on default value
    $imageUrl = "images/genericpost.png";
       
    //check if user is logged in, if the case convert data to the loggedInUser variable, else redirect to loginpage
    if(isset($_SESSION["loggedInUser"]))
    {
        $loggedInUser = $_SESSION["loggedInUser"];
        
    }
    else
    {
        header('Location: ../loginpage.php');
        exit();
    }
    
    file_put_contents('log.txt', "url: ".$_FILES."");

    if(isset($_FILES["ImageUrl"]) && !empty($_FILES["ImageUrl"]))
    {
        file_put_contents('log.txt', "data received");
        //if a file is uploaded we need to save it on the server and generate a new imageUrl
        if ($_FILES["ImageUrl"]["type"] == "image/jpeg" || $_FILES["ImageUrl"]["type"] == "image/png")
        {
            //get extention of file
            $path = $_FILES['ImageUrl']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            
            //get last blogpostid
            $previousId = BlogPostDB::lastInsertedId();
            //increment previous id with 1
            $currentId = $previousId + 1;
            
            //set filename with the id of the blogpost
            $_FILES["ImageUrl"]["name"] = "".$currentId.".".$ext."";
            //get new url
            $imageUrl = $_FILES["ImageUrl"]["name"];
            file_put_contents('log.txt', "oldpath: ".$path." - newpath".$_FILES["ImageUrl"]["name"]."");
            
            //move file to right folder
            move_uploaded_file($_FILES["ImageUrl"]["tmp_name"], "../images/blogpostImages/" . $imageUrl);
            
            //set imageurl for database
            $imageUrl = "images/blogpostImages/".$imageUrl;
        }
        //if format is not supported the form is invalid
        else
        {
             $isValid = false;
        }
    }

    //initialise category id on -1
    $categoryId = -1;
    //check is catagory parameter is set and not null
    if( isset($_POST["Category"]) && !empty($_POST["Category"]))
    {
        //Get all valid categories
        $allCategories = CategoryDB::getAll();
        //iterate over all categories
        foreach ($allCategories as $category)
        {
            //check if names match
            if($category->CategoryName == $_POST["Category"])
            {
                //if matched, set new Id
                $categoryId = $category->Id;
            }
        }
    }
    
    

    //Initialize variables
    $isInserted = false;
    //Check if the parameters exist and are valid (Title, Category and Content)
    if(isset($_POST["Title"]) && !empty($_POST["Title"]) && isset($_POST["Content"]) && !empty($_POST["Content"]) && $categoryId != -1 && isValid == true)
    {
        //create blogpost
        $newPost = new BlogPost(-1, $loggedInUser->Id, $categoryId, $_POST["Title"], -1, $_POST["Content"], $imageUrl);
        //add Comment to database
        $isInserted = BlogPostDB::insert($newPost);
        //get last inserted Id
        $lastId = BlogPostDB::lastInsertedId();
        //set Id of new post
        $newPost->Id = $lastId;
        
        if(true)
        {
            $data["response"] = $newPost;
            $encodeddata = json_encode($data);
            echo($encodeddata);
        }
        else
        {
            //sent data back if something goes wrong
            $data["response"] = "insertError";
            $encodeddata = json_encode($data);
            echo($encodeddata);
        }
        
    }
    else
    {
        //sent data back if something goes wrong
        $data["response"] = "invalidError";
        $encodeddata = json_encode($data);
        echo($encodeddata);
    }
    
    
?>