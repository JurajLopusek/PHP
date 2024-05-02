function checkCookies() {
    if (document.cookie.indexOf('cookies_accepted=true') === -1) {
        displayToast();
    }
}

function acceptCookies() {
    document.cookie = 'cookies_accepted=true; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/';
    document.getElementById('cookie-toast').style.display = 'none';
}

function displayToast() {
    document.getElementById('cookie-toast').style.display = 'block';
}
