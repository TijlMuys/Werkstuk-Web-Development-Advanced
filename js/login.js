$(document).ready(function () {

    //get forms with jQuery
    var loginForm = $("#loginForm");
    var registerForm = $("#registerForm");
    //Hide register form
    registerForm.hide(1000);
    
    //add evenListener to form-switch button
    $("#form-switch-btn").on("click", function(){
        //check the current state
        if($(this).attr("state") == "login")
        {
            //If the current state is login we need to change the state to register
            $(this).attr("state", "register");
            //We need to change the text above the switchbutton
            $("#form-switch-tag").text("Go back to the login page");
            //We need to change the text of the switchbutton itself
            $(this).text("Login");
            //hide the login form
            $("#loginForm").hide();
            //show the registerForm
            $("#registerForm").show(1000);
        }
        else
        {
            //If the current state is not login we need to change the state to login
            $(this).attr("state", "login");
            //We need to change the text above the switchbutton
            $("#form-switch-tag").text("Not Registered yet? Click on the button below");
            //We need to change the text of the switchbutton itself
            $(this).text("Create new Account");
            //hide the login form
            $("#registerForm").hide();
            //show the registerForm
            $("#loginForm").show(1000);
        }
    });
    
    
    //add evenlistener to loginform
    loginForm.on('submit', function(e){
        //prevent default behaviour
        e.preventDefault();
        //proceed with ajax call if both field are valid
        if(validateEmail($("#loginMail")) == true && validatePassword($("#loginPass")) == true)
        {
            //sent ajax request for user validation
            $.ajax({
                url: loginForm.attr("action"),
                type: "POST",
                dataType: "json",
                data: loginForm.serialize(),
                error: function (xhr, ajaxOptions, thrownError) {
                    //show that something went wrong
                    $("#loginForm ").removeClass("is-valid");
                    $("#loginForm").addClass('is-invalid');
                    $("#loginForm .form-control").removeClass("is-valid");
                    $("#loginForm .hidden").addClass('is-invalid');
                    console.log(xhr.status);
                    console.log(thrownError); 
                },
                success: loginAjaxSuccess
            });
            
        }
    });
    
     //add evenlistener to registerform
    registerForm.on('submit', function(e){
        //prevent default behaviour
        e.preventDefault();
        //proceed with ajax call if both field are valid
        if(validateUsername($("#registerUsername")) == true && validateEmail($("#registerMail")) == true && validatePassword($("#registerPass")) == true)
        {
            //sent ajax request for user validation
            $.ajax({
                url: registerForm.attr("action"),
                type: "POST",
                dataType: "json",
                data: registerForm.serialize(),
                error: function (xhr, ajaxOptions, thrownError) {
                     //show that something went wrong
                    $("#registerForm").removeClass("is-valid");
                    $("#registerForm").addClass('is-invalid');
                    $("#registerForm .form-control").removeClass("is-valid");
                    $("#registerForm .hidden").addClass('is-invalid');
                    console.log(xhr.status);
                    console.log(thrownError);
                   
                },
                success: registerAjaxSuccess
            });
            
        }
    });
    
    //Success function of AJAX request for logging in
    function loginAjaxSuccess(data)
    {
        //check if returned user is false
        if(data.loggedInUser == false)
        {
            //if false display error message
            $("#loginForm .form-control").removeClass("is-valid");
            $("#loginForm .hidden").addClass('is-invalid');
        }
        else if(data.loggedInUser == "invalid")
        {
            //if false display error messages
            $("#loginForm .form-control").removeClass("is-valid");
            $("#loginForm .form-control").addClass('is-invalid');
        }
        else
        {
            //mark form as validated
            $("#loginForm .form-control").removeClass("is-invalid");
            $("#loginForm .form-control").addClass('is-valid');
            //Redirect to home page
            $(location).attr('href', 'home.php')
        }   
    }
    //Success function of AJAX request for registering a new user
    function registerAjaxSuccess(data)
    {
        //check if returned user is false
        if(data.loggedInUser == false)
        {
            //if false display error message
            $("#registerForm .form-control").removeClass("is-valid");
            $("#registerForm .hidden").addClass('is-invalid');
        }
        else if(data.loggedInUser == "invalid")
        {
            //if false display error messages
            $("#registerForm .form-control").removeClass("is-valid");
            $("#registerForm .form-control").addClass('is-invalid');
        }
        else
        {
            //mark form as validated
            $("#registerForm .form-control").removeClass("is-invalid");
            $("#registerForm .form-control").addClass('is-valid');
            //Redirect to home page
            $(location).attr('href', 'home.php')
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
    
    function validatePassword(passwordInputTag)
    {
        //test if password matches the regular expression (length need to between 8 and 30 characters)
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
    
    function validateUsername(usernameInputTag)
    {
        //test if username matches the regular expression (length need to between 1 and 30 characters)
        if(/^.{1,30}$/.test(usernameInputTag.val()))
        {
            //add class to indicate that the input field is valid and remove the other
            usernameInputTag.removeClass("is-invalid");
            usernameInputTag.addClass("is-valid");
            return true;
        }
        else
        {
            //add class to indicate that the input field is invalid and remove the other
            usernameInputTag.removeClass("is-valid");
            usernameInputTag.addClass("is-invalid");
            return false;
        }
    }
    
});