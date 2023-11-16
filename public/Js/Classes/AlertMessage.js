class AlertMessage 
{
    constructor(){
        this.toastLiveExample = document.getElementById('toastAlert')
        this.toast = new bootstrap.Toast(toastLiveExample)
        this.toastMessage = document.getElementById("toastMessage");
    }

    /**
     * Show the alert message
     */
    showAlertMessage(message)
    {
        this.toastMessage.innerHTML = message;
        this.toast.show()
    }
}