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
        success: function(data) {
            if(data.blogposts) 
            {
               var content;
               for (i = 0; i < data.blogposts.length; i++) { 
                   content = formatBlogpost(data.blogposts[i]);
                   $("#blogposts").append(content);
               }
            }
        }
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
});