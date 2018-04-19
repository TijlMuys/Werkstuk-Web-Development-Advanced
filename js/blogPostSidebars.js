function blogPostDetailSidebar(currentPost) {
    //Send ajax request to get all posts from server
    $.ajax({
        url: "ajax/blogpostsLoader.php",
        type: "POST",
        dataType: "json",
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(thrownError);
        },
        success: getAllAjaxDetailSuccess
    });
    
    //Success function of AJAX request for all data
    function getAllAjaxDetailSuccess(data)
    {
        console.log(data);
        //call upon the sidebar function
        detailSidebar(data, currentPost);
    }
}

function overviewSidebar(data)
{
    //call generatePopularWidget function
    generatePopularWidget(data);
    //call generateCategoriesWidget function
    generateCategoriesWidget(data, false);
    //call generateArchiveWidget function
    generateArchiveWidget(data, false);
}

function detailSidebar(data, currentPost)
{
    //call generatePopularWidget function
    generateFeaturedWidget(data, currentPost);
    //call generateCategoriesWidget function
    generateCategoriesWidget(data, true);
    //call generateArchiveWidget function
    generateArchiveWidget(data, true);

}

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
        newListItemLink.attr("href", "blogpostdetail.php?Id=" + top3Posts[i]["Id"]);
        //apend listitemlink to listitem
        newListitem.append(newListItemLink);
        //append listitem to popularList
        popularList.append(newListitem);
    }
}

function generateFeaturedWidget(data, currentPost)
{
    console.log("Relevant");
    console.log(data);
    //initialize variables
    var currentCategory = currentPost["CategoryName"];
    var currentId = currentPost["Id"];
    //Filter on the category
    var filteredData = {blogposts: new Array()};
    //iterate over data
    for (var i = 0; i < data.blogposts.length; i++) 
    { 
        //test if the post on index i is in the right category and not the current post itself
        if(data.blogposts[i]["CategoryName"] == currentCategory && data.blogposts[i]["Id"] != currentId)
        {
            filteredData.blogposts.push(data.blogposts[i]);
            
        }
    
    }
    console.log(filteredData);
    //Call function to get information of popular posts
    var top3Posts = getInfoPopularPosts(filteredData);
    console.log(top3Posts);
    //get popular-list element of sidebar
    var featuredList = $(".featured-list");
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
        newListItemLink.attr("href", "blogpostdetail.php?Id=" + top3Posts[i]["Id"]);
        //apend listitemlink to listitem
        newListitem.append(newListItemLink);
        //append listitem to featuredList
        featuredList.append(newListitem); 
        
    }
}

function getInfoPopularPosts(data)
{
    //normally three posts will be shown
    var numberofEntries = 3;
    //check if there are at least three posts
    if(data.blogposts.length < 3)
    {
        numberofEntries = data.blogposts.length;
    }
    //Determine 3 most popular posts
    //First initiate an array that will contains blogIds with their respective comments
    var postsWithCommentData = new Array();
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
    var top3Posts = new Array();
    //Iterate over top 3 of postsdata
    for (i = 0; i < numberofEntries; i++)
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
function generateCategoriesWidget(data, isDetail)
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

    //Add 'all' filter to (re)show all posts
        //make new listitem
        var newListitem = $("<li>");
        //make link to add to Listitem
        var newListItemLink = $("<a href=''>");
        //settext of listitemlink
        newListItemLink.text("All");
        //add new class to listitemlink
        newListItemLink.addClass("categoryLink");
        //add eventListener to link
        newListItemLink.on("click", function(e){
            //stop default behaviour
            e.preventDefault();
            //get category to filter on
            var currentCategory = $(this).text();
            //check if we are on the blogposts overview page or in a detailed view
            if(isDetail == false)
            {
                console.log("catclick");
                //if we are on the overview page call upon the filterOnCategory function
                filterOnCategory(currentCategory);
            }
            else
            {
                //if we are on the detail page we need to redirect to the overview page with the right parameters
                window.location.replace("blogposts.php?catfilter=All");
            }
            
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
        var newListItemLink = $("<a href=''>");
        //settext of listitemlink
        newListItemLink.text(currentCategory);
        //add new class to listitemlink
        newListItemLink.addClass("categoryLink");
        //add eventListener to link
        newListItemLink.on("click", function(e){
            //stop default behaviour
            e.preventDefault();
            //get category to filter on
            var currentCategory = $(this).text();
            if(isDetail == false)
            {
                console.log("catclick");
                //if we are on the overview page call upon the filterOnCategory function
                filterOnCategory(currentCategory);
            }
            else
            {
                //if we are on the detail page we need to redirect to the overview page with the right parameters
                window.location.replace("blogposts.php?catfilter="+currentCategory+"");
                
            }
        });
        //apend listitemlink to listitem
        newListitem.append(newListItemLink);
        //append listitem to categoryList
        categoryList.append(newListitem);
    }

}

//Adds Archive for years and or months with at least 1 correspoding post to the sidebar
function generateArchiveWidget(data, isDetail)
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
         //Modify string for safari browser
         var currentYearMonthString = currentPost["Date"].substr(0, currentPost["Date"].indexOf(" "));
         var currentYearMonth = new Date(currentYearMonthString);
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
         var newListItemLink = $("<a href=''>");
         //settext of listitemlink by using the getFullYear and getMonth methods and our custom getMonthName function
         newListItemLink.text(archiveListingsArray[i].getFullYear() + " - " + getMonthName(archiveListingsArray[i].getMonth()));
         //add new class to listitemlink
         newListItemLink.addClass("archiveLink");
         //add eventListener to link
         newListItemLink.on("click", function(e){
             //stop default behaviour
             e.preventDefault();
             //get category to filter on
             var currentYearMonth = $(this).text();
             console.log(currentYearMonth);
             //check if we are on the blogposts overview page or in a detailed view
            if(isDetail == false)
            {
                //if we are on the overview page call upon the filterOnCategory function
                //call upon the filterOnCategory function
                filterOnDate(currentYearMonth);
            }
            else
            {
                var encodeparam = encodeURIComponent(currentYearMonth.trim());
                //if we are on the detail page we need to redirect to the overview page with the right parameters (without spaces)
                window.location.replace("blogposts.php?datefilter="+encodeparam+"");
            }
         });
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