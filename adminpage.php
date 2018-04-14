<?php
    //include user class
    include_once("data/User.php");
    //start session
    session_start();   
    
    //initiate current loggedinUser on false
    $loggedInUser = false;
    //check if user is logged in, if the case convert data to the logginInuser variable, else redirect to home
    if(isset($_SESSION["loggedInUser"]))
    {
        $loggedInUser = $_SESSION["loggedInUser"];
        //check if user is admin, if not redirect
        if($loggedInUser->IsAdmin != 1)
        {
            header('Location: home.php');
            exit();
        }
        
    }
    else
    {
        header('Location: loginpage.php');
        exit();
    }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Holmepage of blog">
    <meta name="author" content="Tijl Muys">

    <title>Admin</title>

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
              <a class="nav-link" href="aboutpage.php">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="blogposts.php">Blogposts</a>
            </li>
            <?php
              //only add option if a user is logged in
              if($loggedInUser != false)
              {
                  echo("<li class='nav-item'>
                        <a class='nav-link' href='newpost.php'>Create Post</a>
                        </li>");
              }
              //only add option if a user is an administrator
              if($loggedInUser->IsAdmin == 1)
              {
                  echo("<li class='nav-item active'>
                        <a class='nav-link' href='adminpage.php'>Admin
                        <span class='sr-only'>(current)</span>
                        </a>
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
        <div class="col-md-9">

          <h1 id="h1" class="my-4">Admin - Page
            <small></small>
          </h1>
          <h2>Posts</h2>
          <!--Posts overview-->
          <div class="container">
            <div class="row">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Id</th>
                      <th>Title</th>
                      <th>User</th>
                      <th>Date</th>
                      <th>Category</th>
                      <th>Comments</th>
                      <th>View</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody id="myTable">
                    <tr>
                      <td>1</td>
                     <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td><a class="btn btn-info"><span class="text-white">View</span></a></td>
                      <td><a class="btn btn-danger"><span class="text-white">Delete</span></a></td>
                    </tr>
                    <tr>
                      <td>2</td>
                    <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td><a class="btn btn-info"><span class="text-white">View</span></a></td>
                      <td><a class="btn btn-danger"><span class="text-white">Delete</span></a></td>
                    </tr>
                    <tr>
                      <td>3</td>
                     <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td><a class="btn btn-info"><span class="text-white">View</span></a></td>
                      <td><a class="btn btn-danger"><span class="text-white">Delete</span></a></td>
                    </tr>
                    <tr>
                      <td>4</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td><a class="btn btn-info"><span class="text-white">View</span></a></td>
                      <td><a class="btn btn-danger"><span class="text-white">Delete</span></a></td>
                    </tr>
                    <tr class="success">
                      <td>5</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td><a class="btn btn-info"><span class="text-white">View</span></a></td>
                      <td><a class="btn btn-danger"><span class="text-white">Delete</span></a></td>
                    </tr>
                    <tr>
                      <td>6</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td><a class="btn btn-info"><span class="text-white">View</span></a></td>
                      <td><a class="btn btn-danger"><span class="text-white">Delete</span></a></td>
                    </tr>
                    <tr>
                      <td>7</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td><a class="btn btn-info"><span class="text-white">View</span></a></td>
                      <td><a class="btn btn-danger"><span class="text-white">Delete</span></a></td>
                    </tr>
                     <tr>
                      <td>8</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td><a class="btn btn-info"><span class="text-white">View</span></a></td>
                      <td><a class="btn btn-danger"><span class="text-white">Delete</span></a></td>
                    </tr>
                    <tr>
                      <td>9</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td><a class="btn btn-info"><span class="text-white">View</span></a></td>
                      <td><a class="btn btn-danger"><span class="text-white">Delete</span></a></td>
                    </tr>
                    <tr>
                      <td>10</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td>Table cell</td>
                      <td><a class="btn btn-info"><span class="text-white">View</span></a></td>
                      <td><a class="btn btn-danger"><span class="text-white">Delete</span></a></td>
                    </tr>
                  </tbody>
                </table>   
              </div>
            </div> 
            <!-- Pagination -->
          <nav aria-label="Page navigation example">
              <ul class="pagination" id="paginationtop">
                <li class="page-item">
                  <a class="page-link page-link-prev" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                  </a>
                </li>
                <li class="page-item">
                  <a class="page-link page-link-next" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                  </a>
                </li>
              </ul>
            </nav>
        </div>
          <hr>
          <h2>Add Category</h2>
          <br>
          <!-- form -->
          <div id="form-container">
              <form class="needs-validation" >
                 <fieldset id="fieldset">
                 <label for="postTitle"><h4 class="text-secondary">Category</h4></label>
                  <div class="form-row">
                    <div class="col-md-4 mb-3">
                      <input type="text" class="form-control" id="postTitle" placeholder="Category" value="" required>
                      <div class="invalid-feedback">
                          Please choose a Category.
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                       <button class="btn btn-primary" type="submit">Add Category</button>
                    </div>
                  </div>
                </fieldset>
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