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
            console.log("valid");
            
            
            //sent ajax request for user validation
            $.ajax({
                url: loginForm.attr("action"),
                type: "POST",
                dataType: "json",
                data: loginForm.serialize(),
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(thrownError);
                },
                success: loginAjaxSuccess
            });
            
        }
    });
    
    
     //add evenlistener to registerform
    loginForm.on('submit', function(e){
        //prevent default behaviour
        e.preventDefault();
        //proceed with ajax call if both field are valid
        if(validateEmail($("#registerMail")) == true && validatePassword($("#registerPass")) == true)
        {
            console.log("valid");
            //mark form as validated
            loginForm.addClass('was-validated');
            //sent ajax request for user validation
            $.ajax({
                url: loginForm.attr("action"),
                type: "POST",
                dataType: "json",
                data: loginForm.serialize(),
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(thrownError);
                },
                success: loginAjaxSuccess
            });
            
        }
    });
    
    //Success function of AJAX request for all blogposts
    function loginAjaxSuccess(data)
    {
        console.log(data);
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
             //set session variable
            sessionStorage.setItem("loggedInUser", JSON.stringify(data));
            //get sessiondata
            console.log(JSON.parse(sessionStorage.getItem("loggedInUser")));
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
    
});