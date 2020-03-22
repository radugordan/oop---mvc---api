/* global $ */
/* global User */

$(document).ready(onHtmlLoaded());

function onHtmlLoaded(){
    $(document).on('click', '#reset', function(ev){
        ev.preventDefault();
        var userPass = $('#password').val();
        var userRepass = $('#repassword').val();
        var token = getUrlParam('token');
        
        
        var user = new User({
            password: userPass,
            repassword: userRepass,
            token: token
        })
        
        var userXHR = user.resetPassword();
        userXHR.done(function(){
         window.location.href= "http://radgor.app/assets/templates/login.html";
            
        })
    })
    
    
    function getUrlParam(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
            if (results==null){
                return null;
            }
            else{
                return results[1] || 0;
            }
    }
}