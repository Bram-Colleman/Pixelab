// Onload
document.addEventListener('load', function () {
    let reportButtons = document.querySelectorAll(".btn-report");
})

reportButtons = document.querySelectorAll(".btn-report");
for (let i = 0; i < reportButtons.length; i++) {
    reportButtons[i].addEventListener('click', function report(e) {
        // Getting post id
        let postId = this.dataset.postid;
        
        // Post to database
        let formData = new FormData();
        formData.append('postId', postId);

        fetch('ajax/reportPost.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            console.log('Success:', result);
            this.innerHTML = "Reported";
        })
        .catch(error => {
            console.error('Error:', error);
        });


        e.preventDefault();
    });
}