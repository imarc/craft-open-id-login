const clientId = document.querySelector('meta[name="google-signin-client_id"]').getAttribute('content')

const googleButton = `
    <div
        id="g_id_onload"
        data-client_id="${clientId}"
        data-ux_mode="redirect"
        data-login_uri="/actions/open-id-login/default"
    ></div>
    <div class="g_id_signin" data-type="standard"></div>
`

document.getElementById('login-form').insertAdjacentHTML('afterend', googleButton)
