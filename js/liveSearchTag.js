tagButtons = document.querySelectorAll(".btn-tag");
for (let i = 0; i < tagButtons.length; i++) {
    tagButtons[i].addEventListener('click', function getTag(e) {
        // Getting post id
        let searchText = this.innerHTML;

        /* Post to database
        let formData = new FormData();
        formData.append('search', searchText);

        fetch('ajax/searchTag.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            console.log('Success:', result);
        })
        .catch(error => {
            console.error('Error:', error);
        });*/


        e.preventDefault();
    });
}