// Onload
document.addEventListener('load', function () {
    let inputFields = [];
    let comments = [];
})


inputFields = $(".addComment");
comments = $(".comment");
for (let i = 0; i < inputFields.length; i++) {
    inputFields[i].addEventListener("keypress", function (e) {

        if (e.keyCode === 13) {
            e.preventDefault();
            if (inputFields[i].value !== null && inputFields[i].value.trim() !== "") {
                const formData = new FormData();

                formData.append("postId", this.dataset.postid);
                formData.append("comment", inputFields[i].value);

                fetch('ajax/saveComment.php', {
                    method: "POST",
                    body: formData
                })
                    .then(response => response.json())
                    .then(result => {
                        console.log("Success:", result);
                        // Get function
                        if(document.URL.includes("index") || document.URL.includes("explore")) {
                            successFeed(result, i, this);
                        }
                        if(document.URL.includes("postDetail")) {
                            successDetailPost(result, i, this);
                        }
                    })
                    .catch(error => {
                        console.log("Error", error);
                    })
            }
        }
    });
}

function successFeed(result, i, e) {
    let commentList = inputFields[i].parentNode.parentNode.querySelectorAll(".comment");
    let newComment = document.createElement('div');
    newComment.className = "pb-2";
    newComment.innerHTML = "<span><strong>" + e.dataset.username + "</strong></span>" +
        "<br>" + result.body +
        "<span class='timestamp-comment'>Just now</span>";
    if(commentList.length === 0) {
        commentList = inputFields[i].parentNode.parentNode.querySelectorAll(".description");
        commentList[(commentList.length) - 1].appendChild(newComment);
    } else {
        commentList[(commentList.length) - 1].appendChild(newComment);
    }
    inputFields[i].value = '';
}

function successDetailPost(result, i, e) {
    let commentList = inputFields[i].parentNode.parentNode.parentNode.parentNode.querySelectorAll(".comment");
    let newComment = document.createElement('div');
    newComment.className = "pb-2";
    newComment.innerHTML = "<span><strong>" + e.dataset.username + "</strong></span>" +
        "<br>" + result.body +
        "<span class='timestamp-comment'>Just now</span>";
    if(commentList.length === 0) {
        commentList = inputFields[i].parentNode.parentNode.parentNode.parentNode.querySelectorAll(".commentList");
        commentList[(commentList.length) - 1].appendChild(newComment);
    } else {
        commentList[(commentList.length) - 1].appendChild(newComment);
    }
    inputFields[i].value = '';
}