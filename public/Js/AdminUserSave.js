function displayPwdFields()
{
    let checkBoxPasswordChange = document.getElementById("passwordChange");
    
    if (checkBoxPasswordChange.checked) {
        document.getElementById('grpUserPwd').classList.remove("d-none");
        document.getElementById('grpUserPwdConfirmation').classList.remove("d-none");
    } else {
        document.getElementById('grpUserPwd').classList.add("d-none");
        document.getElementById('grpUserPwdConfirmation').classList.add("d-none");
    }
}

window.onload = function() {
    displayPwdFields();
};