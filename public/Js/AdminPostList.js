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
 * Change the action attribute of the form
 * to re-route the action to the AdminPostPublish Controller
 */

function publish(event)
{
    let formPost = document.getElementById("formPost");

    if(formPost !== null){
        formPost.action = "index.php?action=Admin\\Post\\AdminPostPublish"
    } else {
        event.preventDefault(); //Cancel form submission
    }
}

