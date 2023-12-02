class AdminPost {
    constructor () {
        this.image = document.getElementById("imagePost");
        this.imagePath = document.getElementById("imagePath");
        this.resetImage = document.getElementById("resetImage");
        this.formPost = document.getElementById("formPost");
        this.btnResetImage = document.getElementById("btnResetImage");
        this.btnValidation = document.getElementById("btnValidation");

        //Adding listeners
        this.btnValidation.addEventListener("click", this.publish.bind(this));
        this.btnResetImage.addEventListener("click", this.resetImagePost.bind(this));
        this.imagePath.addEventListener("change", this.displayImagePost.bind(this));
    }

    /**
     * Display the selected image post
     */
    displayImagePost(event)
    {           
        // e.files contient un objet FileList
        const [picture] = event.target.files

        // "picture" est un objet File
        if (picture) {
            // On change l'URL de l'image
            this.image.src = URL.createObjectURL(picture)
        }
    }

    /**
     * Reset the image of a post
     */
    resetImagePost()
    {
        this.image.src = "img/blog-post.svg";
        this.imagePath.defaultValue = "img/blog-post.svg";
        this.resetImage.defaultValue = "true";
    }

    /**
     * Change the action attribute of the form
     * to re-route the action to the AdminPostPublish Controller
     */
    publish()
    {
        this.formPost.action = "index.php?action=Admin\\Post\\AdminPostPublish";
    }
}