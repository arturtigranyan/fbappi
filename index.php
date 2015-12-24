<?php

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
