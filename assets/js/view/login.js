/* global $ */
/* global User */

$(document).ready(onHtmlLoaded());

function onHtmlLoaded(){
    var userToken = getUrlParam('token');
    if (userToken != null){
        var userActivation = new User({
            token: userToken
        });
        var activeXhr = userActivation.activate();
        activeXhr.done(function() {
            document.write('<body></body> <style> body {background-color: #E6E6FA}</style>')
            document.write('<h1> Your account has been activated</h1><style> h1 {color: purple} h1 {text-align: center} h1 {padding-top: 10%} </style>');
            setTimeout(function() {
            window.location.href = ROOT_URL + "assets/templates/login.html";}, 1000);
        });
    }
    
    $(document).on('click', '#login', function(ev){
        ev.preventDefault();
        var userEmail = $('#email').val();
        var userPassword = $('#password').val();

        var userLogin = new User({
            email: userEmail,
            password: userPassword
        });
        
        var userXHR = userLogin.login();
        userXHR.done(function(){
            window.location.href= ROOT_URL + "assets/templates/courses.html";
        })
    });

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