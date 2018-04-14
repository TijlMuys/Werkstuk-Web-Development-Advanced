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
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

   <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- JQuery -->
    <script src="vendor/jquery/jquery-3.3.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Script for password hashing (CDN provided by cloudflare) -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js"></script>
    <!-- Custom styles for this template -->
    <link rel="stylesheet" type="text/css" href="css/layout.css">
  </head>

  <body class="text-center">
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
                  echo("<li class='nav-item'>
                        <a class='nav-link' href='adminpage.php'>Admin</a>
                        </li>");
              }
              //option changes depending on the fact wheter of not the user is currently logged in
              if($loggedInUser == false)
              {
                  echo("<li class='nav-item active'>
                        <a class='nav-link' href='loginpage.php'>Login
                        <span class='sr-only'>(current)</span>
                        </a>
                        </li>");
              }
              else
              {
                  echo("<li class='nav-item active'>
                        <a class='nav-link' href='signoutpage.php'>Logout
                        <span class='sr-only'>(current)</span>
                        </a>
                        </li>");
              }
            ?>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <div class="container" id="login-start">
       <div class="row">

        <!-- Column -->
            <div class="col-md-8">
               <!-- LOGIN FORM -->
                <form class="form-signin" id="loginForm" method="post" action="ajax/loginLoader.php" novalidate>
                  <!-- 
                  Login icon used from free version of Font Awesome: https://fontawesome.com/icons/sign-in-alt?style=solid 
                  
                  The 'sign-in-alt' Icon (c) by Font Awesome is licensed under a
                  Creative Commons Attribution 4.0 International License.
                  -->
                  <img class="mb-4" src="images/login.png" alt="" width="72" height="72">
                  <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
                  <div class="form-group" id="loginEmailGroup">
                      <label for="loginMail" class="sr-only">Email address</label>
                      <input type="email" name="loginMail" id="loginMail" class="form-control" placeholder="Email address" required autofocus>
                      <div class="invalid-feedback">Please provide a vaild email address</div>
                  </div>
                  <div class="form-group" id="loginPassGroup">
                      <label for="loginPass" class="sr-only">Password</label>
                      <input type="password" id="loginPass" name="loginPass" class="form-control" placeholder="Password" required>
                      <div class="invalid-feedback">The password needs to be between 8 and 30 characters long</div>
                  </div>
                  <div class="form-group" id="loginHiddenGroup">
                      <input type="hidden" class="form-control hidden">
                      <div class="invalid-feedback">Username or password is incorrect or could not be verified in the database</div>
                  </div>
                  <br>
                  <input class="btn btn-lg btn-primary btn-block" id="loginSubmit" type="submit" value="Sign in">
                  <br>
                </form>
                
                <!-- REGISTER FORM -->
                <form  class="form-signin"  id="registerForm" method="post" action="ajax/registerUser.php" novalidate>
                  <!-- 
                  Login icon used from free version of Font Awesome: https://fontawesome.com/icons/user-plus?style=solid 
                  
                  The 'user-plus' Icon (c) by Font Awesome is licensed under a
                  Creative Commons Attribution 4.0 International License.
                  -->
                  <img class="mb-4" src="images/register.png" alt="" width="72" height="72">
                  <h1 class="h3 mb-3 font-weight-normal">Register New Account</h1>
                    <div class="form-group" id="registerUsernameGroup">
                          <label for="registerUsername" class="sr-only">Username</label>
                          <input type="text" id="registerUsername" name="registerUsername" class="form-control" placeholder="Username" required autofocus>
                          <div class="invalid-feedback">Please provide a username that consists of maximum 30 characters</div>
                      </div>
                     <div class="form-group" id="registerEmailGroup">
                          <label for="registerMail" class="sr-only">Email address</label>
                          <input type="email" id="registerMail" name="registerMail" class="form-control" placeholder="Email address" required>
                          <div class="invalid-feedback">Please provide a vaild email address</div>
                      </div>
                     <div class="form-group" id="registerPassGroup">
                          <label for="registerPass" class="sr-only">Password</label>
                          <input type="password" id="registerPass" name="registerPass" class="form-control" placeholder="Password" required>
                          <div class="invalid-feedback">The password needs to be between 8 and 30 characters long</div>
                    </div>
                    <div class="form-group" id="registerHiddenGroup">
                      <input type="hidden" class="form-control hidden">
                      <div class="invalid-feedback">Sorry, we couldn't add you to our database. The given email-address might be already taken or something went wrong.</div>
                  </div>
                  <br>
                  <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
                  <br>
                </form>
                <br>
                <!-- FORM SWITCH-->
                <div id="form-switch-div">
                   <p id="form-switch-tag">Not Registered yet? Click on the button below</p>
                   <button id="form-switch-btn" type="button" class="btn btn-secondary" state="login">Create new Account</button>
                </div>
                <br>
                <hr>
                <p class="mt-5 mb-3 text-muted">&copy; 2017-2018</p>
            </div>
        </div>
    </div>
    <script src="js/login.js"></script>
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