var searchBar = document.querySelector("#search");
var searchQry;

searchBar.addEventListener("keydown", function(e){
    searchQry = this.value;
})

searchBar.addEventListener("keypress", function(e){
    if (e.key === 'Enter') {
        
      }
})