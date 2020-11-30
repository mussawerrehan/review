function statusChange(review){
    console.log(review);
    var url = "http://127.0.0.1:8000/review/"+ review + "/status";
    $.ajax({
        url: url,
        method: "PUT",
        success: function(data) {
            showSnackBar();
        }
    });
}

function showSnackBar() {
    // Get the snackbar DIV
    var x = document.getElementById("snackbar");

    // Add the "show" class to DIV
    x.className = "show";

    // After 3 seconds, remove the show class from DIV
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}