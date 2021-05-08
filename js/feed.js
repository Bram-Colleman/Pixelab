//onload
// $(function () {
//     $(".fa-heart").toggle();
// });



//clickevent likebutton
let likeButtons = document.querySelectorAll(".btn-like");
for (let i = 0; i < likeButtons.length; i++) {
    likeButtons[i].addEventListener('click', function(event) {
        $(this).children('i').toggleClass("fa-heart-o").toggleClass("fa-heart");
        if (this.dataset.liked === '0') {
            this.dataset.liked = '1';

            let formData = new FormData();
            formData.append('postId', this.dataset.postid);
            formData.append('userId', this.dataset.userid);
            fetch('ajax/likepost.php', {
                method: 'POST',
                body: formData
            })
                // .then(response => response.json())
                .then(response => response.json())
                .then(result => {
                    console.log('Success:', result);
                    $('#' + result.body['postid']).text(result.body['amount'] + " ");
                })
                .catch(error => {
                    console.error('Error:', error);
                });

        }
        else if (this.dataset.liked === '1') {
            this.dataset.liked = '0';

            let formData = new FormData();
            formData.append('postId', this.dataset.postid);
            formData.append('userId', this.dataset.userid);
            fetch('ajax/unlikepost.php', {
                method: 'POST',
                body: formData
            })
                // .then(response => response.json())
                .then(response => response.json())
                .then(result => {
                    console.log('Success:', result);
                    $('#' + result.body['postid']).text(result.body['amount'] + " ");
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
        event.preventDefault();
    });
}