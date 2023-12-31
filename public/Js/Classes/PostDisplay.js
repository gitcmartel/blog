import AlertModal from "./AlertModal.js";

export default class PostDisplay {
  constructor () {
    this.deleteRows = document.getElementsByClassName("btnDeletion");

    //Modal alert
    this.alertModal = new AlertModal();

    //Adding listeners
    if (this.deleteRows !== null) {
      for (let i = 0; i < this.deleteRows.length; i++) {
        this.deleteRows[i].addEventListener("click", () => {
          this.alertModal.setModal(this.deleteRows[i]);
        })
      }
    }
  }

}
