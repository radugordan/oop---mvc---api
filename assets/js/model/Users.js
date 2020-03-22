/* global Users, $ */
//console.log("hello");
function Users(){
    this.models = [];
    this.items = [];
}

Users.prototype.getUser = function() {
    var that = this;
    return $.ajax({
        url: API_URL + "user",
        type: "GET",
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        success: function(resp) { 
            var user = new User(resp);
            that.models.push(user);
        },
        error: function(xhr, status, error) {
            alert("Smth went wrong");
        }
    })
}

Users.prototype.getAllUsers = function() {
    var that = this;
    return $.ajax({
        url: API_URL + "students",
        type: "GET",
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        crossDomain: true,
        success: function(resp) {
            for (var i=0; i<resp.length; i++) {
               var user = new User (resp[i]);
               that.models.push(user);
            }
        },
        error: function(xhr, status, error) {
            alert("Smth went wrong");
        }
    })
}

Users.prototype.logout = function(){
    return $.ajax({
        url: API_URL + "user/logout",
        type : "GET",
        xhrFields: {
            withCredentials: true
        },
        crossDomain: true,
        success: function(resp){
            console.log('succesfull logout');
        },
        error:function(xhr,status,error){
            alert("Goodbye!");
        }
    });
};

Users.prototype.getCourseInfo = function (courseId) {
    var that = this;
    
    return $.ajax ({
    url:  API_URL + "courses/info",
    type: "GET",
    data: {
      id: courseId,  
    },
    
    success: function (resp) {
    for (var i=0; i<resp.length; i++) {
           var user = new User (resp[i]);
           that.models.push(user);
             console.log(user);
        }
    },
    error: function (xhr, status, error) {
        // alert ("Something went wrong!");
        alert("Smth wen wrong");
    }
    });
};

