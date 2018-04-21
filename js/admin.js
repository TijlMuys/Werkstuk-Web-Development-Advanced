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
        success: getAllPostsAjaxSuccess
    });
    
    //Send ajax request to get all categories from server
    $.ajax({
        url: "ajax/categoryLoader.php",
        type: "POST",
        dataType: "json",
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(thrownError);
        },
        success: getAllCategoriesAjaxSuccess
    });
    
    function getAllPostsAjaxSuccess(postdata)
    {
        if(postdata.blogposts) 
        {
            //initialise variable that contains all blogposts
            var newrow;
            //loop over blogposts encapsulate them in a pretty format and add them to webpage
            for (i = 0; i < postdata.blogposts.length; i++) { 
                newrow = formatBlogpostRow(postdata.blogposts[i]);
                $("#post-table").append(newrow);
            } 
            
            adminPagination(4, "paginationpostbar", "postrow", "postsheader");
         }
    }
    
    function getAllCategoriesAjaxSuccess(catdata)
    {
        //get category table
        var catTable = $("#cat-table");
        //iterate over categories
        for (i = 0; i < catdata.length; i++) 
        { 
            //make new category tablerow
            var newcatrow = formatCategoryRow(catdata[i]);
            //append newrow to table
            catTable.append(newcatrow);
        } 
        //pagination
        adminPagination(4, "paginationcatbar", "catrow", "categoriesheader");
    }
    
    function formatCategoryRow(category)
    {
        //Create new tablerow
        var newcatrow = $('<tr>', {class: 'catrow'});
        //Create tabledata for category
        newcatrow.append($('<td>').text(category["CategoryName"]));
        //Create tabledata for remove btn
        var removetdtag = $('<td>');
            //add remove button
            var removebutton = $('<a>', {class: 'btn btn-danger'});
            //add span
            removebutton.html("<span class='text-white'>Delete</span>");
                //add eventlistener
                removebutton.on('click', function(e) {
                //prevent default behaviour
                e.preventDefault();
                //ajax call to remove post
                     $.ajax({
                        url: "ajax/removeCategory.php",
                        type: "POST",
                        dataType: "json",
                        data: {
                            'Id' : category["Id"]
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            console.log(xhr.status);
                            console.log(thrownError);
                        },
                        success: catDeleteSuccess
                    });
                });
            //append
            newcatrow.append(removetdtag.append(removebutton));
        //return newrow
        return newcatrow;
    }
    
    
    function formatBlogpostRow(blogpost)
    {
        //Create new tablerow
        var newrow = $('<tr>', {class: 'postrow'});
        //Create tabledata for id
        newrow.append($('<td>').text(blogpost["Id"]));
        //Create tabledata for Title
        newrow.append($('<td>').text(blogpost["Title"]));
        //Create tabledata for User
        newrow.append($('<td>').text(blogpost["Username"]));
        //Create tabledata for Date
        newrow.append($('<td>').text(blogpost["Date"]));
        //Create tabledata for Category
        newrow.append($('<td>').text(blogpost["CategoryName"]));
        //Create tabledata for Comments
        newrow.append($('<td>').text(blogpost["Comments"].length));
         //Create tabledata for viewlink
        var viewtd = $('<td>');
            //create link to post
            var viewlink = $('<a>', {class: 'btn btn-info'});
            //add href attr
            viewlink.attr("href", "blogpostdetail.php?Id=" + blogpost["Id"]);
            //add span
            viewlink.html("<span class='text-white'>View</span>");
            //append
            newrow.append(viewtd.append(viewlink));
        //Create tabledata for remove btn
        var removetd = $('<td>');
            //add remove button
            var removebtn = $('<a>', {class: 'btn btn-danger post-delete-btn'});
            //add span
            removebtn.html("<span class='text-white'>Delete</span>");
            //add eventlistener
            removebtn.on('click', function(e) {
                //prevent default behaviour
                e.preventDefault();
                //ajax call to remove post
                 $.ajax({
                    url: "ajax/removePost.php",
                    type: "POST",
                    dataType: "json",
                    data: {
                        'Id' : blogpost["Id"]
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr.status);
                        console.log(thrownError);
                    },
                    success: postDeleteSuccess
                });
            });
            //append
           newrow.append(removetd.append(removebtn));
        //return the new row
        return newrow;
    }
    
    function postDeleteSuccess(data)
    {
        if(data.response == "success")
        {
            //reload page
            location.reload();
        }
    }
    
    function  catDeleteSuccess(data)
    {
        if(data.response == "success")
        {
            //reload page
            location.reload();
        }
    }
    
    
    function adminPagination(rowsPerPage, barid, rowclass, header)
    {
        //PAGINATION
        //Get number of blogposts for pagination
        var numberOfPosts = $("."+rowclass+"").length;
        //Pagination (hide posts that aren't on the selected page)
        $("."+rowclass+":gt(" + (rowsPerPage - 1) + ")").hide();
        //Determine number of pages (round up)
        var numberOfPages = Math.ceil(numberOfPosts / rowsPerPage);
        //Add the first (required page) to the pagebar
        $("#"+barid+"").children().eq(-2).after("<li class='page-item active page-number'><a class='page-link' href='#"+header+"'>" + 1 + "</a></li>");
        //Add other pages dynamically to pagebar (if there are any additional pages)
        for(var i = 2;  i <= numberOfPages; i++)
        {
            $("#"+barid+"").children().eq(-2).after("<li class='page-item page-number'><a class='page-link' href='#"+header+"'>" + i + "</a></li>");
        }
        //Add click-event to page numbers
        $("#"+barid+"   li.page-number").on("click", function() {
            //Check if cliked page is already active, if not run code
            if ($(this).hasClass("active") == false)
            {
                //get index of clicked page
                var currentPageNumber = $(this).index();
                //call upon the changepage function
                changePage(currentPageNumber, rowsPerPage, barid, rowclass);

            }
        });
        //Add click event to page-link-prev class (=previous page) ONLY THE FIRST TIME, otherwise a page gets skipped!

        $("#"+barid+" .page-link-prev").on("click", function(){
            //get current active page
            var currentPageNumber = $("#"+barid+"   li.active").first().index();
            //only proceed if currentpage is greater than the min number of pages (=1)
            if (currentPageNumber > 1)
            {
                //decrease currentPagenumber
                currentPageNumber--;
                //call upon the changepage function
                changePage(currentPageNumber, rowsPerPage, barid, rowclass);
            }
        });
        //Add click event to page-link-next class (=next page) ONLY THE FIRST TIME, otherwise a page gets skipped!
        $("#"+barid+" .page-link-next").on("click", function(){
            //recalculate numberOfPages
            var numberOfPosts = $("."+rowclass+"").length;
            var numberOfPages = Math.ceil(numberOfPosts / rowsPerPage);
            //get current active page
            var currentPageNumber = $("#"+barid+"   li.active").first().index();
            //only proceed if currentpage is smaller than the max number of pages
            if (currentPageNumber < numberOfPages)
            {
                //increment currentPagenumber
                currentPageNumber++;
                //call upon the changepage function
                changePage(currentPageNumber, rowsPerPage, barid, rowclass);
            }
        });
    }

    //function to change from one page to another
    function changePage(newPageNumber, rowsPerPage, barid, rowclass)
    {
        //remove active class of current active page
        $("#"+barid+" li").removeClass("active");
        //Add active class to clicked page in pagebar
        $("#"+barid+" .page-item:eq(" + newPageNumber + ")").addClass("active");
        //hide items of previous page
        $("."+rowclass+"").hide();
        //determine the upper bound of the shwon interval of blogposts
        var upperBoundInterval = rowsPerPage * newPageNumber;
        //determine the lower bound of the shwon interval of blogposts
        var lowerBoundInterval = upperBoundInterval - rowsPerPage;
        //loop over posts that have to be shown on clicked page
        for (var i = lowerBoundInterval; i < upperBoundInterval; i++)
        {
            //show n-th item
             $("."+rowclass+":eq(" + i + ")").show();
        }
    }
    
    //Add eventlistener to our category form
    $("#categoryForm").on('submit', function(e){
        //prevent default behaviour
        e.preventDefault();
        //proceed with ajax call if field is valid
        if(validateStringLength($("#CategoryName"), 3, 30))
        {
            //sent ajax request for user validation
            $.ajax({
                //url: $("#catergory-form").attr("action"),
                url: $("#categoryForm").attr("action"),
                type: "POST",
                dataType: "json",
                data: $("#categoryForm").serialize(),
                error: function (xhr, ajaxOptions, thrownError) {
                    //show that something went wrong
                    $("#categoryForm").removeClass("is-valid");
                    $("#categoryForm").addClass('is-invalid');
                    $("#categoryForm .form-control").removeClass("is-valid");
                    $("#categoryForm .hidden").addClass('is-invalid');
                    console.log(xhr.status);
                    console.log(thrownError); 
                },
                success: postCategorySuccess
            });
            
        }
    });
    
    function postCategorySuccess(dataRes)
    {
        if(dataRes.response == "success")
        {
            //reload page
            location.reload();
        }
        else
        {
            //Show that something went wrong
            $("#catergory-form").removeClass("is-valid");
            $("#catergory-form").addClass('is-invalid');
            $("#catergory-form .form-control").removeClass("is-valid");
            $("#catergory-form .hidden").addClass('is-invalid');
        }
       
    }
    
    
});