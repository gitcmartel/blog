import AlertModal from "./AlertModal.js";

export default class Disconnexion {
  constructor () {
    this.btnDisconnexion = document.getElementById("btnDisconnexion");

    //Modal alert
    this.alertModal = new AlertModal();

    //Adding listener

    if (this.btnDisconnexion) {
      this.btnDisconnexion.addEventListener("click", () => {
        this.alertModal.setModal(this.btnDisconnexion);
      });
    }
  }
}
