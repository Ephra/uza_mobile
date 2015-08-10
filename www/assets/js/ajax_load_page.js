
$(document).ready(function () {
    $(".megamenu").megamenu();
    loadPage('body.html');
});
loadPage = function (url) {

    $.get(url, {pg: 'login', file: 'login'}, function (data) {
        $('.body_content').html(data);
    });
}

