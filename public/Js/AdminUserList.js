//Adding the event listener to the search button

btnSearchUser = document.getElementById("btnSearchUser");
btnDevalidation = document.getElementById("btnDevalidation");

btnSearchUser.addEventListener("click", function(event) { 
    searchUsers(event);
});

btnDevalidation.addEventListener("click", function() { 
    devalidation();
});


/**
 * Changes the form action to trigger a user search 
 */

function searchUsers(event)
{
    let formUserList = document.getElementById("formUserList");
    let searchString = document.getElementById("searchString");

    if(formUserList !== null && searchString !== null){
        if(searchString.value.trim() !== ""){
            formUserList.action = "index.php?action=Admin\\User\\AdminUserSearch"
        } else {
            event.preventDefault(); //Cancel form submission
        }
    } else {
        event.preventDefault(); //Cancel form submission
    }
}