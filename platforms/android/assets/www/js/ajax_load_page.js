
$(document).ready(function () {
    $(".megamenu").megamenu();
    loadPage('body.html');
});
loadPage = function (url) {

    $.get(url, {pg: 'login', file: 'login'}, function (data) {
        $('.body_content').html(data);
    });
}

user_login = function () {

    var user_email = $('#email').val();
    var password = $('#password').val();

    var userInput = 'email=' + user_email + '&password=' + user_password;

    if (user_email == '' && password == '') {
        $('.success').fadeOut(200).hide();
        $('.error').fadeOut(200).show();

    }

    else {

        $.ajax({
            type: "POST",
            url: "single_product.html",
            data: userInput,
            success: function () {
                $('.success').fadeIn(200).show();
                $('.error').fadeOut(200).hide();
            }
        });
    }
    return false;
};



user_register = function () {

    var fname = $('#fname').val();
    var lname = $('#lname').val();
    var email = $('#email').val();
    var password = $('#password').val();
    var phone = $('#phone_number').val();
    var location = $('#location').val();

    var userInput = 'fname=' + user_fname + '&lname=' + user_lname + '&email=' + user_email + '&password=' + user_password + '&phone=' + user_phone + '&location=' + location;

    if (fname == '' && lname == '' && email=='' && password==''&& phone=='' && location=='' ) {
        $('.success').fadeOut(200).hide();
        $('.error').fadeOut(200).show();

    }

    else {

        $.ajax({
            type: "POST",
            url: "single_product.html",
            data: userInput,
            success: function () {
                $('.success').fadeIn(200).show();
                $('.error').fadeOut(200).hide();
            }
        });
    }
    return false;
};







       