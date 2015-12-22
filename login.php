<?php

$fb = new Facebook\Facebook([
    'app_id' => '{1673977016172871}',
    'app_secret' => '{ef4b01825ce2472335dd355812f770c1}',
    'default_graph_version' => 'v2.2',
]);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email', 'user_likes']; // optional
$loginUrl = $helper->getLoginUrl('http://{fbappi.herokuapp.com}/login-callback.php', $permissions);

echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';

