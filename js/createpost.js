$(document).ready(function () {
//Add eventlistener to our post form
    $("#postForm").on('submit', function(e){
        //prevent default behaviour
        e.preventDefault();
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
        //proceed with ajax call if both field are valid
        if(validateCommentContent($("#postForm")) == true)
        {
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
    
    //validate postContent
    function validateCommentContent(commentContentInputTag)
    {
        /*
        //test if username matches the regular expression (length need to between 1 and 30 characters)
        if(commentContentInputTag.val().length > 3 && commentContentInputTag.val().length < 1000)
        {
            //add class to indicate that the input field is valid and remove the other
            commentContentInputTag.removeClass("is-invalid");
            commentContentInputTag.addClass("is-valid");
            return true;
        }
        else
        {
            //add class to indicate that the input field is invalid and remove the other
            commentContentInputTag.removeClass("is-valid");
            commentContentInputTag.addClass("is-invalid");
            return false;
        }
        */
        return true;
    }

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
    
});