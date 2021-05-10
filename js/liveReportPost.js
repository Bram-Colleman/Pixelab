let reportButtons = document.querySelectorAll(".btn-report");
for (let i = 0; i < reportButtons.length; i++) {
    reportButtons[i].addEventListener('click', function(e) {
        console.log("hey");
        e.preventDefault();
    });
}