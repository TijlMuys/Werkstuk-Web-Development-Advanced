<?php
    //Check if user is logged in, otherwise redirect to login page
    if(isset($_SESSION["currentUser"]))
    {
        header('Location: loginpage.php');
        exit();
    }
    else
    {
        $currentUser = $_SESSION["currentUser"];
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
        <a class="navbar-brand" href="home.php">MyBlog</a>
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
            <li class="nav-item active">
              <a class="nav-link" href="newpost.php">Create Post
              <span class="sr-only">(current)</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="loginpage.php">Login</a>
            </li>
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
              <form class="needs-validation" >
                 <fieldset id="fieldset">
                  <div class="form-row">
                    <div class="col-md-8 mb-3">
                      <label for="postTitle"><h4 class="text-secondary">Title</h4></label>
                      <input type="text" class="form-control" id="postTitle" placeholder="Title" value="" required>
                      <div class="invalid-feedback">
                          Please choose a Title.
                        </div>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="col-md-8 mb-3">
                      <div class="form-group">
                        <label for="postCategory"><h4 class="text-secondary">Category</h4></label>
                        <select class="form-control" id="postCategory">
                          <option>Politics</option>
                          <option>Science</option>
                          <option>Food</option>
                          <option>Programming</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="col-md-10 mb-3">
                      <div class="form-group">
                        <label for="postContent"><h4 class="text-secondary">Post Content</h4></label>
                        <textarea class="form-control" id="postContent" rows="10" required></textarea>
                        <div class="invalid-feedback">
                          Please choose a Title.
                        </div>
                      </div>
                    </div>
                  </div>
                   <div class="form-row">
                    <div class="col-md-8 mb-3">
                       <div class="form-group">
                        <label for="postImageUrl"><h4 class="text-secondary">Image</h4></label>
                        <input type="file" class="form-control-file" id="postImageUrl">
                      </div>
                    </div>
                  </div>
                </fieldset>
                <br>
              <button class="btn btn-primary" type="submit">Submit form</button>
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