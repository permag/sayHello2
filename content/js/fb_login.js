// Additional JS functions here
window.fbAsyncInit = function() {
  FB.init({
    appId      : '408995115866134', // App ID
    channelUrl : 'http://localhost/dev/sayHello2/', // Channel File
    status     : true, // check login status
    cookie     : true, // enable cookies to allow the server to access the session
    xfbml      : true  // parse XFBML
  });

  // Additional init code here
  FB.getLoginStatus(function(response) {
      if (response.status === 'connected') {
          // connected
      } else if (response.status === 'not_authorized') {
          // not_authorized
          login();
      } else {
          // not_logged_in
         // login();
      }

  });

  $(function(){
    $('#loginLink').click(function(e){
      login();
      e.preventDefault();
    });
    $('#logoutLink').click(function(e){
      logout();
      e.preventDefault();
    });
  });

};

// Load the SDK Asynchronously
(function(d){
   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement('script'); js.id = id; js.async = true;
   js.src = "//connect.facebook.net/en_US/all.js";
   ref.parentNode.insertBefore(js, ref);
 }(document));


function login() {
    FB.login(function(response) {
      if (response.authResponse) {
          // connected
          testAPI();
          $('#loginLink').html('Logging in...').attr('disabled', 'disabled');
      } else {
          // cancelled
      }
    }, { scope: 'read_stream,user_status,user_about_me' });

}

function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
        console.log('Good to see you, ' + response.name + '. id: ' + response.id);
    });
    window.location = 'index.php';
}

function logout() {
  FB.logout(function(response) {
    // user is now logged out
    $('#logoutLink').html('Logging out...').attr('disabled', 'disabled');
    window.location = 'index.php';
  });
}
