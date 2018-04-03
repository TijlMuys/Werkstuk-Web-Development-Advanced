$(document).ready(function () {
    //Send ajax request to get all posts from server
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
    
    //Success function of AJAX request for all blogposts
    function getAllAjaxSuccess(data)
    {
        //call upon the sidebar function
        sidebar(data);
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
        
        //test
        //filterOnCategory("Food");
        
    }
    
     function sidebar(data)
    {
        //call position sidebar function
        positionSidebar();
        //call generatePopularWidget function
        generatePopularWidget(data);
        //call generateCategoriesWidget function
        generateCategoriesWidget(data);
        //call generateArchiveWidget function
        generateArchiveWidget(data);
        
    }
    
     function positionSidebar()
    {
         //Sticky sidebar while scrolling when screen is large enough
        if ($('body').first().innerWidth() > 750) 
        {
            $('#affix').addClass("affix");
        } 
        //If screen is small sidebar moves to bottom (=no sticky sidebar)
        else 
        {
            $('#sidebar').removeClass("affix");
        }
    }
    
    //Generates popular section in sidebar
    function generatePopularWidget(data)
    {
        //Call function to get information of popular posts
        var top3Posts = getInfoPopularPosts(data);
        //get popular-list element of sidebar
        var popularList = $(".popular-list");
        //iterate over top3postinfo
        for (i = 0; i < top3Posts.length; i++) 
        {
            //make new listitem
            var newListitem = $("<li>");
            //make link to add to Listitem
            var newListItemLink = $("<a>");
            //settext of listitemlink
            newListItemLink.text(top3Posts[i]["Title"] + " (" + top3Posts[i]["numComments"] + ")");
            //add href attribute to listitemlink
            newListItemLink.attr("href", "" + "#" + "");
            //apend listitemlink to listitem
            newListitem.append(newListItemLink);
            //append listitem to popularList
            popularList.append(newListitem);
        }
    }
    
    function getInfoPopularPosts(data)
    {
        //Determine 3 most popular posts
        //First initiate an array that will contains blogIds with their respective comments
        var postsWithCommentData = new Array(data.blogposts.length);
        //Iterate over all blogposts
        for (i = 0; i < data.blogposts.length; i++) { 
                var currentPost = data.blogposts[i];
                //Count the amount of comments in currentPost
                var currentCommentNumber = currentPost['Comments'].length;
                //Add the commentNumber as key to the array with value the index of the post
                postsWithCommentData[i] = (currentCommentNumber + "´" + currentPost['Id'] + "`" + currentPost['Title']);
                
        }
        //Sort the array on its values and reverse array
        postsWithCommentData.sort();
        postsWithCommentData.reverse();
        //get top 3 entries in array
        var top3PostsData = postsWithCommentData.slice(0, 3);
        //Initiate an array that will contain a string with the top 3 posts' Titles
        var top3Posts = new Array(3);
        //Iterate over top 3 of postsdata
        for (i = 0; i < 3; i++)
        {
            var numberOfComments = top3PostsData[i].slice(0, top3PostsData[i].indexOf('´'));
            var blogId = top3PostsData[i].slice(top3PostsData[i].indexOf('´')+1, top3PostsData[i].indexOf('`'));
            var Title = top3PostsData[i].slice(top3PostsData[i].indexOf('`')+1, top3PostsData[i].length);
            //add info to top3posts array
            top3Posts[i] = {'numComments': numberOfComments, 'Id': blogId, 'Title': Title};
        }
        //return array with information of top 3 most popular blogposts
        return top3Posts;
    }
    
    //Adds categories with at least 1 correspoding post to the sidebar
    function generateCategoriesWidget(data)
    {
        //get category-list element of sidebar
        var categoryList = $(".categories-list");
        //initiate empty array to hold all unique categories
        var categoriesArray = new Array();
        //iterate over all data
         for (i = 0; i < data.blogposts.length; i++) 
         { 
             var currentPost = data.blogposts[i];
             //get category of current post
             var currentCategory = currentPost["CategoryName"];
             
             //Check if category is already in array, if not (index=-1) add to array and to frontend
             if(categoriesArray.indexOf(currentCategory) == -1)
             {
                 //push new category to array
                 categoriesArray.push(currentCategory);
                 
             }  
        }
        //sort categories
        categoriesArray.sort();
        
        //Add all filter to (re)show all posts
            //make new listitem
            var newListitem = $("<li>");
            //make link to add to Listitem
            var newListItemLink = $("<a>");
            //settext of listitemlink
            newListItemLink.text("All");
            //add new class to listitemlink
            newListItemLink.addClass("categoryLink");
            //add eventListener to link
            newListItemLink.on("click", function(){
                //get category to filter on
                var currentCategory = $(this).text();
                //call upon the filterOnCategory function
                filterOnCategory(currentCategory);
            });
            //apend listitemlink to listitem
            newListitem.append(newListItemLink);
            //append listitem to categoryList
            categoryList.append(newListitem);
        
        //iterate over every unique category
        for (i = 0; i < categoriesArray.length; i++) 
        {
            //Get current Category in array
             var currentCategory = categoriesArray[i];
            //FRONTEND
            //make new listitem
            var newListitem = $("<li>");
            //make link to add to Listitem
            var newListItemLink = $("<a>");
            //settext of listitemlink
            newListItemLink.text(currentCategory);
            //add new class to listitemlink
            newListItemLink.addClass("categoryLink");
            //add eventListener to link
            newListItemLink.on("click", function(){
                //get category to filter on
                var currentCategory = $(this).text();
                //call upon the filterOnCategory function
                filterOnCategory(currentCategory);
            });
            //apend listitemlink to listitem
            newListitem.append(newListItemLink);
            //append listitem to categoryList
            categoryList.append(newListitem);
        }
        
    }
    
    //Adds Archive for years and or months with at least 1 correspoding post to the sidebar
    function generateArchiveWidget(data)
    {
        //get archive-list element of sidebar
        var archiveList = $(".archive-list");
        //initiate empty array to hold all unique archive listings
        var archiveListingsArray = new Array();
        //initiate empty array to hold all unique archive listings in string format
        var archiveListingsStringArray = new Array();
        //iterate over all data
         for (i = 0; i < data.blogposts.length; i++) 
         { 
             var currentPost = data.blogposts[i];
             //get Date of current post
             var currentYearMonth = new Date(currentPost["Date"]);
             //set miliseconds, seconds, minutes, hours and day on zero
             currentYearMonth.setMilliseconds(0);
             currentYearMonth.setSeconds(0);
             currentYearMonth.setMinutes(0);
             currentYearMonth.setHours(0);
             //static day for all days in the same month, opted for the 2nd day, in order to circumvent potention problems with the International Date Line and Time zones
             currentYearMonth.setDate(2);
             //Check if currentYearMonth is already in array, if not (index=-1) add to both arrays
             if(archiveListingsStringArray.indexOf(currentYearMonth.toString()) == -1)
             {
                 archiveListingsArray.push(currentYearMonth);
                 archiveListingsStringArray.push(currentYearMonth.toString());
             }

        }
        //sort array with dates -> annonymous function that compares timespans
        archiveListingsArray.sort(function(a,b){return a - b;});
        
        
        //Iterate over sorted archiveListingsArray to display on frontend
        for (i = 0; i < archiveListingsArray.length; i++) 
        {
             //FRONTEND
             //make new listitem
             var newListitem = $("<li>");
             //make link to add to Listitem
             var newListItemLink = $("<a>");
             //settext of listitemlink by using the getFullYear and getMonth methods and our custom getMonthName function
             newListItemLink.text(archiveListingsArray[i].getFullYear() + " - " + getMonthName(archiveListingsArray[i].getMonth()));
             //add new class to listitemlink
             newListItemLink.addClass("archiveLink");
             //apend listitemlink to listitem
             newListitem.append(newListItemLink);
             //append listitem to archiveList
             archiveList.append(newListitem);
        }
    }
    
    //Function that converts month number to full Month name
    function getMonthName(monthNumber)
    {
        //check if valid monthnumber
        if(monthNumber > 0 && monthNumber < 13)
        {
            //make Array with month names
            var monthArray = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
            //get right name from array
            var monthName = monthArray[monthNumber-1];
            //return right name
            return monthName;
        }
        else
        {
            return false;
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
        //get Date object from rawdata
        var currentDate = new Date($rawData["Date"]);
        //Add yearDate-string (using function to convert monthnumber to full name)
        outerdiv.attr("yearDate", (currentDate.getFullYear() + " - " + getMonthName(currentDate.getMonth())));
        //add isFiltered attribute (default true)
        outerdiv.attr("isFiltered", true);
        
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
    
    //shows blogposts on different pages
    function pagination()
    {
        //PAGINATION
        //Get number of blogposts for pagination
        var numberOfPosts = $("#dynamic-blogposts .card[isFiltered=true]").length;
        //Set limit of postsperpage
        var postsPerPageLimit = 4;
        //Pagination (hide posts that aren't on the selected page)
        $("#dynamic-blogposts .card[isFiltered=true]:gt(" + (postsPerPageLimit - 1) + ")").hide();
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
            if (currentPageNumber < numberOfPages && currentPageNumber != 1)
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
                $("#dynamic-blogposts .card[isFiltered=true]").hide();
                //determine the upper bound of the shwon interval of blogposts
                var upperBoundInterval = postsPerPageLimit * newPageNumber;
                //determine the lower bound of the shwon interval of blogposts
                var lowerBoundInterval = upperBoundInterval - postsPerPageLimit;
                //loop over posts that have to be shown on clicked page
                for (var i = lowerBoundInterval; i < upperBoundInterval; i++)
                {
                    //show n-th item
                     $("#dynamic-blogposts .card[isFiltered=true]:eq(" + i + ")").show();
                }
    }
    
    function resetPageNavBars()
    {
        //remove all pagenumers in the current navbars
        $("#paginationtop .page-number").remove();
        $("#paginationbottom .page-number").remove();
    }
    
    //FILTERING
    function filterOnCategory(categoryName)
    {
        //reset pagination -navbars
        resetPageNavBars();
        //check if category if not all
        if(categoryName != "All")
        {
            

            //select all blogposts that are not part of this category
            var redundantBlogposts = $("#dynamic-blogposts .card:not([category=" + categoryName + "])");
            //set attribute isFiltered on false for these (now) redundant blogposts, so they won't get picked up by the pagination function anymore
            redundantBlogposts.attr("isFiltered", false);
            //hide the redundant blogposts
            redundantBlogposts.hide();

            //select all blogposts that are part of this category
             var desiredBlogposts = $("#dynamic-blogposts .card[category=" + categoryName + "]");
            //set attribute isFiltered on true for the posts of the category
            desiredBlogposts.attr("isFiltered", true);
            //show the desired blogposts
            desiredBlogposts.show();

            //set subtitle of page
            $("#h1 small").text(categoryName);

           
        }
        else
        {
            //If category is all we will select all posts set isActive on true and show them again
            //select all blogposts
             var desiredBlogposts = $("#dynamic-blogposts .card");
            //set attribute isFiltered on true for all posts
            desiredBlogposts.attr("isFiltered", true);
            //show the all blogposts
            desiredBlogposts.show();
            
             //reset subtitle of page
            $("#h1 small").text("All Posts");
        }
        
        //rerun the pagination function
        pagination();
        
    }
    
});