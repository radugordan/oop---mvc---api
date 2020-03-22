 /* global $ */
 $(document).ready(onHtmlLoaded());

 function onHtmlLoaded() {
    // var user = new Users();
    $(document).on('click', '#sendmail', function(ev){
        ev.preventDefault();
      var userEmail= $("#email").val();
      var user = new User ({
          email: userEmail,
     
      });
      // console.log(userLogin);
      var userXhr = user.sendToken();
      userXhr.done(function() {
      console.log("aicidone");
        // window.location.href= "http://localhost:8080/drmaseluta/templates/clientHome.html";   
	document.write('<body></body> <style> body {background-color: #f7f7f7}</style>')
    document.write('<h1> An email for your password reset was sent.</h1><style> h1 {color: purple} h1 {text-align: center} h1 {padding-top: 10%} </style>');
    document.write('<h3>Please check your email!</h3>' +
        '<style> h3 {color: dodgerblue} h3 {text-align: center}</style>');

      });   
    });
}