/* global Course, Courses, $ */

$(document).ready(onHtmlLoaded());

function onHtmlLoaded() {
    var courseId = getUrlParam('id');
    var course = new Courses();
    var users = new Users();
    var courseXhr = course.getCourse(courseId);
    var courseInfoXhr = users.getCourseInfo(courseId);

    courseXhr.done(function(){
        var title = $('<h3>'+course.models[0].title+'</h3>').appendTo('.title');
        
        var description = $('<p>'+course.models[0].description+'</p>').appendTo('.description');
        console.log('test');
        var imageContainer = $('.profilePic');
        imageContainer.attr("src", API_URL +"uploads/" + course.models[0].file);
  })
        
    courseInfoXhr.done(function() {
      for (var i = 0; i<users.models.length; i++) {
        var studentContainer = $('<div id="'+users.models[i].id+'"></div>').appendTo('.student-container');
        studentContainer.addClass('col-sm-6');
        var name = $('<h4>Name:</h4><p>'+users.models[i].name+' '+users.models[i].surname+'</p>');
        var email = $('<h4>Email:</h4><p>'+users.models[i].email+'</p>');
          studentContainer.append(name, email);
      }
    })
}





function getUrlParam(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results==null){
            return null;
        }
        else{
            return results[1] || 0;
        }

}