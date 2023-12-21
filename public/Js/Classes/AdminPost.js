class AdminPost {
  constructor () {
    this.image = document.getElementById("imagePost");
    this.imagePath = document.getElementById("imagePath");
    this.resetImage = document.getElementById("resetImage");
    this.formPost = document.getElementById("formPost");
    this.btnResetImage = document.getElementById("btnResetImage");
    this.validation = document.getElementById("validation");
    this.btnValidation = document.getElementById("btnValidation");
    this.summary = document.getElementById("postSummary");
    this.summaryCharactersCount = document.getElementById("postSummaryCaractersCount");

    //Adding listeners
    this.btnValidation.addEventListener("click", this.publish.bind(this));
    this.btnResetImage.addEventListener("click", this.resetImagePost.bind(this));
    this.imagePath.addEventListener("change", this.displayImagePost.bind(this));
    this.summary.addEventListener("input", this.charactersCount.bind(this));

    //Function call
    this.charactersCount();
  }

  /**
   * Display the selected image post
   */
  displayImagePost (event) {
    // e.files contient un objet FileList
    const [picture] = event.target.files;

    // "picture" est un objet File
    if (picture) {
      // On change l"URL de l"image
      this.image.src = URL.createObjectURL(picture);
    }
  }

  /**
   * Reset the image of a post
   */
  resetImagePost () {
    this.image.src = "img/blog-post.svg";
    this.imagePath.defaultValue = "img/blog-post.svg";
    this.resetImage.defaultValue = "true";
  }

  /**
   * Change the action attribute of the form
   * to re-route the action to the AdminPostPublish Controller
   */
  publish () {
    this.validation.value = true;
  }

  /**
   * Display the number of characters left available
   */
  charactersCount() {
    let maxLength = this.summary.getAttribute('maxlength');
    let currentLength = this.summary.value.length;
    let remainingCharacters = maxLength - currentLength;
    this.summaryCharactersCount.textContent = remainingCharacters + " caractère(s) restant(s) sur " + maxLength + ".";
  }
}
