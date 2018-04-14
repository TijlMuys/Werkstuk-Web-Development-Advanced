<?php
    //include user class
    include_once("data/User.php");
    //start session
    session_start();   
    
    //initiate current loggedinUser on false
    $loggedInUser = false;
    //check if user is logged in, if the case convert data to the logginInuser variable
    if(isset($_SESSION["loggedInUser"]))
    {
        $loggedInUser = $_SESSION["loggedInUser"];
        
    }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Holmepage of blog">
    <meta name="author" content="Tijl Muys">

    <title>Blog Posts</title>

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
            <li class="nav-item active">
              <a class="nav-link" href="blogposts.php">Blogposts
               <span class="sr-only">(current)</span>
              </a>
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

          <h1 id="h1" class="my-4">Blog Posts
            <small>All Posts</small>
          </h1>
          
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
          <!-- Blog post container -->
          <div id="dynamic-blogposts">
              <!-- Blog Post -->
               
            
          </div>
          <!-- Dynamic Blogposts script -->
          <script src="js/blogpostsPage.js"></script>
        
          
        <!-- Pagination -->
          <nav aria-label="Page navigation example">
              <ul class="pagination" id="paginationbottom">
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

        <!-- /Blog Entries Column -->
        </div>

        <!-- Sidebar Widgets Column -->
        <div class="col-md-4" id="affix">

          <!-- Search Widget 
          <div class="card my-4">
            <h5 class="card-header">Search</h5>
            <div class="card-body">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Search for...">
                <span class="input-group-btn">
                  <button class="btn btn-secondary" type="button">Go!</button>
                </span>
              </div>
            </div>
          </div>
          -->

         <!-- Popular Widget -->
          <div class="card my-4">
            <h4 class="card-header">3 Most Popular Posts</h4>
            <div class="card-body">
                <ul class="list-unstyled mb-0 popular-list">
                 </ul>
                </div>
            </div>
        
          <hr />
         
          <!-- Categories Widget -->
          <div class="card my-4">
            <h4 class="card-header">Categories</h4>
            <div class="card-body">
                <ul class="list-unstyled mb-0 categories-list">
                    
                 </ul>
                </div>
            </div>
        
          <hr />
          <!-- Archive Widget -->
          <div class="card my-4">
            <h4 class="card-header">Archive</h4>
            <div class="card-body">
                <ul class="list-unstyled mb-0 archive-list">
                    
                 </ul>
                </div>
            </div>

        </div>
      </div>
      <!-- /.row -->
        <hr />
      </div> <!-- /container -->

    <footer class="container">
      <p>&copy; Tijl Muys, EhB - 2017-2018</p>
    </footer>
  </body>
</html>

<!--
Large parts of the code above were copied from https://startbootstrap.com/template-categories/blogs/ under the MIT license

The MIT License (MIT)

Copyright (c) 2013-2018 Blackrock Digital LLC

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