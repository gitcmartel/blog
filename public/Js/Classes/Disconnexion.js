class Disconnexion {
  constructor () {
    this.btnDisconnexion = document.getElementById('btnDisconnexion')

    //Adding listener
    const alertLabel = document.getElementById('confirmationModalLabel')
    const alertId = document.getElementById('btnConfirmationModal')
    const message = document.getElementById('confirmationMessage')

    if (this.btnDisconnexion) {
      this.btnDisconnexion.addEventListener('click', function () {
        alertLabel.innerHTML = this.getAttribute('data-action')
        alertId.href = this.getAttribute('data-id')
        message.innerHTML = this.getAttribute('data-message')
      })
    }
  }
}
