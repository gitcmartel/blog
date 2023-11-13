

/**
 * Set the hidden html input element to 1
 * Then when a sumbission is performed, the php controller can know if it's a
 * validation or not
 */

function devalidation()
{
    let inputValidation = document.getElementById("validation");
    if(inputValidation !== null){
        inputValidation.setAttribute('value', '0');
    }
}

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


/**
 * Show the alert message
 */

function showAlertMessage(message)
{
    var toastLiveExample = document.getElementById('toastAlert')
    var toast = new bootstrap.Toast(toastLiveExample)
    var toastMessage = document.getElementById("toastMessage");

    toastMessage.innerHTML = message;
    toast.show()
}