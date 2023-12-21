import AlertModal from './AlertModal';

class AdminList {
  constructor () {
    this.formList = document.getElementById("formList");
    this.searchString = document.getElementById("searchString");
    this.inputValidation = document.getElementById("validation");
    this.btnDevalidation = document.getElementById("btnDevalidation");
    this.deleteRows = document.getElementsByClassName("btnDeletion");

    //Modal alert
    this.alertModal = new AlertModal();

    //Adding listeners
    this.btnDevalidation.addEventListener("click", this.devalidation.bind(this));

    if (this.deleteRows !== null) {
      for (let i = 0; i < this.deleteRows.length; i++) {
        this.deleteRows[i].addEventListener("click", () => {
          this.alertModal.setModal(this.deleteRows[i]);
        })
      }
    }
  }

  search (event, action) {
    if (this.searchString.value.trim() !== "") {
      this.formList.action = action;
    } else {
      event.preventDefault(); //Cancel form submission
    }
  }

  /**
   * Set the hidden html input element to 1
   * Then when a submission is performed, the php controller can know if it"s a
   * validation or not
   */

  devalidation () {
    if (this.inputValidation !== null) {
      this.inputValidation.setAttribute("value", "0");
    }
  }
}
