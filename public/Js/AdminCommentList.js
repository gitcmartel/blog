//Adding the event listener to the search button

btnSearchComment = document.getElementById("btnSearchComment");

btnSearchComment.addEventListener("click", function(event) { 
    searchComments(event);
});

/**
 * Changes the form action to trigger a comment search 
 */

function searchComments(event)
{
    let formCommentList = document.getElementById("formCommentList");
    let searchString = document.getElementById("searchString");

    if(formCommentList !== null && searchString !== null){
        if(searchString.value.trim() !== ""){
            formCommentList.action = "index.php?action=Admin\\Comment\\AdminCommentSearch"
        } else {
            event.preventDefault(); //Cancel form submission
        }
    } else {
        event.preventDefault(); //Cancel form submission
    }
}