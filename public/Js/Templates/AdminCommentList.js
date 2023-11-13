//Adding the event listener to the search button

btnSearchComment = document.getElementById("btnSearchComment");
btnDevalidation = document.getElementById("btnDevalidation");

btnSearchComment.addEventListener("click", function(event){
    adminList = new AdminList();
    adminList.search(event, "index.php?action=Admin\\Comment\\AdminCommentSearch");
});

btnDevalidation.addEventListener("click", devalidation);
