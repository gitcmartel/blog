//Adding the event listener to the search button

btnSearchPost = document.getElementById("btnSearchPost");
btnUnpublish = document.getElementById("btnUnpublish");


btnSearchPost.addEventListener("click", function(event) { 
    adminList = new AdminList();
    adminList.search(event, "index.php?action=Admin\\Post\\AdminPostSearch");
});

btnUnpublish.addEventListener("click", unPublish);



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

