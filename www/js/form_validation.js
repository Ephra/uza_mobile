

           function validateName() {

    var name = document.getElementById("name").value;
    if (name.length == 0) {

        producePrompt("Name is required", "errorPromptName", "red");
        return false;
    }

    if (!name.match(/^[A-Za-z]*$/)) {

        producePrompt("Name is invalid", "errorPromptName", "red");
        return false;

    }

    producePrompt("Welcome" + name, "errorPromptName", "green");

}

             function  validatePhone() {
    var phone = document.getElementById('phone').value;
    
    if (phone.length == 0 &&( phone.length!=10)) {

        producePrompt("Name is required", "errorPromptPhone", "red");
        return false;

    }
    
    if(!phone.match(/^[0-9]{10}$/)){
        
          producePrompt("phone number is incomplete", "errorPromptPhone", "red");
        return false;
        
    }
    
     producePrompt("Valid number" + name, "errorPromptPhone", "green");
             }
             
             
             function validateEmail(){
                var  email=document.getElementById('email').value;
                 
                 if(email.length==0){
                     
                      producePrompt("Email is required", "errorPromptEmail", "red");
                      email.focus;
                      return false;
                  }
                  
                  if(!email.match(/[A-Za-z._\-0-9]*[@][A-Za-z]*[\.][a-z]{2,4}$/)){
                         producePrompt("Invalid email" + name, "errorPromptEmail", "red");
                         email.focus;
                         return false;
                      
                  }
                 
                 producePrompt("Your email:" + email, "errorPromptEmail", "green");
             }
             
             function validatePassword(){
                 
                 var password=document.getElementById('password').value;
                 if(password.length==0){
                     
                      producePrompt("Password is required " , "errorPromptPassword", "red");
                      password.focus;
                      return false;
                     
                 }
                 
                 if(!password.match(/^[0-9a-zA-Z]$/)){
                     
                      producePrompt("Password should have only numbers and letter and atleast 6 characters", "errorPromptPassword", "red");
                      password.focus;
                      return false;
                     
                 }
                 
             
                 
                producePrompt("Valid password" , "errorPromptPassword", "green");
                 
                 
             }
                 
              function producePrompt(message, promptLocation, color) {

                    document.getElementById(promptLocation).innerHTML = message;
                    document.getElementById(promptLocation).style.color = color;


}


