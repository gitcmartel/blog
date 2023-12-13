class AdminUserList extends AdminList {
  constructor () {
    super()
    this.btnSearchUser = document.getElementById('btnSearchUser')

    //Adding listeners
    this.btnSearchUser.addEventListener('click', event =>
      this.search(event, 'index.php?action=Admin\\User\\AdminUserSearch')
    )
  }
}
