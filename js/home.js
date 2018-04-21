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
        //Check if there is any data received
        if(data.blogposts) 
        {
            generatePopularPosts(data);
        }
    }
    
    //Generates popular section on homepage
    function generatePopularPosts(data)
    {
        //get current date
        var currentDate = new Date();
        //initialize arrays
        var usedArray = [];
        var allIds = [];
        //Call function to get information of popular posts
        var top3Posts = getInfoPopularPosts(data);
        //get divcontainer for popular posts on homepage
        var popularContainer = $("#popularContainer");
        var featuredContainer = $("#featuredContainer");
        //iterate over top3postinfo
        for (i = 0; i < top3Posts.length; i++) 
        {
            //iterate over all posts
            for (j = 0; j < data.blogposts.length; j++)
            {
                //proceed to add popular item if ids match
                if(top3Posts[i]["Id"] == data.blogposts[j]["Id"])
                {
                    //generate popular listitem
                    addlistItem(data, popularContainer, j);
                    //add id to used array
                    usedArray.push(data.blogposts[j]["Id"]);
                }
                //get date of currentpost
                var postDate = new Date(data.blogposts[j]["Date"]);
                //add id to allids array if not already in there and if the post is of the current month and year
                if(allIds.indexOf(data.blogposts[j]["Id"]) == -1 && postDate.getYear() == currentDate.getYear() && postDate.getMonth() == currentDate.getMonth())
                {
                    allIds.push(data.blogposts[j]["Id"]);
                }
                
            }
        }
        var featuredCount = 0;
        var numberofEntries = 3;
        //check if there are enough posts to feature articles
        if(data.blogposts.length < 6)
        {
            numberofEntries = data.blogposts.length - 3;
            if(numberofEntries < 0)
            {
                numberofEntries = 0;
            }
        }
        
        //keep going until featuredCount reaches numberofEntries
        while(featuredCount < numberofEntries)
        {
            //generate next random number
            var currentRandomKey = Math.floor(Math.random() * allIds.length);
            //get id-value of randomkey
            var currentRandomId = allIds[currentRandomKey];
            //check if currentRandomId is not already in the popular section (usedArray), only proceed when this is the case
            if(usedArray.indexOf(currentRandomId) == -1)
            {
                    //Add featured article
                    addlistItem(data, featuredContainer, currentRandomKey);
                    //add id to used list
                    usedArray.push(data.blogposts[currentRandomKey]["Id"]);
                    //increment featuredcount
                    featuredCount++;
            }
            
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
    
    
    
     //Converts a raw BlogPost into a pretty viewable format for the frontend
    function addlistItem(data, container, id)
    {
        //make new listitem
        var newListitem = $('<div>', {class: 'col-md-4'});
        //add tooltip
        newListitem.attr('title', "This post has " + data.blogposts[id]["Comments"].length + " comments");
        //add eventlistenr for clicking
        newListitem.on("click", function() {
            window.location.href= "blogpostdetail.php?Id=" + data.blogposts[id]["Id"];
        });
        //make div with background
        var newListItemBg = $('<div>', {class: 'pop-home-div'});
        //change url of image
        newListItemBg.css({"background-image": "url(" + data.blogposts[id]["ImageUrl"] + ")"});
        //make new heading of listitem
        var newListItemHead = $('<h3>', {class: 'text-secondary'});
        newListItemHead.text(data.blogposts[id]["Title"]);
        //add new paragraph
        var newListitemPara = $('<p>', {class: 'list-item-para'});
        //set text of paragraph (first 30 words)
        newListitemPara.text(data.blogposts[id]["Content"].split(' ').slice(0,30).join(' ') + " ...");
        //add button
        var newListItembtn = $('<p>', {class: 'list-item-btn'});
        //add link to button
        var newListItemAnchor = $('<a>', {class: 'btn btn-secondary'});
        newListItemAnchor.attr("href", "blogpostdetail.php?Id=" + data.blogposts[id]['Id'] + "");
        newListItemAnchor.attr("role", "button");
        newListItemAnchor.text("View details");

        //apend everything together
        newListitem.append(newListItemBg);
        newListitem.append(newListItemHead);
        newListitem.append(newListitemPara);
        newListItembtn.append(newListItemAnchor);
        newListitem.append(newListItembtn);

        //add to container
        container.append(newListitem);
    
    }
    
   
    
});