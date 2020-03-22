/* global Users, $ */

$(document).ready(onHtmlLoaded());
        function onHtmlLoaded(){
            var user = new Users();
            var userXhr = user.getAllUsers();
            userXhr.done(function(){
                displayUsers();
        });
        
         function displayUsers(){
            for (var i=0; i<user.models.length; i++) {
                var userContainer = $('<div id="'+user.models[i].id+'"></div>').appendTo('.align-studs');
                userContainer.addClass('col-sm-5 col-xs-5');
                userContainer.addClass('student');
                var userName = $('<h5>Name:</h5><p>' + user.models[i].name +" "+ user.models[i].surname + '</p>');
                var userEmail = $('<h5>Email:</h5><p>' + user.models[i].email + '</p>');
                var userPhone = $('<h5>Phone:</h5><p>' + user.models[i].phone + '</p>');
                var imgContainer = $('<div>'+'</div>');
                var imageContainer = $("<img>").appendTo(imgContainer);
                imageContainer.attr("src", API_URL + "/uploads/" + user.models[i].file);
                userContainer.append(imgContainer, userName, userEmail, userPhone);
             }
         }
}