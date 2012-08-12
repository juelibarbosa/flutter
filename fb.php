<?php
session_start();

if (isset($_GET['code']))
{
	$code = $_GET['code'];

	if ($code)
	{
		// get the user access token:
		$url = "https://graph.facebook.com/oauth/access_token?client_id=354211487989261&client_secret=3f71e62611254ff4fe09210955295686&redirect_uri=http://flutter.phpfogapp.com/fb.php&code=$code";

		$ch = curl_init();

		// Set query data here with the URL
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		$token = trim(curl_exec($ch));
		curl_close($ch);

		$params = null;
		//$token = file_get_contents($url);
		parse_str($token, $params);
var_dump($params);
     	$graph_url = "https://graph.facebook.com/me?access_token=" . $params['access_token'];

     	$user = json_decode(file_get_contents($graph_url));
var_dump($user);exit;     	
     	// save all the auth stuff to session:
     	$_SESSION['auth'] = array(
     		'token' => $params['access_token'],
     		'uid' => $user->id,
     		'user' => $user	
 		);

 		header('Location: /');
	}
}