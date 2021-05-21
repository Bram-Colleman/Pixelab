// Onload
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


//filters
let filters = document.querySelectorAll(".btn-radio");
for (let i = 0; i < filters.length; i++) {
    filters[i].addEventListener("click", function () {
        $("#previewImage").removeClass("_1977 aden brannan brooklyn clarendon earlybird gingham hudson");
        $("#previewImage").addClass(this.dataset.filter);
    })
}
