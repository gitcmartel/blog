class AdminCommentList extends AdminList {
    constructor () {
        super();
        this.btnSearchComment = document.getElementById("btnSearchComment");

        //Adding listeners
        this.btnSearchComment.addEventListener("click", (event) => this.search(event, "index.php?action=Admin\\Comment\\AdminCommentSearch"));
        
    }
}