let reportButtons = document.querySelectorAll(".btn-report");
for (let i = 0; i < reportButtons.length; i++) {
    reportButtons[i].addEventListener('click', function(e) {
        // Getting post id
        let postId = this.dataset.postid;
        let userId = this.dataset.userid;
        console.log(postId+"   "+userId);

        e.preventDefault();
    });
}