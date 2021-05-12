document.querySelector("#btn-loadmore").addEventListener('click', function(event) {

        let formData = new FormData();
        formData.append('currentAmount',  this.dataset.currentpostamount);
        fetch('ajax/loadMorePosts.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(result => {
                console.log('Success:', result);
                if (result.body !== "Something went wrong."){
                    this.dataset.currentpostamount = parseInt(this.dataset.currentpostamount) + 20;
                    for (let i = 0; i<result.body[1]; i++) {
                        // console.log(result.body[2][i]);
                        document.querySelector("#PostContainer")
                            .appendChild(createPost(result.body[0][i]['posterImage'],
                                result.body[0][i]['user'],
                                result.body[0][i]['image'],
                                result.body[0][i]['sessionUser'],
                                result.body[0][i]['likes'],
                                result.body[0][i]['id'],
                                result.body[0][i]['sessionUserId'],
                                result.body[0][i]['description'],
                                result.body[0][i]['comments'],
                                result.body[0][i]['timeAgo'],
                                result.body[0][i]['commentsAgo']
                            ));
                    }
                    loadScripts();

                } else {
                    $("#btn-loadmore").toggle();
                    let noMorePosts = document.createElement("p");
                    noMorePosts.innerText = "There are no more posts.";
                    document.querySelector("#btn-loadmore").parentElement.appendChild(noMorePosts);
                }

            })
            .catch(error => {
                console.error('Error:', error.message);
            });
    event.preventDefault();
});

