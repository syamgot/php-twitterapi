<?php


namespace api\twitter;

use \InvalidArgumentException;
use \RuntimeException;
use \RangeException;
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * [TwitterOAuth PHP Library for the Twitter REST API](https://twitteroauth.com/)
 *
 *
 */
class TwitterApi implements ITwitterApi {

	private $conn;
	private $timeouts = [10,15];

	function __construct($consumerKey, $consumerSecret, $accessToken = null, $accessTokenSecret = null) {
		$this->conn = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
		$this->conn->setTimeouts($this->timeouts[0],$this->timeouts[1]);
	}

	public function searchTweets($query, $options = []) {

		//
		if (!is_string($query)) {
			throw new InvalidArgumentException('第一引数は文字列でなければなりません. ('.strval($query).')');
		}
		if ($query === '') {
			throw new RangeException('第一引数は1文字以上の文字列でなければなりません.');
		}

		return $this->convRes($this->conn->get('search/tweets', array_merge(['q' => $query], $options)));

	}

	public function tweet($tweet, $options = []) {
		//
		if (!is_string($tweet)) {
			throw new InvalidArgumentException('第一引数は文字列でなければなりません. ('.strval($tweet).')');
		}
		if ($tweet === '') {
			throw new RangeException('第一引数は1文字以上の文字列でなければなりません.');
		}

		return $this->convRes($this->conn->post('statuses/update', array_merge(['status' => $tweet], $options)));
	}

	public function mediaUpload($tweet, $paths, $options = []) {

		//
		if (!is_string($tweet)) {
			throw new InvalidArgumentException('第一引数は文字列でなければなりません. ('.strval($tweet).')');
		}
		if ($tweet === '') {
			throw new RangeException('第一引数は1文字以上の文字列でなければなりません.');
		}
		//
		if (!is_array($paths)) {
			throw new InvalidArgumentException('第二引数は配列でなければなりません. ('.strval($query).')');
		}
		if (count($paths) === 0) {
			throw new RangeException('第二引数は空の配列であってはいけません.');
		}

		//
		$mediaIds = [];
		foreach ($paths as $path) {
			if (!file_exists($path)) {
				throw new RuntimeException("ファイルが存在しません $path");
			}
			$media = $this->conn->upload('media/upload', array('media' => $path));
			array_push($mediaIds, $media->media_id_string);
		}
		$parameters = array(
		    'status' => $tweet,
		    'media_ids' => implode(',', $mediaIds),
		);
		return $this->convRes($this->conn->post('statuses/update', array_merge($parameters, $options)));

	}

	public function homeTimeline($options = []) {
		return $this->conn->get('statuses/home_timeline', $options);
	}

	/**
	 *
	 * @param mixed $user intならuser_id, 他ならscreen_nameとして扱う
	 * @param array $options 
	 * @return 
	 */
	public function userTimeline($user, $options = []) {
		$arr = is_int($user) ? ['user_id' => $user] : ['screen_name' => $user]; 
		return $this->convRes($this->conn->get('statuses/user_timeline', array_merge($arr,$options)));
	}

	/**
	 *
	 * @param mixed $user intならuser_id, 他ならscreen_nameとして扱う
	 * @param array $options 
	 * @return 
	 */
	public function favorites($user, $options = []) {
		$arr = is_int($user) ? ['user_id' => $user] : ['screen_name' => $user]; 
		return $this->convRes($this->conn->get('favorites/list', array_merge($arr,$options)));
	}

	public function followers($options = []) {
		return $this->convRes($this->conn->get('favorites/list', $options));
	}

	/**
	 * TwitterAPIは、存在しなければ空で返り、エラーにはならない
	 */
	public function lookup($ids, $options = []) {

		//
		if (!is_array($ids)) {
			throw new InvalidArgumentException('第一引数は配列でなければなりません. ('.strval($query).')');
		}
		if (count($ids) < 1) {
			throw new RangeException('第一引数は1件以上の配列でなければなりません.');
		}

		//
		return $this->convRes($this->conn->get('statuses/lookup', array_merge(['id'=>implode(',',$ids)], $options)));
	}

	/**
	 * TwitterAPIは、存在しなければエラーを返す
	 * @param int $id
	 */
	public function show($id, $options = []) {
		//
		if (!is_int($id)) {
			throw new InvalidArgumentException('第一引数は数値でなければなりません. ('.strval($id).')');
		}
		return $this->convRes($this->conn->get('statuses/show', array_merge(['id' => $id], $options)));
	}

	/**
	 * @param int $id
	 * @return
	 */
	public function destory($id, $options = []) {
		//
		if (!is_int($id)) {
			throw new InvalidArgumentException('第一引数は数値でなければなりません. ('.strval($id).')');
		}
		return $this->convRes($this->conn->post('statuses/destroy', array_merge(['id' => $id], $options)));
	}

	/**
	 * @param int $id
	 * @return
	 */
	public function retweets($id, $options = []) {
		//
		if (!is_int($id)) {
			throw new InvalidArgumentException('第一引数は数値でなければなりません. ('.strval($id).')');
		}
		return $this->convRes($this->conn->get('statuses/retweets', array_merge(['id' => $id], $options)));
	}

	/**
	 *
	 *
	 */
	public function requestToken($callback) {
		return $this->convRes($this->conn->oauth('oauth/request_token', ['oauth_callback' => $callback]));
	}

	/**
	 *
	 * @return string url
	 */
	public function authentication($token) {
		return $this->conn->url('oauth/authenticate', ['oauth_token' => $token]);
	}

	/**
	 *
	 *
	 */
	public function accessToken($verifier) {
		return $this->conn->oauth('oauth/access_token', ['oauth_verifier' => $verifier]);
	}

	/**
	 * json_encode & error check
	 *
	 */
	private function convRes($body) {
		$body = json_decode(json_encode($body),true);
		if (array_key_exists('errors', $body)) {
			throw new TwitterApiException($body['errors'], 'TwitterAPIがエラーを返却しました.');
		}
		return $body;
	}

	/**
	 *
	 */
	public function rateLimit($options = []) {
		return $this->convRes($this->conn->get('application/rate_limit_status', $options));
	}

	/**
	 *
	 */
	public function showUser($user, $options = []) {
		$arr = is_int($user) ? ['id' => $user] : ['screen_name' => $user]; 
		return $this->convRes($this->conn->get('users/show', array_merge($arr, $options)));
	}

	/**
	 *
	 */
	public function favorite($id, $options = []) {
		if (!is_int($id)) {
			throw new InvalidArgumentException('第一引数は数値でなければなりません. ('.strval($id).')');
		}
		return $this->convRes($this->conn->post('favorites/create', array_merge(['id' => $id], $options)));
	}

}







