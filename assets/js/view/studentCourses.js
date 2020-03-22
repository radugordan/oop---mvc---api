/* global Course, $ */

$(document).ready(onHtmlLoaded());

function onHtmlLoaded(){
//       console.log("test");
    var courseContainer = $('.course-container');
    var courses = new Courses();
    var coursesXhr = courses.getStudentCourse();

    coursesXhr.done(function(){
        for (var i = 0; i<courses.models.length; i++) {
            var courseContainer = $('<div class="col-sm-12" id="'+ courses.models[i].id +'">').appendTo('.course-container');
            
            var title = $('<h3>'+courses.models[i].title+'</h3>');
            
            var description = $('<p>'+courses.models[i].description+'</p>');
            
            var removeBtn = $('<input type="button" course-id="'+courses.models[i].id+'" class="btn btn-primary" value="Remove"><hr>');
            
            courseContainer.append(title, description, removeBtn);
            
            onBtnClicked()
         
        }
    })
    
    function onBtnClicked() {
        var removeBtn = $('input[type="button"][value="Remove"]');
//        console.log(removeBtn);
        removeBtn.on('click', function(){
            var courseId = $(this).attr('course-id');
            var removeCourse = new Courses({
                course_id: courseId
            })
            var removeCourseXhr = courses.removeStudentCourse(courseId);
            removeCourseXhr.done(function(){
                var delay = 500; 
                setTimeout(function(){ window.location = "http://radgor.app/assets/templates/profile.html"; }, delay);
//                window.location.href="http://radgor.app/assets/templates/profile.html";
            })
            
            
        })
        
    }
  
}