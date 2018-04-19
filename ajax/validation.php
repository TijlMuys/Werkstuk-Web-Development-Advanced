<?php
    //CONTAINS Validation functions

    //function to check if parameter is set and not empty
    function validPostVar($postVarName) 
    {
        if(isset($_POST["".$postVarName.""]) && !empty($_POST["".$postVarName.""]))
        {
            return true;
        }
        return false;
    }

    function validfileVar($fileVarName) 
    {
        if(isset($_FILES["".$fileVarName.""]) && !empty($_FILES["".$fileVarName.""]))
        {
            return true;
        }
        return false;
    }
    
    //function to check string length
    function checkStringLength($string, $lowerbound, $upperbound) 
    {
        if(strlen($string) >= $lowerbound && strlen($string) <= $upperbound) 
        {
          return true;
        } 
        return false;
    }

    //function to check if file is valid
    function checkImageFile($file, $maxSize)
    {
        $validExtentions =  Array('jpg', 'png', 'gif');
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileSize = $file['size'];
        if(in_array($ext, $validExtentions) && $fileSize < $maxSize)
        {
            return true;
        }
        
        return false;
    }

    //function to validate email
    function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

?>