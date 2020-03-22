/* global Users, $ */

$(document).ready(onHtmlLoaded());
//console.log("test");

function onHtmlLoaded(){
    var user = new Users();
    var userInfo = user.getUser();
    
    userInfo.done(function(){
        var fname = $("<p>" + user.models[0].name +" "+ user.models[0].surname+ "</p>").appendTo('.fname');    
        
        var email = $("<p>" + user.models[0].email + "</p>").appendTo('.email');   
        
        var phone = $("<p>" + user.models[0].phone + "</p>").appendTo('.phone');  
    //        
        var container = $('.descript');
        var imageContainer = $(".profilePic");

        imageContainer.attr("src", API_URL +"uploads/" + user.models[0].file);
        container.append(imageContainer);
    })
    
}
