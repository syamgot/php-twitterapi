<?php

require __DIR__.'/../vendor/autoload.php';

use API\Twitter\TwitterApi;


// define
$consumerKey 	= '';
$consumerSecret = '';
$callback 		= 'http://xxxxxxxx/oauth_tutorial/callback.php';

try {

	session_start();

	// トークンをリクエスト
	$api 		= new TwitterApi($consumerKey, $consumerSecret);
	$token 		= $api->requestToken($callback);
	
	
	// トークンを保持
	$_SESSION['oauth_token'] 		= $token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $token['oauth_token_secret'];
	
	// 認証画面へのURLを取得
	$url = $api->authentication($token['oauth_token']);
	
	// 認証画面へ遷移
	header( 'location: '. $url );

}
catch (Exception $e) {
	echo ($e->getMessage());
}


