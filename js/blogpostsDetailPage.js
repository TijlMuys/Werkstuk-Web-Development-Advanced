$(document).ready(function () {
    //Get id from url
        //get the url parameters for the blogpostId
        var IdUrlParam = getUrlParams();
        console.log(IdUrlParam);
        //if there is a Id parameter, proceed
        if(typeof IdUrlParam["Id"] !== 'undefined')
        {
            console.log({Id:IdUrlParam["Id"]});
            //extract Id from params array
            //Send ajax request to get all posts from server
            $.ajax({
                url: "ajax/blogpostdetailLoader.php",
                type: "POST",
                dataType: "json",
                data: {
                    'Id' : IdUrlParam["Id"]
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(thrownError);
                },
                success: getAllAjaxSuccess
            });
        }
    
    
    //Success function of AJAX request for all blogposts
    function getAllAjaxSuccess(data)
    {
        console.log(data);
        
        //Check if there is any data received, if the case construct page
        if(data.blogpostdetail) 
        {
            console.log(data.blogpostdetail);
            formatDetails(data.blogpostdetail);
            formatComments(data.blogpostdetail);
            
        }
    }
    
    //function to construct details of blogpost in pretty container
    function formatDetails(data)
    {  
        //get blogpostdetailDiv
        var blogDetailContainer = $("#mainContent");
        
        //Create header tag
        var headertag = $('<h1>', {class: 'mt-4'});
        //set title of blogpost
        headertag.text(data["Title"]);
        //Append header
        blogDetailContainer.append(headertag);
        
        //create tag that contains author-info and category
        var subheadertag = $('<p>', {class: 'lead'});
        //set text of author and category
        subheadertag.html("by <span class='text-info'>" + data["Username"] + "</span> - <span class='text-secondary'>" + data["CategoryName"] + "</span>");
        //Append subheader
        blogDetailContainer.append(subheadertag);
        
        //add horizontal rule
        blogDetailContainer.append("<hr>");
        
        //Create image tag
        var imagetag = $('<img>', {class: 'img-fluid rounded img-blogpost'});
        //add image source
        imagetag.attr("src", data["ImageUrl"]);
        //Dynamic resize
        imagetag.css({"max-width": "100%", "height": "auto"});
        //generate alternate text
        imagetag.attr("alt", ("Corresponding Image of Blogpost with Title: " + data["Title"]));
        //append imagetag
        blogDetailContainer.append(imagetag);
        
         //add horizontal rule
        blogDetailContainer.append("<hr>");
        
        //create textcontent tag
        var textContent =  $('<p>');
        //add textcontent to tag
        textContent.text(data["Content"]);
        //append textcontent
        blogDetailContainer.append(textContent);
        
        //add horizontal rule
        blogDetailContainer.append("<hr>");
        
    }
    
     //function to construct comments of blogpost in pretty container
    function formatComments(data)
    {  
        //get commentDiv
        var commentContainer = $("#commentDiv");
        //Iterate over Comments
        for(var i = 0; i < data["Comments"].length; i++)
        {
           
            //Create outerdiv of comment
            var commentOuterDiv = $('<div>', {class: 'media mb-4'});
            
            //create image tag for comment
            var commentImage = $('<img>', {class: 'd-flex mr-3 rounded-circle', src: 'images/chat.png', alt: 'text bubble'});
            //Append imagetag
            commentOuterDiv.append(commentImage);
            
            //Create innerDiv of comment
            var commentInnerDiv = $('<div>', {class: 'media-body'});
            
            //create header tag for comment
            var commentHeader = $('<h5>', {class: 'mt-0'});
            //set text for header
            commentHeader.text(""+ data["Comments"][i]["Username"] +"");
            //add header to innerdiv
            commentInnerDiv.append(commentHeader);
            
            //create paragraph tag for comment
            var commentContent = $('<p>', {class: 'text-secondary'});
            //set text for paragraph
            commentContent.html(""+ data["Comments"][i]["Content"] +"<br>");
           
            
             //create date tag for comment
            var commentDate = $('<small>', {class: 'text-info'});
            //set text for date tag
            commentDate.text("Comment written on "+ data["Comments"][i]["Date"] +"");
            //add date tag to paragraph
            commentContent.append(commentDate);
            
            //add paragraph to innerdiv
            commentInnerDiv.append(commentContent);

            //Add innerdiv to outerdiv
            commentOuterDiv.append(commentInnerDiv);
            
            //add horizontal rule to outer div
            commentOuterDiv.append("<hr>");
            
            //add outerdiv to our commentdiv
            commentContainer.append(commentOuterDiv);
            
        }
      
    }
    
    //Function that return the url parameters of a GET request
    function getUrlParams()
    {
        //initiate empty array
        var params = {};
        //get the url of the current page, and for all matches of the regular expression we call an annonymous function that adds the key and value of the parameter to the params array
        //following line of code found at http://papermashup.com/read-url-get-variables-withjavascript/, originally written by Ashley Ford
        window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {params[key] = value;});
        //return the array
        return params;
    }
    
    //Add eventlistener to our comment form
    $("#commentForm").on('submit', function(e){
        //prevent default behaviour
        e.preventDefault();
        console.log($("#commentForm").attr("action"));
        console.log($("#commentContent").val());
        console.log($("#blogpostId").val());
        console.log($("#commentForm").serialize());
        //proceed with ajax call if both field are valid
        if(validateCommentContent($("#commentContent")) == true)
        {
            //sent ajax request for user validation
            $.ajax({
                url: $("#commentForm").attr("action"),
                type: "POST",
                dataType: "json",
                data: $("#commentForm").serialize(),
                error: function (xhr, ajaxOptions, thrownError) {
                    //show that something went wrong
                    $("#commentForm").removeClass("is-valid");
                    $("#commentForm").addClass('is-invalid');
                    $("#commentForm .form-control").removeClass("is-valid");
                    $("#commentForm .hidden").addClass('is-invalid');
                    console.log(xhr.status);
                    console.log(thrownError); 
                },
                success: postCommentSuccess
            });
            
        }
    });
    
    //validate commentContent
    function validateCommentContent(commentContentInputTag)
    {
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
    }
    
    function postCommentSuccess(dataRes)
    {
        console.log(dataRes.response);
        if(dataRes.response == "success")
        {
            //reload page
            location.reload();
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