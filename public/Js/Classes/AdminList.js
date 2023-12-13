class AdminList {
  constructor () {
    this.formList = document.getElementById('formList')
    this.searchString = document.getElementById('searchString')
    this.inputValidation = document.getElementById('validation')
    this.btnDevalidation = document.getElementById('btnDevalidation')
    this.deleteRows = document.getElementsByClassName('btnDeletion')

    //Adding listeners
    this.btnDevalidation.addEventListener('click', this.devalidation.bind(this))

    if (this.deleteRows !== null) {
      const alertLabel = document.getElementById('confirmationModalLabel')
      const alertId = document.getElementById('btnConfirmationModal')
      const message = document.getElementById('confirmationMessage')
      for (let i = 0; i < this.deleteRows.length; i++) {
        this.deleteRows[i].addEventListener('click', function () {
          alertLabel.innerHTML = this.getAttribute('data-action')
          alertId.href = this.getAttribute('data-id')
          message.innerHTML = this.getAttribute('data-message')
        })
      }
    }
  }

  search (event, action) {
    if (this.searchString.value.trim() !== '') {
      this.formList.action = action
    } else {
      event.preventDefault() //Cancel form submission
    }
  }

  /**
   * Set the hidden html input element to 1
   * Then when a submission is performed, the php controller can know if it's a
   * validation or not
   */

  devalidation () {
    if (this.inputValidation !== null) {
      this.inputValidation.setAttribute('value', '0')
    }
  }
}
