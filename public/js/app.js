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

$(document).ready(function() {
    $(document).on("change", "#ratingFilter", function() {
        console.log($(this).val());
        var query = $(this).val();
        var url = "http://127.0.0.1:8000/home";
        var http = new XMLHttpRequest();
        var params = 'star='+query;
        http.open('POST', url, true);
        http.send(params)
        // $.ajax({
        //     url: url,
        //     method: "POST",
        //     data: {
        //         query: query
        //     },
        //     success: function(data) {
        //         showSnackBar();
        //         console.log(data)
        //         $(".table tbody").html("");
        //         if(data.length > 0)
        //         {
        //
        //         }
        //         var new_tbody = "";
        //         data.forEach(function(product)
        //         {
        //             new_tbody +=
        //                 '<tr><td>' + product.id +
        //                 '</td><td>' + product.name +
        //                 '</td><td>' + '<img src="http://127.0.0.1:8000/uploads/' + product.image + '">'
        //                 '</td><td>' + product.rating +
        //                 '</td></tr>'
        //             ;
        //         })
        //
        //
        //         $(".table tbody").html(new_tbody);
        //     }
        // });
    })
});