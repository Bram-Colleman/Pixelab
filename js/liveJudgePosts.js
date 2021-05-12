keepPostButtons = document.querySelectorAll(".btn-keepPost");
deletePostButtons = document.querySelectorAll(".btn-deletePost");

judgePost(keepPostButtons, 1);
judgePost(deletePostButtons, 0);

function judgePost($postButtons, $keepPost){
    for (let i = 0; i < $postButtons.length; i++) {
        $postButtons[i].addEventListener('click', (e) => {
            // Getting post id
            let postId = e.path[3].attributes[1].nodeValue;
    
            // Hide post
            e.path[3].style.display = "none";
            
            // Post to database
            let formData = new FormData();
            formData.append('postId', postId);
            formData.append('keepPost', $keepPost);
    
            
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
}