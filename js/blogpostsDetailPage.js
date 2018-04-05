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
        var imagetag = $('<img>', {class: 'img-fluid rounded'});
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
    
});