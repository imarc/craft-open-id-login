/**
 * OpenID Login plugin for Craft CMS
 *
 * OpenID Login JS
 *
 * @author    Imarc
 * @copyright Copyright (c) 2020 Imarc
 * @link      https://www.imarc.com/
 * @package   OpenidLogin
 * @since     1.0.0
 */

document.getElementById("poweredby").insertAdjacentHTML('beforebegin', `<div id="glogin" class="g-signin2" data-theme="dark"></div>`);

gapi.load('auth2', function() {
    auth2 = gapi.auth2.init({
        cookiepolicy: 'single_host_origin'
    });
    element = document.getElementById('glogin');
    auth2.attachClickHandler(element, {}, onSignIn, onFailure);
});

function onSignIn(googleUser) {
    //Redirect to controller
    var uri = "/actions/open-id-login/default?id_token=" + encodeURI(googleUser.getAuthResponse().id_token)

    window.location.href = uri;
}

function onFailure(googleUser) {
    console.log('Failed Login')
}
