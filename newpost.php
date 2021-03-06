<?php
    //include initilize file
    include_once("initialize.php");
    //include user classes
    include_once("database/UserDB.php");
    //include encryption methods
    include_once("ajax/encryption.php");
    //start session
    session_start();    
    
    //initiate current loggedinUser on false
    $loggedInUser = false;
    //check if user is logged in, if the case convert data to the logginInuser variable, else redirect to home
    if(isset($_SESSION["loggedInUser"]))
    {
        $loggedInUser = $_SESSION["loggedInUser"];
        
    }
    else
    {
        //check if you can log in with cookie data
        if(isset($_COOKIE["usermail"]) && isset($_COOKIE["password"]))
        {
            //if present, attempt login of user with cookie data
            $users = UserDB::getAll();
            //initiate variable that will hold the logged in user (when it exists)
            $loggedInUser = false;
            //iterate over all users
            for($i = 0; $i < count($users); $i++)
            {
                //check if username and password match, if the case set logginUser and break loop 
                if($users[$i]->Password == encrypt(decrypt($_COOKIE["password"], $_COOKIE["usermail"]), $_COOKIE["usermail"]) && $users[$i]->Email == $_COOKIE["usermail"])
                {
                    $loggedInUser = $users[$i];
                    break;
                }
            }
        }
        else
        {
            //redirect to loginpage
            header('Location: loginpage.php');
            exit();
        }
        
    }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Holmepage of blog">
    <meta name="author" content="Tijl Muys">

    <title>Create Post</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- JQuery -->
    <script src="vendor/jquery/jquery-3.3.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Custom styles for this template -->
    <link rel="stylesheet" type="text/css" href="css/layout.css">
  </head>

  <body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="site-navbar">
      <div class="container">
         <a class="navbar-brand" href="home.php">MyBlog <?php if($loggedInUser != false) { echo("<small class='text-secondary'> - Welcome ". $loggedInUser->Username ."!</small>");} ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="blogposts.php">Blogposts</a>
            </li>
            <?php
              //only add option if a user is logged in
              if($loggedInUser != false)
              {
                  echo("<li class='nav-item active'>
                        <a class='nav-link' href='newpost.php'>Create Post
                        <span class='sr-only'>(current)</span>
                        </a>
                        </li>");
              }
              //only add option if a user is an administrator
              if($loggedInUser->IsAdmin == 1)
              {
                  echo("<li class='nav-item'>
                        <a class='nav-link' href='adminpage.php'>Admin</a>
                        </li>");
              }
              //option changes depending on the fact wheter of not the user is currently logged in
              if($loggedInUser == false)
              {
                  echo("<li class='nav-item'>
                        <a class='nav-link' href='loginpage.php'>Login</a>
                        </li>");
              }
              else
              {
                  echo("<li class='nav-item'>
                        <a class='nav-link' href='signoutpage.php'>Logout</a>
                        </li>");
              }
            ?>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <div class="container" id="start-content">

      <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

          <h1 id="h1" class="my-4">Create new Post
            <small></small>
          </h1>
          <!-- form -->
          <div id="form-container">
              <form enctype="multipart/form-data" class="needs-validation" id="postForm" method="POST" action='ajax/addPost.php'>
                 <fieldset id="fieldset">
                  <div class="form-row">
                    <div class="col-md-8 mb-3">
                      <label for="Title"><h4 class="text-secondary">Title</h4></label>
                      <input type="text" class="form-control" id="Title" name="Title" placeholder="Title" value="" required>
                      <div class="invalid-feedback">
                          Please add a title with a minimum length of 2 and a maximum length of 100.
                        </div>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="col-md-8 mb-3">
                      <div class="form-group">
                        <label for="Category"><h4 class="text-secondary">Category</h4></label>
                        <select class="form-control" id="Category" name="Category">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="col-md-10 mb-3">
                      <div class="form-group">
                        <label for="Content"><h4 class="text-secondary">Post Content</h4></label>
                        <textarea class="form-control" id="Content" name="Content" rows="10" required></textarea>
                        <div class="invalid-feedback">
                          Please add some content to the post with a minimum length of 10 and a maximum length of 20000.
                        </div>
                      </div>
                    </div>
                  </div>
                   <div class="form-row">
                    <div class="col-md-8 mb-3">
                       <div class="form-group">
                        <label for="ImageUrl"><h4 class="text-secondary">Image</h4></label>
                        <input type="file" class="form-control-file" id="ImageUrl" name="ImageUrl" accept="image/png, image/gif, image/jpeg">
                        <div class="text-danger" id="file-feedback" hidden>
                            Please add a valid image file of the .png, .jpg or .gif extention. The file may not be larger than 2 MB.
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class='form-group'>
                          <input type='hidden' class='form-control hidden' id='error' name='error' value="-">
                          <div class='invalid-feedback'>Sorry, we couldn't add your comment to our database. It seems something went wrong</div>
                    </div>
                </fieldset>
                <br>
              <button class="btn btn-primary" type="submit">Create Post</button>
            </form>
          </div>
      

        <!-- /Blog Entries Column -->
        </div>
      </div>
    </div>
    <hr />
    <footer class="container">
      <p>&copy; Tijl Muys, EhB - 2017-2018</p>
    </footer>
    <!-- script -->
    <script src="js/formValidation.js"></script>
    <script src="js/createpost.js"></script>
  </body>
</html>
<!--
Large parts of the code above were copied from https://getbootstrap.com/docs/4.0/examples/jumbotron/ under the MIT license

The MIT License (MIT)

Copyright (c) 2011-2018 Twitter, Inc.
Copyright (c) 2011-2018 The Bootstrap Authors

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
-->