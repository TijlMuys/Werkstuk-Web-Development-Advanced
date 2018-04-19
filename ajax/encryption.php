<?php

   //encryption and decryption functions 
    function encrypt($plain, $key) 
    { 
         return openssl_encrypt($crypted,"AES-128-ECB", $key); 
    } 

    function decrypt($crypted, $key) 
    { 
        return openssl_decrypt($crypted,"AES-128-ECB", $key);
    }

?>