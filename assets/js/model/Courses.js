/* global Courses, $ */
//console.log("courses");
function Courses() {
    this.models = [];
    this.nrPages=1;
}

Courses.prototype.getAll = function(pageValue) {
    var that = this;
    return $.ajax({
        url: API_URL + "courses/page",
        type: "GET",
        data: {
            page: pageValue
            },
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function(resp) {
            that.models = [];
            for (var i=0; i<resp.courses.length; i++) {
               var course = new Course(resp.courses[i]);
               that.models.push(course);
            }
            that.nrPage = resp.nrPages;
        },
        error: function(xhr, status, error) {
            alert("smth went wrong");
        }
        
    })
}

Courses.prototype.getCourse = function (courseId) {
    var that = this;
    
    return $.ajax ({
    url: API_URL + "courses/item",
    type: "GET",
    data: {
      id: courseId,  
    },
    
    success: function (resp) {
    
            var course = new Course (resp);
            that.models= [];
            that.models.push(course);
          
    },
    error: function (xhr, status, error) {
        // alert ("Something went wrong!");
        alert("Smth wen wrong");
    }
    
    });
};



Courses.prototype.addStudentCourse = function(courseId) {
    var that = this;
    return $.ajax({
        url: API_URL + "courses/studAdd",
        type: "POST",
        data: {
            course_id: courseId
        },
//        data: JSON.stringify({
//            id: courseId
//        }),
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function(resp) {
       
            console.log(resp);
        },
        error: function(xhr, status, error){
            alert("smth went wrong");
        }
        
    })
}

Courses.prototype.getStudentCourse = function() {
     var that = this;
    return $.ajax({
        url: API_URL + "courses/student",
        type: "GET",
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function(resp) {
            for (var i=0; i<resp.length; i++) {
                
               var course = new Course(resp[i]);
               that.models.push(course);
//                 console.log(course);
//                window.location.href="http://radgor.app/assets/templates/profile.html";
            }
        },
        error: function(xhr, status, error) {
            alert("smth went wrong");
        }
        
    })
}

Courses.prototype.removeStudentCourse = function(courseId) {
    var that = this;
    return $.ajax({
        url: "http://localhost/Elearn/courses/remove",
        type: "POST",
        data: {
            course_id: courseId
        },
//        data: JSON.stringify({
//            id: courseId
//        }),
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function(resp) {
       
            console.log(resp);
        },
        error: function(xhr, status, error){
            alert("smth went wrong");
        }
        
    })
}
























