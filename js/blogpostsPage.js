$(document).ready(function () {
    //Send ajax request to get all posts from server
    $.ajax({
        url: "ajax/blogpostsLoader.php",
        type: "POST",
        dataType: "json",
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(thrownError);
        },
        success: getAllAjaxSuccess
    });
    
    
    //Success function of AJAX request for all blogposts
    function getAllAjaxSuccess(data)
    {
        //call upon the sidebar function
        overviewSidebar(data);
        //Check if there is any data received
        if(data.blogposts) 
        {
            //initialise variable that contains all blogposts
            var content;
            //loop over blogposts encpasulate them in a pretty format and add them to webpage
            for (i = 0; i < data.blogposts.length; i++) { 
                content = formatBlogpost(data.blogposts[i]);
                $("#dynamic-blogposts").append(content);
            } 
         }
        //call upon the pagination function (FIRST TIME)
        pagination(true);
        
        //test if there was an external filter
        //get the potential url parameters for the filter
        var filterUrlParams = getUrlParams();
        //if there is a filter parameter call, apply the filtering
        if(typeof filterUrlParams["catfilter"] !== 'undefined')
        {
            //Remove potential # that are added to the url when using page numbers
            filterOnCategory(filterUrlParams["catfilter"].replace("#", ""));
        }
        if(typeof filterUrlParams["datefilter"] !== 'undefined')
        {
            var decodedFilter = decodeURI(filterUrlParams["datefilter"]);
            //Remove potential # that are added to the url when using page numbers
            filterOnDate(decodedFilter.replace("#", ""));
        }
    }
    
   
    
     //Converts a raw BlogPost into a pretty viewable format for the frontend
    function formatBlogpost($rawData)
    {
        //Create outer div
        var outerdiv = $('<div>', {class: 'card mb-4'});
        //add some atrributes with metadata used for filtering and other functionalities, 
        //Add amount of comments 
        outerdiv.attr("commentCount", $rawData["Comments"].length);
        //Add category
        outerdiv.attr("category", $rawData["CategoryName"]);
        //get Date object from rawdata and do some string manipulation for safari browser
        var currentYearMonthString = $rawData["Date"].substr(0, $rawData["Date"].indexOf(" "));
        var currentDate = new Date(currentYearMonthString);
        //Add yearDate-string (using function to convert monthnumber to full name)
        outerdiv.attr("yearDate", (currentDate.getFullYear() + " - " + getMonthName(currentDate.getMonth())));
        //add isFiltered attribute (default true)
        outerdiv.attr("isFiltered", true);
        //Add Tooltip
        outerdiv.attr("Title", "This post has " + $rawData["Comments"].length + " comments");
        
        //Create image tag
            var imagetag = $('<img>', {class: 'card-img-top'});
            //add blogpost ImageUrl
            imagetag.attr("src", $rawData["ImageUrl"]);
            //Dynamic resize
            imagetag.addClass("img-fluid, img-blogpost");
            imagetag.css({"max-width": "100%"});
            //generate alternate text
            imagetag.attr("alt", ("Corresponding Image of Blogpost with Title: " + $rawData["Title"]));
            //append to body
            outerdiv.append(imagetag);
        
        //Create blogpost body
            var blogpostbody = $('<div>', {class: 'card-body'});
          
            //Create Blogpost Title
            var blogpostTitle = $('<h2>', {class: 'card-title'});
            blogpostTitle.text($rawData["Title"]);
            blogpostbody.append(blogpostTitle);
            //Create Blogpost (condensed) Content
            var blogpostContent = $('<p>', {class: 'card-text'});
            //get condensed article with only the first 100 words //slice first 100 on spaces and join them again
            var blopostTextCondensed = $rawData["Content"].split(' ').slice(0,100).join(' ');
            blogpostContent.text(blopostTextCondensed + " ...");
            //add opacity gradient (if supported)
            blogpostContent.css({"opacity":"0.75"});
            //Append to blogpost
            blogpostbody.append(blogpostContent);
            //Create detailpage link
            var blogpostLink = $('<a>', {class: 'btn btn-primary'});
            blogpostLink.attr("href", "blogpostdetail.php?Id=" + $rawData["Id"]);
            blogpostLink.text("Read More");
            blogpostbody.append(blogpostLink);
        //append to outerdiv
        outerdiv.append(blogpostbody);
        
        //Create blogpost-footer
            var blogpostfooter = $('<div>', {class: 'card-footer text-muted'});
            blogpostfooter.text("Posted on " + $rawData["Date"] + ", by " + $rawData["Username"]);
        //append to outerdiv
        outerdiv.append(blogpostfooter);
        
        //add EventListeners for doubleclicking and hovering
        outerdiv.on("click", function() {
            window.location.href= "blogpostdetail.php?Id=" + $rawData["Id"];
        });
        /*
        outerdiv.on("hover", function() {
            $(this).attr("Title", "This post has " + $rawData["Comments"].length + " comments");
        });
        */
        return outerdiv;
    
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