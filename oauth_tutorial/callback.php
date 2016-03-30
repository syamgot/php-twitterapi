<?php

require __DIR__.'/../vendor/autoload.php';

use API\Twitter\TwitterApi;


// define
$consumerKey 	= '';
$consumerSecret = '';

try {

	session_start();

	// リクエストから値を取得
	$oauth_token 		= $_REQUEST['oauth_token'];
	$verifier 			= $_REQUEST['oauth_verifier'];
	
	// 保持していた値を取得
	$oauth_token_secret = $_SESSION['oauth_token_secret'];
	
	// アクセストークンを取得
	$api  	= new TwitterApi($consumerKey, $consumerSecret, $oauth_token, $oauth_token_secret);
	$result = $api->accessToken($verifier);

	echo 'oauth_token: ' 		.$result['oauth_token'].'<br />';
	echo 'oauth_token_secret: ' .$result['oauth_token_secret'].'<br />';
	echo 'user_id: ' 			.$result['user_id'].'<br />';
	echo 'screen_name: ' 		.$result['screen_name'].'<br />';

}
catch (Exception $e) {
	echo ($e->getMessage());
}


