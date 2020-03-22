/* global Course, Courses, $ */

$(document).ready(onHtmlLoaded());

function onHtmlLoaded() {
    var courseId = getUrlParam('id');
    var course = new Courses();
    var courseXhr = course.getCourse(courseId);
    
    
    courseXhr.done(function(){
        var title = $('<h3>'+course.models[0].title+'</h3>').appendTo('.title');
        
        var description = $('<p>'+course.models[0].description+'</p>').appendTo('.description');
        console.log('test');
        var imageContainer = $('#blah');
        imageContainer.attr("src", API_URL + "uploads/" + course.models[0].file);
  })
        
    $(document).on('click', '#submit', function(ev){
        ev.preventDefault();
        
        var title = $('#title').val();
        var description = $('#description').val();
        var courseImgInp=$('#imgInp');
        var files = courseImgInp[0].files;
        var courseImg = files[0];
        var formData = new FormData() ;
        formData.append("title", title);
        formData.append("description", description);
        formData.append("id", courseId);
        formData.append("file", courseImg);
        var courseEdit = new Course ({
            title: title,
            description: description,
            id: courseId,
            file: courseImg
        });
        var courseXhr = courseEdit.editCourse(formData);
//        console.log(course)
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