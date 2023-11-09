//Adding the event listener to the search button

btnPublish = document.getElementById("btnPublish");
btnResetImage = document.getElementById("btnResetImage");
imagePath = document.getElementById("imagePath");

btnPublish.addEventListener("click", function(event) { 
    publish(event);
});

btnResetImage.addEventListener("click", function() { 
    resetImagePost();
});

btnResetImage.addEventListener("change", function(event) { 
    displayImagePost(event);
});


/**
 * Display the selected image post
 */

function displayImagePost(e)
{           
    var image = document.getElementById("imagePost");

        // e.files contient un objet FileList
        const [picture] = e.files

        // "picture" est un objet File
        if (picture) {
            // On change l'URL de l'image
            image.src = URL.createObjectURL(picture)
        }
}

/**
 * Reset the image of a post
 */

function resetImagePost()
{
    let image = document.getElementById("imagePost");
    let imagePath = document.getElementById("imagePath");
    let resetImage = document.getElementById("resetImage");

    image.src = "img/blog-post.svg";
    imagePath.defaultValue = "img/blog-post.svg";
    resetImage.defaultValue = "true";
}