$(function () {
    $(".fa-heart").toggle();
});

let likeButtons = document.querySelectorAll(".btn-like");
for (let i = 0; i < likeButtons.length; i++) {
    likeButtons[i].addEventListener('click', function(event) {
        // alert(this.dataset.postid);
        $(this).children('.fa-heart-o').toggle();
        $(this).children('.fa-heart').toggle();
        // $(".btn-icon").toggle("fa-heart");

        event.preventDefault();
    });
}