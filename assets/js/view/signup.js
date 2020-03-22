/* global $ */
/* global User */

$(document).ready(onHtmlLoaded());

    function onHtmlLoaded(){
        $(document).on('click', '#signup', function(ev){
            ev.preventDefault();
            var firstName= $('#fname').val();
            var lastName= $('#lname').val();
            var userEmail= $('#mail').val();
            var userPhone=$('#phone').val();
            var userPassword= $('#pass').val();
            var userRepassword= $('#repassword').val();
            var userValue=$('#value option:checked').val();
            var userImgInp=$('#imgInp');
            
            var files = userImgInp[0].files;
            var userImg = files[0];
            
            var formData = new FormData() 
                formData.append("name", firstName);
                formData.append("surname", lastName);
                formData.append("email", userEmail);
                formData.append("phone", userPhone);
                formData.append("password", userPassword);
                formData.append("repassword", userRepassword);
                formData.append("value", userValue);
                formData.append("file", userImg);
            
        var user = new User ({
            name:firstName,
            surname: lastName,
            email: userEmail,
            phone: userPhone,
            password: userPassword,
            repassword: userRepassword,
            value: userValue,
            file: userImg
        })  
        var userXhr = user.createAccount(formData);
        userXhr.done(function(){
            // alert();
            window.location.href ="http://localhost/Elearn/assets/templates/login.html"
        });

        
        
    });
        
}
    
