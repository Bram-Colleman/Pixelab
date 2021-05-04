let reader = new FileReader();
reader.onload = function (e) {
    $('#uploadedImage').attr('src', e.target.result);
}

function readURL(input) {
    if (input.files && input.files[0]) {
        reader.readAsDataURL(input.files[0]);
    }
}

$("#file-input").on("change", function(){
    readURL(this);
});