function createPost(avatar, posterName, postImg, sessionUsername, likes, postId, userId, description, comments, timeAgo, commentsAgo) {
    let div1 = document.createElement("div");
    div1.className = "container-fluid shadow w-35 pt-1 pb-1 mt-5";


    // Username and avatar
    let divHeader1 = document.createElement("div");
    divHeader1.className = "row h-5 mb-1";
    let divHeader2 = document.createElement("div");
    divHeader2.className = "col-auto flex-fill align-self-center text-center p-0";
    let aHeader1 = document.createElement("a");
    aHeader1.setAttribute("href", "./profilePage.php?user="+posterName);
    let imgHeader = document.createElement("img");
    if (avatar !== null) {
        imgHeader.src = "./uploads/avatars/"+avatar;
        imgHeader.className = "rounded-circle avatarIcon";
        imgHeader.alt = "User avatar";

    } else {
        imgHeader.src = "./images/blank_avatar.png";
        imgHeader.className = "rounded-circle max-w-1-half-vw";
        imgHeader.alt = "blank avatar";
    }
    imgHeader.setAttribute("role", "button");
    let divHeader3 = document.createElement("div");
    divHeader3.className = "col-3 flex-fill align-self-center p-0";
    let aHeader2 = document.createElement("a");
    aHeader2.innerText = " " + posterName;
    aHeader2.className = "text-decoration-none text-black fw-bold";
    aHeader2.setAttribute("href", "./profilePage.php?user="+posterName);
    let divHeader4 = document.createElement("div");
    divHeader4.className = "col-7 d-flex flex-fill align-self-center justify-content-end timestamp-post";
    let pPostedTimeAgo = document.createElement("p");
    pPostedTimeAgo.className = "mb-0";
    pPostedTimeAgo.innerText = "Posted "+ timeAgo +" ago";


    div1.appendChild(divHeader1);
    divHeader1.appendChild(divHeader2);
    divHeader2.appendChild(imgHeader);
    divHeader2.appendChild(aHeader1);
    divHeader1.appendChild(divHeader3);
    divHeader3.appendChild(aHeader2);
    divHeader1.appendChild(divHeader4);
    divHeader4.appendChild(pPostedTimeAgo);


    // Post
    let divPost1 = document.createElement("div");
    divPost1.className = "row";
    let divPost2 = document.createElement("div");
    divPost2.className = "col-12 text-center p-0";
    let imgPost = document.createElement("img");
    if (postImg !== "") {
        imgPost.src = "./uploads/posts/"+postImg;
        imgPost.alt = "User post";
    } else {
        imgPost.src = "./images/blank_post.jpg";
        imgPost.alt = "blank post";
    }
    imgPost.className = "max-w-100 min-w-100";

    div1.appendChild(divPost1);
    divPost1.appendChild(divPost2);
    divPost2.appendChild(imgPost);


    // ActionButton
    let divAction1 = document.createElement("div");
    divAction1.className = "row d-flex pt-1";
    let divAction2 = document.createElement("div");
    divAction2.className = "col-1 max-w-7";
    let aAction1 = document.createElement("a");
    aAction1.className = "border-0 outline-none bg-none text-black btn-like";
    aAction1.setAttribute("href", "#");
    aAction1.setAttribute("data-postid", postId);
    aAction1.setAttribute("data-userid", userId);
    let iAction = document.createElement("i");
    iAction.setAttribute("aria-hidden", "true");
    if (likes.includes(sessionUsername)) {
        iAction.className = "btn-icon fa fa-heart";
        aAction1.setAttribute("data-liked", "1");

    } else {
        iAction.className = "btn-icon fa fa-heart-o";
        aAction1.setAttribute("data-liked", "0");

    }
    let divAction3 = document.createElement("div");
    divAction3.className = "col-11 d-flex justify-content-end";
    let aAction2 = document.createElement("a");
    aAction2.className = "border-0 outline-none bg-none text-blac btn-report text-end";
    aAction2.setAttribute("href", "#");
    aAction2.setAttribute("data-postid", postId);
    aAction2.innerText = "Report";


    div1.appendChild(divAction1);
    divAction1.appendChild(divAction2);
    divAction2.appendChild(aAction1);
    aAction1.appendChild(iAction);
    divAction1.appendChild(divAction3);
    divAction3.appendChild(aAction2);


    // Likes
    let divLikes1 = document.createElement("div");
    divLikes1.className = "row pt-half";
    let divLikes2 = document.createElement("div");
    divLikes2.className = "col-12";
    let spanLikesAmount = document.createElement("span");
    spanLikesAmount.innerText = likes.length;
    spanLikesAmount.className = "like-count";
    spanLikesAmount.id = postId;
    let spanLikesText = document.createElement("span");
    spanLikesText.innerText = " likes";

    div1.appendChild(divLikes1);
    divLikes1.appendChild(divLikes2);
    divLikes2.appendChild(spanLikesAmount);
    divLikes2.appendChild(spanLikesText);


    // Description
    let divDescription1 = document.createElement("div");
    divDescription1.className = "row pt-half description";
    let divDescription2 = document.createElement("div");
    divDescription2.className = "col-12";
    let divDescription3 = document.createElement("div");
    divDescription3.className = "row";
    let divDescription4 = document.createElement("div");
    divDescription4.className = "col-12 pb-2";
    let spanDescriptionUsername = document.createElement("span");
    spanDescriptionUsername.className = "fw-bold";
    spanDescriptionUsername.innerText = posterName;
    let spanDescriptionText = document.createElement("span");
    spanDescriptionText.innerText = " " + description;

    div1.appendChild(divDescription1);
    divDescription1.appendChild(divDescription2);
    divDescription2.appendChild(divDescription3);
    divDescription3.appendChild(divDescription4);
    divDescription4.appendChild(spanDescriptionUsername);
    divDescription4.appendChild(spanDescriptionText);


    // Comments
    if (comments[0] !== undefined) {
        for(let i = 0; i< comments.length; i++) {
            let divComments1 = document.createElement("div");
            divComments1.className = "row pt-half comment";
            let divComments2 = document.createElement("div");
            divComments2.className = "col-12 pb-2";
            let divComments3 = document.createElement("div");
            divComments3.className = "row";
            let divComments4 = document.createElement("div");
            divComments4.className = "col-12";
            let spanCommentUsername = document.createElement("span");
            spanCommentUsername.className = "fw-bold";
            spanCommentUsername.innerText = comments[i]['username'];
            let divComments5 = document.createElement("div");
            divComments5.className = "row";
            let divComments6 = document.createElement("div");
            divComments6.className = "col-12";
            let spanCommentText = document.createElement("div");
            spanCommentText.innerText = comments[i]['content'];
            let spanCommentTimeAgo = document.createElement("div");
            spanCommentTimeAgo.className = "timestamp-comment";
            spanCommentTimeAgo.innerText = commentsAgo[i];

            div1.appendChild(divComments1);
            divComments1.appendChild(divComments2);
            divComments2.appendChild(divComments3);
            divComments3.appendChild(divComments4);
            divComments4.appendChild(spanCommentUsername);
            divComments2.appendChild(divComments5);
            divComments5.appendChild(divComments6);
            divComments6.appendChild(spanCommentText);
            divComments6.appendChild(spanCommentTimeAgo);
        }
    }
    // CommentInput
    let divCommentInput1 = document.createElement("div");
    divCommentInput1.className = "d-flex border-top-gray";
    let inputCommentInput = document.createElement("input");
    inputCommentInput.className = "w-100 border-0 addComment py-half-rem";
    inputCommentInput.setAttribute("type", "text");
    inputCommentInput.setAttribute("name", "comment");
    inputCommentInput.setAttribute("placeholder", "Add a comment as "+sessionUsername+"...");
    inputCommentInput.setAttribute("data-postid", postId);
    inputCommentInput.setAttribute("data-username", sessionUsername);

    div1.appendChild(divCommentInput1);
    divCommentInput1.appendChild(inputCommentInput);


    return div1;
}
function loadScripts() {
    let likeScript = document.createElement("script");
    likeScript.setAttribute("src", "js/liveLikePost.js");
    document.querySelector("body").appendChild(likeScript);

    let commentScript = document.createElement("script");
    commentScript.setAttribute("src", "js/liveCommentPost.js");
    document.querySelector("body").appendChild(commentScript);

    let reportScript = document.createElement("script");
    reportScript.setAttribute("src", "js/liveReportPost.js");
    document.querySelector("body").appendChild(reportScript);
}