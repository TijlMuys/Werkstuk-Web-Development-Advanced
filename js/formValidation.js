//function that validates a password (no spaces)
function validatePassword(passwordInputTag)
{
    //test if password matches the regular expression (length needs to be between 8 and 30 characters, no spaces)
    if(/^\S{8,30}$/.test(passwordInputTag.val()))
    {
        //add class to indicate that the input field is valid and remove the other
        passwordInputTag.removeClass("is-invalid");
        passwordInputTag.addClass("is-valid");
        return true;
    }
    else
    {
        //add class to indicate that the input field is invalid and remove the other
        passwordInputTag.removeClass("is-valid");
        passwordInputTag.addClass("is-invalid");
        return false;
    }
}

//function that validates email addresses with regular expression
function validateEmail(emailInputTag)
{
    //test if email matches the regular expression (needs to contain both a @ and a .)
    if(/\S+@\S+\.\S+/.test(emailInputTag.val()))
    {
        //add class to indicate that the input field is valid and remove the other
        emailInputTag.removeClass("is-invalid");
        emailInputTag.addClass("is-valid");
        return true;
    }
    else
    {
        //add class to indicate that the input field is invalid and remove the other
        emailInputTag.removeClass("is-valid");
        emailInputTag.addClass("is-invalid");
        return false;
    }
}   

//fucntion that validates the length of a string
function validateStringLength(InputTag, minLength, maxLength)
{
    //test if string matches has the proper length
    if(InputTag.val().length >= minLength && InputTag.val().length <= maxLength)
    {
        //add class to indicate that the input field is valid and remove the invalid-class
        InputTag.removeClass("is-invalid");
        InputTag.addClass("is-valid");
        return true;
    }
    else
    {
        //add class to indicate that the input field is invalid and remove the valid-class
        InputTag.removeClass("is-valid");
        InputTag.addClass("is-invalid");
        return false;
    }
}

//function that checks if the fileupload is valid
function validateFile(file, maxsize)
{
   
    //check if only max 1 file is uploaded
    if(file.prop("files").length > 1)
    {
        //add class to indicate that the input field is invalid and remove the oposite class
        file.removeClass("is-valid");
        file.addClass("is-invalid");
        return false;
    }
    //if no file return true
    if(file.prop("files").length == 0)
    {
        //add class to indicate that the input field is valid and remove the oposite class
        file.removeClass("is-invalid");
        file.addClass("is-valid");
        return true;
    }
    //allowed extentions
    var validExtensions = new Array("jpg","png","gif");
    //get current extention
    var currentExtention = file.prop("files")[0].name.split('.').pop().toLowerCase();
    
    //get current filesize
    var currentSize =  file.prop("files")[0].size
    //if filesize if greater than 2 MB return false
    if(currentSize > maxsize)
    {
        //add class to indicate that the input field is invalid and remove the oposite class
        file.removeClass("is-valid");
        file.addClass("is-invalid");
        //resolve bootstrap bug with showing invalid feedback after input type=file tag
        file.next().removeAttr("hidden");
        return false;
    }
    
    
    
    //iterate over vaildextentions
    for(var i = 0; i <= validExtensions.length; i++)
    {
        //return true if currentextention is in the vaildextentions array
        if(validExtensions[i] == currentExtention)
        {
           //add class to indicate that the input field is valid and remove the oposite class
            file.removeClass("is-invalid");
            file.addClass("is-valid");
            return true;
        }
    }
    //if invalid return false
    //add class to indicate that the input field is invalid and remove the oposite class
    file.removeClass("is-valid");
    file.addClass("is-invalid");
    //resolve bootstrap bug with showing invalid feedback after input type=file tag
    file.next().removeAttr("hidden");
    return false;
}

//function that checks is string is not empty
function isNotEmpty(InputTag)
{
    if(InputTag.val().length != "" && InputTag.val() != null)
    {
        InputTag.removeClass("is-invalid");
        InputTag.addClass("is-valid");
        return true;
    }
    else
    {
        InputTag.removeClass("is-valid");
        InputTag.addClass("is-invalid");
        return false;
    }
}