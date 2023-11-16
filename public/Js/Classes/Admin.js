class Admin
{
    constructor() {
        this.formList = document.getElementById("formList");
        this.searchString = document.getElementById("searchString");
        this.inputValidation = document.getElementById("validation");
        this.image = document.getElementById("imagePost");
        this.imagePath = document.getElementById("imagePath");
        this.resetImage = document.getElementById("resetImage");
        this.formPost = document.getElementById("formPost");
    }

    search(event, action) {
        if(this.searchString.value.trim() !== ""){
            this.formList.action = action;
        } else {
            event.preventDefault(); //Cancel form submission
        }
    }

    /**
     * Set the hidden html input element to 1
     * Then when a sumbission is performed, the php controller can know if it's a
     * validation or not
     */

    devalidation() {
        if(this.inputValidation !== null){
            this.inputValidation.setAttribute('value', '0');
        }
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
    publish(event)
    {
        if(this.formPost !== null){
            this.formPost.action = "index.php?action=Admin\\Post\\AdminPostPublish"
        } else {
            event.preventDefault(); //Cancel form submission
        }
    }
    
    addListener(eventToAdd, elementId, controller, functionName) {
        let listenerELement = document.getElementById(elementId);
        if(listenerELement !== null){
            listenerELement.addEventListener(eventToAdd, (event) => {
                this[functionName](event, controller);
            })
        }
    }
}