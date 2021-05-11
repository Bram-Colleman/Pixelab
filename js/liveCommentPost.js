//onload
document.addEventListener('load', function () {
    let inputFields = [];
    let comments = [];
})


    inputFields = $(".addComment");
    comments = $(".comment");
    for (let i = 0; i < inputFields.length; i++) {
        inputFields[i].addEventListener("keypress", function comment (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                let postId = this.dataset.postid;
                let username = this.dataset.username;
                let content = inputFields[i].value;

                const formData = new FormData();

                formData.append("postId", postId);
                formData.append("comment", content);

            fetch('ajax/saveComment.php', {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(result => {
                    console.log("Success:", result);
                    let commentList = inputFields[i].parentNode.parentNode.querySelectorAll(".comment");
                    let newComment = document.createElement('div');
                    newComment.innerHTML = "<span><strong>" + username + "</strong></span>" +
                        "<br>" + result.body +
                        "<span class='timestamp-comment'>Just now</span>";
                    if(commentList.length === 0) {
                        commentList = inputFields[i].parentNode.parentNode.querySelectorAll(".description");
                        commentList[(commentList.length) - 1].appendChild(newComment);
                    } else {
                        commentList[(commentList.length) - 1].appendChild(newComment);
                    }
                    inputFields[i].value = '';
                })
                .catch(error => {
                    console.log("Error", error);
                })
            }
        });
}