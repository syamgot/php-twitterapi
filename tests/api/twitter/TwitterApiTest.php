<?php

namespace api\twitter;

use api\twitter\TwitterApi;
use api\twitter\TwitterApiException;

class TwitterApiTest extends \PHPUnit_Framework_TestCase {

	protected $consumerKey 			= 'WqRH0y4v8qcReXsFFPLlA';
	protected $consumerSecret 		= 'bqD6YiwcdnORpd9LEsS3F0Kb72SEECEVN4M7KR1mc';
	protected $accessToken 			= '';
	protected $accessTokenSecret 	= '';

	/**
	 * @test
	 */
	public function test_認証エラーの例外がスローされるか() {
		try {
			$api = new TwitterApi('','');
			$api->searchTweets('ほげ');
		}
		catch (TwitterApiException $e) {
			$messages 	= $e->getMessages();
			$message 	= $messages[0];
			$this->assertEquals($message['code'], 215);
		}
	}

	/**
	 * @test
	 */
	public function test_searchTweets_実行されるか() {
		$api = new TwitterApi($this->consumerKey, $this->consumerSecret);
		$res = $api->searchTweets('ほげ');
		$this->assertNotNull($res);
	}

}
