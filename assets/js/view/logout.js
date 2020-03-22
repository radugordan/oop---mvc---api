/* global $ */
/* global User */

$(document).ready(onHtmlLoaded());

function onHtmlLoaded() {
    $(document).on("click", '#logout', function(ev){
       ev.preventDefault();
       var user = new Users();
       var userLogoutXhr= user.logout();
       userLogoutXhr.done(function(){
          window.location.href = ROOT_URL + "/assets/templates/login.html";
       });
    });  
}