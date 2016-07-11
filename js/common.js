/*
Changed function names from readCookie(), createCookie()
and eraseCookie() to getCookie(), setCookie() and
deleteCookie().
// Create/write a cookie and store it for 1 day
setCookie('myCookie', 'myValue', 1);
// Get my cookie
getCookie('myCookie');
// Delete/erase my cookie
deleteCookie('myCookie');
*/
function setCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function deleteCookie(name) {
    setCookie(name,"",-1);
}

function ajaxUpdate(id, data){
    if($(".search-form").size())
        var dom=$(".search-form");
    else
        var dom=$(".power_btn");
    if($(".selected").next().hasClass('next'))
        $("html,body").animate({scrollTop:dom.offset().top},800)
}