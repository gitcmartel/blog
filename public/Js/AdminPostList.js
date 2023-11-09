//Adding the event listener to the search button

btnSearchPost = document.getElementById("btnSearchPost");
btnUnpublish = document.getElementById("btnUnpublish");

btnSearchPost.addEventListener("click", function(event) { 
    searchPosts(event);
});

btnUnpublish.addEventListener("click", function() { 
    unPublish();
});


/**
 * Changes the form action to trigger a post search 
 */

function searchPosts(event)
{
    let formPostList = document.getElementById("formPostList");
    let searchString = document.getElementById("searchString");

    if(formPostList !== null && searchString !== null){
        if(searchString.value.trim() !== ""){
            formPostList.action = "index.php?action=Admin\\Post\\AdminPostSearch"
        } else {
            event.preventDefault(); //Cancel form submission
        }
    } else {
        event.preventDefault(); //Cancel form submission
    }
}

/**
 * Set the hidden html input element to 1
 * Then when a sumbission is performed, the php controller can know if it's a
 * publication or not
 */
function unPublish()
{
    let inputUnpublish = document.getElementById("unpublish");
    if(inputUnpublish !== null){
        inputUnpublish.setAttribute('value', 'true');
    }
}

