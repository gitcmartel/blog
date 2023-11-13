//Adding the event listener to the search button

btnSearchUser = document.getElementById("btnSearchUser");
btnDevalidation = document.getElementById("btnDevalidation");

btnSearchUser.addEventListener("click", function(event) { 
    adminList = new AdminList();
    adminList.search(event, "index.php?action=Admin\\User\\AdminUserSearch");;
});

btnDevalidation.addEventListener("click", function() { 
    devalidation();
});
