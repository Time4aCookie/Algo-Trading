let user_id;
let username;
document.addEventListener("DOMContentLoaded", function(){
    cache();
    document.getElementById("login").addEventListener("click", function(){login();});
    document.getElementById("register").addEventListener("click", function(){register();});
    document.getElementById("logout").addEventListener("click", function(){logout();});
});
//handles when login button clicked
function login() {
    var username = String(document.getElementById("login-username").value);
    var password = String(document.getElementById("login-password").value);
    if (!username || !password) {
        document.getElementById("login-message").innerText = "Whoops! You've got to enter both your username & password.";
        $('#login-message').show();
        $('#login-message').fadeOut(2000);
        return;
    }
    // calling fetch api
    var query={
        method: "POST",
        headers:{
            //lets the server know we are sending info in url format to put it in the POST
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'username='+encodeURIComponent(username)+'&password='+encodeURIComponent(password)
    };
    // Make the fetch request and handle response
    fetch('login.php', query)
        .then(function(response) {
            if (!response.ok) {
                throw new Error('response status not 200');
            }
            return response.json();
        })
        //another .then bc previous promise returns another promise("return response.json()")
        .then(function(output) {
            if (output.success) {
                document.getElementById("login-message").innerText = username + ", welcome back to your calendar.";
                //gets rid of login section bc user is already logged in 
                $('#login-message').show();
                $('#login-message').fadeOut(2000);
                $('.login').fadeOut(2000);
                $('.register').fadeOut(2000);
                $('#logout').show();
            } else {
                document.getElementById("login-message").innerText = "Whoops! " + output.error;
                $('#login-message').show();
                $('#login-message').fadeOut(2000);
            }
        })
        .catch(function(error) {
            console.error('Login failed:', error);
            document.getElementById("login-message").innerText = "Whoops! There seems to be an error. Our engineers are hard at work trying to fix your error, please try again soon :)";
            $('#login-message').show();
            $('#login-message').fadeOut(2000);
        });
}
//handles a user trying to register a new acct
function register() {
    var username = String(document.getElementById("register-username").value);
    var password = String(document.getElementById("register-password").value);
    var email = String(document.getElementById("register-email").value);
    if (!username || !password || !email) {
        document.getElementById("register-message").innerText = "Whoops! You've got to enter a username, password and email to register.";
        $('#register-message').show();
        $('#register-message').fadeOut(2000);
        return;
    }
    // calling fetch api
    var query={
        method: "POST",
        headers:{
            //lets the server know we are sending info in url format to put it in the POST
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'username='+encodeURIComponent(username)+'&password='+encodeURIComponent(password)+'&email='+encodeURIComponent(email)
    };
    // Make the fetch request and handle response
    fetch('register.php', query)
        .then(function(response) {
            if (!response.ok) {
                throw new Error('response status not 200');
            }
            return response.json();
        })
        //another .then bc previous promise returns another promise("return response.json()")
        .then(function(output) {
            if (output.success) {
                document.getElementById("register-message").innerText = username + ", you have succesfully registered, please log in";
                $('#register-message').show();
                $('#register-message').fadeOut(2000);
            } else {
                document.getElementById("register-message").innerText = "Whoops! " + output.error;
                $('#register-message').show();
                $('#register-message').fadeOut(2000);
            }
        })
        .catch(function(error) {
            console.error('Register failed:', error);
            document.getElementById("register-message").innerText = "Whoops! There seems to be an error. Our engineers are hard at work trying to fix your error, please try again soon :)";
            $('#register-message').show();
            $('#register-message').fadeOut(2000);
        });
}
//handles a user trying to log out
function logout() {
    fetch('logout.php')
        .then(response => {
            if(response.ok) {
                //hide logout button again bc user is now logged out
                $('#logout').hide();
                $('.login').show();
                $('.register').show();
                document.getElementById("login-username").value ="";
                document.getElementById("login-password").value ="";
                document.getElementById("register-username").value = "";
                document.getElementById("register-password").value ="";
                document.getElementById("register-email").value = "";
                
            }
            else{
                console.error('Logout failed: ', response.status);
            }
        })
        .catch(error =>{
            console.error('Logout failed: ', error);
        });
}
function cache(){
    fetch('loginCache.php')
        .then(response => {
            if(!response.ok) {
                throw new Error('response status not 200');
            }
            return response.json();
        })
        .then(function(output){
            if(output.success){
                document.getElementById("login-username").value = output.username;
                document.getElementById("login-password").value = output.password;
                user_id = output.user_id;
                username = output.username;
                login();
            }
        })
        .catch(error =>{
            console.error('Logout failed: ', error);
        });
}

