
$(document).ready(function () {
    $(".megamenu").megamenu();
    loadPage('body.html');
});
loadPage = (function (url) {
    NProgress.start();
    $.get(url, {pg: 'login', file: 'login'}, function (data) {
        $('.body_content').html(data);
    NProgress.done();
    });
});











       

