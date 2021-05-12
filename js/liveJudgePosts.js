keepPostButtons = document.querySelectorAll(".btn-keepPost");
deletePostButtons = document.querySelectorAll(".btn-deletePost");

for (let i = 0; i < keepPostButtons.length; i++) {
    keepPostButtons[i].addEventListener('click', (e) => {
        // Getting post id
        let postId = e.path[3].attributes[1].nodeValue;

        // Hide post
        e.path[3].style.display = "none";
        
        // Post to database
        let formData = new FormData();
        formData.append('postId', postId);
        formData.append('keepPost', 1);

        
        fetch('ajax/judgePost.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            console.log('Success:', result);
        })
        .catch(error => {
            console.error('Error:', error);
        });
        console.log(e.path[3]);

        e.preventDefault();
    });
}

for (let i = 0; i < deletePostButtons.length; i++) {
    deletePostButtons[i].addEventListener('click', (e) => {
        
        // Getting post id
        let postId = e.path[3].attributes[1].nodeValue;
        console.log("deleted");

        // Hide post
        e.path[3].style.display = "none";
        
        // Post to database
        let formData = new FormData();
        formData.append('postId', postId);
        formData.append('keepPost', 0);

        fetch('ajax/judgePost.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            console.log('Success:', result);
        })
        .catch(error => {
            console.error('Error:', error);
        });


        e.preventDefault();
    });
}