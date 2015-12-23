<?php

require_once __DIR__ . '/vendor/autoload.php';
session_start();

$fb = new Facebook\Facebook([
    'app_id' => '495125297340244',
    'app_secret' => 'c8fec76a911a11491b893e079bb91779',
    'default_graph_version' => 'v2.5',
]);

$helper = $fb->getCanvasHelper();



$permissions = ['email', 'publish_actions']; // optional

print_r($permissions);
die;

try {
    if (isset($_SESSION['facebook_access_token'])) {
        $accessToken = $_SESSION['facebook_access_token'];
    } else {
        $accessToken = $helper->getAccessToken();
    }
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}
if (isset($accessToken)) {
    if (isset($_SESSION['facebook_access_token'])) {
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    } else {
        $_SESSION['facebook_access_token'] = (string) $accessToken;
        // OAuth 2.0 client handler
        $oAuth2Client = $fb->getOAuth2Client();
        // Exchanges a short-lived access token for a long-lived one
        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
        $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }

    // validating the access token
    try {
        $request = $fb->get('/me');
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        if ($e->getCode() == 190) {
            unset($_SESSION['facebook_access_token']);
            $helper = $fb->getRedirectLoginHelper();
            $loginUrl = $helper->getLoginUrl('https://fbappi.herokuapp.com/', $permissions);
            echo "<script>window.top.location.href='".$loginUrl."'</script>";
            exit;
        }
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

    // posting on user timeline using publish_actins permission
    try {
        // message must come from the user-end
        $data = ['message' => 'testing...'];
        $request = $fb->post('/me/feed', $data);
        $response = $request->getGraphEdge()->asArray;
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

    echo $response['id'];

    // Now you can redirect to another page and use the
    // access token from $_SESSION['facebook_access_token']
} else {
    $helper = $fb->getRedirectLoginHelper();
    $loginUrl = $helper->getLoginUrl('https://fbappi.herokuapp.com/', $permissions);
    echo "<script>window.top.location.href='".$loginUrl."'</script>";
}

?>

<h1 id="fb-welcome"></h1>

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