var searchBar = document.querySelector("#searchBar");
var searchQry;

searchBar.addEventListener("keydown", function(e){
    searchQry = this.value;
})

searchBar.addEventListener("keypress", function(e){
    if (e.key === 'Enter') {
        alert(searchQry);
        e.preventDefault();
      }
})