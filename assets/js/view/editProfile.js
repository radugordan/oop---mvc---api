/* global Users, User, $ */

$(document).ready(onHtmlLoaded());
console.log("test");

function onHtmlLoaded(){
    var user = new Users();
    var userInfo = user.getUser();
    userInfo.done(function(){
        var fname = $("<p>" + user.models[0].name +" "+ user.models[0].surname+ "</p>").appendTo('.name');
        var email = $("<p>" + user.models[0].email + "</p>").appendTo('.email');
        var phone = $("<p>" + user.models[0].phone + "</p>").appendTo('.phone');
        var imageContainer = $('#blah');

        imageContainer.attr("src", API_URL + "uploads/" + user.models[0].file);

    });
       
    $(document).on('click', '#submit', function(ev){
        ev.preventDefault();

        var userName = $('#name').val();
        var userSurname = $('#surname').val();
        var userPhone = $('#number').val();     
        var userEmail = $('#email').val();
        var userImgInp=$('#imgInp');
        var files = userImgInp[0].files;
        var userImg = files[0];
        var formData = new FormData()
        formData.append("name", userName);
        formData.append("surname", userSurname);
        formData.append("email", userEmail);
        formData.append("phone", userPhone);
        formData.append("file", userImg);
        var userEdit = new User({
            name: userName,
            surname: userSurname,
            phone: userPhone,
            email: userEmail,
            phone: userPhone,
            file: userImg
        });
        var userEditXhr = userEdit.editUserInfo(formData);
        userEditXhr.done(function(){
            //do something
            })
        })
}
