<?php
    //destray session
    session_start();
    session_destroy();
    //destroy cookies
    setcookie("usermail", "", time()-3600, '/');
    setcookie("password", "", time()-3600, '/');
    //redirect
    header("Location: home.php"); /* Redirect browser */
    exit();
?>