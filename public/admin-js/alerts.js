// AUTO CLOSE ALERT GLOBAL
$(document).ready(function () {
    setTimeout(function () {
        $(".alert").fadeOut(500, function () {
            $(this).remove();
        });
    }, 5000);
});
