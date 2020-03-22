/* global Course, $ */

$(document).ready(onHtmlLoaded());

function onHtmlLoaded(){
    
    var courses = new Courses();
    var courseContainer = $('.courses');
    var coursesXhr = courses.getAll();
    coursesXhr.done(function(){
        displayCourses();
        displayPages();
    });

    function displayCourses(){
         $('.course-container').html('');
        for (var i = 0; i<courses.models.length; i++) {
            var courseContainer = $('<article id="'+courses.models[i].id+'"></article>').appendTo('.course-container');
            courseContainer.addClass('courses');
            
            var title = $('<h2>'+courses.models[i].title+'</h2>');
            
            var descriptionContainer = $('<div class="row course"></div>');
            var description = $('<div class="col-sm-12"><p>'+courses.models[i].description+'</p></div>').appendTo(descriptionContainer);
                
            var btnContainer = $('<div></div>').addClass('col-sm-12').appendTo(descriptionContainer);
            var addBtn = $('<input type="button" course-id="'+courses.models[i].id+'" class="btn btn-primary" value="+Add Course">').appendTo(btnContainer);
            var infoBtn = $('<input type="button" course-id="'+courses.models[i].id+'" class="btn btn-primary" value="Info">').appendTo(btnContainer);

            var imgContainer = $('<div>'+'</div>');
            var imageContainer = $('<img>').appendTo(imgContainer);
            imageContainer.attr("src", "http://localhost/Elearn/uploads/" + courses.models[i].file);
            
            courseContainer.append(title, imgContainer, descriptionContainer);
        }
        onBtnClicked();
    }
    
    function displayPages() {
        var pageContainer = $('.pagination');
        for (var i =1; i<=courses.nrPage; i++){
            var pageBtn = $('<li data-value='+i+'>'+i+'</li>');
            pageBtn.addClass('btn btn-default');
            pageContainer.append(pageBtn);
             $(pageBtn).on('click', function(){
                var pageValue = $(this).data('value');
                courses.getAll(pageValue).done(displayCourses());
            })
        }
    }
    
    function onBtnClicked() {
        var addBtn = $('input[type="button"][value="+Add Course"]');
        var infoBtn = $('input[type="button"][value="Info"]');
       
        addBtn.on('click', function(){
            var courseId = $(this).attr('course-id');
            var addCourse = new Courses({
                course_id: courseId
            });
            var addCourseXhr = courses.addStudentCourse(courseId);
        });
        infoBtn.on('click', function(){
            var courseId = $(this).attr('course-id');
            window.location.href = ROOT_URL + "assets/templates/course.html?id="+courseId;
            
        });
    }
}