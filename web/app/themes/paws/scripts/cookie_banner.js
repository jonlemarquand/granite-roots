var $ = jQuery;

$(document).ready(function(){

    // Check if consent cookie is set
    function getCookie(name) {
        var dc = document.cookie;

        var prefix = name + "=";

        var begin = dc.indexOf("; " + prefix);
        if (begin == -1) {
            begin = dc.indexOf(prefix);
            if (begin != 0) return null;
        } else {
            begin += 2;
            var end = document.cookie.indexOf(";", begin);
            if (end == -1) {
            end = dc.length;
            }
        }

        return decodeURI(dc.substring(begin + prefix.length, end));
    }

    if( getCookie('cookie_consent') == null) {
        $('#js_cookie_container').css('display', 'block')
    }

    // Consent Cookie
    function setConsentCookie(value) {
        // Set a cookie
        var expires = new Date();
        expires.setTime(expires.getTime() + 31536000000); //1 year
        document.cookie = 'cookie_consent' + '=' + value + ';path=/;expires=' + expires.toUTCString();
        $('#js_cookie_container').slideUp();
    }

    document.addEventListener('click', function (event) {

        //Cookie Triggers
        if (event.target.id == "js_accept_cookie_button") {
            setConsentCookie(true);
        }

        if (event.target.id == "js_decline_cookie_button") {
            setConsentCookie(false);
        }

    });
    
});
