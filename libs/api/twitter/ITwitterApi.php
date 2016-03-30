<?php


namespace api\twitter;

interface ITwitterApi {

	/**
	 *
	 *
	 * POST statuses/update
	 *
	 * Response formats JSON
	 * Requires authentication? Yes (user context only)
	 * Rate limited? Yes
	 * 
	 * @see https://dev.twitter.com/rest/reference/post/statuses/update
	 */
	public function tweet($tweet, $options = []);


	/**
	 *
	 *
	 * POST search/tweets
	 *
	 * Response formats JSON
	 * Requires authentication? Yes
	 * Rate limited? Yes
	 * Requests / 15-min window (user auth) 180
	 * Requests / 15-min window (app auth) 450
	 *
	 * @see https://dev.twitter.com/rest/reference/get/search/tweets
	 */
	public function searchTweets($query, $options = []);


	/**
	 *
	 * POST media/upload
	 *
	 * Response formats JSON
	 * Requires authentication? Yes (user context only)
	 * Rate limited? Yes
	 *
	 * @see https://dev.twitter.com/rest/reference/post/media/upload
	 * @see https://dev.twitter.com/rest/public/uploading-media
	 */
	public function mediaUpload($tweet, $paths, $options = []);


	/**
	 *
	 *
	 * GET favorites/list
	 *
	 * Response formats JSON
	 * Requires authentication? Yes
	 * Rate limited? Yes
	 * Requests / 15-min window (user auth) 15
	 * Requests / 15-min window (app auth) 15
	 */
	public function favorites($user, $options = []);


	/**
	 *
	 *
	 * GET followers/list
	 *
	 * Response formats JSON
	 * Requires authentication? Yes
	 * Rate limited? Yes
	 * Requests / 15-min window (user auth) 15
	 * Requests / 15-min window (app auth) 30
	 *
	 */
	public function followers($options = []);


	/**
	 *
	 *
	 * GET statuses/home_timeline
	 *
	 * Response formats JSON
	 * Requires authentication? Yes (user context only)
	 * Rate limited? Yes
	 * Requests / 15-min window (user auth) 15
	 *
	 * @see https://dev.twitter.com/rest/reference/get/statuses/home_timeline
	 */
	public function homeTimeline($options = []);


	/**
	 *
	 * GET statuses/user_timeline
	 *
	 * Response formats JSON
	 * Requires authentication? Yes
	 * Rate limited? Yes
	 * Requests / 15-min window (user auth) 180
	 * Requests / 15-min window (app auth) 300
	 *
	 * @see https://dev.twitter.com/rest/reference/get/statuses/user_timeline
	 */
	public function userTimeline($user, $options = []);


	/**
	 *
	 * GET statuses/lookup
	 *
	 * Response formats JSON
	 * Requires authentication? Yes
	 * Rate limited? Yes
	 * Requests / 15-min window (user auth) 180
	 * Requests / 15-min window (app auth) 60
	 *
	 * @see https://dev.twitter.com/rest/reference/get/statuses/lookup
	 */
	public function lookup($ids, $options = []);

	/**
	 *
	 * GET statuses/show/:id
	 *
	 * Response formats JSON
	 * Requires authentication? Yes
	 * Rate limited? Yes
	 * Requests / 15-min window (user auth) 180
	 * Requests / 15-min window (app auth) 180
	 *
	 * @see https://dev.twitter.com/rest/reference/get/statuses/show/:id
	 */
	public function show($id, $options = []);

	/**
	 *
	 */
	public function destory($id, $options = []);

	/**
	 *
	 *
	 */
	public function retweets($id, $options = []);

	/**
	 *
	 *
	 */
	public function requestToken($callback);

	/**
	 *
	 *
	 */
	public function accessToken($verifier);

	/**
	 *
	 *
	 */
	public function authentication($token);

	/**
	 *
	 *
	 */
	public function rateLimit($options = []);

	/**
	 *
	 *
	 */
	public function showUser($user, $options = []);

	/**
	 *
	 * Response formatsJSON
	 * Requires authentication? Yes (user context only)
	 * Rate limited? Yes
	 *
	 * @see https://dev.twitter.com/rest/reference/post/favorites/create
	 */
	public function favorite($id, $options = []);

}



