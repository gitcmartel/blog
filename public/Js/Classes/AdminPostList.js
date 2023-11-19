class AdminPostList extends AdminList
{
    constructor() {
        super();
        this.btnSearchPost = document.getElementById("btnSearchPost");

        //Adding listener
        this.btnSearchPost.addEventListener("click", (event) => this.search(event, "index.php?action=Admin\\Post\\AdminPostSearch"));
    }
}