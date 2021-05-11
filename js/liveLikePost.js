//onload
document.addEventListener('load', function () {
    let likeButtons = [];
})

likeButtons = document.querySelectorAll(".btn-like");
for (let i = 0; i < likeButtons.length; i++) {
    likeButtons[i].addEventListener('click', function like(event) {
        $(this).children('i').toggleClass("fa-heart-o").toggleClass("fa-heart");
            let formData = new FormData();
            formData.append('postId', this.dataset.postid);
            formData.append('userId', this.dataset.userid);
            formData.append('isLiked', this.dataset.liked);
            fetch('ajax/likepost.php', {
                method: 'POST',
                body: formData
            })
                // .then(response => response.json())
                .then(response => response.json())
                .then(result => {
                    console.log('Success:', result);
                    $('#' + result.body['postid']).text(result.body['amount'] + " ");
                    (this.dataset.liked === '1')?this.dataset.liked = '0' : this.dataset.liked = '1';

                })
                .catch(error => {
                    console.error('Error:', error);
                });
        event.preventDefault();
    });
}
