//Adding the event listeners

//Comment list
admin = new Admin();
admin.addListener("click", "btnSearchComment", "index.php?action=Admin\\Comment\\AdminCommentSearch", "search");
admin.addListener("click", "btnDevalidation", "", "devalidation");

//Post list
admin.addListener("click", "btnSearchPost", "index.php?action=Admin\\Post\\AdminPostSearch", "search");
admin.addListener("click", "btnDevalidation", "", "devalidation");

//Post
admin.addListener("click", "btnValidation", "", "publish");
admin.addListener("click", "btnResetImage", "", "resetImagePost");
admin.addListener("change", "imagePath", "", "displayImagePost");

//User list
admin.addListener("click", "btnSearchUser", "index.php?action=Admin\\User\\AdminUserSearch", "search");
admin.addListener("click", "btnDevalidation", "", "devalidation");

//User
password = new Password();
password.addListener("click", "passwordChange", "displayPwdFields");
password.addListener("click", "togglePassword", "showPassword");
password.addListener("click", "togglePasswordConfirmation", "showPassword");

window.onload = function() {
    password.displayPwdFields("passwordChange", "grpUserPwd", "grpUserPwdConfirmation");
};


//All lists
let deleteRows = document.getElementsByClassName('rowAction');

if(deleteRows !== null) {
    for (let i = 0; i < deleteRows.length; i++) {
        deleteRows[i].addEventListener('click', function(){
            let alertLabel = document.getElementById('confirmationModalLabel');
            alertLabel.innerHTML = this.getAttribute('data-action');
            let alertId = document.getElementById('btnConfirmationModal');
            alertId.href = this.getAttribute('data-id');
            let message = document.getElementById('confirmationMessage');
            message.innerHTML = this.getAttribute('data-message');
        });
    }
}