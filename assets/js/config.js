API_URL = location.protocol + '//' + location.host + '/Elearn/api/';
ROOT_URL = location.protocol + '//' + location.host + '/Elearn/';

(function($){
    $.ajaxSetup({
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        } 
    });
})(jQuery)