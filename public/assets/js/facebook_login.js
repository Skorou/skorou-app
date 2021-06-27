window.fbGetLoginStatus = function () {
    FB.getLoginStatus(function (response) {
        if (isConnected(response)) {
            fbLogIn(response);
        }
    });
}
function isConnected(response) {
    return response.status === 'connected';
}

function fbLogIn(response) {
    let loginForm = document.querySelector('.login-form');
    let input = getHiddenInput("fbAuthResponse", JSON.stringify(response.authResponse));
    loginForm.appendChild(input);
    loginForm.submit();
}

function getHiddenInput(name, value) {
    let input = document.createElement("input");
    input.setAttribute("type", "hidden");
    input.setAttribute("name", name);
    input.setAttribute("value", value);
}