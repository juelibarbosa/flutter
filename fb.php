<?php
include_once(config.php);
session_start();

//$fb_redirect_url = 'http://localhost:8080';
$fb_redirect_url = 'http://flutter.phpfogapp.com';

if (isset($_GET['code']))
{
	$code = $_GET['code'];

	if ($code)
	{
		// get the user access token:
		$url = "https://graph.facebook.com/oauth/access_token?client_id=354211487989261&client_secret=3f71e62611254ff4fe09210955295686&redirect_uri=$fb_redirect_url/fb.php&code=$code";

		// Set query data here with the URL
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		$token = trim(curl_exec($ch));

		$params = null;
		parse_str($token, $params);
     	$graph_url = "https://graph.facebook.com/me?access_token=" . $params['access_token'];

     	curl_setopt($ch, CURLOPT_URL, $graph_url);
     	$user = json_decode(trim(curl_exec($ch)));
		curl_close($ch);

     	// save all the auth stuff to session:
     	$_SESSION['auth'] = array(
     		'token' => $params['access_token'],
     		'uid' => $user->id,
     		'user' => $user	
 		);

 		header('Location: /');
	}
}