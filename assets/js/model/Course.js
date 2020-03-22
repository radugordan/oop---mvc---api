/*global $ */

function Course(options){
    this.id = options.id;
    this.title = options.title;
    this.description = options.description;
    this.file = options.file;
    this.creator_id = options.creator_id;
    this.created_at = options.created_at;
}

Course.prototype.editCourse = function(formData){
    return $.ajax ({
        url: API_URL + "/courses/edit",
        type: "POST",
        data: formData,
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
        processData: false,
        contentType: false, 
        success: function(resp){
            console.log("success");
        },
        error: function(xhr, status, error){
            alert("You are not a teacher!!! ALERT!!! ALERT!");
            

        }
    })
}