$(document).ready(function () {
    var form = $("#form");
    $.ajax({
        url: "ajax/blogpostsLoader.php",
        type: "POST",
        dataType: "json",
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        },
        success: getAllAjaxSuccess
    });
    
    $('#form').submit(function (e) {
    e.preventDefault(); //normaal gedrag van submit event tegenhouden (=versturen formulier)
    var form = $(this);
    
    $.ajax({
        url: form.attr("action"),
        type: "POST",
        dataType: "json",
        data: form.serialize(), // alle data van het formulier
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        },
        success: function(data) {
            form.hide(); //formulier verwijderen
            
            if(data.blogposts) 
            {
               var content;
               for (i = 0; i < data.blogposts.length; i++) { 
                   content = formatBlogpost(data.blogposts[i]);
                   $("#data").append(content);
               }
                
            }
        }
    });
  });
    
    
    //Converts a raw BlogPost into a pretty viewable format for the frontend
    function formatBlogpost($rawData)
    {
        //Create outer div
        var outerdiv = $('<div>', {class: 'card mb-4'});
        
            //Create image tag
            var imagetag = $('<img>', {class: 'card-img-top'});
            //add blogpost ImageUrl
            imagetag.attr("src", $rawData["ImageUrl"]);
            //generate alternate text
            imagetag.attr("alt", ("Corresponding Image of Blogpost with Title: " + $rawData["Title"]));
        //append to outerdiv
        outerdiv.append(imagetag);
        
            //Create blogpost body
            var blogpostbody = $('<div>', {class: 'card-body'});  
            //Create Blogpost Title
            var blogpostTitle = $('<h2>', {class: 'card-title'});
            blogpostTitle.text($rawData["Title"]);
            blogpostbody.append(blogpostTitle);
            //Create Blogpost (condensed) Content
            var blogpostContent = $('<p>', {class: 'card-text'});
            blogpostContent.text($rawData["Content"]);
            blogpostbody.append(blogpostContent);
            //Create detailpage link
            var blogpostLink = $('<a>', {class: 'btn btn-primary'});
            blogpostLink.attr("href", "#");
            blogpostLink.text("Read More");
            blogpostbody.append(blogpostLink);
        //append to outerdiv
        outerdiv.append(blogpostbody);
        
        //Create blogpost-footer
            var blogpostfooter = $('<div>', {class: 'card-footer text-muted'});
            blogpostfooter.text("Posted on " + $rawData["Date"] + ", by " + $rawData["Username"]);
            blogpostfooter.append($('<hr>'));
        //append to outerdiv
        outerdiv.append(blogpostfooter);
        
        return outerdiv;
    
    }
    
    //Success function of AJAX request for all blogposts
    function getAllAjaxSuccess(data)
    {
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
        //call upon the pagination function
        pagination();
    }
    
    //shows blogposts on different pages
    function pagination()
    {
        //PAGINATION
        //Get number of blogposts for pagination
        var numberOfPosts = $("#dynamic-blogposts .card").length;
        //Set limit of postsperpage
        var postsPerPageLimit = 4;
        //Pagination (hide posts that aren't on the selected page)
        $("#dynamic-blogposts .card:gt(" + (postsPerPageLimit - 1) + ")").hide();
        //Determine number of pages (round up)
        var numberOfPages = Math.ceil(numberOfPosts / postsPerPageLimit);
        //Add first page to frontend for both pagination menus(insert after second to last page-item)
        $("#paginationtop .page-item").eq(-2).after("<li class='page-item active page-number'><a class='page-link' href='#'>" + 1 + "</a></li>");
        $("#paginationbottom .page-item").eq(-2).after("<li class='page-item active page-number'><a class='page-link' href='#'>" + 1 + "</a></li>");
        //Add other pages dynamically to both pagenavs (if there are any additional pages)
        for(var i = 2;  i <= numberOfPages; i++)
        {
            $("#paginationtop .page-item").eq(-2).after("<li class='page-item page-number'><a class='page-link' href='#'>" + i + "</a></li>");
            $("#paginationbottom .page-item").eq(-2).after("<li class='page-item page-number'><a class='page-link' href='#'>" + i + "</a></li>");
        }
        //Add click-event to page numbers
        $(".pagination li.page-number").on("click", function() {
            //Check if cliked page is already active, if not run code
            if ($(this).hasClass("active") == false)
            {
                //get index of clicked page
                var currentPageNumber = $(this).index();
                //call upon the changepage function
                changePage(currentPageNumber, postsPerPageLimit);
                
            }
        });
        //Add click event to page-link-prev class (=previous page)
        $(".pagination .page-link-prev").on("click", function(){
            //get current active page
            var currentPageNumber = $(".pagination li.active").first().index();
            //only proceed if currentpage is greater than the min number of pages (=1)
            if (currentPageNumber > 1)
            {
                //decrease currentPagenumber
                currentPageNumber--;
                //call upon the changepage function
                changePage(currentPageNumber, postsPerPageLimit);
            }
        });
        //Add click event to page-link-next class (=next page)
        $(".pagination .page-link-next").on("click", function(){
            //get current active page
            var currentPageNumber = $(".pagination li.active").first().index();
            //only proceed if currentpage is smaller than the max number of pages
            if (currentPageNumber < numberOfPages)
            {
                //increment currentPagenumber
                currentPageNumber++;
                //call upon the changepage function
                changePage(currentPageNumber, postsPerPageLimit);
            }
        });
    }
    
    //function to change from one page to another
    function changePage(newPageNumber, postsPerPageLimit)
    {
        //remove active class of current active page
                $(".pagination li").removeClass("active");
                //Add active class to clicked page in both page-navbars
                $("#paginationtop .page-item:eq(" + newPageNumber + ")").addClass("active");
                $("#paginationbottom .page-item:eq(" + newPageNumber + ")").addClass("active");
                //hide items of previous page
                $("#dynamic-blogposts .card").hide();
                //determine the upper bound of the shwon interval of blogposts
                var upperBoundInterval = postsPerPageLimit * newPageNumber;
                //determine the lower bound of the shwon interval of blogposts
                var lowerBoundInterval = upperBoundInterval - postsPerPageLimit;
                //loop over posts that have to be shown on clicked page
                for (var i = lowerBoundInterval; i < upperBoundInterval; i++)
                {
                    //show n-th item
                     $("#dynamic-blogposts .card:eq(" + i + ")").show();
                }
    }
    
});