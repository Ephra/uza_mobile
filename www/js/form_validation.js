$(document).ready(function(){
    
    $('#login_button').click(function(){
        
      $(".eror").hide();
        var hasError=false;
        var reg_email=/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var email=$("#email").val();
        var password=$("#password").val();
        if(email=="" || password==""){
            //$("#password").after('<span class="error">Please enter your email address.</span>');
           // hasError=true;
            alert('wrong password or email');
        }
        else{
            alert('ok');
            //if(!reg_email.test(email)){
               //  $("#password").after('<span class="error">Enter a valid email address.</span>');
            //hasError = true;
            }
            
        
    
    });
    
});