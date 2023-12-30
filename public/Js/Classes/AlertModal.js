export default class AlertModal {
  constructor () {
    this.alertLabel = document.getElementById('confirmationModalLabel');
    this.alertId = document.getElementById('btnConfirmationModal');
    this.message = document.getElementById('confirmationMessage');
  }

  setModal (element) {
    this.alertLabel.innerHTML = element.getAttribute('data-action');
    this.alertId.href = element.getAttribute('data-id');
    this.message.innerHTML = element.getAttribute('data-message');
  }
}
