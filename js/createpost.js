$(document).ready(function () {
    //get all categories from server
     $.ajax({
                url: "ajax/categoryLoader.php",
                type: "POST",
                dataType: "json",
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(thrownError);
                },
                success: addCategories
            });
    //ajax response function for adding categories to dropbox
    function addCategories(categoryData)
    {
        //get container
        var catContainer = $("#Category");
        //iterate over categories
        for (i = 0; i < categoryData.length; i++) 
        { 
            //create new category option in selectbox
            var currentOption = $('<option>');
            //add categorytext to tag
            currentOption.text(categoryData[i]["CategoryName"]);
            //add option to container
            catContainer.append(currentOption);
        } 
    }
    
    //Add eventlistener to our post form
    $("#postForm").on('submit', function(e){
        //prevent default behaviour
        e.preventDefault();
        //proceed if form is valid
        if(postformValidation() == true)
        {
           //generate formdata (serialize doesn't work with ajax)
            var formData = new FormData();
            //add data to formdata
            formData.append('Title', $("#Title").val());
            formData.append('Category', $("#Category").val());
            formData.append('Content', $("#Content").val());
            //add value if fileupload is not empty
            if( document.getElementById("ImageUrl").files.length == 1 )
            {
               formData.append('ImageUrl', document.getElementById("ImageUrl").files[0], document.getElementById("ImageUrl").files[0].name); 
            }

            console.log(formData);
        
            //sent ajax request for user validation
            $.ajax({
                url: $("#postForm").attr("action"),
                type: 'POST',
                dataType: "json",
                //use formdata instead of serialize function
                data: formData,
                //needs to ba added in order to use formData
                cache: false,
                contentType: false,
                processData: false,
                error: function (xhr, ajaxOptions, thrownError) {
                    //show that something went wrong
                    $("#postForm").removeClass("is-valid");
                    $("#postForm").addClass('is-invalid');
                    $("#postForm .form-control").removeClass("is-valid");
                    $("#postForm .hidden").addClass('is-invalid');
                    console.log(xhr.status);
                    console.log(thrownError); 
                },
                success: postSuccess
            });     
        }
        
            
            
    });

    //Ajax Response function
    function postSuccess(dataRes)
    {
        console.log(dataRes);
        if(dataRes.response != "insertError" && dataRes.response != "invalidError")
        {
            console.log(dataRes.response);
            //reload page
            console.log("success");
            window.location.replace("blogpostdetail.php?Id=" + dataRes.response["Id"] + "");
        }
        else
        {
            //Show that something went wrong
            $("#commentForm").removeClass("is-valid");
            $("#commentForm").addClass('is-invalid');
            $("#commentForm .form-control").removeClass("is-valid");
            $("#commentForm .hidden").addClass('is-invalid');
        }
    }
    
    //function to validate form
    function postformValidation()
    {
        //call all validation functions for Title, Category, Content and Image
        if(validateStringLength($("#Title"),2, 100) && isNotEmpty($("#Category")) && validateStringLength($("#Content"), 10, 20000) && validateFile($("#ImageUrl"), 2000000))
        {
            return true;
        }
        else
        {
            return false
        }
    }
    
});