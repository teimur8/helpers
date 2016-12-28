//объект для работы с куки через js
var js_cookie = {
    get: function (cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
        }
        return "";
    },
    save: function(name, value, expires){
        var exper = "expires=" + expires;
        document.cookie = name + "=" + value + "; " + exper;
    }
};