/* global $*/

function User(options){
    this.id = options.id;
    this.name = options.name;
    this.surname = options.surname;
    this.email = options.email;
    this.phone = options.phone;
    this.password = options.password;
    this.repassword = options.repassword;
    this.value = options.value;
    this.file = options.file;
    this.token = options.token;
}

User.prototype.createAccount=function(formData){
     return $.ajax({
        url: API_URL + "user/signup",
        type: "POST",
//        crossDomain: true,
        data: formData,
        processData: false,
        contentType: false, 
        success: function(resp){

        },
        error:function(xhr, status, error){

            var jsonResponse = JSON.parse(xhr.responseText);
            var firstName = $('.fname-error');
            if (jsonResponse['name']) {
                firstName.html(jsonResponse['name']);
            }
            else {
            firstName.html("");
            } 
            
            var jsonResponse = JSON.parse(xhr.responseText);
            var firstName = $('.lname-error');
            if (jsonResponse['surname']) {
                firstName.html(jsonResponse['surname']);
            }
            else {
            firstName.html("");
            }  
            
            var jsonResponse = JSON.parse(xhr.responseText);
            var firstName = $('.email-error');
            if (jsonResponse['email']) {
                firstName.html(jsonResponse['email']);
            }
            else {
            firstName.html("");
            }  
            
            var jsonResponse = JSON.parse(xhr.responseText);
            var firstName = $('.phone-error');
            if (jsonResponse['phone']) {
                firstName.html(jsonResponse['phone']);
            }
            else {
            firstName.html("");
            }  
            
            var jsonResponse = JSON.parse(xhr.responseText);
            var firstName = $('.pass-error');
            if (jsonResponse['password']) {
                firstName.html(jsonResponse['password']);
            }
            else {
            firstName.html("");
            }   
            var jsonResponse = JSON.parse(xhr.responseText);
            var firstName = $('.repass-error');
            if (jsonResponse['password']) {
                firstName.html(jsonResponse['password']);
            }
            else {
            firstName.html("");
            }   
            var jsonResponse = JSON.parse(xhr.responseText);
            var firstName = $('.file-error');
            if (jsonResponse['img']) {
                firstName.html(jsonResponse['img']);
            }
            else {
            firstName.html("");
            }
        }
    });
};

User.prototype.login=function (){
    var loginData = { 
                    email: this.email,
                    password: this.password
                    };

    return $.ajax ({
        url: API_URL +"user/login",
        type: "POST",
        data: loginData,
       crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success : function (resp) {

        },
        error: function(xhr, status, error){
        var jsonResponse = JSON.parse(xhr.responseText);
            var error = $('.pass-error');
            if (jsonResponse['login']) {
                error.html(jsonResponse['login'])
            }
            var jsonResponse = JSON.parse(xhr.responseText);
            var error = $('.active-error');
            if (jsonResponse['active']) {
                error.html(jsonResponse['active'])
            }
            else {
                error.html("");
            }
        }
    });
};

User.prototype.activate=function (){
    var loginData = { 
                    token: this.token
                    };

    return $.ajax ({
        url: API_URL + "user/login",
        type: "POST",
        data: loginData,
       crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success : function (resp) {
            console.log("You're logged in!");
        },
        error: function(xhr, status, error){
            // console.log("Error");
        }
    });
};

User.prototype.sendToken = function(){
    var sendData ={
                    email: this.email
                    }
    return $.ajax({
        url: "http://192.168.10.10/user/mail",
        type: "POST",
        data: sendData,
        success: function(resp){
        },
        error: function(xhr, status, error) {

             var jsonResponse = JSON.parse(xhr.responseText);
            var error = $('.email-error');
            if (jsonResponse) {
                error.html(jsonResponse)
            }
            else {
                error.html("");
            } 
        }
    }); 
};

User.prototype.resetPassword = function(){
    var sendData ={
                    token: this.token,
                    password: this.password,
                    repassword: this.repassword
                    }
    return $.ajax({
        url: API_URL + "user/resetPass",
        type: "POST",
        data: sendData,
        success: function(resp){
        
        },
        error: function(xhr, status, error) {
            alert ('smth went wrong'); 
        
            } 
    }); 
};



User.prototype.editUserInfo = function(formData) {
    return $.ajax ({
        url: API_URL + "user/edit",
        type: "POST",
        data: formData,
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        processData: false,
        contentType: false, 
        success: function(resp){
            console.log('succes edit');
        },
        error:function(xhr, status, error){
//            alert("smth went wrong");
            var jsonResponse = JSON.parse(xhr.responseText);
            var error = $('.name-error');
            if (jsonResponse['name']) {
                error.html(jsonResponse['name'])
            }
            else {
                error.html("");
            }     
            var jsonResponse = JSON.parse(xhr.responseText);
            var error = $('.surname-error');
            if (jsonResponse['surname']) {
                error.html(jsonResponse['surname'])
            }
            else {
                error.html("");
            }     
            var jsonResponse = JSON.parse(xhr.responseText);
            var error = $('.email-error');
            if (jsonResponse['email']) {
                error.html(jsonResponse['email'])
            }
            else {
                error.html("");
            }     
            var jsonResponse = JSON.parse(xhr.responseText);
            var error = $('.phone-error');
            if (jsonResponse['phone']) {
                error.html(jsonResponse['phone'])
            }
            else {
                error.html("");
            } 
        }
    });
}; 


