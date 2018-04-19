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
    pagination(false);
}

//filteronDate
function filterOnDate(YearMonthString)
{
    //reset pagination -navbars
    resetPageNavBars();
    //select all blogposts that are not part of this date
    var redundantBlogposts = $("#dynamic-blogposts .card:not([yeardate='" + YearMonthString + "'])");
    //set attribute isFiltered on false for these (now) redundant blogposts, so they won't get picked up by the pagination function anymore
    redundantBlogposts.attr("isFiltered", false);
    //hide the redundant blogposts
    redundantBlogposts.hide();

    //select all blogposts that are part of this date
     var desiredBlogposts = $("#dynamic-blogposts .card[yeardate='" + YearMonthString + "']");
    //set attribute isFiltered on true for the posts of the date
    desiredBlogposts.attr("isFiltered", true);
    //show the desired blogposts
    desiredBlogposts.show();

    //set subtitle of page
    $("#h1 small").text(YearMonthString);

    //rerun the pagination function (not first time)
    pagination(false);
}

//shows blogposts on different pages
function pagination(isFirst)
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
    //Add click event to page-link-prev class (=previous page) ONLY THE FIRST TIME, otherwise a page gets skipped!
    if(isFirst == true)
    {
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
        //Add click event to page-link-next class (=next page) ONLY THE FIRST TIME, otherwise a page gets skipped!
        $(".pagination .page-link-next").on("click", function(){
            //recalculate numberOfPages
            var numberOfPosts = $("#dynamic-blogposts .card[isFiltered=true]").length;
            var numberOfPages = Math.ceil(numberOfPosts / 4);
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

//reset pagenavbars
function resetPageNavBars()
{
    //remove all pagenumers in the current navbars
    $("#paginationtop .page-number").remove();
    $("#paginationbottom .page-number").remove();
}