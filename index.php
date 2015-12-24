<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId      : '495125297340244',
                xfbml      : true,
                version    : 'v2.5'
            });

            // ADD ADDITIONAL FACEBOOK CODE HERE
        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

    <h1 id="fb-welcome">Facebook Login</h1>

    <script>
        function onLogin(response) {
            if (response.status == 'connected') {
                FB.api('/me?fields=first_name', function(data) {
                    var welcomeBlock = document.getElementById('fb-welcome');
                    welcomeBlock.innerHTML = 'Hello, ' + data.first_name + '!';
                });
            }
        }

        FB.getLoginStatus(function(response) {
            // Check login status on load, and if the user is
            // already logged in, go directly to the welcome message.
            if (response.status == 'connected') {
                onLogin(response);
            } else {
                // Otherwise, show Login dialog first.
                FB.login(function(response) {
                    onLogin(response);
                }, {scope: 'user_friends, email'});
            }
        });
    </script>


</body>
</html>



<?php

/*
require_once __DIR__ . '/vendor/autoload.php';
session_start();

$fb = new Facebook\Facebook([
    'app_id' => '495125297340244',
    'app_secret' => 'c8fec76a911a11491b893e079bb91779',
    'default_graph_version' => 'v2.5',
]);


$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('https://fbappi.herokuapp.com/index.php', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
?>