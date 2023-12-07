class AlertMessage 
{
    constructor(){
        this.toastAlert = document.getElementById('toastAlert')
        this.toast = new bootstrap.Toast(toastAlert)
        this.toastMessage = document.getElementById("toastMessage");
    }

    /**
     * Show the alert message
     */
    show(message)
    {
        this.toastMessage.innerHTML = message;
        this.toast.show()
    }
}