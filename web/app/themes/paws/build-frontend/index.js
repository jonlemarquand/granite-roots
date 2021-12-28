var $ = jQuery;

$(document).ready(function(){
    // Ajax test call 
    // $.ajax({
    //     type: "POST",
    //     url: example_call.ajaxurl,
    //     data: ({ 
    //         action: "example_call", 
    //     }),
    //     success: function(response) {
    //         console.log(response)
    //     },
    // });
    
});

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

var $ = jQuery;

$(document).ready(function(){
    // Script!
    console.log("running");
});

function docReady(fn) {//IE9+
    // see if DOM is already available
    if (document.readyState === "complete" || document.readyState === "interactive") {
        // call on next available tick
        setTimeout(fn, 1);
    } else {
        document.addEventListener("DOMContentLoaded", fn);
    }
}
//For Tabs Gutenberg block
docReady(function () {//Above IE8

	var navigationTabs = document.getElementsByClassName("block-tabs__nav-item");
	Array.from(navigationTabs).forEach(function (navigationTab) {
		navigationTab.addEventListener('click', function (navigationTab){
			changeTab(navigationTab);
		});
	});

	const changeTab = (e) => {
		if (e) {
			let target = e.target;
			target = target.getAttribute('data-tab-target');
			console.log(['Clicked target : '], target);

			//TODO: parent level awareness in case there are other Block tabs in the Editor
			let selectedTabNavItem = document.querySelector(".block-tabs__nav-item[data-tab-target='" + target + "']");
			let selectedTab = document.querySelector(".block-tab[data-tab-index='" + target + "']");

			//1. Hide active tab
			[...document.getElementsByClassName('block-tab')].map(x => x.setAttribute("data-tab-visible", false));
			[...document.getElementsByClassName('block-tabs__nav-item')].map(x => x.classList.remove('active'));

			//2. Show tab
			selectedTab.setAttribute("data-tab-visible", true);
			selectedTabNavItem.classList.add("active");
		}
	}

});