export default class AlertMessage {
  constructor () {
    this.toastAlert = document.getElementById("toastAlert");
    this.toast = new bootstrap.Toast(toastAlert);
    this.toast.show();
  }
}
