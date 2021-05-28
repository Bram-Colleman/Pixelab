let image = document.querySelector("#uploadedImage");

image.addEventListener("click", (e)=>{
    console.log('click');
    getLocation();
})

// Function to get location
function getLocation() {
    if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

// Function to create dataset with location
function showPosition(position) {
    document.querySelector("#long").value = position.coords.longitude;
    document.querySelector("#lat").value = position.coords.latitude;
}
