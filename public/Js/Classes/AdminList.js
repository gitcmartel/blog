class AdminList
{
    constructor() {
        this.formList = document.getElementById("formList");
        this.searchString = document.getElementById("searchString");
    }

    search(event, action) {
        if(this.searchString.value.trim() !== ""){
            this.formList.action = action;
        } else {
            event.preventDefault(); //Cancel form submission
        }
    }
}