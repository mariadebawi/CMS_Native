$(document).ready(function() {
    //get the year
    $("#year").text(new Date().getFullYear());
    // End of click event

});


$(function() {

    $("#show").on("click", function() {
            var x = $("#Password");
            if (x.attr('type') === "password") {
                x.attr('type', 'text');
                $(this).removeClass('fa fa-eye-slash')
                $(this).addClass('fa fa-eye')
            } else {
                x.attr('type', 'password');
                $(this).removeClass('fa fa-eye')
                $(this).addClass('fa fa-eye-slash')
            } // End of if
        }) // End of click event

});